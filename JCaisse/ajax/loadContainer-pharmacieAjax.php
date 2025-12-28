        <?php



            session_start();

            if(!$_SESSION['iduser']){

            header('Location:../index.php'); 

            } 



            require('../connection.php');

 

            require('../declarationVariables.php');

            if($mois==1){
                $annee_paie=$annee - 1;
            }
            else{
                $annee_paie=$annee;
            }


            if ($_SESSION['compte']==1) {

                $sqlGetComptePay="SELECT * FROM `".$nomtableCompte."` where idCompte<>3 ORDER BY idCompte";

                $resPay = mysql_query($sqlGetComptePay) or die ("persoonel requête 2".mysql_error());

                // var_dump($cpt_array);

                $cpt_array = [];

                while ($cpt = mysql_fetch_array($resPay)) {

                    # code...

                    $cpt_array[] = $cpt;  // var_dump($key);

                }

            }


                if($_SESSION['proprietaire']==1){

                   

                    $sqlV="SELECT DISTINCT p.idPagnet

                        FROM `".$nomtablePagnet."` p

                        INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                        WHERE (l.classe=0 || l.classe=1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0   ORDER BY p.idPagnet DESC";

                    $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                    $T_ventes = 0 ;

                    $S_ventes = 0;

                    while ($pagnet = mysql_fetch_assoc($resV)) {

                        $sqlS="SELECT SUM(apayerPagnet)

                        FROM `".$nomtablePagnet."`

                        where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."' && type=0  ORDER BY idPagnet DESC";

                        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                        $S_ventes = mysql_fetch_array($resS);

                        $T_ventes = $S_ventes[0] + $T_ventes;

                    }



                    $sqlM="SELECT DISTINCT m.idMutuellePagnet

                        FROM `".$nomtableMutuellePagnet."` m

                        INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet

                        WHERE l.classe=0  && m.idClient=0  && m.verrouiller=1 && m.datepagej ='".$dateString2."' && m.type=0   ORDER BY m.idMutuellePagnet DESC";

                    $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());

                    $T_mutuelles = 0 ;

                    $S_mutuelles = 0;

                    while ($mutuelle = mysql_fetch_assoc($resM)) {

                        $sqlS="SELECT SUM(apayerPagnet)

                        FROM `".$nomtableMutuellePagnet."`

                        where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idMutuellePagnet='".$mutuelle['idMutuellePagnet']."' && type=0  ORDER BY idMutuellePagnet DESC";

                        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                        $S_mutuelles = mysql_fetch_array($resS);

                        $T_mutuelles = $S_mutuelles[0] + $T_mutuelles;

                    }

        

                    $sqlD="SELECT DISTINCT p.idPagnet

                        FROM `".$nomtablePagnet."` p

                        INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                        WHERE l.classe=2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0  ORDER BY p.idPagnet DESC";

                    $resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());

                    $T_depenses = 0 ;

                    $S_depenses = 0;

                    while ($pagnet = mysql_fetch_assoc($resD)) {

                        $sqlS="SELECT SUM(apayerPagnet)

                        FROM `".$nomtablePagnet."`

                        where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."'  && type=0  ORDER BY idPagnet DESC";

                        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                        $S_depenses = mysql_fetch_array($resS);

                        $T_depenses = $S_depenses[0] + $T_depenses;

                    }



                    $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where  idClient!=0  && dateVersement  ='".$dateString2."'  ORDER BY idVersement DESC";

                    $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());

                    $T_versements = mysql_fetch_array($resP5) ;



                }

                else{

                    $sql2="SELECT * FROM `".$nomtablePagnet."` where iduser='".$_SESSION['iduser']."' && idClient=0 && datepagej ='".$dateString2."' && type=0  ORDER BY idPagnet DESC";

                    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());

                    $total=0;

                    while ($pagnet0 = mysql_fetch_assoc($res2))

                    $total+=$pagnet0['apayerPagnet'];

        

                    $sqlV="SELECT DISTINCT p.idPagnet

                        FROM `".$nomtablePagnet."` p

                        INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                        WHERE (l.classe=0 || l.classe=1) && p.iduser='".$_SESSION['iduser']."' && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0  ORDER BY p.idPagnet DESC";

                    $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                    $T_ventes = 0 ;

                    $S_ventes = 0;

                    while ($pagnet = mysql_fetch_assoc($resV)) {

                        $sqlS="SELECT SUM(apayerPagnet)

                        FROM `".$nomtablePagnet."`

                        where iduser='".$_SESSION['iduser']."' && idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."' && type=0  ORDER BY idPagnet DESC";

                        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                        $S_ventes = mysql_fetch_array($resS);

                        $T_ventes = $S_ventes[0] + $T_ventes;

                    }



                    $sqlM="SELECT DISTINCT m.idMutuellePagnet

                        FROM `".$nomtableMutuellePagnet."` m

                        INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet

                        WHERE l.classe=0 && m.iduser='".$_SESSION['iduser']."' && m.idClient=0  && m.verrouiller=1 && m.datepagej ='".$dateString2."' && m.type=0   ORDER BY m.idMutuellePagnet DESC";

                    $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());

                    $T_mutuelles = 0 ;

                    $S_mutuelles = 0;

                    while ($mutuelle = mysql_fetch_assoc($resM)) {

                        $sqlS="SELECT SUM(apayerPagnet)

                        FROM `".$nomtableMutuellePagnet."`

                        where idClient=0 && iduser='".$_SESSION['iduser']."' &&  verrouiller=1 && datepagej ='".$dateString2."' && idMutuellePagnet='".$mutuelle['idMutuellePagnet']."' && type=0  ORDER BY idMutuellePagnet DESC";

                        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                        $S_mutuelles = mysql_fetch_array($resS);

                        $T_mutuelles = $S_mutuelles[0] + $T_mutuelles;

                    }

        

                    $sqlD="SELECT DISTINCT p.idPagnet

                        FROM `".$nomtablePagnet."` p

                        INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                        WHERE l.classe=2 && p.iduser='".$_SESSION['iduser']."' && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."'   && p.type=0  ORDER BY p.idPagnet DESC";

                    $resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());

                    $T_depenses = 0 ;

                    $S_depenses = 0;

                    while ($pagnet = mysql_fetch_assoc($resD)) {

                        $sqlS="SELECT SUM(apayerPagnet)

                        FROM `".$nomtablePagnet."`

                        where iduser='".$_SESSION['iduser']."' && idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."'   && type=0  ORDER BY idPagnet DESC";

                        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                        $S_depenses = mysql_fetch_array($resS);

                        $T_depenses = $S_depenses[0] + $T_depenses;

                    }

                    $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient!=0  && iduser='".$_SESSION['iduser']."' && dateVersement  ='".$dateString2."'  ORDER BY idVersement DESC";

                    $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());

                    $T_versements = mysql_fetch_array($resP5) ;

        

                }

                $T_caisse =$T_ventes + $T_mutuelles - $T_depenses;

            ?>



            <div class="jumbotron">

                <div class="col-sm-4 pull-right" >

                    <form name="searchDesignationForm" id="searchDesignationForm" method="post" >

                        <div class="form-group" >

                            <input type="text" class="form-control" placeholder="Recherche Prix..." id="designationInfo" name="designation" autocomplete="off"  size="100"/>


                        </div>

                    </form>

                </div>

                <?php if ($_SESSION['caisse']==1 && $_SESSION['proprietaire']==1){ ?>

                    <div class="col-sm-2 pull-right" >

                        <form name="formulaireFCaisse" method="post" >

                            <button type="submit" class="btn btn-warning  pull-right" id="fermerCaisse" name="btnFermerCaisse">

                                Fermeture Caisse

                            </button>

                        </form>

                    </div>

                <?php }

                else if ($_SESSION['caisse']==0 && $_SESSION['proprietaire']==1) {  ?>

                    <div class="col-sm-2 pull-right" >

                        <form name="formulaireOCaisse" method="post" >

                            <button type="submit" class="btn btn-success  pull-right" id="ouvrirCaisse" name="btnOuvrirCaisse" >

                                Ouverture Caisse

                            </button>

                        </form>

                    </div>

                <?php }?>

                <h2>Journal de caisse du : <?php echo $dateString2; ?></h2>

                <?php if ($_SESSION['caissier']==1 || $_SESSION['gerant']==1 || $_SESSION['proprietaire']==1){ ?>

                <div class="panel-group">

                    <div class="panel" style="background:#cecbcb;">

                        <div class="panel-heading" >

                            <h4 class="panel-title">

                            <a data-toggle="collapse" href="#collapse1">Total opérations : <?php echo number_format(($T_caisse + $T_versements[0]), 2, ',', ' '); ?>   </a>

                            </h4>

                        </div>

                        <div id="collapse1" class="panel-collapse collapse">

                            <div class="panel-heading" style="margin-left:2%;">

                                <?php 

                                    $sqlv="select * from `".$nomtableDesignation."` where (classe=0 || classe=1)";

                                    $resv=mysql_query($sqlv);

                                    if(mysql_num_rows($resv)){

                                        echo' <h6>Ventes : '. number_format($T_ventes, 2, ',', ' ').'   </h6> ';

                                        if ($_SESSION['mutuelle']==1){

                                            echo' <h6>Mutuelles : '. number_format($T_mutuelles, 2, ',', ' ').'   </h6> ';

                                        }

                                    }

                                ?>

                                <?php 

                                    $sqld="select * from `".$nomtableDesignation."` where classe=2";

                                    $resd=mysql_query($sqld);

                                    if(mysql_num_rows($resd)){ 

                                        echo' <h6>Depenses : '.number_format($T_depenses, 2, ',', ' ').'   </h6>'; 

                                    }

                                ?>

                                <?php 

                                    $sqld="select * from `".$nomtableVersement."` where dateVersement  ='".$dateString2."' ";

                                    $resd=mysql_query($sqld);

                                    if(mysql_num_rows($resd)){ 

                                        echo' <h6>Versements : '.number_format($T_versements[0], 2, ',', ' ').'   </h6>'; 

                                    }

                                ?>

                            </div>

                        </div>

                    </div>

                    

                </div>

                <?php }?>

            </div>



            <?php if ($_SESSION['caisse']==1 || $_SESSION['proprietaire']==1){ ?>

                     

                <!--*******************************Debut Rechercher Produit****************************************-->

                <form  class="pull-right" id="searchProdForm" method="post" name="searchProdForm" >

                    <input type="text" size="45" class="form-control" name="produit" placeholder="Rechercher produit vendu..."  id="produitVendu2" autocomplete="off" />

                        <!-- <span id="reponsePV"></span> -->

                </form>

                <!--*******************************Fin Rechercher Produit****************************************-->

                <?php  

                    $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."' and YEAR(datePs)='".$annee_paie."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";

                    $res0=mysql_query($sql0);
                    $ps = mysql_fetch_array($res0) ;
                    $idPS = @$ps['idPS'];
                    $montantFixePayement = @$ps['montantFixePayement'];
            

                    if(mysql_num_rows($res0)){

                        if($jour > 0){

                            if($jour > 4){

                                if ($_SESSION['mutuelle']==1){
                                    echo ' 
                                        <form name="formulairePagnet" method="post"  >
        
                                            <button disabled="true" type="button" style="margin-right:50px"  class="btn btn-success pull-left noImpr addPanier-pharmacie" id="addPanier-pharmacie" name="btnSavePagnetVente">
                    
                                                <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                    
                                                <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">  
                    
                                            </button>
                    
                                        </form>
                
                                        <form name="formulairePagnet" method="post"   >
                    
                                            <button disabled="true" type="button" class="btn btn-info noImpr addImputation-pharmacie" id="addImputation-pharmacie" name="btnSavePagnetImputation">
                    
                                                <i class="glyphicon glyphicon-plus"></i>Ajouter une Imputation
                    
                                                <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> 
                    
                                            </button>
                    
                                        </form>
                                    ';
                                }
                                else{
                                    echo ' 
                                        <form name="formulairePagnet" method="post">
        
                                            <button type="button" disabled="true" class="btn btn-success noImpr addPanier-pharmacie" id="addPanier-pharmacie" name="btnSavePagnetVente">
                    
                                                <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                    
                                                <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">  
                    
                                            </button>
                    
                                        </form>
                                    ';
                                }

                            }
                            else{

                                if ($_SESSION['mutuelle']==1){
                                    echo ' 
                                        <form name="formulairePagnet" method="post"  >
        
                                            <button type="button" style="margin-right:50px"  class="btn btn-success pull-left noImpr addPanier-pharmacie" id="addPanier-pharmacie" name="btnSavePagnetVente">
                    
                                                <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                    
                                                <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">  
                    
                                            </button>
                    
                                        </form>
                
                                        <form name="formulairePagnet" method="post"   >
                    
                                            <button type="button" class="btn btn-info noImpr addImputation-pharmacie" id="addImputation-pharmacie" name="btnSavePagnetImputation">
                    
                                                <i class="glyphicon glyphicon-plus"></i>Ajouter une Imputation
                    
                                                <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> 
                    
                                            </button>
                    
                                        </form>
                                    ';
                                }
                                else{
                                    echo ' 
                                        <form name="formulairePagnet" method="post">
        
                                            <button type="button" class="btn btn-success noImpr addPanier-pharmacie" id="addPanier-pharmacie" name="btnSavePagnetVente">
                    
                                                <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                    
                                                <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">  
                    
                                            </button>
                    
                                        </form>
                                    ';
                                }

                            }

                        //    echo "<h6><span id='blinker' style='color:red;'>VEUILLEZ EFFECTUER LE PAIEMENT DU SERVICE JCAISSE AVANT LE 5 !</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onclick='effectue_paiementWave(".$idPS.")' style='text-decoration:underline;'>Cliquez ici pour payer</a></h6>";
                            echo "<h6><span id='blinker' style='color:red;'>VEUILLEZ EFFECTUER LE PAIEMENT DU SERVICE JCAISSE AVANT LE 5 !</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onclick='selectNbMoisPaiement(".$idPS.",".$montantFixePayement.")' style='text-decoration:underline;'>Payer <img src='images/Wave.png' width='25' height='25'></a>&nbsp;&nbsp;&nbsp;&nbsp;<a hidden href='#' onclick='effectue_paiement(".$idPS.")' style='text-decoration:underline;'>Cliquez ici pour payer par OMoney</a></h6>";

                            echo '<br>';
                        }

                        else{

                            if ($_SESSION['mutuelle']==1){
                                echo ' 
                                    <form name="formulairePagnet" method="post"  >
    
                                        <button type="button" style="margin-right:50px"  class="btn btn-success pull-left noImpr addPanier-pharmacie" id="addPanier-pharmacie" name="btnSavePagnetVente">
                
                                            <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                
                                            <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">  
                
                                        </button>
                
                                    </form>
            
                                    <form name="formulairePagnet" method="post"   >
                
                                        <button type="button" class="btn btn-info noImpr addImputation-pharmacie" id="addImputation-pharmacie" name="btnSavePagnetImputation">
                
                                            <i class="glyphicon glyphicon-plus"></i>Ajouter une Imputation
                
                                            <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> 
                
                                        </button>
                
                                    </form>
                                ';
                            }
                            else{
                                echo ' 
                                    <form name="formulairePagnet" method="post">
    
                                        <button type="button" class="btn btn-success noImpr addPanier-pharmacie" id="addPanier-pharmacie" name="btnSavePagnetVente">
                
                                            <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                
                                            <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">  
                
                                        </button>
                
                                    </form>
                                ';
                            }
    
                            echo '<br>';

                        }

                    }

                    else{

                        if ($_SESSION['mutuelle']==1){
                            echo ' 
                                <form name="formulairePagnet" method="post"  >

                                    <button type="button" style="margin-right:50px"  class="btn btn-success pull-left noImpr addPanier-pharmacie" id="addPanier-pharmacie" name="btnSavePagnetVente">
            
                                        <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
            
                                        <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">  
            
                                    </button>
            
                                </form>
        
                                <form name="formulairePagnet" method="post"   >
            
                                    <button type="button" class="btn btn-info noImpr addImputation-pharmacie" id="addImputation-pharmacie" name="btnSavePagnetImputation">
            
                                        <i class="glyphicon glyphicon-plus"></i>Ajouter une Imputation
            
                                        <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> 
            
                                    </button>
            
                                </form>
                            ';
                        }
                        else{
                            echo ' 
                                <form name="formulairePagnet" method="post">

                                    <button type="button" class="btn btn-success noImpr addPanier-pharmacie" id="addPanier-pharmacie" name="btnSavePagnetVente">
            
                                        <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
            
                                        <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">  
            
                                    </button>
            
                                </form>
                            ';
                        }

                        echo '<br>';

                    }										

                ?>



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

                    var blink_speed = 500; 

                        var t = setInterval(function () {

                             var ele = document.getElementById('blinker'); 

                             ele.style.visibility = (ele.style.visibility == 'hidden' ? '' : 'hidden'); 

                            }, blink_speed);

                        function showPreviewPanier(event,idPagnet) {
                            var file = document.getElementById('input_file_Panier'+idPagnet).value;
                            var reader = new FileReader();
                            reader.onload = function()
                            {
                                var format = file.substr(file.length - 3);
                                var pdf = document.getElementById('output_pdf_Panier'+idPagnet);
                                var image = document.getElementById('output_image_Panier'+idPagnet);
                                if(format=='pdf'){
                                    document.getElementById('output_pdf_Panier'+idPagnet).style.display = "block";
                                    document.getElementById('output_image_Panier'+idPagnet).style.display = "none";
                                    pdf.src = reader.result;
                                }
                                else{
                                    document.getElementById('output_image_Panier'+idPagnet).style.display = "block";
                                    document.getElementById('output_pdf_Panier'+idPagnet).style.display = "none";
                                    image.src = reader.result;
                                }
                            }
                            reader.readAsDataURL(event.target.files[0]);
                            document.getElementById('btn_upload_Panier'+idPagnet).style.display = "block";
                        }
                        function showPreviewMutuelle(event,idMutuelle) {
                            var file = document.getElementById('input_file_Mutuelle'+idMutuelle).value;
                            var reader = new FileReader();
                            reader.onload = function()
                            {
                                var format = file.substr(file.length - 3);
                                var pdf = document.getElementById('output_pdf_Mutuelle'+idMutuelle);
                                var image = document.getElementById('output_image_Mutuelle'+idMutuelle);
                                if(format=='pdf'){
                                    document.getElementById('output_pdf_Mutuelle'+idMutuelle).style.display = "block";
                                    document.getElementById('output_image_Mutuelle'+idMutuelle).style.display = "none";
                                    pdf.src = reader.result;
                                }
                                else{
                                    document.getElementById('output_image_Mutuelle'+idMutuelle).style.display = "block";
                                    document.getElementById('output_pdf_Mutuelle'+idMutuelle).style.display = "none";
                                    image.src = reader.result;
                                }
                            }
                            reader.readAsDataURL(event.target.files[0]);
                            document.getElementById('btn_upload_Mutuelle'+idMutuelle).style.display = "block";
                        }

                    </script>

                <!-- Fin Javascript de l'Accordion pour Tout les Paniers -->



                <!-- Debut de l'Accordion pour Tout les Paniers -->

                    <div class="panel-group" id="accordion">



                        <?php

                            // On détermine sur quelle page on se trouve

                            // if(isset($_GET['page']) && !empty($_GET['page'])){

                            //     $currentPage = (int) strip_tags($_GET['page']);

                            // }else{

                            //     $currentPage = 1;

                            // }

                            if(isset($_POST["page"])){

                                $currentPage = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number

                                if(!is_numeric($currentPage)){die('Numéro de page invalide!');} //incase of invalid page number

                            }else{

                                $currentPage = 1; //if there's no page number, set it to 1

                            }

                            // On détermine le nombre d'articles par page

                            $parPage = 10;



                            if($_SESSION['proprietaire']==1){



                                if (isset($_POST['produit'])) {

                                    $produit=@htmlspecialchars($_POST["produit"]);

                                    /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/

                                        $sqlC="SELECT

                                            COUNT(*) AS total

                                            FROM

                                            (

                                            SELECT p.idPagnet FROM `".$nomtablePagnet."` p 

                                            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                                            where p.datepagej ='".$dateString2."' AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND l.designation='".$produit."'

                                                UNION ALL

                                            SELECT m.idMutuellePagnet FROM `".$nomtableMutuellePagnet."` m 

                                            INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet

                                            where m.datepagej ='".$dateString2."' AND m.verrouiller=1 AND (m.type=0 || m.type=30) AND l.designation='".$produit."'

                                            ) AS a ";

                                        $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());

                                        $nbre = mysql_fetch_array($resC) ;

                                        $nbPaniers = (int) $nbre[0];

                                    /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/



                                    echo '<input type="hidden" id="produitAfter"  value="'.$produit.'"  >';



                                    // On calcule le nombre de pages total

                                    $pages = ceil($nbPaniers / $parPage);

                                    

                                    $premier = ($currentPage * $parPage) - $parPage;



                                    /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/

                                        $sqlP1="SELECT codePagnet

                                            FROM

                                            (

                                            SELECT  CONCAT(p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p  

                                            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                                            where p.datepagej ='".$dateString2."' AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND l.designation='".$produit."' 

                                                UNION ALL

                                            SELECT CONCAT(m.heurePagnet,'+',m.idMutuellePagnet,'+2') AS codePagnetMutuelle FROM `".$nomtableMutuellePagnet."` m 

                                            INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet

                                            where m.datepagej ='".$dateString2."' AND m.verrouiller=1 AND (m.type=0 || m.type=30) AND l.designation='".$produit."' 

                                        ) AS a ORDER BY codePagnet DESC LIMIT ".$premier.",".$parPage." ";

                                        $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());

                                    /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                                }

                                else{ 

                                    /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/

                                        $sqlC="SELECT

                                                COUNT(*) AS total

                                            FROM

                                            (

                                            SELECT p.idPagnet FROM `".$nomtablePagnet."` p where p.datepagej ='".$dateString2."' AND p.verrouiller=1 AND (p.type=0 || p.type=30)

                                                UNION ALL

                                            SELECT m.idMutuellePagnet FROM `".$nomtableMutuellePagnet."` m where m.datepagej ='".$dateString2."' AND m.verrouiller=1 AND (m.type=0 || m.type=30)

                                            ) AS a ";

                                        $resC = mysql_query($sqlC) or die ("persoonel requête 1".mysql_error());

                                        $nbre = mysql_fetch_array($resC) ;

                                        $nbPaniers = (int) $nbre[0];

                                    /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/



                                    // On calcule le nombre de pages total

                                    $pages = ceil($nbPaniers / $parPage);

                                    

                                    $premier = ($currentPage * $parPage) - $parPage;



                                    /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/

                                        $sqlP1="SELECT codePagnet

                                            FROM

                                            (

                                            SELECT  CONCAT(p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p where p.datepagej ='".$dateString2."' AND p.verrouiller=1 AND (p.type=0 || p.type=30)

                                                UNION ALL

                                            SELECT CONCAT(m.heurePagnet,'+',m.idMutuellePagnet,'+2') AS codePagnetMutuelle FROM `".$nomtableMutuellePagnet."` m where m.datepagej ='".$dateString2."' AND m.verrouiller=1 AND (m.type=0 || m.type=30)

                                            ) AS a ORDER BY codePagnet DESC LIMIT ".$premier.",".$parPage." ";

                                        $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());

                                    /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                                }



                                /**Debut requete pour Lister les Paniers en cours d'Aujourd'hui (1 au maximum) **/

                                    $sqlP0="SELECT codePagnet

                                    FROM

                                    (SELECT CONCAT(p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p where p.iduser='".$_SESSION['iduser']."' AND p.datepagej ='".$dateString2."' AND p.verrouiller=0 

                                    UNION ALL

                                    SELECT CONCAT(m.heurePagnet,'+',m.idMutuellePagnet,'+2') AS codePagnetMutuelle FROM `".$nomtableMutuellePagnet."` m where m.iduser='".$_SESSION['iduser']."' AND m.datepagej ='".$dateString2."' AND m.verrouiller=0 

                                    ) AS a ORDER BY codePagnet DESC  ";

                                    $resP0 = mysql_query($sqlP0) or die ("persoonel requête 20".mysql_error());

                                /**Fin requete pour Lister les Paniers en cours d'Aujourd'hui (1 au maximum) **/



                                /**Debut requete pour Rechercher le dernier Panier Ajouter  **/

                                    $reqA="SELECT heurePagnet

                                    FROM

                                    (SELECT p.heurePagnet FROM `".$nomtablePagnet."` p where  p.datepagej ='".$dateString2."' AND p.verrouiller=1 AND (p.type=0 || p.type=30)

                                    UNION 

                                    SELECT m.heurePagnet FROM `".$nomtableMutuellePagnet."` m where m.datepagej ='".$dateString2."' AND m.verrouiller=1 AND (m.type=0 || m.type=30)

                                    ) AS a ORDER BY heurePagnet DESC LIMIT 1";

                                    $resA=mysql_query($reqA) or die ("persoonel requête 2".mysql_error());

                                /**Fin requete pour Rechercher le dernier Panier Ajouter  **/

                            }

                            else{

                                if (isset($_POST['produit'])) {

                                    $produit=@htmlspecialchars($_POST["produit"]);

                                    /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/

                                        $sqlC="SELECT

                                            COUNT(*) AS total

                                            FROM

                                            (

                                            SELECT p.idPagnet FROM `".$nomtablePagnet."` p 

                                            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                                            where p.iduser='".$_SESSION['iduser']."' AND p.datepagej ='".$dateString2."' AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND l.designation='".$produit."'

                                                UNION ALL

                                            SELECT m.idMutuellePagnet FROM `".$nomtableMutuellePagnet."` m 

                                            INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet

                                            where m.iduser='".$_SESSION['iduser']."' AND m.datepagej ='".$dateString2."' AND m.verrouiller=1 AND (m.type=0 || m.type=30) AND l.designation='".$produit."'

                                            ) AS a ";

                                        $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());

                                        $nbre = mysql_fetch_array($resC) ;

                                        $nbPaniers = (int) $nbre[0];

                                    /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/



                                    echo '<input type="hidden" id="produitAfter"  value="'.$produit.'"  >';



                                    // On calcule le nombre de pages total

                                    $pages = ceil($nbPaniers / $parPage);

                                    

                                    $premier = ($currentPage * $parPage) - $parPage;



                                    /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/

                                        $sqlP1="SELECT codePagnet

                                            FROM

                                            (

                                            SELECT  CONCAT(p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p  

                                            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                                            where p.iduser='".$_SESSION['iduser']."' AND p.datepagej ='".$dateString2."' AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND l.designation='".$produit."' 

                                                UNION ALL

                                            SELECT CONCAT(m.heurePagnet,'+',m.idMutuellePagnet,'+2') AS codePagnetMutuelle FROM `".$nomtableMutuellePagnet."` m 

                                            INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet

                                            where m.iduser='".$_SESSION['iduser']."' AND m.datepagej ='".$dateString2."' AND m.verrouiller=1 AND (m.type=0 || m.type=30) AND l.designation='".$produit."' 

                                        ) AS a ORDER BY codePagnet DESC LIMIT ".$premier.",".$parPage." ";

                                        $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());

                                    /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                                }

                                else{ 

                                    /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/

                                        $sqlC="SELECT

                                                COUNT(*) AS total

                                            FROM

                                            (

                                            SELECT p.idPagnet FROM `".$nomtablePagnet."` p where p.iduser='".$_SESSION['iduser']."' AND  p.datepagej ='".$dateString2."' AND p.verrouiller=1 AND (p.type=0 || p.type=30)

                                                UNION ALL

                                            SELECT m.idMutuellePagnet FROM `".$nomtableMutuellePagnet."` m where m.iduser='".$_SESSION['iduser']."' AND  m.datepagej ='".$dateString2."' AND m.verrouiller=1 AND (m.type=0 || m.type=30)

                                            ) AS a ";

                                        $resC = mysql_query($sqlC) or die ("persoonel requête 1".mysql_error());

                                        $nbre = mysql_fetch_array($resC) ;

                                        $nbPaniers = (int) $nbre[0];

                                    /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/



                                    // On calcule le nombre de pages total

                                    $pages = ceil($nbPaniers / $parPage);

                                    

                                    $premier = ($currentPage * $parPage) - $parPage;



                                    /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/

                                        $sqlP1="SELECT codePagnet

                                            FROM

                                            (

                                            SELECT  CONCAT(p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p where p.iduser='".$_SESSION['iduser']."' AND p.datepagej ='".$dateString2."' AND p.verrouiller=1 AND (p.type=0 || p.type=30) 

                                                UNION ALL

                                            SELECT CONCAT(m.heurePagnet,'+',m.idMutuellePagnet,'+2') AS codePagnetMutuelle FROM `".$nomtableMutuellePagnet."` m where m.iduser='".$_SESSION['iduser']."' AND m.datepagej ='".$dateString2."' AND m.verrouiller=1 AND (m.type=0 || m.type=30)

                                            ) AS a ORDER BY codePagnet DESC LIMIT ".$premier.",".$parPage." ";

                                        $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());

                                    /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                                }



                                /**Debut requete pour Lister les Paniers en cours d'Aujourd'hui (1 au maximum) **/

                                    $sqlP0="SELECT codePagnet

                                    FROM

                                    (SELECT CONCAT(p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p where p.iduser='".$_SESSION['iduser']."' AND p.datepagej ='".$dateString2."' AND p.verrouiller=0 

                                    UNION ALL

                                    SELECT CONCAT(m.heurePagnet,'+',m.idMutuellePagnet,'+2') AS codePagnetMutuelle FROM `".$nomtableMutuellePagnet."` m where m.iduser='".$_SESSION['iduser']."' AND m.datepagej ='".$dateString2."' AND m.verrouiller=0 

                                    ) AS a ORDER BY codePagnet DESC  ";

                                    $resP0 = mysql_query($sqlP0) or die ("persoonel requête 20".mysql_error());

                                /**Fin requete pour Lister les Paniers en cours d'Aujourd'hui (1 au maximum) **/



                                /**Debut requete pour Rechercher le dernier Panier Ajouter  **/

                                    $reqA="SELECT heurePagnet

                                    FROM

                                    (SELECT p.heurePagnet FROM `".$nomtablePagnet."` p where p.iduser='".$_SESSION['iduser']."' AND p.datepagej ='".$dateString2."' AND p.verrouiller=1 AND (p.type=0 || p.type=30)

                                    UNION 

                                    SELECT m.heurePagnet FROM `".$nomtableMutuellePagnet."` m where m.iduser='".$_SESSION['iduser']."' AND m.datepagej ='".$dateString2."' AND m.verrouiller=1 AND (m.type=0 || m.type=30)

                                    ) AS a ORDER BY heurePagnet DESC LIMIT 1";

                                    $resA=mysql_query($reqA) or die ("persoonel requête 2".mysql_error());

                                /**Fin requete pour Rechercher le dernier Panier Ajouter  **/



                            }

                        ?>         

                        

                        <!-- Debut Boucle while concernant les Paniers en cours (2 aux maximum) -->  

                            <?php while ($pagnets = mysql_fetch_assoc($resP0)) {?>

                                <?php	

                                    $pagnetJour = explode("+", $pagnets['codePagnet']);

                                    if($pagnetJour[2]==1){

                                        $sqlT1="SELECT * FROM `".$nomtablePagnet."` where idPagnet='".$pagnetJour[1]."' ";

                                        $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());

                                        $pagnet = mysql_fetch_assoc($resT1);

                                        if($pagnet!=null){ ?>

                                            <div class="panel <?= ($pagnet['idClient']==0) ? 'panel-primary' : 'panel-info'?>" id="panelPanier<?= $pagnet['idPagnet'];?>">

                                                <div class="panel-heading">

                                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                                        <div class="right-arrow pull-right">+</div>

                                                        <a class="row" href="#">

                                                            <span class="noImpr col-md-2 col-sm-2 col-xs-12" id="panierEncours<?= $pagnet['idPagnet'];?>"> Panier <?php echo ': En cours ...'; ?> </span>

                                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12"> Date: <?php echo $pagnet['datepagej']." ".$pagnet['heurePagnet']; ?> </span>

                                                            <!-- <span class="spanDate noImpr col-md-2 col-sm-2 col-xs-12">Heure: <?php //echo $pagnet['heurePagnet']; ?></span> -->

                                                            <?php	$sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                    $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                                                                    $TotalT = mysql_fetch_array($resT) ;

                                                            ?>

                                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total panier: <span <?php echo  "id=somme_Total".$pagnet['idPagnet']; ?> ><?php echo $TotalT[0]; ?>  </span></span>

                                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total à payer: <span <?php echo  "id=somme_Apayer".$pagnet['idPagnet'] ; ?> ><?php echo ($TotalT[0] - (($TotalT[0] * $pagnet['taux']) / 100)); ?> </span></span>      

                                                        </a>

                                                    </h4>

                                                </div>

                                                <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?> class="panel-collapse collapse in">

                                                    <div class="panel-body" >
                                                        <div class="" id="btnContent<?= $pagnet['idPagnet'];?>" style="display:none;">

                                                            <!--*******************************Debut Retourner Pagnet****************************************-->

                                                                <button disabled="true" type="button" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                        <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                </button>



                                                                <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                    <div class="modal-dialog">

                                                                        <!-- Modal content-->

                                                                        <div class="modal-content">

                                                                            <div class="modal-header panel-primary">

                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                <h4 class="modal-title">Confirmation</h4>

                                                                            </div>

                                                                            <form class="form-inline noImpr"  id="factForm" method="post"  >

                                                                                <div class="modal-body">

                                                                                    <p><?php echo "Voulez-vous retourner le panier numéro <b>".$pagnet['idPagnet']."<b>" ; ?></p>

                                                                                    <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>

                                                                                </div>

                                                                                <div class="modal-footer">

                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                    <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                                </div>

                                                                            </form>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                    

                                                            <!--*******************************Debut Facture****************************************-->

                                                                <button type="button" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                    Facture

                                                                </button>



                                                                <div class="modal fade" <?php echo  "id=msg_fact_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                    <div class="modal-dialog">

                                                                        <!-- Modal content-->

                                                                        <div class="modal-content">

                                                                            <div class="modal-header panel-primary">

                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                <h4 class="modal-title">Informations Client</h4>

                                                                            </div>

                                                                            <form class="form-inline noImpr"  method="post" action="pdfFacturePharmacie.php" target="_blank" >

                                                                                <div class="modal-body">

                                                                                    <div class="row">

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Prenom(s) Client</label>

                                                                                            <input type="text" class="form-control" name="prenom" >

                                                                                        </div>

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Nom Client</label>

                                                                                            <input type="text" class="form-control" name="nom" >

                                                                                        </div>

                                                                                    </div>

                                                                                    <div class="row">

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Adresse Client</label>

                                                                                            <input type="text" class="form-control" name="adresse" >

                                                                                        </div>

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Telephone Client</label>

                                                                                            <input type="text" class="form-control" name="telephone" >

                                                                                        </div>

                                                                                    </div>

                                                                                    <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>

                                                                                </div>

                                                                                <div class="modal-footer">

                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                    <button type="submit" name="btnFacture" class="btn btn-success">Confirmer</button>

                                                                                </div>

                                                                            </form>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            <!--*******************************Fin Facture****************************************-->


                                                                <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                                    Ticket de Caisse

                                                                </button>


                                                            <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                method="post" action="barcodeFacture.php" >

                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                            </form>

                                                        </div>

                                                        <div class="cache_btn_Terminer row" id="cache_btn_Terminer<?= $pagnet['idPagnet'];?>">

                                                            <!--*******************************Debut Ajouter Ligne****************************************-->

                                                            <form  class="form-inline noImpr ajouterProdForm col-md-4 col-sm-4 col-xs-12" id="ajouterProdFormPh<?= $pagnet['idPagnet'];?>" onsubmit="return false" >

                                                                <input type="text" class="inputbasic form-control codeBarreLignePh" name="codeBarre" id="panier_<?= $pagnet['idPagnet'];?>" style="width:100%;" autofocus="" autocomplete="off"  required />

                                                                <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?>>

                                                                <input type="hidden" id="typeVente" value="3"/>

                                                                    <!-- <span id="reponseV"></span> -->

                                                                <!-- <button type="submit" name="btnEnregistrerCodeBarre"

                                                                id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs " ><span class="glyphicon glyphicon-plus" ></span>

                                                                </button> -->

                                                            </form>

                                                            <!--*******************************Fin Ajouter Ligne****************************************-->

                                                            <div class="col-md-8 col-sm-8 col-xs-12 content2">

                                                                <!--*******************************Debut Terminer Pagnet****************************************-->

                                                                <form class="form-inline noImpr" id="factForm" method="post">

                                                                    <input type="hidden" <?php echo "id=val_remise".$pagnet['idPagnet'] ; ?> name="remise" value="0">

                                                                    <select class="form-control col-md-2 col-sm-2 col-xs-3 tauxPanier filedReadonly<?= $pagnet['idPagnet'] ; ?>" name="taux" <?php echo  "id=taux".$pagnet['idPagnet'].""; ?>  onchange="modif_TauxPanier(this.value,<?php echo  $pagnet['idPagnet']; ?>)" >
                                                                        <option selected="true" disabled="disabled" value="<?= $pagnet['taux']; ?>"><?= $pagnet['taux'].' %' ; ?></option>
                                                                        <option value="0">0 %</option>
                                                                        <option value="3">3 %</option>
                                                                        <option value="5">5 %</option>
                                                                        <option value="10">10 %</option>
                                                                        <option value="15">15 %</option>
                                                                    </select>

                                                                    <?php if ($_SESSION['compte']==1) { ?>

                                                                        <select class="compte <?= $pagnet['idPagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3"  data-idPanier="<?= $pagnet['idPagnet'];?>" name="compte"  <?php echo  "id=compte".$pagnet['idPagnet'] ; ?>>

                                                                            <!-- <option value="caisse">Caisse</option> -->

                                                                            <?php                                                     

                                                                            if ($pagnet['idCompte']!=0) {

                                                                                                                                            

                                                                                $sqlGetCompte3="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$pagnet['idCompte'];

                                                                                $resPay3 = mysql_query($sqlGetCompte3) or die ("persoonel requête 2".mysql_error());

                                                                                $cpt = mysql_fetch_array($resPay3);

                                                                            ?>

                                                                                <option value="<?= $pagnet['idCompte'];?>"><?= $cpt['nomCompte'];?></option>

                                                                            <?php } 

                                                                            foreach ($cpt_array as $key) { ?>

                                                                                <option value="<?= $key['idCompte'];?>"><?= $key['nomCompte'];?></option>

                                                                            <?php } ?>

                                                                        </select>                                                     



                                                                        <?php if ($pagnet['type']=='30') {

                                                                                

                                                                                $sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$pagnet['idClient']."";

                                                                                $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());

                                                                                $client = mysql_fetch_assoc($res3);

                                                                            ?>

                                                                                <input type="text" class="client clientInput form-control col-md-2 col-sm-2 col-xs-3" data-type="simple" data-idPanier="<?= $pagnet['idPagnet'];?>" name="clientInput" id="clientInput<?= $pagnet['idPagnet'];?>" autocomplete="off" value="<?= $client['prenom']." ".$client['nom']; ?>"/>

                                                                                <input type="number" class="avanceInput form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $pagnet['idPagnet'];?>" name="avanceInput" id="avanceInput<?= $pagnet['idPagnet'];?>" autocomplete="off" value="<?= $pagnet['avance']; ?>"/>

                                                                                <select class="compteAvance <?= $pagnet['idPagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $pagnet['idPagnet'];?>" name="compteAvance"  <?php echo  "id=compteAvance".$pagnet['idPagnet'] ; ?>>

                                                                                    <!-- <option value="caisse">Caisse</option> -->

                                                                                    <?php 

                                                                                    foreach ($cpt_array as $key) {  

                                                                                        if($key['idCompte'] != 2){

                                                                                        ?>

                                                                                            <option value="<?= $key['idCompte'];?>"><?= $key['nomCompte'];?></option>

                                                                                        <?php } 

                                                                                        } ?>

                                                                                </select> 

                                                                        <?php

                                                                                # code...

                                                                            } else {

                                                                                # code...                                                            

                                                                        ?> 

                                                                                <input type="text" class="client clientInput form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-type="simple" data-idPanier="<?= $pagnet['idPagnet'];?>" name="clientInput" id="clientInput<?= $pagnet['idPagnet'];?>" autocomplete="off" placeholder="Client"/>

                                                                                <input type="number" class="avanceInput form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-idPanier="<?= $pagnet['idPagnet'];?>" name="avanceInput" id="avanceInput<?= $pagnet['idPagnet'];?>" autocomplete="off" placeholder="Avance"/>

                                                                                <select class="compteAvance <?= $pagnet['idPagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-idPanier="<?= $pagnet['idPagnet'];?>" name="compteAvance" <?php echo "id=compteAvance".$pagnet['idPagnet'] ; ?>>

                                                                                    <!-- <option value="caisse">Caisse</option> -->

                                                                                <?php foreach ($cpt_array as $key) { 

                                                                                    if($key['idCompte'] != 2){

                                                                                    ?>

                                                                                        <option value="<?= $key['idCompte'];?>"><?= $key['nomCompte'];?></option>

                                                                                    <?php }

                                                                                    } ?>

                                                                                </select> 

                                                                        <?php } ?> 



                                                                    <?php } ?>



                                                                    <?php if($pagnet['versement']!=0 && $pagnet['versement']!=null){  ?> 

                                                                        <input type="number" name="versement" id="versement<?= $pagnet['idPagnet']; ?>" <?= (@$pagnet['idCompte']!=0) ? 'style="display:none;"' : '';?>

                                                                        class="versement form-control col-md-2 col-sm-2 col-xs-3" <?php echo  "value=".$pagnet['versement']."" ; ?> >

                                                                    <?php }

                                                                    else{   ?>

                                                                        <input type="number" name="versement" id="versement<?= $pagnet['idPagnet']; ?>" <?= (@$pagnet['idCompte']!=0) ? 'style="display:none;"' : '';?>

                                                                        class="versement form-control col-md-2 col-sm-2 col-xs-3"  placeholder="Espèce...">

                                                                    <?php } ?>                                                                          

                                                                    <input type="hidden" name="idPagnet"   <?php echo  "value=".$pagnet['idPagnet']."" ; ?>>

                                                                    <input type="hidden" name="totalp" <?php echo  "id=totalp".$pagnet['idPagnet']."" ; ?> value="<?php echo $pagnet['totalp']; ?>" >

                                                                    <!-- <div class="btnAction col-md-1 col-xs-12"> -->

                                                                        <button type="button" style="" name="btnImprimerFacture" data-idPanier="<?= $pagnet['idPagnet'];?>" <?php echo  "id=btnImprimerFacture".$pagnet['idPagnet']."" ; ?> class="btn btn-success btn_Termine_Panier_Ph terminer" data-toggle="modal" ><span class="glyphicon glyphicon-ok"></span>
                                                                        <img src="images/loading-gif3.gif" class="img-load-terminer" style="height: 30px;width: 30px;display:none;" alt="GIF" srcset=""></button>

                                                                        <button tabindex="1" type="button" class="btn btn-danger annuler" data-toggle="modal" <?php echo  "data-target=#msg_ann_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                            <span class="glyphicon glyphicon-remove"></span>

                                                                        </button>

                                                                    <!-- </div> -->

                                                                </form>

                                                                <!--*******************************Fin Terminer Pagnet****************************************-->          

                                                                <!--*******************************Debut Annuler Pagnet****************************************-->

                                                                <!-- <div class="col-md-1 col-sm-1 col-xs-1">   -->

                                                                <!-- </div>   -->



                                                                <div class="modal fade" <?php echo  "id=msg_ann_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                    <div class="modal-dialog">

                                                                        <!-- Modal content-->

                                                                        <div class="modal-content">

                                                                            <div class="modal-header panel-primary">

                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                <h4 class="modal-title">Confirmation</h4>

                                                                            </div>

                                                                            <form class="form-inline noImpr"  id="factForm" method="post"  >

                                                                                <div class="modal-body">

                                                                                    <p><?php echo "Voulez-vous annuler le panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>

                                                                                    <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>

                                                                                </div>

                                                                                <div class="modal-footer">

                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                    <button type="button" name="btnAnnulerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnAnnulerPagnet_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-annulerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                                </div>

                                                                            </form>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                                <!--*******************************Fin Annuler Pagnet****************************************-->

                                                            </div>

                                                        </div>

                                                        <table id="tablePanier<?= $pagnet['idPagnet'];?>" class="tabPanier table"  width="100%" >

                                                            <thead>

                                                                <tr>

                                                                    <th>Référence</th>

                                                                    <th>Quantité</th>

                                                                    <th class="hidden-sm hidden-xs">Forme</th>

                                                                    <th>Prix Public</th>

                                                                </tr>

                                                            </thead>

                                                            <tbody>

                                                                <?php

                                                                    $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                                    $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                                    while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                                        <tr>

                                                                            <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                                            <td>

                                                                                <?php if ($ligne['forme']=='Transaction'){ ?>

                                                                                        

                                                                                <?php }

                                                                                else{ ?>

                                                                                    <?php

                                                                                        if($ligne['classe']==0){

                                                                                            if($ligne['idStock']!=0){

                                                                                                $sqlS="SELECT * FROM `".$nomtableStock."` where idStock=".$ligne['idStock'];

                                                                                                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                                                                                                $stock = mysql_fetch_assoc($resS) ;

                                                                                            

                                                                                                $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$stock['idDesignation'];

                                                                                                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                                                                                                $designation = mysql_fetch_assoc($resD);

                                                                                                ?>  

                                                                                                    <input class="quantite form-control filedReadonly<?= $pagnet['idPagnet'] ; ?>" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                                                onkeyup="modif_Quantite_Ph(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 70%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                                        />

                                                                                                <?php 

                                                                                            }

                                                                                            else{

                                                                                                $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

                                                                                                $resD=mysql_query($sqlD) or die ("select Designation impossible =>".mysql_error());

                                                                                                $designation = mysql_fetch_assoc($resD);

                                                                                                ?>  

                                                                                                <input class="quantite form-control filedReadonly<?= $pagnet['idPagnet'] ; ?>" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                                                onkeyup="modif_Quantite_PhP(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" style="width: 70%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?> value="<?= $ligne['quantite'];?>"/>

                                                                                                <?php 



                                                                                            }

                                                                                        }

                                                                                        if($ligne['classe']==1 || $ligne['classe']==2) { ?> 

                                                                                            <input class="quantite form-control filedReadonly<?= $pagnet['idPagnet'] ; ?>" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                                            onkeyup="modif_QuantiteSDP(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" style="width: 70%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                                    />

                                                                                        <?php  

                                                                                        }

                                                                                        if($ligne['classe']==5 || $ligne['classe']==7 ) {?>

                                                                                            <?php echo 'Montant'; ?>

                                                                                        <?php }

                                                                                } ?>

                                                                            </td>

                                                                            <td class="hidden-sm hidden-xs"><?php echo $ligne['forme']; ?> </td>

                                                                            <td>

                                                                                <input class="prixPublic form-control filedReadonly<?= $pagnet['idPagnet'] ; ?>" style="width: 70%"  type="number" <?php echo  "id=prixPublic".$ligne['prixPublic'].""; ?>  <?php echo  "value=".$ligne['prixPublic'].""; ?>

                                                                                    onkeyup="modif_Prix_Ph(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" />

                                                                            </td>

                                                                            <td>

                                                                                <button type="submit" 	 class="btn btn-warning pull-right btn_Retourner_Produit btn_Retourner_Produit<?= $pagnet['idPagnet'] ; ?>" data-toggle="modal" <?php echo  "data-target=#msg_rtrnAvant_ligne".$ligne['numligne'] ; ?>>

                                                                                        <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                </button>



                                                                                <div class="modal fade" <?php echo  "id=msg_rtrnAvant_ligne".$ligne['numligne'] ; ?> role="dialog">

                                                                                    <div class="modal-dialog">

                                                                                        <!-- Modal content-->

                                                                                        <div class="modal-content">

                                                                                            <div class="modal-header panel-primary">

                                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                                <h4 class="modal-title">Confirmation</h4>

                                                                                            </div>

                                                                                            <form class="form-inline noImpr" id="factForm" method="post">

                                                                                                <div class="modal-body">

                                                                                                    <p><?php echo  "Voulez-vous annuler cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>

                                                                                                    <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                                                    <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >

                                                                                                    <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >

                                                                                                    <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >

                                                                                                    <input type="hidden" name="forme" <?php echo  "value=".$ligne['forme'].""; ?> >

                                                                                                    <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                                    <input type="hidden" name="prixPublic" 	<?php echo  "value=".$ligne['prixPublic'].""; ?> >

                                                                                                    <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                                    <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                                </div>

                                                                                                <div class="modal-footer">

                                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                                    <button type="button" name="btnRetourAvant" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourAvant_Ph">Confirmer</button>

                                                                                                </div>

                                                                                            </form>

                                                                                        </div>

                                                                                    </div>

                                                                                </div>

                                                                            </td>

                                                                        </tr>

                                                                            <?php  

                                                                    }  

                                                                ?>

                                                            </tbody>

                                                        </table>
                                                        <div id="footerContent<?= $pagnet['idPagnet'];?>" style="display:none;">

                                                            <div>

                                                                <?php echo  '********************************************* <br/>'; ?>

                                                                <?php echo  'TOTAL : <span id="total_p'.$pagnet['idPagnet'].'"></span> <br/>'; ?>

                                                            </div>
                                                            <div>

                                                                <?php  echo 'Remise <span id="taux_p'.$pagnet['idPagnet'].'"></span><br/>';?>                                            

                                                            </div>

                                                            <div>

                                                                <?php echo  '<b>Net à payer : <span id="apayer_p'.$pagnet['idPagnet'].'"></span></b><br/>'; ?>

                                                            </div>

                                                                <?php if($_SESSION['compte']==1):?>

                                                            <div style="display:none;" id="divcompte_p<?= $pagnet['idPagnet'];?>">

                                                                <?php  echo 'Compte : <span id="compte_p'.$pagnet['idPagnet'].'"></span><br/>';?>

                                                            </div>

                                                            <div style="display:none;" id="divavance_p<?= $pagnet['idPagnet'];?>">

                                                                <?php  echo 'Avance : <span id="avance_p'.$pagnet['idPagnet'].'"></span><br/>';?>

                                                                <?php  echo 'Reste : <span id="reste_p'.$pagnet['idPagnet'].'"></span><br/>';?>

                                                            </div>

                                                            <div style="display:none;" id="divcompteavance_p<?= $pagnet['idPagnet'];?>">

                                                                <?php  echo 'Compte avance : <span id="compteAvance_p'.$pagnet['idPagnet'].'"></span><br/>';?>

                                                            </div>

                                                            <?php endif; ?>

                                                            <div style="display:none;" id="divversement_p<?= $pagnet['idPagnet'];?>">

                                                                <?php  echo 'Espèces : <span id="versement_p'.$pagnet['idPagnet'].'"></span><br/>';?>

                                                            </div>

                                                            <div style="display:none;" id="divrendu_p<?= $pagnet['idPagnet'];?>">

                                                                <?php  echo 'Rendu : <span id="rendu_p'.$pagnet['idPagnet'].'"></span><br/>';?>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                          <?php

                                        }

                                    }

                                    else if($pagnetJour[2]==2){

                                        $sqlT2="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet='".$pagnetJour[1]."'  ";

                                        $resT2 = mysql_query($sqlT2) or die ("persoonel requête 2".mysql_error());

                                        $mutuelle = mysql_fetch_assoc($resT2);

                                        if($mutuelle!=null){?>

                                            <div class="panel panel-default" id="panelPanier<?= $mutuelle['idMutuellePagnet'];?>">

                                                <div class="panel-heading">

                                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#mutuelle".$mutuelle['idMutuellePagnet']."" ; ?>  class="panel-title expand">

                                                        <div class="right-arrow pull-right">+</div>

                                                        <a class="row" href="#">

                                                            <span class="noImpr col-md-2 col-sm-2 col-xs-12" id="panierEncoursMutuelle<?= $mutuelle['idMutuellePagnet'];?>"> Panier <?php echo ': En cours ...'; ?> </span>

                                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12"> Date: <?php echo $mutuelle['datepagej']." ".$mutuelle['heurePagnet']; ?> </span>

                                                            <!-- <span class="spanDate noImpr">Heure: <?php //echo $mutuelle['heurePagnet']; ?></span> -->

                                                            <?php	$sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idMutuellePagnet=".$mutuelle['idMutuellePagnet']." ";

                                                                    $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                                                                    $TotalT = mysql_fetch_array($resT) ;

                                                            ?>

                                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total panier: <span <?php echo  "id=somme_Total".$mutuelle['idMutuellePagnet']; ?> ><?php echo $TotalT[0]; ?>  </span></span>

                                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total à payer: <span <?php echo  "id=somme_Apayer".$mutuelle['idMutuellePagnet'] ; ?> ><?php echo ($TotalT[0] - (($TotalT[0] * $mutuelle['taux'])/100)); ?> </span></span>

                                                        </a>

                                                    </h4>

                                                </div>

                                                <div <?php echo  "id=mutuelle".$mutuelle['idMutuellePagnet']."" ; ?> class="panel-collapse collapse in" >

                                                    <div class="panel-body">
                                                        
                                                        <div class="" id="btnContent_M<?= $mutuelle['idMutuellePagnet'];?>" style="display:none;">
                                                            
                                                                <!--*******************************Debut Retourner Pagnet****************************************-->

                                                                <?php if ($mutuelle['iduser']==$_SESSION['iduser']){ ?>

                                                                    <button type="button" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$mutuelle['idMutuellePagnet'] ; ?>>

                                                                        <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                    </button>

                                                                    <?php }

                                                                    else {  ?>

                                                                    <button disabled="true" type="button" class="btn btn-danger pull-right" data-toggle="modal" >

                                                                        <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                    </button>

                                                                    <?php }?>



                                                                    <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet".$mutuelle['idMutuellePagnet'] ; ?> role="dialog">

                                                                    <div class="modal-dialog">

                                                                        <!-- Modal content-->

                                                                        <div class="modal-content">

                                                                            <div class="modal-header panel-primary">

                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                <h4 class="modal-title">Confirmation</h4>

                                                                            </div>

                                                                            <form class="form-inline noImpr"  id="factForm" method="post"  >

                                                                                <div class="modal-body">

                                                                                    <p><?php echo "Voulez-vous retourner le panier numéro <b>".$mutuelle['idMutuellePagnet']."<b>" ; ?></p>

                                                                                    <input type="hidden" name="idMutuellePagnet" id="idMutuellePagnet"  <?php echo  "value='".$mutuelle['idMutuellePagnet']."'" ; ?>>

                                                                                </div>

                                                                                <div class="modal-footer">

                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                    <button type="button" name="btnRetournerImputation" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" class="btn btn-success btnRetournerImputation">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                                </div>

                                                                            </form>

                                                                        </div>

                                                                    </div>

                                                                    </div>

                                                                    <!--*******************************Fin Retourner Pagnet****************************************-->



                                                                    <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_mutuelle".$mutuelle['idMutuellePagnet'] ; ?>>

                                                                    Facture

                                                                    </button>



                                                                    <div class="modal fade" <?php echo  "id=msg_fact_mutuelle".$mutuelle['idMutuellePagnet'] ; ?> role="dialog">

                                                                    <div class="modal-dialog">

                                                                    <!-- Modal content-->

                                                                    <div class="modal-content">

                                                                        <div class="modal-header panel-primary">

                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                            <h4 class="modal-title">Informations Client</h4>

                                                                        </div>

                                                                        <form class="form-inline noImpr"  method="post" action="pdfFacturePharmacie.php" target="_blank" >

                                                                            <div class="modal-body">

                                                                                <div class="row">

                                                                                    <div class="col-xs-6">

                                                                                        <label for="reference">Adresse Client</label>

                                                                                        <input type="text" class="form-control" name="adresse" >

                                                                                    </div>

                                                                                    <div class="col-xs-6">

                                                                                        <label for="reference">Telephone Client</label>

                                                                                        <input type="text" class="form-control" name="telephone" >

                                                                                    </div>

                                                                                </div>

                                                                                <input type="hidden" name="idMutuellePagnet" id="idMutuellePagnet"  <?php echo  "value='".$mutuelle['idMutuellePagnet']."'" ; ?>>

                                                                            </div>

                                                                            <div class="modal-footer">

                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                <button type="submit" name="btnFacture" class="btn btn-success">Confirmer</button>

                                                                            </div>

                                                                        </form>

                                                                    </div>

                                                                    </div>

                                                                    </div>


                                                                    <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$mutuelle['idMutuellePagnet'] ;?>').submit();">

                                                                    Ticket de Caisse

                                                                    </button>


                                                                    <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$mutuelle['idMutuellePagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                    method="post" action="barcodeFacture.php" >

                                                                    <input type="hidden" name="idMutuellePagnet" id="idMutuellePagnet"  <?php echo  "value=".$mutuelle['idMutuellePagnet']."" ; ?> >

                                                                    </form>
                                                        </div>

                                                        <div class="cache_btn_Terminer" id="cache_btn_Terminer_M<?= $mutuelle['idMutuellePagnet']; ?>">

                                                            <!--*******************************Debut Ajouter Ligne****************************************-->

                                                            <form method="post" class="form-inline pull-left noImpr ajouterProdFormMutuelle row" id="ajouterProdFormMutuelle<?= $mutuelle['idMutuellePagnet'];?>" style="width:100%">

                                                                <input type="text" class="inputbasic form-control col-md-3 col-sm-3 col-xs-4 codeBarreLigneMutuelle" name="codeBarre" id="panier_<?= $mutuelle['idMutuellePagnet'];?>" autofocus="" autocomplete="off" required />

                                                                <input type="hidden" name="idMutuellePagnet" id="idMutuellePagnet" <?php echo  "value=".$mutuelle['idMutuellePagnet']."" ; ?> >

                                                                <input type="hidden" id="typeVenteM" value="4"/>

                                                                <!-- <input type="hidden" id="typeClientM" value="2"/> -->

                                                                    <!-- <span id="reponseV"></span> -->

                                                                <!-- <button tabindex="1" type="submit" name="btnEnregistrerCodeBarreMutuelle"

                                                                id="btnEnregistrerCodeBarreMutuelleAjaxPh" class="btn btn-primary btnxs " ><span class="glyphicon glyphicon-plus" ></span></button><div id="reponseS"></div> -->

                                                                

                                                                <!-- <div class="clientMutuelleForm"> -->

                                                                <!-- <form class="form-inline noImpr" method="post" onsubmit="return false"> -->

                                                                <!--<div class="clientMutuelleForm"> -->

                                                                <span class="reponseClient">

                                                                    <input type="text" id="clientMutuelle<?= $mutuelle['idMutuellePagnet'];?>" class="form-control clientMutuelleInput col-md-3 col-sm-3 col-xs-4 clientMutuelle"  data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" value="<?php echo $mutuelle['adherant'] ; ?>"  placeholder="Adherant...." autocomplete="off"  />

                                                                </span> 

                                                                <input type="text" id="codeAdherantMutuelle<?= $mutuelle['idMutuellePagnet'];?>" class="form-control col-md-3 col-sm-3 col-xs-4 codeAdherantMutuelle" value="<?php echo $mutuelle['codeAdherant'] ; ?>"  placeholder="Code Adherant...."   />

                                                                

                                                                <input type="text" class="form-control col-md-3 col-sm-3 col-xs-4 codeBeneficiaire" id="codeBeneficiaire<?= $mutuelle['idMutuellePagnet'];?>" name="codeBeneficiaire" value="<?php echo $mutuelle['codeBeneficiaire'] ; ?>" placeholder="Code Beneficiaire...."  />

                                                                <input type="text" class="form-control col-md-3 col-sm-3 col-xs-4 numeroRecu" id="numeroRecu<?= $mutuelle['idMutuellePagnet'];?>" name="numeroRecu" value="<?php echo $mutuelle['numeroRecu'] ; ?>" placeholder="Numero Reçu...."  />

                                                                <input type="date" class="form-control col-md-3 col-sm-3 col-xs-4 dateRecu" id="dateRecu<?= $mutuelle['idMutuellePagnet'];?>" name="dateRecu" value="<?php echo $mutuelle['dateRecu'] ; ?>" placeholder="Date Reçu.."  />

                                                                <!-- </div> -->                                                                   

                                                                <!-- <div id="divImputation" > -->

                                                                <!-- </div> -->

                                                                <!-- </form> -->

                                                                <!-- </div> -->

                                                                

                                                            </form>

                                                            <!--*******************************Fin Ajouter Ligne****************************************-->

                                                            <div class="row content2" style="width:100%">

                                                            <!-- <div class="col-md-8 col-sm-8 col-xs-12 content2"> -->

                                                            <!--*******************************Debut Terminer Pagnet****************************************-->

                                                                <form class="form-inline noImpr factFormM" id="factFormM">

                                                                    <!-- <span style="margin-left:50px;"> Mutuelle </span>  -->

                                                                    <select class="form-control col-md-2 col-sm-2 col-xs-3 idMutuelle" placeholder="Mutuelle" name="idMutuelle" <?php echo  "id=mutuellePagnet".$mutuelle['idMutuellePagnet'].""; ?> onchange="modif_MutuellePagnet(this.value)" >  >
                                                                        <?php
                                                                            if ($mutuelle['idMutuelle']!=0 && $mutuelle['idMutuelle']!=null) {
                                                                                $sqlE="SELECT * FROM `".$nomtableMutuelle."` where idMutuelle=".$mutuelle["idMutuelle"]." ";

                                                                                $resE = mysql_query($sqlE) or die ("persoonel requête 2".mysql_error());

                                                                                if($resE){

                                                                                    $exMtlle=mysql_fetch_array($resE);

                                                                                    echo '<option selected="true" value="'.$exMtlle["idMutuelle"].'§'.$mutuelle['idMutuellePagnet'].'" >'.$exMtlle['nomMutuelle'].'</option>';

                                                                                }
                                                                            }
                                                                            else{

                                                                                echo '<option>--Choisir un mutuelle--</option>';

                                                                            }

                                                                            $sqlM="SELECT * from `".$nomtableMutuelle."` order by idMutuelle desc";

                                                                            $resM=mysql_query($sqlM);

                                                                            while($mtlle=mysql_fetch_array($resM)){

                                                                            echo '<option value="'.$mtlle["idMutuelle"].'§'.$mutuelle['idMutuellePagnet'].'" >'.$mtlle['nomMutuelle'].'</option>';

                                                                            }

                                                                        ?>

                                                                    </select>

                                                                    <select class="form-control col-md-2 col-sm-2 col-xs-3 tauxMutuelle multiMutuelle"  <?php echo  "id=multiMutuelle".$mutuelle['idMutuellePagnet'].""; ?> onchange="modif_MutuelleTaux(this.value)" >  >
                                                                        <?php
                                                                            echo '<option selected disabled="true" value="'.$mutuelle['taux'].'§'.$tauxMtlle["idMutuelle"].'§'.$mutuelle['idMutuellePagnet'].'" >'.$mutuelle['taux'].' %</option>';

                                                                            $sqlTE="SELECT * FROM `".$nomtableMutuelle."` where idMutuelle=".$mutuelle["idMutuelle"]." ";
                                                                            $resTE = mysql_query($sqlTE) or die ("persoonel requête 2".mysql_error());

                                                                            if($resTE){
                                                                                $tauxMtlle=mysql_fetch_array($resTE);

                                                                                echo '<option value="'.$tauxMtlle["tauxMutuelle"].'§'.$tauxMtlle["idMutuelle"].'§'.$mutuelle['idMutuellePagnet'].'" >'.$tauxMtlle['tauxMutuelle'].' %</option>';
                                                                                if($tauxMtlle["taux1"]!=0){
                                                                                    echo '<option value="'.$tauxMtlle["taux1"].'§'.$tauxMtlle["idMutuelle"].'§'.$mutuelle['idMutuellePagnet'].'" >'.$tauxMtlle['taux1'].' %</option>';
                                                                                }
                                                                                if($tauxMtlle["taux2"]!=0){
                                                                                    echo '<option value="'.$tauxMtlle["taux2"].'§'.$tauxMtlle["idMutuelle"].'§'.$mutuelle['idMutuellePagnet'].'" >'.$tauxMtlle['taux2'].' %</option>';
                                                                                }
                                                                                if($tauxMtlle["taux3"]!=0){
                                                                                    echo '<option value="'.$tauxMtlle["taux3"].'§'.$tauxMtlle["idMutuelle"].'§'.$mutuelle['idMutuellePagnet'].'" >'.$tauxMtlle['taux3'].' %</option>';
                                                                                }
                                                                                if($tauxMtlle["taux4"]!=0){
                                                                                    echo '<option value="'.$tauxMtlle["taux4"].'§'.$tauxMtlle["idMutuelle"].'§'.$mutuelle['idMutuellePagnet'].'" >'.$tauxMtlle['taux4'].' %</option>';
                                                                                }
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                    
                                                                    <?php if($mutuelle['versement']!=0 && $mutuelle['versement']!=null){  ?> 

                                                                        <input type="number" name="versementMutuelle" id="versementMutuelle<?= $mutuelle['idMutuellePagnet']; ?>" <?= (@$mutuelle['idCompte']!=0) ? 'style="display:none;"' : '';?>

                                                                        class="versementMutuelle form-control col-md-2 col-sm-2 col-xs-3" <?php echo "value=".$mutuelle['versement'].""; ?> >

                                                                        <?php }

                                                                    else{   ?>

                                                                        <input type="number" name="versementMutuelle" id="versementMutuelle<?= $mutuelle['idMutuellePagnet']; ?>" <?= (@$mutuelle['idCompte']!=0) ? 'style="display:none;"' : '';?>

                                                                        class="versementMutuelle form-control col-md-2 col-sm-2 col-xs-3" placeholder="Espèce...">

                                                                    <?php } ?> 


                                                                    <?php if ($_SESSION['compte']==1) { ?>

                                                                        <select class="compte compteMutuelle <?= $mutuelle['idMutuellePagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3"  data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" name="compte" data-type="mutuelle"  style="margin-left:5px;" <?php echo  "id=compte".$mutuelle['idMutuellePagnet'] ; ?>>

                                                                            <!-- <option value="caisse">Caisse</option> -->

                                                                            <?php                                                     

                                                                            if ($mutuelle['idCompte']!=0) {

                                                                                                                                            

                                                                                $sqlGetCompte3="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$mutuelle['idCompte'];

                                                                                $resPay3 = mysql_query($sqlGetCompte3) or die ("persoonel requête 2".mysql_error());

                                                                                $cpt = mysql_fetch_array($resPay3);

                                                                            ?>

                                                                                <option value="<?= $mutuelle['idCompte'];?>"><?= $cpt['nomCompte'];?></option>

                                                                            <?php } 

                                                                            foreach ($cpt_array as $key) { ?>

                                                                                <option value="<?= $key['idCompte'];?>"><?= $key['nomCompte'];?></option>

                                                                            <?php } ?>

                                                                        </select>                                                     



                                                                        <?php if ($mutuelle['type']=='30') {

                                                                                

                                                                            $sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$mutuelle['idClient']."";

                                                                            $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());

                                                                            $client = mysql_fetch_assoc($res3);

                                                                        ?>

                                                                            <input type="text" class="client clientInput clientMutuelle form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" data-type="mutuelle" name="clientInput" id="clientInput<?= $mutuelle['idMutuellePagnet'];?>" autocomplete="off" value="<?= $client['prenom']." ".$client['nom']; ?>"/>

                                                                            <input type="number" class="avanceInput avanceInputM form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" name="avanceInput" id="avanceInput<?= $mutuelle['idMutuellePagnet'];?>" autocomplete="off" value="<?= $mutuelle['avance']; ?>"/>

                                                                            <select class="compteAvance compteAvanceM <?= $mutuelle['idMutuellePagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" name="compteAvance" <?php echo "id=compteAvance".$mutuelle['idMutuellePagnet'] ; ?>>

                                                                                <!-- <option value="caisse">Caisse</option> -->

                                                                                <?php 

                                                                                foreach ($cpt_array as $key) {  

                                                                                    if($key['idCompte'] != 2){

                                                                                    ?>

                                                                                        <option value="<?= $key['idCompte'];?>"><?= $key['nomCompte'];?></option>

                                                                                    <?php } 

                                                                                    } ?>

                                                                            </select> 

                                                                        <?php

                                                                                # code...

                                                                            } else {

                                                                                # code...                                                            

                                                                        ?> 

                                                                                <input type="text" class="client clientInput clientMutuelle form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" data-type="mutuelle" name="clientInput" id="clientInput<?= $mutuelle['idMutuellePagnet'];?>" autocomplete="off" placeholder="Client"/>

                                                                                <input type="number" class="avanceInput avanceInputM form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" name="avanceInput" id="avanceInput<?= $mutuelle['idMutuellePagnet'];?>" autocomplete="off" placeholder="Avance"/>

                                                                                <select class="compteAvance compteAvanceM <?= $mutuelle['idMutuellePagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-idPanier="<?= $mutuelle['idMutuellePagnet'] ; ?>" name="compteAvance" <?php echo "id=compteAvance".$mutuelle['idMutuellePagnet'] ; ?>>

                                                                                    <!-- <option value="caisse">Caisse</option> -->

                                                                                <?php foreach ($cpt_array as $key) { 

                                                                                    if($key['idCompte'] != 2){

                                                                                    ?>

                                                                                        <option value="<?= $key['idCompte'];?>"><?= $key['nomCompte'];?></option>

                                                                                    <?php }

                                                                                    } ?>

                                                                                </select> 

                                                                        <?php } ?> 

                                                                    <?php } ?>                                                                     



                                                                    <input type="hidden" name="idMutuellePagnet"   <?php echo  "value=".$mutuelle['idMutuellePagnet']."" ; ?>>

                                                                    <input type="hidden" name="totalp" <?php echo  "id=totalp".$mutuelle['idMutuellePagnet']."" ; ?> value="<?php echo $mutuelle['totalp']; ?>" >

                                                                    <button type="button" name="btnTerminerImputation" <?php echo  "id=btnTerminerMutuelle".$mutuelle['idMutuellePagnet']."" ; ?> class="btn btn-success btn_Termine_Panier_M terminer" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" ><span class="glyphicon glyphicon-ok"></span>
                                                                        <img src="images/loading-gif3.gif" class="img-load-terminer-M" style="height: 30px;width: 30px;display:none;" alt="GIF" srcset=""></button>
                                                                    </button>

                                                                    <button tabindex="1" type="button" 	 class="btn btn-danger annuler annulerMutuelle" data-toggle="modal" <?php echo  "data-target=#msg_ann_imputation".$mutuelle['idMutuellePagnet'] ; ?>>

                                                                        <span class="glyphicon glyphicon-remove"></span>

                                                                    </button>

                                                                </form>

                                                            <!--*******************************Fin Terminer Pagnet****************************************-->

                                                            <!--*******************************Debut Annuler Pagnet****************************************-->

                                                                <div class="modal fade" <?php echo  "id=msg_ann_imputation".$mutuelle['idMutuellePagnet'] ; ?> role="dialog">

                                                                    <div class="modal-dialog">

                                                                        <!-- Modal content-->

                                                                        <div class="modal-content">

                                                                            <div class="modal-header panel-primary">

                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                <h4 class="modal-title">Confirmation</h4>

                                                                            </div>

                                                                            <form class="form-inline noImpr"  id="factForm" method="post"  >

                                                                                <div class="modal-body">

                                                                                    <p><?php echo "Voulez-vous annuler le panier numéro <b>".$mutuelle['idMutuellePagnet']."</b>" ; ?></p>

                                                                                    <input type="hidden" name="idMutuellePagnet" id="idMutuellePagnet"  <?php echo  "value='".$mutuelle['idMutuellePagnet']."'" ; ?>>

                                                                                </div>

                                                                                <div class="modal-footer">

                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                    <button type="button" name="btnAnnulerImputation" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" class="btn btn-success btnAnnulerImputation">Confirmer<img src="images/loading-gif3.gif" class="img-load-annulerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                                </div>

                                                                            </form>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            <!--*******************************Fin Annuler Pagnet****************************************-->



                                                            </div>

                                                        </div>

                                                        <table id="tableMutuelle<?= $mutuelle['idMutuellePagnet'];?>" class="tabMutuelle table"  width="100%" >

                                                            <thead class="noImpr">

                                                                <tr>

                                                                    <th>Référence</th>

                                                                    <th>Quantité</th>

                                                                    <th>Forme</th>

                                                                    <th>Prix Public</th>

                                                                </tr>

                                                            </thead>

                                                            <tbody>

                                                                <?php

                                                                    $sql8="SELECT * FROM `".$nomtableLigne."` where idMutuellePagnet=".$mutuelle['idMutuellePagnet']." ORDER BY numligne DESC";

                                                                    $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                                    while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                                        <tr>

                                                                            <td class="designation">

                                                                                <?php

                                                                                    if($ligne['classe']==6){

                                                                                        echo '<input class="form-control" style="width: 100%"  type="text" value="'.$ligne['designation'].'"

                                                                                            onkeyup="modif_Designation(this.value,'.$ligne['numligne'].','.$mutuelle['idMutuellePagnet'].')" />

                                                                                        ';

                                                                                    }

                                                                                    else{?>

                                                                                        <?php echo $ligne['designation']; 

                                                                                    }

                                                                                ?>

                                                                            </td>

                                                                            <td> 

                                                                                <?php

                                                                                    if($ligne['classe']==0){

                                                                                        if($ligne['idStock']!=0){ ?>  

                                                                                        <?php 

                                                                                        }

                                                                                        else{

                                                                                            $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

                                                                                            $resD=mysql_query($sqlD) or die ("select Designation impossible =>".mysql_error());

                                                                                            $designation = mysql_fetch_assoc($resD);

                                                                                    

                                                                                            ?>  

                                                                                                <input class="quantite form-control filedReadonly_M<?=  $mutuelle['idMutuellePagnet']; ?>" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                                            onkeyup="modif_Quantite_Mutuelle(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $mutuelle['idMutuellePagnet']; ?>)" style="width: 70%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                                    >

                                                                                            <?php 



                                                                                        }

                                                                                    }

                                                                                    else {?>

                                                                                        <?php echo 'Montant'; ?>

                                                                                <?php }?>

                                                                            </td>

                                                                            <td class="forme "><?php echo $ligne['forme']; ?> </td>

                                                                            <td>

                                                                                <input class="prixPublic form-control filedReadonly_M<?=  $mutuelle['idMutuellePagnet']; ?>" style="width: 70%"  type="number" <?php echo  "id=prixPublic".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['prixPublic'].""; ?>

                                                                                            onkeyup="modif_Prix_Mutuelle(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $mutuelle['idMutuellePagnet']; ?>)" >

                                                                            </td>

                                                                            <td>

                                                                                <button type="submit" 	 class="btn btn-warning pull-right btn_Retourner_Produit btn_Retourner_Produit_M<?= $mutuelle['idMutuellePagnet'] ; ?>" data-toggle="modal" <?php echo  "data-target=#msg_rtrnAvant_ligne".$ligne['numligne'] ; ?>>

                                                                                        <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                </button>



                                                                                <div class="modal fade" <?php echo  "id=msg_rtrnAvant_ligne".$ligne['numligne'] ; ?> role="dialog">

                                                                                    <div class="modal-dialog">

                                                                                        <!-- Modal content-->

                                                                                        <div class="modal-content">

                                                                                            <div class="modal-header panel-primary">

                                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                                <h4 class="modal-title">Confirmation</h4>

                                                                                            </div>

                                                                                            <form class="form-inline noImpr" id="factForm" method="post">

                                                                                                <div class="modal-body">

                                                                                                    <p><?php echo  "Voulez-vous annuler cette ligne du panier numéro <b>".$mutuelle['idMutuellePagnet']."</b>" ; ?></p>

                                                                                                    <input type="hidden" name="idPagnet" <?php echo  "value=".$mutuelle['idMutuellePagnet']."" ; ?> >

                                                                                                    <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >

                                                                                                    <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >

                                                                                                    <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >

                                                                                                    <input type="hidden" name="forme" <?php echo  "value=".$ligne['forme'].""; ?> >

                                                                                                    <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                                    <input type="hidden" name="prixPublic" 	<?php echo  "value=".$ligne['prixPublic'].""; ?> >

                                                                                                    <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                                    <input type="hidden" name="totalp"<?php echo  "value=".$mutuelle['totalp'].""; ?> >

                                                                                                </div>

                                                                                                <div class="modal-footer">

                                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                                    <button type="button" name="btnRetourAvant" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourAvant_Ph">Confirmer</button>

                                                                                                </div>

                                                                                            </form>

                                                                                        </div>

                                                                                    </div>

                                                                                </div>

                                                                            </td>

                                                                        </tr>

                                                                        <?php  

                                                                    }  

                                                                ?>

                                                            </tbody>

                                                        </table>

                                                        <div id="footerContent_M<?= $mutuelle['idMutuellePagnet'];?>" style="display:none;"> 

                                                            <!--*******************************Debut Total Facture****************************************-->

                                                                <div class="col-sm-4 ">

                                                                    <div>

                                                                        <?php echo  '********************************************* <br/>'; ?>

                                                                        <?php echo  'TOTAL : <span id="total_m'.$mutuelle['idMutuellePagnet'].'"></span><br/>'; ?>

                                                                    </div>

                                                                    <div>

                                                                        <?php echo  '<b>Net à payer Adherant : <span id="apayer_adherant'.$mutuelle['idMutuellePagnet'].'"></span></b><br/>'; ?>

                                                                    </div>

                                                                    <div>

                                                                        <?php echo  '<b>Net à payer Mutuelle  : <span id="apayer_mutuelle'.$mutuelle['idMutuellePagnet'].'"></span></b><br/>'; ?>

                                                                    </div>

                                                                        <?php if($_SESSION['compte']==1):?>

                                                                            <div style="display:none;" id="divcompte_m<?= $mutuelle['idMutuellePagnet'];?>">

                                                                                <?php  echo 'Compte : <span id="compte_m'.$mutuelle['idMutuellePagnet'].'"></span><br/>';?>

                                                                            </div>

                                                                            <div style="display:none;" id="divavance_m<?= $mutuelle['idMutuellePagnet'];?>">

                                                                                <?php  echo 'Avance : <span id="avance_m'.$mutuelle['idMutuellePagnet'].'"></span><br/>';?>

                                                                                <?php  echo 'Reste : <span id="reste_m'.$mutuelle['idMutuellePagnet'].'"></span><br/>';?>

                                                                            </div>

                                                                            <div style="display:none;" id="divcompteavance_m<?= $mutuelle['idMutuellePagnet'];?>">

                                                                                <?php  echo 'Compte avance : <span id="compteAvance_m'.$mutuelle['idMutuellePagnet'].'"></span><br/>';?>

                                                                            </div>

                                                                    <?php endif; ?>

                                                                    <div style="display:none;" id="divversement_m<?= $mutuelle['idMutuellePagnet'];?>">

                                                                        <?php  echo 'Espèces : <span id="versement_m'.$mutuelle['idMutuellePagnet'].'"></span><br/>';?>

                                                                    </div>

                                                                    <div style="display:none;" id="divrendu_m<?= $mutuelle['idMutuellePagnet'];?>">

                                                                        <?php  echo 'Rendu : <span id="rendu_m'.$mutuelle['idMutuellePagnet'].'"></span><br/>';?>

                                                                    </div>

                                                                </div>

                                                                <div class="col-sm-4 ">

                                                                    <div>

                                                                        <?php echo  '********************************************* <br/>'; ?>

                                                                        <?php

                                                                            echo  'Mutuelle : <span id="nom_m'.$mutuelle['idMutuellePagnet'].'"></span> (<span id="taux_m'.$mutuelle['idMutuellePagnet'].'"></span>%)<br/>';

                                                                        ?>

                                                                    </div>

                                                                    <div>

                                                                        <?php echo  '<b> Adherant : <span id="adherant_m'.$mutuelle['idMutuellePagnet'].'"></span></b><br/>'; ?>

                                                                    </div>

                                                                    <div>

                                                                        <?php echo  '<b>Code Adherant  : <span id="codeAdherant_m'.$mutuelle['idMutuellePagnet'].'"></span></b><br/>'; ?>

                                                                    </div>

                                                                </div>

                                                                <div class="col-sm-4 ">

                                                                    <div>

                                                                        <?php echo  '********************************************* <br/>'; ?>

                                                                        <?php echo  '<b>Code Beneficiaire  : <span id="codeBeneficiaire_m'.$mutuelle['idMutuellePagnet'].'"></span></b><br/>'; ?>

                                                                    </div>

                                                                    <div>

                                                                        <?php echo  '<b> Numero Reçu : <span id="numeroRecu_m'.$mutuelle['idMutuellePagnet'].'"></span></b><br/>'; ?>

                                                                    </div>

                                                                    <div>

                                                                        <?php echo  '<b>Date Reçu  : <span id="dateRecu_m'.$mutuelle['idMutuellePagnet'].'"></span></b><br/>'; ?>

                                                                    </div>

                                                                </div>

                                                            <!--*******************************Fin****************************************-->

                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                          <?php

                                        }

                                    }

                                ?>

                            <?php   } ?>

                        <!-- Fin Boucle while concernant les Paniers en cours (2 aux maximum) -->



                        <?php if (isset($_POST['produit'])) { 

                           // var_dump(1);?>

                            <!-- Debut Boucle while concernant les Paniers Vendus produit -->

                                <?php $n=$nbPaniers; 

                                    while ($ventes = mysql_fetch_assoc($resP1)) {   ?>

                                    <?php	$idmax=mysql_result($resA,0); 

                                            // var_dump($idmax);

                                    ?>

                                    <?php

                                        $vente = explode("+", $ventes['codePagnet']);

                                        if($vente[2]==1){

                                            $sqlT1="SELECT * FROM `".$nomtablePagnet."` where idPagnet='".$vente[1]."'  ";

                                            $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());

                                            $pagnet = mysql_fetch_assoc($resT1);

                                            // var_dump($pagnet);

                                            if($pagnet!=null){

                                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";

                                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                                                $ligne = mysql_fetch_assoc($res) ;

                                                if (($ligne['classe']==0 || $ligne['classe']==1) && ($pagnet['type']==0 || $pagnet['type']==30)){?>

                                                    <?php if ($pagnet['totalp']==0) {?>

                                                        <div class="panel <?= ($pagnet['idClient']==0) ? 'panel-warning' : 'panel-info'?>">

                                                            <div class="panel-heading">

                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                                                    <div class="right-arrow pull-right">+</div>

                                                                    <a href="#"> Panier <?php echo $n; ?>

                                                                        <span class="spanDate noImpr"> </span>

                                                                        <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

                                                                        <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                                                        <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                                $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                                                                                $TotalT = mysql_fetch_array($resT);

                                                                                $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                                $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());

                                                                                $TotalP = mysql_fetch_array($resP);?>

                                                                        <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                                                        <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                                        <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                                                    </a>

                                                                </h4>

                                                            </div>

                                                            <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>

                                                                <?php 

                                                                    if($idmax == $pagnet['heurePagnet']){

                                                                        ?> class="panel-collapse collapse in" <?php

                                                                    }

                                                                    else  {

                                                                        ?> class="panel-collapse collapse " <?php

                                                                    }

                                                                ?>  >

                                                                <div class="panel-body" >

                                                                    <!--*******************************Debut Retourner Pagnet****************************************-->

                                                                        <button disabled="true" type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                                <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                        </button>

        

                                                                        <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                            <div class="modal-dialog">

                                                                                <!-- Modal content-->

                                                                                <div class="modal-content">

                                                                                    <div class="modal-header panel-primary">

                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                        <h4 class="modal-title">Confirmation</h4>

                                                                                    </div>

                                                                                    <form class="form-inline noImpr"  id="factForm" method="post"  >

                                                                                        <div class="modal-body">

                                                                                            <p><?php echo "Voulez-vous retourner le panier numéro <b>".$pagnet['idPagnet']."<b>" ; ?></p>

                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>

                                                                                        </div>

                                                                                        <div class="modal-footer">

                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                            <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                                        </div>

                                                                                    </form>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                            

                                                                    <!--*******************************Debut Facture****************************************-->

                                                                        <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                            Facture

                                                                        </button>

        

                                                                        <div class="modal fade" <?php echo  "id=msg_fact_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                            <div class="modal-dialog">

                                                                                <!-- Modal content-->

                                                                                <div class="modal-content">

                                                                                    <div class="modal-header panel-primary">

                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                        <h4 class="modal-title">Informations Client</h4>

                                                                                    </div>

                                                                                    <form class="form-inline noImpr"  method="post" action="pdfFacturePharmacie.php" target="_blank" >

                                                                                        <div class="modal-body">

                                                                                            <div class="row">

                                                                                                <div class="col-xs-6">

                                                                                                    <label for="reference">Prenom(s) Client</label>

                                                                                                    <input type="text" class="form-control" name="prenom" >

                                                                                                </div>

                                                                                                <div class="col-xs-6">

                                                                                                    <label for="reference">Nom Client</label>

                                                                                                    <input type="text" class="form-control" name="nom" >

                                                                                                </div>

                                                                                            </div>

                                                                                            <div class="row">

                                                                                                <div class="col-xs-6">

                                                                                                    <label for="reference">Adresse Client</label>

                                                                                                    <input type="text" class="form-control" name="adresse" >

                                                                                                </div>

                                                                                                <div class="col-xs-6">

                                                                                                    <label for="reference">Telephone Client</label>

                                                                                                    <input type="text" class="form-control" name="telephone" >

                                                                                                </div>

                                                                                            </div>

                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>

                                                                                        </div>

                                                                                        <div class="modal-footer">

                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                            <button type="submit" name="btnFacture" class="btn btn-success">Confirmer</button>

                                                                                        </div>

                                                                                    </form>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    <!--*******************************Fin Facture****************************************-->

                                                                    


                                                                    

                                                                        <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                                            Ticket de Caisse

                                                                        </button>

                                                                    

                                                                    

                                                                    <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                        method="post" action="barcodeFacture.php" >

                                                                        <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                    </form>

        

                                                                    <table class="table ">

                                                                        <thead class="noImpr">

                                                                            <tr>

                                                                                <th></th>

                                                                                <th>Référence</th>

                                                                                <th>Quantité</th>

                                                                                <th>Forme</th>

                                                                                <th>Prix Public</th>

                                                                            </tr>

                                                                        </thead>

                                                                        <tbody>

                                                                            <?php

                                                                                $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                                                $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                                                while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                                                    <tr>

                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >

                                                                                        </td>

                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                                                        <td>

                                                                                            <?php if ($ligne['forme']=='Transaction'){ ?>

                                                                                                    <?php if ($ligne['quantite']==1): ?>

                                                                                                        <?php echo  'Depot'; ?> <span class="factureFois"></span>

                                                                                                    <?php endif; ?>

                                                                                                    <?php if ($ligne['quantite']==0): ?>

                                                                                                        <?php echo  'Retrait'; ?> <span class="factureFois"></span>

                                                                                                    <?php endif; ?>

                                                                                            <?php }

                                                                                            else{ ?>

                                                                                                <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>

                                                                                            <?php } ?>

                                                                                        </td>

                                                                                        <td class="forme ">

                                                                                            <?php echo $ligne['forme']; ?>

                                                                                        </td>

                                                                                        <td>

                                                                                            <?php echo  $ligne['prixPublic']; ?>  <span class="factureFois" ></span>

                                                                                        </td>

                                                                                        <td>

                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                            </button>

        

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne".$ligne['numligne'] ; ?> role="dialog">

                                                                                                <div class="modal-dialog">

                                                                                                    <!-- Modal content-->

                                                                                                    <div class="modal-content">

                                                                                                        <div class="modal-header panel-primary">

                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                                                        </div>

                                                                                                        <form class="form-inline noImpr" id="factForm" method="post" >

                                                                                                            <div class="modal-body">

                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>

                                                                                                                <input type="hidden" name="idPagnet" id="idPagnet<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                                                                <input type="hidden" name="designation" id="designation<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['designation'].""; ?> >

                                                                                                                <input type="hidden" name="numligne" id="numligne<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['numligne'].""; ?> >

                                                                                                                <input type="hidden" name="idStock" id="idStock<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['idStock'].""; ?> >

                                                                                                                <input type="hidden" name="forme" id="forme<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['forme'].""; ?> >

                                                                                                                <input type="hidden" name="prixPublic" id="prixPublic<?= $ligne['numligne']; ?>" 	<?php echo  "value=".$ligne['prixPublic'].""; ?> >

                                                                                                                <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                                                <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                                                <div class="row">

                                                                                                                    <div class="col-xs-6">

                                                                                                                        <label for="reference">Ancienne quantite </label>

                                                                                                                        <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                                                    </div>

                                                                                                                    <div class="col-xs-6">

                                                                                                                        <label for="reference">Nouvelle quantite</label>

                                                                                                                        <!-- <input type="text" class="form-control" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> > -->
                                                                                                                        <input type="text" class="form-control inputRetourApres_Ph" name="quantite" data-numligne="<?= $ligne['numligne']; ?>" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >


                                                                                                                    </div>

                                                                                                                </div>

                                                                                                            </div>

                                                                                                            <div class="modal-footer">

                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                                                <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

                                                                                                            </div>

                                                                                                        </form>

                                                                                                    </div>

                                                                                                </div>

                                                                                            </div>

                                                                                        </td>

                                                                                    </tr>

                                                                                    <?php  

                                                                                }  

                                                                            ?>

                                                                        </tbody>

                                                                    </table>

        

                                                                    <!--*******************************Debut total Facture****************************************-->

                                                                        <div>

                                                                            <div>

                                                                                <?php echo  '********************************************* <br/>'; ?>

                                                                                <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>

                                                                            </div>
                                                                            <div>

                                                                                <?php if($pagnet['taux']!=0 && $pagnet['taux']!=null): ?>

                                                                                    <?php  echo 'Remise ('. $pagnet['taux'].' %) : '.(($pagnet['totalp'] * $pagnet['taux']) / 100).' <br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>
                                                                            <div>

                                                                                <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>

                                                                                    <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>

                                                                            </div>

                                                                                <?php if($_SESSION['compte']==1):?>

                                                                            <div>

                                                                                <?php if($pagnet['idCompte']!=0 && $pagnet['idCompte']>0): 

                                                                                        $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$pagnet['idCompte'];

                                                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());

                                                                                        $cpt = mysql_fetch_array($resPay2);

                                                                                    ?>

                                                                                    <?php  echo 'Compte : '. $cpt['nomCompte'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['avance']!=0 && $pagnet['avance']>0): 

                                                                                    ?>

                                                                                    <?php  echo 'Avance : '.$pagnet['avance'].'<br/>';?>

                                                                                    <?php  echo 'Reste : '.($pagnet['apayerPagnet'] - $pagnet['avance']).'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['avance']!=0 && $pagnet['avance']>0): 

                                                                                        $sqlGetCompteAvance="SELECT * FROM `".$nomtableVersement."` v, `".$nomtableCompte."` c where v.idCompte=c.idCompte AND idPagnet = ".$pagnet['idPagnet'];

                                                                                        $resAvance = mysql_query($sqlGetCompteAvance) or die ("persoonel requête 2".mysql_error());

                                                                                        $cptA = mysql_fetch_array($resAvance);

                                                                                    ?>

                                                                                    <?php  echo 'Compte avance : '.$cptA['nomCompte'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <?php endif; ?>

                                                                            <div>

                                                                                <?php if($pagnet['versement']!=0): ?>

                                                                                    <?php  echo 'Espèces : '.$pagnet['versement'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['restourne']!=0 && $pagnet['restourne']>0): ?>

                                                                                    <?php  echo 'Rendu : '.$pagnet['restourne'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                        </div>

                                                                    <!--*******************************Fin Total Facture****************************************-->

                                                                </div>

                                                            </div>

                                                        </div>

                                                    <?php }

                                                    else {?>

                                                        <div class="panel  <?= ($pagnet['idClient']==0) ? 'panel-success' : 'panel-info'?>">

                                                            <div class="panel-heading">

                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                                                    <div class="right-arrow pull-right">+</div>

                                                                    <a href="#"> Panier <?php echo $n; ?>

                                                                        <span class="spanDate noImpr"> </span>

                                                                        <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

                                                                        <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                                                        <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                                $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                                                                                $TotalT = mysql_fetch_array($resT);

                                                                                $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                                $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());

                                                                                $TotalP = mysql_fetch_array($resP);?>

                                                                        <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                                                        <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                                        <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                                                    </a>

                                                                </h4>

                                                            </div>

                                                            <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>

                                                                <?php 

                                                                    if($idmax == $pagnet['heurePagnet']){

                                                                        ?> class="panel-collapse collapse in" <?php

                                                                    }

                                                                    else  {

                                                                        ?> class="panel-collapse collapse " <?php

                                                                    }

                                                                ?>  >

                                                                <div class="panel-body" >

                                                                    <!--*******************************Debut Retourner Pagnet****************************************-->

                                                                        <?php if ($pagnet['iduser']==$_SESSION['iduser']){ ?>

                                                                            <button type="submit"  class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                                <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                            </button>

                                                                        <?php }

                                                                        else {  ?>

                                                                            <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >

                                                                                <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                            </button>

                                                                        <?php }?>

        

                                                                        <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                            <div class="modal-dialog">

                                                                                <!-- Modal content-->

                                                                                <div class="modal-content">

                                                                                    <div class="modal-header panel-primary">

                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                        <h4 class="modal-title">Confirmation</h4>

                                                                                    </div>

                                                                                    <form class="form-inline noImpr"  id="factForm" method="post"  >

                                                                                        <div class="modal-body">

                                                                                            <p><?php echo "Voulez-vous retourner le panier numéro <b>".$pagnet['idPagnet']."<b>" ; ?></p>

                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>

                                                                                        </div>

                                                                                        <div class="modal-footer">

                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                            <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                                        </div>

                                                                                    </form>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    <!--*******************************Fin Retourner Pagnet****************************************-->

        

                                                                    <!--*******************************Debut Facture****************************************-->

                                                                      
                                                                        

                                                                            <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                                Facture

                                                                            </button>

                                                                        


                                                                        

                                                                        <div class="modal fade" <?php echo  "id=msg_fact_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                            <div class="modal-dialog">

                                                                                <!-- Modal content-->

                                                                                <div class="modal-content">

                                                                                    <div class="modal-header panel-primary">

                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                        <h4 class="modal-title">Informations Client</h4>

                                                                                    </div>

                                                                                    <form class="form-inline noImpr"  method="post" action="pdfFacturePharmacie.php" target="_blank" >

                                                                                        <div class="modal-body">

                                                                                            <div class="row">

                                                                                                <div class="col-xs-6">

                                                                                                    <label for="reference">Prenom(s) Client</label>

                                                                                                    <input type="text" class="form-control" name="prenom" >

                                                                                                </div>

                                                                                                <div class="col-xs-6">

                                                                                                    <label for="reference">Nom Client</label>

                                                                                                    <input type="text" class="form-control" name="nom" >

                                                                                                </div>

                                                                                            </div>

                                                                                            <div class="row">

                                                                                                <div class="col-xs-6">

                                                                                                    <label for="reference">Adresse Client</label>

                                                                                                    <input type="text" class="form-control" name="adresse" >

                                                                                                </div>

                                                                                                <div class="col-xs-6">

                                                                                                    <label for="reference">Telephone Client</label>

                                                                                                    <input type="text" class="form-control" name="telephone" >

                                                                                                </div>

                                                                                            </div>

                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>

                                                                                        </div>

                                                                                        <div class="modal-footer">

                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                            <button type="submit" name="btnFacture" class="btn btn-success">Confirmer</button>

                                                                                        </div>

                                                                                    </form>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    <!--*******************************Fin Facture****************************************-->

                                                                    


                                                                    

                                                                        <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                                        Ticket de Caisse

                                                                        </button>

                                                                    

                                                                

                                                                    <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                        method="post" action="barcodeFacture.php" >

                                                                        <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                    </form>

        

                                                                    <table class="table ">

                                                                        <thead class="noImpr">

                                                                            <tr>

                                                                                <th></th>

                                                                                <th>Référence</th>

                                                                                <th>Quantité</th>

                                                                                <th>Forme</th>

                                                                                <th>Prix Public</th>

                                                                            </tr>

                                                                        </thead>

                                                                        <tbody>

                                                                            <?php

                                                                                $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                                                $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                                                while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                                                    <tr>

                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >

                                                                                        </td>

                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                                                        <td>

                                                                                            <?php if ($ligne['forme']=='Transaction'){ ?>

                                                                                                    <?php if ($ligne['quantite']==1): ?>

                                                                                                        <?php echo  'Depot'; ?> <span class="factureFois"></span>

                                                                                                    <?php endif; ?>

                                                                                                    <?php if ($ligne['quantite']==0): ?>

                                                                                                        <?php echo  'Retrait'; ?> <span class="factureFois"></span>

                                                                                                    <?php endif; ?>

                                                                                            <?php }

                                                                                            else{ ?>

                                                                                                <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>

                                                                                            <?php } ?>

                                                                                        </td>

                                                                                        <td class="forme ">

                                                                                            <?php echo $ligne['forme']; ?>

                                                                                        </td>

                                                                                        <td>

                                                                                            <?php echo  $ligne['prixPublic']; ?>  <span class="factureFois" ></span>

                                                                                        </td>

                                                                                        <td>

                                                                                            <?php if ($pagnet['iduser']==$_SESSION['iduser']){ ?>

                                                                                                <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

                                                                                                        <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                                </button>

                                                                                            <?php }

                                                                                            else {  ?>

                                                                                                <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >

                                                                                                        <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                                </button>

                                                                                            <?php }?>

        

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne".$ligne['numligne'] ; ?> role="dialog">

                                                                                                <div class="modal-dialog">

                                                                                                    <!-- Modal content-->

                                                                                                    <div class="modal-content">

                                                                                                        <div class="modal-header panel-primary">

                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                                                        </div>

                                                                                                        <form class="form-inline noImpr" id="factForm" method="post" >

                                                                                                            <div class="modal-body">

                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>

                                                                                                                <input type="hidden" name="idPagnet" id="idPagnet<?= $ligne['numligne']; ?>" <?php echo "value=".$pagnet['idPagnet']."" ; ?> >

                                                                                                                <input type="hidden" name="designation" id="designation<?= $ligne['numligne']; ?>" <?php echo "value=".$ligne['designation'].""; ?> >

                                                                                                                <input type="hidden" name="numligne" id="numligne<?= $ligne['numligne']; ?>" <?php echo "value=".$ligne['numligne'].""; ?> >

                                                                                                                <input type="hidden" name="idStock" id="idStock<?= $ligne['numligne']; ?>" <?php echo "value=".$ligne['idStock'].""; ?> >

                                                                                                                <input type="hidden" name="forme" id="forme<?= $ligne['numligne']; ?>" <?php echo "value=".$ligne['forme'].""; ?> >

                                                                                                                <input type="hidden" name="prixPublic" id="prixPublic<?= $ligne['numligne']; ?>" <?php echo "value=".$ligne['prixPublic'].""; ?> >

                                                                                                                <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo "value=".$ligne['prixtotal'].""; ?> >

                                                                                                                <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo "value=".$pagnet['totalp'].""; ?> >

                                                                                                                <div class="row">

                                                                                                                    <div class="col-xs-6">

                                                                                                                        <label for="reference">Ancienne quantite </label>

                                                                                                                        <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                                                    </div>

                                                                                                                    <div class="col-xs-6">

                                                                                                                        <label for="reference">Nouvelle quantite</label>

                                                                                                                        <!-- <input type="text" class="form-control" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> > -->
                                                                                                                        <input type="text" class="form-control inputRetourApres_Ph" name="quantite" data-numligne="<?= $ligne['numligne']; ?>" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                                                    </div>

                                                                                                                </div>

                                                                                                            </div>

                                                                                                            <div class="modal-footer">

                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                                                <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

                                                                                                            </div>

                                                                                                        </form>

                                                                                                    </div>

                                                                                                </div>

                                                                                            </div>

                                                                                        </td>

                                                                                    </tr>

                                                                                    <?php  

                                                                                }  

                                                                            ?>

                                                                        </tbody>

                                                                    </table>

        

                                                                    <!--*******************************Debut total Facture****************************************-->

                                                                        <div>

                                                                            <div>

                                                                                <?php echo  '********************************************* <br/>'; ?>

                                                                                <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>

                                                                            </div>
                                                                            <div>

                                                                                <?php if($pagnet['taux']!=0 && $pagnet['taux']!=null): ?>

                                                                                    <?php  echo 'Remise ('. $pagnet['taux'].' %) : '.(($pagnet['totalp'] * $pagnet['taux']) / 100).' <br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>
                                                                            <div>

                                                                                <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>

                                                                                    <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>

                                                                            </div>

                                                                                <?php if($_SESSION['compte']==1):?>

                                                                            <div>

                                                                                <?php if($pagnet['idCompte']!=0 && $pagnet['idCompte']>0): 

                                                                                        $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$pagnet['idCompte'];

                                                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());

                                                                                        $cpt = mysql_fetch_array($resPay2);

                                                                                    ?>

                                                                                    <?php  echo 'Compte : '. $cpt['nomCompte'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['avance']!=0 && $pagnet['avance']>0): 

                                                                                    ?>

                                                                                    <?php  echo 'Avance : '.$pagnet['avance'].'<br/>';?>

                                                                                    <?php  echo 'Reste : '.($pagnet['apayerPagnet'] - $pagnet['avance']).'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['avance']!=0 && $pagnet['avance']>0): 

                                                                                        $sqlGetCompteAvance="SELECT * FROM `".$nomtableVersement."` v, `".$nomtableCompte."` c where v.idCompte=c.idCompte AND idPagnet = ".$pagnet['idPagnet'];

                                                                                        $resAvance = mysql_query($sqlGetCompteAvance) or die ("persoonel requête 2".mysql_error());

                                                                                        $cptA = mysql_fetch_array($resAvance);

                                                                                    ?>

                                                                                    <?php  echo 'Compte avance : '.$cptA['nomCompte'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <?php endif; ?>

                                                                            <div>

                                                                                <?php if($pagnet['versement']!=0): ?>

                                                                                    <?php  echo 'Espèces : '.$pagnet['versement'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['restourne']!=0 && $pagnet['restourne']>0): ?>

                                                                                    <?php  echo 'Rendu : '.$pagnet['restourne'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                        </div>

                                                                    <!--*******************************Fin Total Facture****************************************-->

                                                                </div>

                                                            </div>

                                                        </div>

                                                    <?php }?>

                                                    <?php

                                                }

                                            }

                                        }

                                        else if($vente[2]==2){

                                            $sqlT1="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet='".$vente[1]."' ";

                                            $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());

                                            $mutuelle = mysql_fetch_assoc($resT1);

                                            if($mutuelle!=null){

                                                $sql="SELECT * FROM `".$nomtableLigne."` where idMutuellePagnet=".$mutuelle['idMutuellePagnet']." ";

                                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                                                $ligne = mysql_fetch_assoc($res) ;

                                                if($ligne['classe']==0){?>

                                                    <div class="panel panel-info">

                                                        <div class="panel-heading">

                                                            <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#mutuelle".$mutuelle['idMutuellePagnet']."" ; ?>  class="panel-title expand">

                                                                <div class="right-arrow pull-right">+</div>

                                                                <a href="#"> Imputation

                                                                    <span class="spanDate noImpr"> </span>

                                                                    <span class="spanDate noImpr"> Date: <?php echo $mutuelle['datepagej']; ?> </span>

                                                                    <span class="spanDate noImpr">Heure: <?php echo $mutuelle['heurePagnet']; ?></span>

                                                                    <span class="spanDate noImpr"> Total panier: <?php echo $mutuelle['totalp']; ?> </span>

                                                                    <span class="spanDate noImpr">Total à payer: <?php echo $mutuelle['apayerPagnet']; ?></span>

                                                                    <span class="spanDate noImpr"> Facture : #<?php echo $mutuelle['idMutuellePagnet']; ?></span>

                                                                </a>

                                                            </h4>

                                                        </div>

                                                        <div <?php echo  "id=mutuelle".$mutuelle['idMutuellePagnet']."" ; ?>

                                                            <?php 

                                                                if($idmax == $mutuelle['heurePagnet']){

                                                                    ?> class="panel-collapse collapse in" <?php

                                                                }

                                                                else  {

                                                                    ?> class="panel-collapse collapse " <?php

                                                                }

                                                            ?>  >

                                                            <div class="panel-body" >

                                                                <!--*******************************Debut Retourner Pagnet****************************************-->

                                                                    <?php if ($mutuelle['iduser']==$_SESSION['iduser']){ ?>

                                                                        <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$mutuelle['idMutuellePagnet'] ; ?>>

                                                                            <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                        </button>

                                                                    <?php }

                                                                    else {  ?>

                                                                        <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >

                                                                            <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                        </button>

                                                                    <?php }?>

        

                                                                    <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet".$mutuelle['idMutuellePagnet'] ; ?> role="dialog">

                                                                        <div class="modal-dialog">

                                                                            <!-- Modal content-->

                                                                            <div class="modal-content">

                                                                                <div class="modal-header panel-primary">

                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                    <h4 class="modal-title">Confirmation</h4>

                                                                                </div>

                                                                                <form class="form-inline noImpr"  id="factForm" method="post"  >

                                                                                    <div class="modal-body">

                                                                                        <p><?php echo "Voulez-vous retourner le panier numéro <b>".$mutuelle['idMutuellePagnet']."<b>" ; ?></p>

                                                                                        <input type="hidden" name="idMutuellePagnet" id="idMutuellePagnet"  <?php echo  "value='".$mutuelle['idMutuellePagnet']."'" ; ?>>

                                                                                    </div>

                                                                                    <div class="modal-footer">

                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                        <button type="button" name="btnRetournerImputation" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" class="btn btn-success btnRetournerImputation">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                                    </div>

                                                                                </form>

                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                    
                                                                <!--*******************************Debut Editer Pagnet****************************************-->

                                                                    <?php if ($_SESSION['proprietaire']==1 && $_SESSION['editionPanier']==1){ ?>

                                                                        <button type="button" class="btn btn-primary pull-left modeEditionBtn_Mt btn_disabled_after_click" id="edit-<?= $mutuelle['idMutuellePagnet'] ; ?>">

                                                                            <span class="glyphicon glyphicon-edit"></span> Editer

                                                                        </button>



                                                                        <div class="modal fade" <?php echo  "id=msg_edit_mutuelle".$mutuelle['idMutuellePagnet'] ; ?> role="dialog">

                                                                            <div class="modal-dialog">

                                                                                <div class="modal-content">

                                                                                    <div class="modal-header panel-primary">

                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                        <h4 class="modal-title">Alert</h4>

                                                                                    </div>

                                                                                    <p>Une erreur est survenu lors de l'édition. <br>

                                                                                        Veuillez rééssayer!</p>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    <?php } ?>

                                                                <!--*******************************Fin Editer Pagnet****************************************-->

                                                                    <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_mutuelle".$mutuelle['idMutuellePagnet'] ; ?>>

                                                                        Facture

                                                                    </button>


        

                                                                <div class="modal fade" <?php echo  "id=msg_fact_mutuelle".$mutuelle['idMutuellePagnet'] ; ?> role="dialog">

                                                                    <div class="modal-dialog">

                                                                        <!-- Modal content-->

                                                                        <div class="modal-content">

                                                                            <div class="modal-header panel-primary">

                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                <h4 class="modal-title">Informations Client</h4>

                                                                            </div>

                                                                            <form class="form-inline noImpr"  method="post" action="pdfFacturePharmacie.php" target="_blank" >

                                                                                <div class="modal-body">

                                                                                    <div class="row">

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Adresse Client</label>

                                                                                            <input type="text" class="form-control" name="adresse" >

                                                                                        </div>

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Telephone Client</label>

                                                                                            <input type="text" class="form-control" name="telephone" >

                                                                                        </div>

                                                                                    </div>

                                                                                    <input type="hidden" name="idMutuellePagnet" id="idMutuellePagnet"  <?php echo  "value='".$mutuelle['idMutuellePagnet']."'" ; ?>>

                                                                                </div>

                                                                                <div class="modal-footer">

                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                    <button type="submit" name="btnFacture" class="btn btn-success">Confirmer</button>

                                                                                </div>

                                                                            </form>

                                                                        </div>

                                                                    </div>

                                                                </div>


                                                                    <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$mutuelle['idMutuellePagnet'] ;?>').submit();">

                                                                    Ticket de Caisse

                                                                    </button>


                                                                <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$mutuelle['idMutuellePagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                    method="post" action="barcodeFacture.php" >

                                                                    <input type="hidden" name="idMutuellePagnet" id="idMutuellePagnet"  <?php echo  "value=".$mutuelle['idMutuellePagnet']."" ; ?> >

                                                                </form>

        

                                                                <table class="table ">

                                                                    <thead class="noImpr">

                                                                        <tr>

                                                                            <th></th>

                                                                            <th>Référence</th>

                                                                            <th>Quantité</th>

                                                                            <th>Forme</th>

                                                                            <th>Prix Public</th>

                                                                        </tr>

                                                                    </thead>

                                                                    <tbody>

                                                                        <?php

                                                                            $sql8="SELECT * FROM `".$nomtableLigne."` where idMutuellePagnet=".$mutuelle['idMutuellePagnet']." ORDER BY numligne DESC";

                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                                            while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                                                <tr>

                                                                                    <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$mutuelle['idMutuellePagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >

                                                                                    </td>

                                                                                    <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                                                    <td>

                                                                                    <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>

                                                                                    </td>

                                                                                    <td class="forme ">

                                                                                        <?php echo $ligne['forme']; ?>

                                                                                    </td>

                                                                                    <td>

                                                                                        <?php echo  $ligne['prixPublic']; ?>  <span class="factureFois" ></span>

                                                                                    </td>

                                                                                    <td>

                                                                                        <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal">

                                                                                                <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                        </button>

                                                                                    </td>

                                                                                </tr>

                                                                                <?php  

                                                                            }  

                                                                        ?>

                                                                    </tbody>

                                                                </table>

                                                                            

                                                                <!--*******************************Debut Total Facture****************************************-->

                                                                    <div class="col-sm-4 ">

                                                                        <div>

                                                                            <?php echo  '********************************************* <br/>'; ?>

                                                                            <?php echo  'TOTAL : '.$mutuelle['totalp'].'<br/>'; ?>

                                                                        </div>

                                                                        <div>

                                                                            <?php if($mutuelle['remise']!=0 && $mutuelle['remise']>0): ?>

                                                                                <?php  echo 'Taux Imputation :'. $mutuelle['taux'].' %<br/>';?>

                                                                            <?php endif; ?>

                                                                        </div>

                                                                        <div>

                                                                            <?php echo  '<b>Net à payer Adherant : '.$mutuelle['apayerPagnet'].'</b><br/>'; ?>

                                                                        </div>

                                                                        <div>

                                                                            <?php echo  '<b>Net à payer Mutuelle  : '.$mutuelle['apayerMutuelle'].'</b><br/>'; ?>

                                                                        </div>

                                                                        <div>

                                                                            <?php if($mutuelle['versement']!=0): ?>

                                                                                <?php  echo 'Espèces : '.$mutuelle['versement'].'<br/>';?>

                                                                            <?php endif; ?>

                                                                        </div>

                                                                        <div>

                                                                            <?php if($mutuelle['restourne']!=0 && $mutuelle['restourne']>0): ?>

                                                                                <?php  echo 'Rendu : '.$mutuelle['restourne'].'<br/>';?>

                                                                            <?php endif; ?>

                                                                        </div>

                                                                            <?php if($_SESSION['compte']==1):?>

                                                                        <div>

                                                                            <?php if($mutuelle['idCompte']!=0 && $mutuelle['idCompte']>0): 

                                                                                    $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$mutuelle['idCompte'];

                                                                                    $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());

                                                                                    $cpt = mysql_fetch_array($resPay2);

                                                                                ?>

                                                                                <?php  echo 'Compte : '. $cpt['nomCompte'].'<br/>';?>

                                                                            <?php endif; ?>

                                                                        </div>

                                                                        <div>

                                                                            <?php if($mutuelle['avance']!=0 && $mutuelle['avance']>0): 

                                                                                ?>

                                                                                <?php  echo 'Avance : '.$mutuelle['avance'].'<br/>';?>

                                                                                <?php  echo 'Reste : '.($mutuelle['apayerPagnet'] - $mutuelle['avance']).'<br/>';?>

                                                                            <?php endif; ?>

                                                                        </div>

                                                                        <div>

                                                                            <?php if($mutuelle['avance']!=0 && $mutuelle['avance']>0): 

                                                                                    $sqlGetCompteAvance="SELECT * FROM `".$nomtableVersement."` v, `".$nomtableCompte."` c where v.idCompte=c.idCompte AND idMutuellePagnet = ".$mutuelle['idMutuellePagnet'];

                                                                                    $resAvance = mysql_query($sqlGetCompteAvance) or die ("persoonel requête 2".mysql_error());

                                                                                    $cptA = mysql_fetch_array($resAvance);

                                                                                ?>

                                                                                <?php  echo 'Compte avance : '.$cptA['nomCompte'].'<br/>';?>

                                                                            <?php endif; ?>

                                                                        </div>

                                                                        <?php endif; ?>

                                                                    </div>

                                                                    <div class="col-sm-4 ">

                                                                        <div>

                                                                            <?php echo  '********************************************* <br/>'; ?>

                                                                            <?php

                                                                                $sqlE="SELECT * FROM `".$nomtableMutuelle."` where idMutuelle=".$mutuelle["idMutuelle"]." ";

                                                                                $resE = mysql_query($sqlE) or die ("persoonel requête 2".mysql_error());

                                                                                if($resE){

                                                                                    $mtlle=mysql_fetch_array($resE);

                                                                                    echo  'Mutuelle : '.$mtlle['nomMutuelle'].' ('.$mtlle['tauxMutuelle'].'%)<br/>';

                                                                                }

                                                                            ?>

                                                                        </div>

                                                                        <div>

                                                                            <?php echo  '<b> Adherant : '.$mutuelle['adherant'].'</b><br/>'; ?>

                                                                        </div>

                                                                        <div>

                                                                            <?php echo  '<b>Code Adherant  : '.$mutuelle['codeAdherant'].'</b><br/>'; ?>

                                                                        </div>

                                                                    </div>

                                                                    <div class="col-sm-4 ">

                                                                        <div>

                                                                            <?php echo  '********************************************* <br/>'; ?>

                                                                            <?php echo  '<b>Code Beneficiaire  : '.$mutuelle['codeBeneficiaire'].'</b><br/>'; ?>

                                                                        </div>

                                                                        <div>

                                                                            <?php echo  '<b> Numero Reçu : '.$mutuelle['numeroRecu'].'</b><br/>'; ?>

                                                                        </div>

                                                                        <div>

                                                                            <?php echo  '<b>Date Reçu  : '.$mutuelle['dateRecu'].'</b><br/>'; ?>

                                                                        </div>

                                                                    </div>

                                                                <!--*******************************Fin****************************************-->

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <?php

                                                }

                                            }

                                        }

                                    ?>

                                <?php $n=$n-1;   } ?>

                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                        <?php 

                            }

                            else {

                        ?>

                            <!-- Debut Boucle while concernant les Paniers Vendus non produit -->

                                <?php   $n=$nbPaniers - (($currentPage * 10) - 10); 

                                        while ($ventes = mysql_fetch_assoc($resP1)) { ?>

                                    <?php	$idmax=mysql_result($resA,0);  

                                            // var_dump($idmax);

                                    ?>

                                        <?php

                                            $vente = explode("+", $ventes['codePagnet']);

                                            if($vente[2]==1){

                                                $sqlT1="SELECT * FROM `".$nomtablePagnet."` where idPagnet='".$vente[1]."'  ";

                                                $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());

                                                $pagnet = mysql_fetch_assoc($resT1);

                                                // var_dump($pagnet);

                                                if($pagnet!=null){

                                                    $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";

                                                    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                                                    $ligne = mysql_fetch_assoc($res) ;

                                                    if (($ligne['classe']==0 || $ligne['classe']==1) && ($pagnet['type']==0 || $pagnet['type']==30)){?>

                                                        <?php if ($pagnet['totalp']==0) {?>

                                                            <div class="panel <?= ($pagnet['idClient']==0) ? 'panel-warning' : 'panel-info'?>">

                                                                <div class="panel-heading">

                                                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                                                        <div class="right-arrow pull-right">+</div>

                                                                        <a href="#"> Panier <?php echo $n; ?>

                                                                            <span class="spanDate noImpr"> </span>

                                                                            <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

                                                                            <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                                                            <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                                    $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                                                                                    $TotalT = mysql_fetch_array($resT);

                                                                                    $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                                    $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());

                                                                                    $TotalP = mysql_fetch_array($resP);?>

                                                                            <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                                                            <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                                            <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                                                        </a>

                                                                    </h4>

                                                                </div>

                                                                <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>

                                                                    <?php 

                                                                        if($idmax == $pagnet['heurePagnet']){

                                                                            ?> class="panel-collapse collapse in" <?php

                                                                        }

                                                                        else  {

                                                                            ?> class="panel-collapse collapse " <?php

                                                                        }

                                                                    ?>  >

                                                                    <div class="panel-body" >

                                                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                                                            <button disabled="true" type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                            </button>

            

                                                                            <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                                <div class="modal-dialog">

                                                                                    <!-- Modal content-->

                                                                                    <div class="modal-content">

                                                                                        <div class="modal-header panel-primary">

                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                                        </div>

                                                                                        <form class="form-inline noImpr"  id="factForm" method="post"  >

                                                                                            <div class="modal-body">

                                                                                                <p><?php echo "Voulez-vous retourner le panier numéro <b>".$pagnet['idPagnet']."<b>" ; ?></p>

                                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>

                                                                                            </div>

                                                                                            <div class="modal-footer">

                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                                <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                                            </div>

                                                                                        </form>

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                    

                                                                        <!--*******************************Debut Editer Pagnet****************************************-->

                                                                        <?php if ($_SESSION['proprietaire']==1 && $_SESSION['editionPanier']==1){ ?>

                                                                            <button type="button" class="btn btn-primary pull-left modeEditionBtn_Ph btn_disabled_after_click" id="edit-<?= $pagnet['idPagnet'] ; ?>">

                                                                                <span class="glyphicon glyphicon-edit"></span> Editer

                                                                            </button>



                                                                            <div class="modal fade" <?php echo  "id=msg_edit_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                                <div class="modal-dialog">

                                                                                    <div class="modal-content">

                                                                                        <div class="modal-header panel-primary">

                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                            <h4 class="modal-title">Alert</h4>

                                                                                        </div>

                                                                                        <p>Une erreur est survenu lors de l'édition. <br>

                                                                                            Veuillez rééssayer!</p>

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        <?php } ?>

                                                                        <!--*******************************Fin Editer Pagnet****************************************-->

            

                                                                        <!--*******************************Debut Facture****************************************-->

                                                                            <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                                Facture

                                                                            </button>

            

                                                                            <div class="modal fade" <?php echo  "id=msg_fact_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                                <div class="modal-dialog">

                                                                                    <!-- Modal content-->

                                                                                    <div class="modal-content">

                                                                                        <div class="modal-header panel-primary">

                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                            <h4 class="modal-title">Informations Client</h4>

                                                                                        </div>

                                                                                        <form class="form-inline noImpr"  method="post" action="pdfFacturePharmacie.php" target="_blank" >

                                                                                            <div class="modal-body">

                                                                                                <div class="row">

                                                                                                    <div class="col-xs-6">

                                                                                                        <label for="reference">Prenom(s) Client</label>

                                                                                                        <input type="text" class="form-control" name="prenom" >

                                                                                                    </div>

                                                                                                    <div class="col-xs-6">

                                                                                                        <label for="reference">Nom Client</label>

                                                                                                        <input type="text" class="form-control" name="nom" >

                                                                                                    </div>

                                                                                                </div>

                                                                                                <div class="row">

                                                                                                    <div class="col-xs-6">

                                                                                                        <label for="reference">Adresse Client</label>

                                                                                                        <input type="text" class="form-control" name="adresse" >

                                                                                                    </div>

                                                                                                    <div class="col-xs-6">

                                                                                                        <label for="reference">Telephone Client</label>

                                                                                                        <input type="text" class="form-control" name="telephone" >

                                                                                                    </div>

                                                                                                </div>

                                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>

                                                                                            </div>

                                                                                            <div class="modal-footer">

                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                                <button type="submit" name="btnFacture" class="btn btn-success">Confirmer</button>

                                                                                            </div>

                                                                                        </form>

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        <!--*******************************Fin Facture****************************************-->

                                                                        


                                                                        

                                                                            <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                                                Ticket de Caisse

                                                                            </button>

                                                                        

                                                                        

                                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                            method="post" action="barcodeFacture.php" >

                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                        </form>

            

                                                                        <table class="table ">

                                                                            <thead class="noImpr">

                                                                                <tr>

                                                                                    <th></th>

                                                                                    <th>Référence</th>

                                                                                    <th>Quantité</th>

                                                                                    <th>Forme</th>

                                                                                    <th>Prix Public</th>

                                                                                </tr>

                                                                            </thead>

                                                                            <tbody>

                                                                                <?php

                                                                                    $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                                                    $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                                                    while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                                                        <tr>

                                                                                            <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >

                                                                                            </td>

                                                                                            <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                                                            <td>

                                                                                                <?php if ($ligne['forme']=='Transaction'){ ?>

                                                                                                        <?php if ($ligne['quantite']==1): ?>

                                                                                                            <?php echo  'Depot'; ?> <span class="factureFois"></span>

                                                                                                        <?php endif; ?>

                                                                                                        <?php if ($ligne['quantite']==0): ?>

                                                                                                            <?php echo  'Retrait'; ?> <span class="factureFois"></span>

                                                                                                        <?php endif; ?>

                                                                                                <?php }

                                                                                                else{ ?>

                                                                                                    <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>

                                                                                                <?php } ?>

                                                                                            </td>

                                                                                            <td class="forme ">

                                                                                                <?php echo $ligne['forme']; ?>

                                                                                            </td>

                                                                                            <td>

                                                                                                <?php echo  $ligne['prixPublic']; ?>  <span class="factureFois" ></span>

                                                                                            </td>

                                                                                            <td>

                                                                                                <button disabled="true" type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

                                                                                                        <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                                </button>

            

                                                                                                <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne".$ligne['numligne'] ; ?> role="dialog">

                                                                                                    <div class="modal-dialog">

                                                                                                        <!-- Modal content-->

                                                                                                        <div class="modal-content">

                                                                                                            <div class="modal-header panel-primary">

                                                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                                                <h4 class="modal-title">Confirmation</h4>

                                                                                                            </div>

                                                                                                            <form class="form-inline noImpr" id="factForm" method="post" >

                                                                                                                <div class="modal-body">

                                                                                                                    <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>

                                                                                                                    <input type="hidden" name="idPagnet" id="idPagnet<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                                                                    <input type="hidden" name="designation" id="designation<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['designation'].""; ?> >

                                                                                                                    <input type="hidden" name="numligne" id="numligne<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['numligne'].""; ?> >

                                                                                                                    <input type="hidden" name="idStock" id="idStock<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['idStock'].""; ?> >

                                                                                                                    <input type="hidden" name="forme" id="forme<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['forme'].""; ?> >

                                                                                                                    <input type="hidden" name="prixPublic" id="prixPublic<?= $ligne['numligne']; ?>" 	<?php echo  "value=".$ligne['prixPublic'].""; ?> >

                                                                                                                    <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                                                    <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                                                    <div class="row">

                                                                                                                        <div class="col-xs-6">

                                                                                                                            <label for="reference">Ancienne quantite </label>

                                                                                                                            <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                                                        </div>

                                                                                                                        <div class="col-xs-6">

                                                                                                                            <label for="reference">Nouvelle quantite</label>

                                                                                                                            <!-- <input type="text" class="form-control" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> > -->
                                                                                                                            <input type="text" class="form-control inputRetourApres_Ph" name="quantite" data-numligne="<?= $ligne['numligne']; ?>" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >


                                                                                                                        </div>

                                                                                                                    </div>

                                                                                                                </div>

                                                                                                                <div class="modal-footer">

                                                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                                                    <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

                                                                                                                </div>

                                                                                                            </form>

                                                                                                        </div>

                                                                                                    </div>

                                                                                                </div>

                                                                                            </td>

                                                                                        </tr>

                                                                                        <?php  

                                                                                    }  

                                                                                ?>

                                                                            </tbody>

                                                                        </table>

            

                                                                        <!--*******************************Debut total Facture****************************************-->

                                                                            <div>

                                                                                <div>

                                                                                    <?php echo  '********************************************* <br/>'; ?>

                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>

                                                                                </div>
                                                                                <div>

                                                                                    <?php if($pagnet['taux']!=0 && $pagnet['taux']!=null): ?>

                                                                                        <?php  echo 'Remise ('. $pagnet['taux'].' %) : '.(($pagnet['totalp'] * $pagnet['taux']) / 100).' <br/>';?>

                                                                                    <?php endif; ?>

                                                                                </div>
                                                                                <div>

                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>

                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>

                                                                                    <?php endif; ?>

                                                                                </div>

                                                                                <div>

                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>

                                                                                </div>

                                                                                    <?php if($_SESSION['compte']==1):?>

                                                                                <div>

                                                                                    <?php if($pagnet['idCompte']!=0 && $pagnet['idCompte']>0): 

                                                                                            $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$pagnet['idCompte'];

                                                                                            $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());

                                                                                            $cpt = mysql_fetch_array($resPay2);

                                                                                        ?>

                                                                                        <?php  echo 'Compte : '. $cpt['nomCompte'].'<br/>';?>

                                                                                    <?php endif; ?>

                                                                                </div>

                                                                                <div>

                                                                                    <?php if($pagnet['avance']!=0 && $pagnet['avance']>0): 

                                                                                        ?>

                                                                                        <?php  echo 'Avance : '.$pagnet['avance'].'<br/>';?>

                                                                                        <?php  echo 'Reste : '.($pagnet['apayerPagnet'] - $pagnet['avance']).'<br/>';?>

                                                                                    <?php endif; ?>

                                                                                </div>

                                                                                <div>

                                                                                    <?php if($pagnet['avance']!=0 && $pagnet['avance']>0): 

                                                                                            $sqlGetCompteAvance="SELECT * FROM `".$nomtableVersement."` v, `".$nomtableCompte."` c where v.idCompte=c.idCompte AND idPagnet = ".$pagnet['idPagnet'];

                                                                                            $resAvance = mysql_query($sqlGetCompteAvance) or die ("persoonel requête 2".mysql_error());

                                                                                            $cptA = mysql_fetch_array($resAvance);

                                                                                        ?>

                                                                                        <?php  echo 'Compte avance : '.$cptA['nomCompte'].'<br/>';?>

                                                                                    <?php endif; ?>

                                                                                </div>

                                                                                <?php endif; ?>

                                                                                <div>

                                                                                    <?php if($pagnet['versement']!=0): ?>

                                                                                        <?php  echo 'Espèces : '.$pagnet['versement'].'<br/>';?>

                                                                                    <?php endif; ?>

                                                                                </div>

                                                                                <div>

                                                                                    <?php if($pagnet['restourne']!=0 && $pagnet['restourne']>0): ?>

                                                                                        <?php  echo 'Rendu : '.$pagnet['restourne'].'<br/>';?>

                                                                                    <?php endif; ?>

                                                                                </div>

                                                                            </div>

                                                                        <!--*******************************Fin Total Facture****************************************-->

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        <?php }

                                                        else {?>

                                                            <div class="panel  <?= ($pagnet['idClient']==0) ? 'panel-success' : 'panel-info'?>">

                                                                <div class="panel-heading">

                                                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                                                        <div class="right-arrow pull-right">+</div>

                                                                        <a href="#"> Panier <?php echo $n; ?>

                                                                            <span class="spanDate noImpr"> </span>

                                                                            <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

                                                                            <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                                                            <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                                    $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                                                                                    $TotalT = mysql_fetch_array($resT);

                                                                                    $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                                    $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());

                                                                                    $TotalP = mysql_fetch_array($resP);?>

                                                                            <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                                                            <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                                            <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                                                        </a>

                                                                    </h4>

                                                                </div>

                                                                <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>

                                                                    <?php 

                                                                        if($idmax == $pagnet['heurePagnet']){

                                                                            ?> class="panel-collapse collapse in" <?php

                                                                        }

                                                                        else  {

                                                                            ?> class="panel-collapse collapse " <?php

                                                                        }

                                                                    ?>  >

                                                                    <div class="panel-body" >

                                                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                                                            <?php if ($pagnet['iduser']==$_SESSION['iduser']){ ?>

                                                                                <button type="submit"  class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                                </button>

                                                                            <?php }

                                                                            else {  ?>

                                                                                <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >

                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                                </button>

                                                                            <?php }?>

            

                                                                            <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                                <div class="modal-dialog">

                                                                                    <!-- Modal content-->

                                                                                    <div class="modal-content">

                                                                                        <div class="modal-header panel-primary">

                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                                        </div>

                                                                                        <form class="form-inline noImpr"  id="factForm" method="post"  >

                                                                                            <div class="modal-body">

                                                                                                <p><?php echo "Voulez-vous retourner le panier numéro <b>".$pagnet['idPagnet']."<b>" ; ?></p>

                                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>

                                                                                            </div>

                                                                                            <div class="modal-footer">

                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                                <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                                            </div>

                                                                                        </form>

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                            <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                        

                                                                            <!--*******************************Debut Editer Pagnet****************************************-->

                                                                            <?php if ($_SESSION['proprietaire']==1 && $_SESSION['editionPanier']==1){ ?>

                                                                                <button type="button" class="btn btn-primary pull-left modeEditionBtn_Ph btn_disabled_after_click" id="edit-<?= $pagnet['idPagnet'] ; ?>">

                                                                                    <span class="glyphicon glyphicon-edit"></span> Editer

                                                                                </button>



                                                                                <div class="modal fade" <?php echo  "id=msg_edit_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                                    <div class="modal-dialog">

                                                                                        <div class="modal-content">

                                                                                            <div class="modal-header panel-primary">

                                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                                <h4 class="modal-title">Alert</h4>

                                                                                            </div>

                                                                                            <p>Une erreur est survenu lors de l'édition. <br>

                                                                                                Veuillez rééssayer!</p>

                                                                                        </div>

                                                                                    </div>

                                                                                </div>

                                                                            <?php } ?>

                                                                            <!--*******************************Fin Editer Pagnet****************************************-->

                

                                                                            <!--*******************************Debut Facture****************************************-->


                                                                            

                                                                                <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                                    Facture

                                                                                </button>

                                                                        

                                                                            

                                                                            <div class="modal fade" <?php echo  "id=msg_fact_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                                <div class="modal-dialog">

                                                                                    <!-- Modal content-->

                                                                                    <div class="modal-content">

                                                                                        <div class="modal-header panel-primary">

                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                            <h4 class="modal-title">Informations Client</h4>

                                                                                        </div>

                                                                                        <form class="form-inline noImpr"  method="post" action="pdfFacturePharmacie.php" target="_blank" >

                                                                                            <div class="modal-body">

                                                                                                <div class="row">

                                                                                                    <div class="col-xs-6">

                                                                                                        <label for="reference">Prenom(s) Client</label>

                                                                                                        <input type="text" class="form-control" name="prenom" >

                                                                                                    </div>

                                                                                                    <div class="col-xs-6">

                                                                                                        <label for="reference">Nom Client</label>

                                                                                                        <input type="text" class="form-control" name="nom" >

                                                                                                    </div>

                                                                                                </div>

                                                                                                <div class="row">

                                                                                                    <div class="col-xs-6">

                                                                                                        <label for="reference">Adresse Client</label>

                                                                                                        <input type="text" class="form-control" name="adresse" >

                                                                                                    </div>

                                                                                                    <div class="col-xs-6">

                                                                                                        <label for="reference">Telephone Client</label>

                                                                                                        <input type="text" class="form-control" name="telephone" >

                                                                                                    </div>

                                                                                                </div>

                                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>

                                                                                            </div>

                                                                                            <div class="modal-footer">

                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                                <button type="submit" name="btnFacture" class="btn btn-success">Confirmer</button>

                                                                                            </div>

                                                                                        </form>

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        <!--*******************************Fin Facture****************************************-->

                                                                        


                                                                        

                                                                            <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                                            Ticket de Caisse

                                                                            </button>

                                                                        

                                                                    

                                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                            method="post" action="barcodeFacture.php" >

                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                        </form>

            

                                                                        <table class="table ">

                                                                            <thead class="noImpr">

                                                                                <tr>

                                                                                    <th></th>

                                                                                    <th>Référence</th>

                                                                                    <th>Quantité</th>

                                                                                    <th>Forme</th>

                                                                                    <th>Prix Public</th>

                                                                                </tr>

                                                                            </thead>

                                                                            <tbody>

                                                                                <?php

                                                                                    $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                                                    $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                                                    while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                                                        <tr>

                                                                                            <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >

                                                                                            </td>

                                                                                            <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                                                            <td>

                                                                                                <?php if ($ligne['forme']=='Transaction'){ ?>

                                                                                                        <?php if ($ligne['quantite']==1): ?>

                                                                                                            <?php echo  'Depot'; ?> <span class="factureFois"></span>

                                                                                                        <?php endif; ?>

                                                                                                        <?php if ($ligne['quantite']==0): ?>

                                                                                                            <?php echo  'Retrait'; ?> <span class="factureFois"></span>

                                                                                                        <?php endif; ?>

                                                                                                <?php }

                                                                                                else{ ?>

                                                                                                    <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>

                                                                                                <?php } ?>

                                                                                            </td>

                                                                                            <td class="forme ">

                                                                                                <?php echo $ligne['forme']; ?>

                                                                                            </td>

                                                                                            <td>

                                                                                                <?php echo  $ligne['prixPublic']; ?>  <span class="factureFois" ></span>

                                                                                            </td>

                                                                                            <td>

                                                                                                <?php if ($pagnet['iduser']==$_SESSION['iduser']){ ?>

                                                                                                    <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

                                                                                                            <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                                    </button>

                                                                                                <?php }

                                                                                                else {  ?>

                                                                                                    <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >

                                                                                                            <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                                    </button>

                                                                                                <?php }?>

            

                                                                                                <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne".$ligne['numligne'] ; ?> role="dialog">

                                                                                                    <div class="modal-dialog">

                                                                                                        <!-- Modal content-->

                                                                                                        <div class="modal-content">

                                                                                                            <div class="modal-header panel-primary">

                                                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                                                <h4 class="modal-title">Confirmation</h4>

                                                                                                            </div>

                                                                                                            <form class="form-inline noImpr" id="factForm" method="post" >

                                                                                                                <div class="modal-body">

                                                                                                                    <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>

                                                                                                                    <input type="hidden" name="idPagnet" id="idPagnet<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                                                                    <input type="hidden" name="designation" id="designation<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['designation'].""; ?> >

                                                                                                                    <input type="hidden" name="numligne" id="numligne<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['numligne'].""; ?> >

                                                                                                                    <input type="hidden" name="idStock" id="idStock<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['idStock'].""; ?> >

                                                                                                                    <input type="hidden" name="forme" id="forme<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['forme'].""; ?> >

                                                                                                                    <input type="hidden" name="prixPublic" id="prixPublic<?= $ligne['numligne']; ?>" 	<?php echo  "value=".$ligne['prixPublic'].""; ?> >

                                                                                                                    <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                                                    <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                                                    <div class="row">

                                                                                                                        <div class="col-xs-6">

                                                                                                                            <label for="reference">Ancienne quantite </label>

                                                                                                                            <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                                                        </div>

                                                                                                                        <div class="col-xs-6">

                                                                                                                            <label for="reference">Nouvelle quantite</label>

                                                                                                                            <!-- <input type="text" class="form-control" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> > -->
                                                                                                                            <input type="text" class="form-control inputRetourApres_Ph" name="quantite" data-numligne="<?= $ligne['numligne']; ?>" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >


                                                                                                                        </div>

                                                                                                                    </div>

                                                                                                                </div>

                                                                                                                <div class="modal-footer">

                                                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                                                    <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

                                                                                                                </div>

                                                                                                            </form>

                                                                                                        </div>

                                                                                                    </div>

                                                                                                </div>

                                                                                            </td>

                                                                                        </tr>

                                                                                        <?php  

                                                                                    }  

                                                                                ?>

                                                                            </tbody>

                                                                        </table>

            

                                                                        <!--*******************************Debut total Facture****************************************-->

                                                                            <div>

                                                                                <div>

                                                                                    <?php echo  '********************************************* <br/>'; ?>

                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>

                                                                                </div>
                                                                                <div>

                                                                                    <?php if($pagnet['taux']!=0 && $pagnet['taux']!=null): ?>

                                                                                        <?php  echo 'Remise ('. $pagnet['taux'].' %) : '.(($pagnet['totalp'] * $pagnet['taux']) / 100).' <br/>';?>

                                                                                    <?php endif; ?>

                                                                                </div>
                                                                                <div>

                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>

                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>

                                                                                    <?php endif; ?>

                                                                                </div>

                                                                                <div>

                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>

                                                                                </div>

                                                                                    <?php if($_SESSION['compte']==1):?>

                                                                                <div>

                                                                                    <?php if($pagnet['idCompte']!=0 && $pagnet['idCompte']>0): 

                                                                                            $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$pagnet['idCompte'];

                                                                                            $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());

                                                                                            $cpt = mysql_fetch_array($resPay2);

                                                                                        ?>

                                                                                        <?php  echo 'Compte : '. $cpt['nomCompte'].'<br/>';?>

                                                                                    <?php endif; ?>

                                                                                </div>

                                                                                <div>

                                                                                    <?php if($pagnet['avance']!=0 && $pagnet['avance']>0): 

                                                                                        ?>

                                                                                        <?php  echo 'Avance : '.$pagnet['avance'].'<br/>';?>

                                                                                        <?php  echo 'Reste : '.($pagnet['apayerPagnet'] - $pagnet['avance']).'<br/>';?>

                                                                                    <?php endif; ?>

                                                                                </div>

                                                                                <div>

                                                                                    <?php if($pagnet['avance']!=0 && $pagnet['avance']>0): 

                                                                                            $sqlGetCompteAvance="SELECT * FROM `".$nomtableVersement."` v, `".$nomtableCompte."` c where v.idCompte=c.idCompte AND idPagnet = ".$pagnet['idPagnet'];

                                                                                            $resAvance = mysql_query($sqlGetCompteAvance) or die ("persoonel requête 2".mysql_error());

                                                                                            $cptA = mysql_fetch_array($resAvance);

                                                                                        ?>

                                                                                        <?php  echo 'Compte avance : '.$cptA['nomCompte'].'<br/>';?>

                                                                                    <?php endif; ?>

                                                                                </div>

                                                                                <?php endif; ?>

                                                                                <div>

                                                                                    <?php if($pagnet['versement']!=0): ?>

                                                                                        <?php  echo 'Espèces : '.$pagnet['versement'].'<br/>';?>

                                                                                    <?php endif; ?>

                                                                                </div>

                                                                                <div>

                                                                                    <?php if($pagnet['restourne']!=0 && $pagnet['restourne']>0): ?>

                                                                                        <?php  echo 'Rendu : '.$pagnet['restourne'].'<br/>';?>

                                                                                    <?php endif; ?>

                                                                                </div>

                                                                            </div>

                                                                        <!--*******************************Fin Total Facture****************************************-->

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        <?php }?>

                                                    <?php

                                                    }

                                                    else if ($ligne['classe']==2 && $pagnet['type']==0) {?>

                                                        <div class="panel panel-danger">

                                                            <div class="panel-heading">

                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                                                    <div class="right-arrow pull-right">+</div>

                                                                    <a href="#"> Panier <?php echo $n; ?>

                                                                        <span class="spanDate noImpr"> </span>

                                                                        <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

                                                                        <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                                                        <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                                $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                                                                                $TotalT = mysql_fetch_array($resT);

                                                                                $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                                $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());

                                                                                $TotalP = mysql_fetch_array($resP);

                                                                        ?>

                                                                        <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                                                        <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                                        <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                                                    </a>

                                                                </h4>

                                                            </div>

                                                            <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>

                                                                <?php 

                                                                    if($idmax == $pagnet['heurePagnet']){

                                                                        ?> class="panel-collapse collapse in" <?php

                                                                    }

                                                                    else  {

                                                                        ?> class="panel-collapse collapse " <?php

                                                                    }

            

                                                                ?>  >

                                                                <div class="panel-body" >

                                                                    <!--*******************************Debut Retourner Pagnet****************************************-->

                                                                        <?php if ($pagnet['iduser']==$_SESSION['iduser']){ ?>

                                                                            <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                            </button>

                                                                        <?php }

                                                                        else {  ?>

                                                                            <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >

                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                            </button>

                                                                        <?php }?>

            

                                                                        <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                            <div class="modal-dialog">

                                                                                <!-- Modal content-->

                                                                                <div class="modal-content">

                                                                                    <div class="modal-header panel-primary">

                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                        <h4 class="modal-title">Confirmation</h4>

                                                                                    </div>

                                                                                    <form class="form-inline noImpr"  id="factForm" method="post"  >

                                                                                        <div class="modal-body">

                                                                                            <p><?php echo "Voulez-vous retourner le panier numéro <b>".$pagnet['idPagnet']."<b>" ; ?></p>

                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>

                                                                                        </div>

                                                                                        <div class="modal-footer">

                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                            <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                                        </div>

                                                                                    </form>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                    

                                                                    

                                                                        <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                                        Ticket de Caisse

                                                                        </button>

                                                                    

                                                                    

                                                                    <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                        method="post" action="barcodeFacture.php" >

                                                                        <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                    </form>

            

                                                                    <table class="table ">

                                                                        <thead class="noImpr">

                                                                        <tr>

                                                                            <th></th>

                                                                            <th>Référence</th>

                                                                            <th>Quantité</th>

                                                                            <th>Forme</th>

                                                                            <th>Prix Public</th>

                                                                        </tr>

                                                                        </thead>

                                                                        <tbody>

                                                                            <?php

                                                                                $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                                                $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                                                while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                                                    <tr>

                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >

                                                                                        </td>

                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                                                        <td>

                                                                                            <?php if ($ligne['forme']=='Transaction'){ ?>

                                                                                                    <?php if ($ligne['quantite']==1): ?>

                                                                                                        <?php echo  'Depot'; ?> <span class="factureFois"></span>

                                                                                                    <?php endif; ?>

                                                                                                    <?php if ($ligne['quantite']==0): ?>

                                                                                                        <?php echo  'Retrait'; ?> <span class="factureFois"></span>

                                                                                                    <?php endif; ?>

                                                                                            <?php }

                                                                                            else{ ?>

                                                                                                <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>

                                                                                            <?php } ?>

                                                                                        </td>

                                                                                        <td class="forme ">

                                                                                            <?php echo $ligne['forme']; ?>

                                                                                        </td>

                                                                                        <td>

                                                                                            <?php echo  $ligne['prixPublic']; ?>  <span class="factureFois" ></span>

                                                                                        </td>

                                                                                        <td>

                                                                                            <?php if ($pagnet['iduser']==$_SESSION['iduser']){ ?>

                                                                                                <button type="submit" disabled="true" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

                                                                                                        <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                                </button>

                                                                                            <?php }

                                                                                            else {  ?>

                                                                                                <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >

                                                                                                        <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                                </button>

                                                                                            <?php }?>

            

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne".$ligne['numligne'] ; ?> role="dialog">

                                                                                                <div class="modal-dialog">

                                                                                                    <!-- Modal content-->

                                                                                                    <div class="modal-content">

                                                                                                        <div class="modal-header panel-primary">

                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                                                        </div>

                                                                                                        <form class="form-inline noImpr" id="factForm" method="post" >

                                                                                                            <div class="modal-body">

                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>

                                                                                                                <input type="hidden" name="idPagnet" id="idPagnet<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                                                                <input type="hidden" name="designation" id="designation<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['designation'].""; ?> >

                                                                                                                <input type="hidden" name="numligne" id="numligne<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['numligne'].""; ?> >

                                                                                                                <input type="hidden" name="idStock" id="idStock<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['idStock'].""; ?> >

                                                                                                                <input type="hidden" name="forme" id="forme<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['forme'].""; ?> >

                                                                                                                <input type="hidden" name="prixPublic" id="prixPublic<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixPublic'].""; ?> >

                                                                                                                <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                                                <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                                                <div class="row">

                                                                                                                    <div class="col-xs-6">

                                                                                                                        <label for="reference">Ancienne quantite </label>

                                                                                                                        <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                                                    </div>

                                                                                                                    <div class="col-xs-6">

                                                                                                                        <label for="reference">Nouvelle quantite</label>

                                                                                                                        <!-- <input type="text" class="form-control" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> > -->
                                                                                                                        <input type="text" class="form-control inputRetourApres_Ph" name="quantite" data-numligne="<?= $ligne['numligne']; ?>" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >


                                                                                                                    </div>

                                                                                                                </div>

                                                                                                            </div>

                                                                                                            <div class="modal-footer">

                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                                                <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

                                                                                                            </div>

                                                                                                        </form>

                                                                                                    </div>

                                                                                                </div>

                                                                                            </div>

                                                                                        </td>

                                                                                    </tr>

                                                                                    <?php  

                                                                                }  

                                                                            ?>

                                                                        </tbody>

                                                                    </table>

            

                                                                    <!--*******************************Debut Total Facture****************************************-->

                                                                        <div class="col-sm-11">

                                                                            <div>

                                                                                <?php echo  '********************************************* <br/>'; ?>

                                                                                <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>

                                                                                    <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>

                                                                            </div>

                                                                                <?php if($_SESSION['compte']==1):?>

                                                                            <div>

                                                                                <?php if($pagnet['idCompte']!=0 && $pagnet['idCompte']>0): 

                                                                                        $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$pagnet['idCompte'];

                                                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());

                                                                                        $cpt = mysql_fetch_array($resPay2);

                                                                                    ?>

                                                                                    <?php  echo 'Compte : '. $cpt['nomCompte'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['avance']!=0 && $pagnet['avance']>0): 

                                                                                    ?>

                                                                                    <?php  echo 'Avance : '.$pagnet['avance'].'<br/>';?>

                                                                                    <?php  echo 'Reste : '.($pagnet['apayerPagnet'] - $pagnet['avance']).'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['avance']!=0 && $pagnet['avance']>0): 

                                                                                        $sqlGetCompteAvance="SELECT * FROM `".$nomtableVersement."` v, `".$nomtableCompte."` c where v.idCompte=c.idCompte AND idPagnet = ".$pagnet['idPagnet'];

                                                                                        $resAvance = mysql_query($sqlGetCompteAvance) or die ("persoonel requête 2".mysql_error());

                                                                                        $cptA = mysql_fetch_array($resAvance);

                                                                                    ?>

                                                                                    <?php  echo 'Compte avance : '.$cptA['nomCompte'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <?php endif; ?>

                                                                            <div>

                                                                                <?php if($pagnet['versement']!=0): ?>

                                                                                    <?php  echo 'Espèces : '.$pagnet['versement'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['restourne']!=0 && $pagnet['restourne']>0): ?>

                                                                                    <?php  echo 'Rendu : '.$pagnet['restourne'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                        </div>
                                                                        <div class="col-sm-1" tyle="text-align:right;" >
                                                                            <br />
                                                                            <?php if($pagnet['image']!=null && $pagnet['image']!=' '){
                                                                                $format=substr($pagnet['image'], -3); ?>
                                                                                <?php if($format=='pdf'){ ?>
                                                                                    <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvPanier".$pagnet['idPagnet'] ; ?> onclick="imageUpPanier(<?php echo $pagnet['idPagnet']; ?>,<?php echo $pagnet['image']; ?>)" 	 />
                                                                                <?php }
                                                                                    else { 
                                                                                ?>
                                                                                    <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvPanier".$pagnet['idPagnet'] ; ?> onclick="imageUpPanier(<?php echo $pagnet['idPagnet']; ?>,<?php echo $pagnet['image']; ?>)" 	 />
                                                                                <?php } ?>
                                                                            <?php }
                                                                                else { 
                                                                            ?>
                                                                                <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" <?php echo "data-target=#imageNvPanier".$pagnet['idPagnet'] ; ?> onclick="imageUpPanier(<?php echo $pagnet['idPagnet']; ?>,<?php echo $pagnet['image']; ?>)" 	 />
                                                                            <?php } ?>
                                                                        </div>
                                                                    <!--*******************************Fin Total Facture****************************************-->

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <?php

                                                    }

                                                    else if ($ligne['classe']==3 && $pagnet['type']==0) {?>

                                                        <div class="panel panel-warning">

                                                            <div class="panel-heading">

                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                                                    <div class="right-arrow pull-right">+</div>

                                                                    <a href="#"> Panier <?php echo $n; ?>

                                                                        <span class="spanDate noImpr"> </span>

                                                                        <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

                                                                        <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                                                        <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                                $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                                                                                $TotalT = mysql_fetch_array($resT);

                                                                                $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                                $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());

                                                                                $TotalP = mysql_fetch_array($resP);

                                                                        ?>

                                                                        <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                                                        <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                                        <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                                                    </a>

                                                                </h4>

                                                            </div>

                                                            <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>

                                                                <?php 

                                                                    if($idmax == $pagnet['heurePagnet']){

                                                                        ?> class="panel-collapse collapse in" <?php

                                                                    }

                                                                    else  {

                                                                        ?> class="panel-collapse collapse " <?php

                                                                    }

                                                                ?>  >

                                                                <div class="panel-body" >

                                                                    <!--*******************************Debut Retourner Pagnet****************************************-->

                                                                        <?php if ($pagnet['iduser']==$_SESSION['iduser']){ ?>

                                                                            <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                            </button>

                                                                        <?php }

                                                                        else {  ?>

                                                                            <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >

                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                            </button>

                                                                        <?php }?>

            

                                                                        <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                            <div class="modal-dialog">

                                                                                <!-- Modal content-->

                                                                                <div class="modal-content">

                                                                                    <div class="modal-header panel-primary">

                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                        <h4 class="modal-title">Confirmation</h4>

                                                                                    </div>

                                                                                    <form class="form-inline noImpr"  id="factForm" method="post"  >

                                                                                        <div class="modal-body">

                                                                                            <p><?php echo "Voulez-vous retourner le panier numéro <b>".$pagnet['idPagnet']."<b>" ; ?></p>

                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>

                                                                                        </div>

                                                                                        <div class="modal-footer">

                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                            <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                                        </div>

                                                                                    </form>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    <!--*******************************Fin Retourner Pagnet****************************************-->

            

                                                                    <!--*******************************Debut Facture****************************************-->

                                                                        <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                            Facture

                                                                        </button>

            

                                                                        <div class="modal fade" <?php echo  "id=msg_fact_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                            <div class="modal-dialog">

                                                                                <!-- Modal content-->

                                                                                <div class="modal-content">

                                                                                    <div class="modal-header panel-primary">

                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                        <h4 class="modal-title">Informations Client</h4>

                                                                                    </div>

                                                                                    <form class="form-inline noImpr"  method="post" action="pdfFacturePharmacie.php" target="_blank" >

                                                                                        <div class="modal-body">

                                                                                            <div class="row">

                                                                                                <div class="col-xs-6">

                                                                                                    <label for="reference">Prenom(s) Client</label>

                                                                                                    <input type="text" class="form-control" name="prenom" >

                                                                                                </div>

                                                                                                <div class="col-xs-6">

                                                                                                    <label for="reference">Nom Client</label>

                                                                                                    <input type="text" class="form-control" name="nom" >

                                                                                                </div>

                                                                                            </div>

                                                                                            <div class="row">

                                                                                                <div class="col-xs-6">

                                                                                                    <label for="reference">Adresse Client</label>

                                                                                                    <input type="text" class="form-control" name="adresse" >

                                                                                                </div>

                                                                                                <div class="col-xs-6">

                                                                                                    <label for="reference">Telephone Client</label>

                                                                                                    <input type="text" class="form-control" name="telephone" >

                                                                                                </div>

                                                                                            </div>

                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>

                                                                                        </div>

                                                                                        <div class="modal-footer">

                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                            <button type="submit" name="btnFacture" class="btn btn-success">Confirmer</button>

                                                                                        </div>

                                                                                    </form>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    <!--*******************************Fin Facture****************************************-->

                                                                        


                                                                        

                                                                        <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                                        Ticket de Caisse

                                                                        </button>

                                                                        


                                                                        

                                                                    <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                        method="post" action="barcodeFacture.php" >

                                                                        <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                    </form>

            

                                                                    <table class="table ">

                                                                        <thead class="noImpr">

                                                                            <tr>

                                                                                <th></th>

                                                                                <th>Référence</th>

                                                                                <th>Quantité</th>

                                                                                <th>Forme</th>

                                                                                <th>Prix Public</th>

                                                                            </tr>

                                                                        </thead>

                                                                        <tbody>

                                                                            <?php

                                                                                $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                                                $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                                                while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                                                    <tr>

                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >

                                                                                        </td>

                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                                                        <td>

                                                                                            <?php if ($ligne['forme']=='Transaction'){ ?>

                                                                                                    <?php if ($ligne['quantite']==1): ?>

                                                                                                        <?php echo  'Depot'; ?> <span class="factureFois"></span>

                                                                                                    <?php endif; ?>

                                                                                                    <?php if ($ligne['quantite']==0): ?>

                                                                                                        <?php echo  'Retrait'; ?> <span class="factureFois"></span>

                                                                                                    <?php endif; ?>

                                                                                            <?php }

                                                                                            else{ ?>

                                                                                                <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>

                                                                                            <?php } ?>

                                                                                        </td>

                                                                                        <td class="forme ">

                                                                                            <?php echo $ligne['forme']; ?>

                                                                                        </td>

                                                                                        <td>

                                                                                            <?php echo  $ligne['prixPublic']; ?>  <span class="factureFois" ></span>

                                                                                        </td>

                                                                                        <td>

                                                                                            <?php if ($pagnet['iduser']==$_SESSION['iduser']){ ?>

                                                                                                <button type="submit" disabled="true"  class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

                                                                                                        <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                                </button>

                                                                                            <?php }

                                                                                            else {  ?>

                                                                                                <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >

                                                                                                        <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                                </button>

                                                                                            <?php }?>

            

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne".$ligne['numligne'] ; ?> role="dialog">

                                                                                                <div class="modal-dialog">

                                                                                                    <!-- Modal content-->

                                                                                                    <div class="modal-content">

                                                                                                        <div class="modal-header panel-primary">

                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                                                        </div>

                                                                                                        <form class="form-inline noImpr" id="factForm" method="post" >

                                                                                                            <div class="modal-body">

                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>

                                                                                                                <input type="hidden" name="idPagnet" id="idPagnet<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                                                                <input type="hidden" name="designation" id="designation<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['designation'].""; ?> >

                                                                                                                <input type="hidden" name="numligne" id="numligne<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['numligne'].""; ?> >

                                                                                                                <input type="hidden" name="idStock" id="idStock<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['idStock'].""; ?> >

                                                                                                                <input type="hidden" name="forme" id="forme<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['forme'].""; ?> >

                                                                                                                <input type="hidden" name="quantite" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                                                <input type="hidden" name="prixPublic" id="prixPublic<?= $ligne['numligne']; ?>" 	<?php echo  "value=".$ligne['prixPublic'].""; ?> >

                                                                                                                <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                                                <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                                            </div>

                                                                                                            <div class="modal-footer">

                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                                                <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

                                                                                                            </div>

                                                                                                        </form>

                                                                                                    </div>

                                                                                                </div>

                                                                                            </div>

                                                                                        </td>

                                                                                    </tr>

                                                                                    <?php  

                                                                                }  

                                                                            ?>

                                                                        </tbody>

                                                                    </table>

            

                                                                    <!--*******************************Debut Total Facture****************************************-->

                                                                        <div>

                                                                            <div>

                                                                                <?php echo  '********************************************* <br/>'; ?>

                                                                                <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>

                                                                            </div>
                                                                            <div>

                                                                                <?php if($pagnet['taux']!=0 && $pagnet['taux']!=null): ?>

                                                                                    <?php  echo 'Remise ('. $pagnet['taux'].' %) : '.(($pagnet['totalp'] * $pagnet['taux']) / 100).' <br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>
                                                                            <div>

                                                                                <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>

                                                                                    <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>

                                                                            </div>

                                                                                <?php if($_SESSION['compte']==1):?>

                                                                            <div>

                                                                                <?php if($pagnet['idCompte']!=0 && $pagnet['idCompte']>0): 

                                                                                        $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$pagnet['idCompte'];

                                                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());

                                                                                        $cpt = mysql_fetch_array($resPay2);

                                                                                    ?>

                                                                                    <?php  echo 'Compte : '. $cpt['nomCompte'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['avance']!=0 && $pagnet['avance']>0): 

                                                                                    ?>

                                                                                    <?php  echo 'Avance : '.$pagnet['avance'].'<br/>';?>

                                                                                    <?php  echo 'Reste : '.($pagnet['apayerPagnet'] - $pagnet['avance']).'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['avance']!=0 && $pagnet['avance']>0): 

                                                                                        $sqlGetCompteAvance="SELECT * FROM `".$nomtableVersement."` v, `".$nomtableCompte."` c where v.idCompte=c.idCompte AND idPagnet = ".$pagnet['idPagnet'];

                                                                                        $resAvance = mysql_query($sqlGetCompteAvance) or die ("persoonel requête 2".mysql_error());

                                                                                        $cptA = mysql_fetch_array($resAvance);

                                                                                    ?>

                                                                                    <?php  echo 'Compte avance : '.$cptA['nomCompte'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <?php endif; ?>

                                                                            <div>

                                                                                <?php if($pagnet['versement']!=0): ?>

                                                                                    <?php  echo 'Espèces : '.$pagnet['versement'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['restourne']!=0 && $pagnet['restourne']>0): ?>

                                                                                    <?php  echo 'Rendu : '.$pagnet['restourne'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                        </div>

                                                                    <!--*******************************Fin Total Facture****************************************-->

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <?php

                                                    }

                                                    else if ($ligne['classe']==5) {?>

                                                        <div class="panel panel-info">

                                                            <div class="panel-heading">

                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                                                    <div class="right-arrow pull-right">+</div>

                                                                    <a href="#"> Panier <?php echo $n; ?>

                                                                        <span class="spanDate noImpr"> </span>

                                                                        <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

                                                                        <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                                                        <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                                $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                                                                                $TotalT = mysql_fetch_array($resT);

                                                                                $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                                $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());

                                                                                $TotalP = mysql_fetch_array($resP);

                                                                        ?>

                                                                        <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                                                        <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                                        <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                                                    </a>

                                                                </h4>

                                                            </div>

                                                            <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>

                                                                <?php 

                                                                    if($idmax == $pagnet['heurePagnet']){

                                                                        ?> class="panel-collapse collapse in" <?php

                                                                    }

                                                                    else  {

                                                                        ?> class="panel-collapse collapse " <?php

                                                                    }

            

                                                                ?>  >

                                                                <div class="panel-body" >

                                                                    <!--*******************************Debut Retourner Pagnet****************************************-->

                                                                        <?php if ($pagnet['iduser']==$_SESSION['iduser']){ ?>

                                                                            <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                            </button>

                                                                        <?php }

                                                                        else {  ?>

                                                                            <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >

                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                            </button>

                                                                        <?php }?>

            

                                                                        <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                            <div class="modal-dialog">

                                                                                <!-- Modal content-->

                                                                                <div class="modal-content">

                                                                                    <div class="modal-header panel-primary">

                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                        <h4 class="modal-title">Confirmation</h4>

                                                                                    </div>

                                                                                    <form class="form-inline noImpr"  id="factForm" method="post"  >

                                                                                        <div class="modal-body">

                                                                                            <p><?php echo "Voulez-vous retourner le panier numéro <b>".$pagnet['idPagnet']."<b>" ; ?></p>

                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>

                                                                                        </div>

                                                                                        <div class="modal-footer">

                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                            <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                                        </div>

                                                                                    </form>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                        


                                                                        

                                                                        <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                                        Ticket de Caisse

                                                                        </button>

                                                                        

                                                                        

                                                                    <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                        method="post" action="barcodeFacture.php" >

                                                                        <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                    </form>

            

                                                                    <table class="table ">

                                                                        <thead class="noImpr">

                                                                            <tr>

                                                                                <th></th>

                                                                                <th>Référence</th>

                                                                                <th>Quantité</th>

                                                                                <th>Forme</th>

                                                                                <th>Prix Public</th>

                                                                            </tr>

                                                                        </thead>

                                                                        <tbody>

                                                                            <?php

                                                                                $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                                                $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                                                while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                                                    <tr>

                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >

                                                                                        </td>

                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                                                        <td>

                                                                                            <?php echo  'Montant'; ?> <span class="factureFois"></span>

                                                                                        </td>

                                                                                        <td class="forme ">

                                                                                            <?php echo $ligne['forme']; ?>

                                                                                        </td>

                                                                                        <td>

                                                                                            <?php echo  $ligne['prixPublic']; ?>  <span class="factureFois" ></span>

                                                                                        </td>

                                                                                        <td>

                                                                                            <?php if ($pagnet['iduser']==$_SESSION['iduser']){ ?>

                                                                                                <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

                                                                                                        <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                                </button>

                                                                                            <?php }

                                                                                            else {  ?>

                                                                                                <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >

                                                                                                        <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                                </button>

                                                                                            <?php }?>

            

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne".$ligne['numligne'] ; ?> role="dialog">

                                                                                                <div class="modal-dialog">

                                                                                                    <!-- Modal content-->

                                                                                                    <div class="modal-content">

                                                                                                        <div class="modal-header panel-primary">

                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                                                        </div>

                                                                                                        <form class="form-inline noImpr" id="factForm" method="post" >

                                                                                                            <div class="modal-body">

                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>

                                                                                                                <input type="hidden" name="idPagnet" id="idPagnet<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                                                                <input type="hidden" name="designation" id="designation<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['designation'].""; ?> >

                                                                                                                <input type="hidden" name="numligne" id="numligne<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['numligne'].""; ?> >

                                                                                                                <input type="hidden" name="idStock" id="idStock<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['idStock'].""; ?> >

                                                                                                                <input type="hidden" name="forme" id="forme<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['forme'].""; ?> >

                                                                                                                <input type="hidden" name="quantite" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                                                <input type="hidden" name="prixPublic" id="prixPublic<?= $ligne['numligne']; ?>" 	<?php echo  "value=".$ligne['prixPublic'].""; ?> >

                                                                                                                <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                                                <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                                            </div>

                                                                                                            <div class="modal-footer">

                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                                                <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

                                                                                                            </div>

                                                                                                        </form>

                                                                                                    </div>

                                                                                                </div>

                                                                                            </div>

                                                                                        </td>

                                                                                    </tr>

                                                                                    <?php  

                                                                                }  

                                                                            ?>

                                                                        </tbody>

                                                                    </table>

            

                                                                    <!--*******************************Debut Total Facture****************************************-->

                                                                        <div>

                                                                            <div>

                                                                                <?php echo  '********************************************* <br/>'; ?>

                                                                                <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>

                                                                                    <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>

                                                                            </div>

                                                                                <?php if($_SESSION['compte']==1):?>

                                                                            <div>

                                                                                <?php if($pagnet['idCompte']!=0 && $pagnet['idCompte']>0): 

                                                                                        $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$pagnet['idCompte'];

                                                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());

                                                                                        $cpt = mysql_fetch_array($resPay2);

                                                                                    ?>

                                                                                    <?php  echo 'Compte : '. $cpt['nomCompte'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['avance']!=0 && $pagnet['avance']>0): 

                                                                                    ?>

                                                                                    <?php  echo 'Avance : '.$pagnet['avance'].'<br/>';?>

                                                                                    <?php  echo 'Reste : '.($pagnet['apayerPagnet'] - $pagnet['avance']).'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['avance']!=0 && $pagnet['avance']>0): 

                                                                                        $sqlGetCompteAvance="SELECT * FROM `".$nomtableVersement."` v, `".$nomtableCompte."` c where v.idCompte=c.idCompte AND idPagnet = ".$pagnet['idPagnet'];

                                                                                        $resAvance = mysql_query($sqlGetCompteAvance) or die ("persoonel requête 2".mysql_error());

                                                                                        $cptA = mysql_fetch_array($resAvance);

                                                                                    ?>

                                                                                    <?php  echo 'Compte avance : '.$cptA['nomCompte'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <?php endif; ?>

                                                                            <div>

                                                                                <?php if($pagnet['versement']!=0): ?>

                                                                                    <?php  echo 'Espèces : '.$pagnet['versement'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['restourne']!=0 && $pagnet['restourne']>0): ?>

                                                                                    <?php  echo 'Rendu : '.$pagnet['restourne'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                        </div>

                                                                    <!--*******************************Fin Total Facture****************************************-->

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <?php

                                                    }

                                                    else if ($ligne['classe']==7) {?>

                                                        <div class="panel panel-default">

                                                            <div class="panel-heading">

                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                                                    <div class="right-arrow pull-right">+</div>

                                                                    <a href="#"> Panier <?php echo $n; ?>

                                                                        <span class="spanDate noImpr"> </span>

                                                                        <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

                                                                        <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                                                        <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                                $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                                                                                $TotalT = mysql_fetch_array($resT);

                                                                                $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                                                $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());

                                                                                $TotalP = mysql_fetch_array($resP);

                                                                        ?>

                                                                        <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                                                        <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                                        <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                                                    </a>

                                                                </h4>

                                                            </div>

                                                            <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>

                                                                <?php 

                                                                    if($idmax == $pagnet['heurePagnet']){

                                                                        ?> class="panel-collapse collapse in" <?php

                                                                    }

                                                                    else  {

                                                                        ?> class="panel-collapse collapse " <?php

                                                                    }

                                                                ?>  >

                                                                <div class="panel-body" >

                                                                    <!--*******************************Debut Retourner Pagnet****************************************-->

                                                                        <?php if ($pagnet['iduser']==$_SESSION['iduser']){ ?>

                                                                            <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                            </button>

                                                                        <?php }

                                                                        else {  ?>

                                                                            <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >

                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                            </button>

                                                                        <?php }?>

            

                                                                        <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                                            <div class="modal-dialog">

                                                                                <!-- Modal content-->

                                                                                <div class="modal-content">

                                                                                    <div class="modal-header panel-primary">

                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                        <h4 class="modal-title">Confirmation</h4>

                                                                                    </div>

                                                                                    <form class="form-inline noImpr"  id="factForm" method="post"  >

                                                                                        <div class="modal-body">

                                                                                            <p><?php echo "Voulez-vous retourner le panier numéro <b>".$pagnet['idPagnet']."<b>" ; ?></p>

                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>

                                                                                        </div>

                                                                                        <div class="modal-footer">

                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                            <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                                        </div>

                                                                                    </form>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                        


                                                                        

                                                                        <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                                        Ticket de Caisse

                                                                        </button>

                                                                        

            

                                                                    <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                        method="post" action="barcodeFacture.php" >

                                                                        <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                    </form>

            

                                                                    <table class="table ">

                                                                        <thead class="noImpr">

                                                                        <tr>

                                                                            <th></th>

                                                                            <th>Référence</th>

                                                                            <th>Quantité</th>

                                                                            <th>Forme</th>

                                                                            <th>Prix Public</th>

                                                                        </tr>

                                                                        </thead>

                                                                        <tbody>

                                                                            <?php

                                                                                $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                                                $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                                                while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                                                    <tr>

                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >

                                                                                        </td>

                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                                                        <td>

                                                                                            <?php echo  'Montant'; ?> <span class="factureFois"></span>

                                                                                        </td>

                                                                                        <td class="forme ">

                                                                                            <?php echo $ligne['forme']; ?>

                                                                                        </td>

                                                                                        <td>

                                                                                            <?php echo  $ligne['prixPublic']; ?>  <span class="factureFois" ></span>

                                                                                        </td>

                                                                                        <td>

                                                                                            <?php if ($pagnet['iduser']==$_SESSION['iduser']){ ?>

                                                                                                <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

                                                                                                        <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                                </button>

                                                                                            <?php }

                                                                                            else {  ?>

                                                                                                <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >

                                                                                                        <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                                </button>

                                                                                            <?php }?>

            

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne".$ligne['numligne'] ; ?> role="dialog">

                                                                                                <div class="modal-dialog">

                                                                                                    <!-- Modal content-->

                                                                                                    <div class="modal-content">

                                                                                                        <div class="modal-header panel-primary">

                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                                                        </div>

                                                                                                        <form class="form-inline noImpr" id="factForm" method="post" >

                                                                                                            <div class="modal-body">

                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>

                                                                                                                <input type="hidden" name="idPagnet" id="idPagnet<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                                                                <input type="hidden" name="designation" id="designation<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['designation'].""; ?> >

                                                                                                                <input type="hidden" name="numligne" id="numligne<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['numligne'].""; ?> >

                                                                                                                <input type="hidden" name="idStock" id="idStock<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['idStock'].""; ?> >

                                                                                                                <input type="hidden" name="forme" id="forme<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['forme'].""; ?> >

                                                                                                                <input type="hidden" name="quantite" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                                                <input type="hidden" name="prixPublic" id="prixPublic<?= $ligne['numligne']; ?>" 	<?php echo  "value=".$ligne['prixPublic'].""; ?> >

                                                                                                                <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                                                <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                                            </div>

                                                                                                            <div class="modal-footer">

                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                                                <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres_Ph">Confirmer<img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

                                                                                                            </div>

                                                                                                        </form>

                                                                                                    </div>

                                                                                                </div>

                                                                                            </div>

                                                                                        </td>

                                                                                    </tr>

                                                                                    <?php  

                                                                                }  

                                                                            ?>

                                                                        </tbody>

                                                                    </table>

            

                                                                    <!--*******************************Debut Total Facture****************************************-->

                                                                        <div>

                                                                            <div>

                                                                                <?php echo  '********************************************* <br/>'; ?>

                                                                                <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>

                                                                                    <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>

                                                                            </div>

                                                                                <?php if($_SESSION['compte']==1):?>

                                                                            <div>

                                                                                <?php if($pagnet['idCompte']!=0 && $pagnet['idCompte']>0): 

                                                                                        $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$pagnet['idCompte'];

                                                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());

                                                                                        $cpt = mysql_fetch_array($resPay2);

                                                                                    ?>

                                                                                    <?php  echo 'Compte : '. $cpt['nomCompte'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['avance']!=0 && $pagnet['avance']>0): 

                                                                                    ?>

                                                                                    <?php  echo 'Avance : '.$pagnet['avance'].'<br/>';?>

                                                                                    <?php  echo 'Reste : '.($pagnet['apayerPagnet'] - $pagnet['avance']).'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['avance']!=0 && $pagnet['avance']>0): 

                                                                                        $sqlGetCompteAvance="SELECT * FROM `".$nomtableVersement."` v, `".$nomtableCompte."` c where v.idCompte=c.idCompte AND idPagnet = ".$pagnet['idPagnet'];

                                                                                        $resAvance = mysql_query($sqlGetCompteAvance) or die ("persoonel requête 2".mysql_error());

                                                                                        $cptA = mysql_fetch_array($resAvance);

                                                                                    ?>

                                                                                    <?php  echo 'Compte avance : '.$cptA['nomCompte'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <?php endif; ?>

                                                                            <div>

                                                                                <?php if($pagnet['versement']!=0): ?>

                                                                                    <?php  echo 'Espèces : '.$pagnet['versement'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($pagnet['restourne']!=0 && $pagnet['restourne']>0): ?>

                                                                                    <?php  echo 'Rendu : '.$pagnet['restourne'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                        </div>

                                                                    <!--*******************************Fin Total Facture****************************************-->

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <?php

                                                    }

                                                    ?>

                                                    <div class="modal fade" <?php echo  "id=imageNvPanier".$pagnet['idPagnet'] ; ?> role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header" style="padding:35px 50px;">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Panier : <b>#<?php echo  $pagnet['idPagnet'] ; ?></b></h4>
                                                                </div>
                                                                <form   method="post" enctype="multipart/form-data">
                                                                    <div class="modal-body" style="padding:40px 50px;">
                                                                        <input  type="text" style="display:none" name="idPanier" id="idPanier_Upd_Nv" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> />
                                                                        <div class="form-group" style="text-align:center;" >
                                                                            <?php 
                                                                                if($pagnet['image']!=null && $pagnet['image']!=' '){ 
                                                                                    $format=substr($pagnet['image'], -3);
                                                                                    ?>
                                                                                        <input type="file" name="file" value="<?php echo  $pagnet['image']; ?>" accept="image/*" id="input_file_Panier<?php echo  $pagnet['idPagnet']; ?>" onchange="showPreviewPanier(event,<?php echo  $pagnet['idPagnet']; ?>);"/><br />
                                                                                        <?php if($format=='pdf'){ ?>
                                                                                            <iframe id="output_pdf_Panier<?php echo  $pagnet['idPagnet']; ?>" src="./PiecesJointes/<?php echo  $pagnet['image']; ?>" width="100%" height="500px"></iframe>
                                                                                            <img style="display:none;" width="500px" height="500px" id="output_image_Panier<?php echo  $pagnet['idPagnet'];  ?>"/>
                                                                                        <?php }
                                                                                        else { ?>
                                                                                            <img  src="./PiecesJointes/<?php echo  $pagnet['image']; ?>" width="500px" height="500px" id="output_image_Panier<?php echo  $pagnet['idPagnet']; ?>"/>
                                                                                            <iframe id="output_pdf_Panier<?php echo  $pagnet['idPagnet'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                                        <?php } ?>
                                                                                    <?php 
                                                                                }
                                                                                else{ ?>
                                                                                    <input type="file" name="file" accept="image/*" id="input_file_Panier<?php echo  $pagnet['idPagnet']; ?>" id="cover_image" onchange="showPreviewPanier(event,<?php echo  $pagnet['idPagnet']; ?>);"/><br />
                                                                                    <img  style="display:none;" width="500px" height="500px" id="output_image_Panier<?php echo  $pagnet['idPagnet']; ?>"/>
                                                                                    <iframe id="output_pdf_Panier<?php echo  $pagnet['idPagnet'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                                <?php
                                                                                }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                            <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgPanier" id="btn_upload_Panier<?php echo  $pagnet['idPagnet']; ?>" >
                                                                                <span class="glyphicon glyphicon-upload"></span> Enregistrer
                                                                            </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                            
                                                    <?php

                                                }

                                            }

                                            else if($vente[2]==2){

                                                $sqlT1="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet='".$vente[1]."' ";

                                                $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());

                                                $mutuelle = mysql_fetch_assoc($resT1);

                                                if($mutuelle!=null){

                                                    $sql="SELECT * FROM `".$nomtableLigne."` where idMutuellePagnet=".$mutuelle['idMutuellePagnet']." ";

                                                    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                                                    $ligne = mysql_fetch_assoc($res) ;

                                                    if($ligne['classe']==0){?>

                                                        <div class="panel panel-info">

                                                            <div class="panel-heading">

                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#mutuelle".$mutuelle['idMutuellePagnet']."" ; ?>  class="panel-title expand">

                                                                    <div class="right-arrow pull-right">+</div>

                                                                    <a href="#"> Imputation

                                                                        <span class="spanDate noImpr"> </span>

                                                                        <span class="spanDate noImpr"> Date: <?php echo $mutuelle['datepagej']; ?> </span>

                                                                        <span class="spanDate noImpr">Heure: <?php echo $mutuelle['heurePagnet']; ?></span>

                                                                        <span class="spanDate noImpr"> Total panier: <?php echo $mutuelle['totalp']; ?> </span>

                                                                        <span class="spanDate noImpr">Total à payer: <?php echo $mutuelle['apayerPagnet']; ?></span>

                                                                        <span class="spanDate noImpr"> Facture : #<?php echo $mutuelle['idMutuellePagnet']; ?></span>

                                                                    </a>

                                                                </h4>

                                                            </div>

                                                            <div <?php echo  "id=mutuelle".$mutuelle['idMutuellePagnet']."" ; ?>

                                                                <?php 

                                                                    if($idmax == $mutuelle['heurePagnet']){

                                                                        ?> class="panel-collapse collapse in" <?php

                                                                    }

                                                                    else  {

                                                                        ?> class="panel-collapse collapse " <?php

                                                                    }

                                                                ?>  >

                                                                <div class="panel-body" >

                                                                    <!--*******************************Debut Retourner Pagnet****************************************-->

                                                                        <?php if ($mutuelle['iduser']==$_SESSION['iduser']){ ?>

                                                                            <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$mutuelle['idMutuellePagnet'] ; ?>>

                                                                                <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                            </button>

                                                                        <?php }

                                                                        else {  ?>

                                                                            <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >

                                                                                <span class="glyphicon glyphicon-remove"></span>Retourner

                                                                            </button>

                                                                        <?php }?>

            

                                                                        <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet".$mutuelle['idMutuellePagnet'] ; ?> role="dialog">

                                                                            <div class="modal-dialog">

                                                                                <!-- Modal content-->

                                                                                <div class="modal-content">

                                                                                    <div class="modal-header panel-primary">

                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                        <h4 class="modal-title">Confirmation</h4>

                                                                                    </div>

                                                                                    <form class="form-inline noImpr"  id="factForm" method="post"  >

                                                                                        <div class="modal-body">

                                                                                            <p><?php echo "Voulez-vous retourner le panier numéro <b>".$mutuelle['idMutuellePagnet']."<b>" ; ?></p>

                                                                                            <input type="hidden" name="idMutuellePagnet" id="idMutuellePagnet"  <?php echo  "value='".$mutuelle['idMutuellePagnet']."'" ; ?>>

                                                                                        </div>

                                                                                        <div class="modal-footer">

                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                            <button type="button" name="btnRetournerImputation" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" class="btn btn-success btnRetournerImputation">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                                        </div>

                                                                                    </form>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    <!--*******************************Fin Retourner Pagnet****************************************-->

        
                                                                    <!--*******************************Debut Editer Pagnet****************************************-->

                                                                        <?php if ($_SESSION['proprietaire']==1 && $_SESSION['editionPanier']==1){ ?>

                                                                            <button type="button" class="btn btn-primary pull-left modeEditionBtn_Mt btn_disabled_after_click" id="edit-<?= $mutuelle['idMutuellePagnet'] ; ?>">

                                                                                <span class="glyphicon glyphicon-edit"></span> Editer

                                                                            </button>



                                                                            <div class="modal fade" <?php echo  "id=msg_edit_mutuelle".$mutuelle['idMutuellePagnet'] ; ?> role="dialog">

                                                                                <div class="modal-dialog">

                                                                                    <div class="modal-content">

                                                                                        <div class="modal-header panel-primary">

                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                            <h4 class="modal-title">Alert</h4>

                                                                                        </div>

                                                                                        <p>Une erreur est survenu lors de l'édition. <br>

                                                                                            Veuillez rééssayer!</p>

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        <?php } ?>

                                                                    <!--*******************************Fin Editer Pagnet****************************************-->

                                                                        <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_mutuelle".$mutuelle['idMutuellePagnet'] ; ?>>

                                                                            Facture

                                                                        </button>


            

                                                                    <div class="modal fade" <?php echo  "id=msg_fact_mutuelle".$mutuelle['idMutuellePagnet'] ; ?> role="dialog">

                                                                        <div class="modal-dialog">

                                                                            <!-- Modal content-->

                                                                            <div class="modal-content">

                                                                                <div class="modal-header panel-primary">

                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                                    <h4 class="modal-title">Informations Client</h4>

                                                                                </div>

                                                                                <form class="form-inline noImpr"  method="post" action="pdfFacturePharmacie.php" target="_blank" >

                                                                                    <div class="modal-body">

                                                                                        <div class="row">

                                                                                            <div class="col-xs-6">

                                                                                                <label for="reference">Adresse Client</label>

                                                                                                <input type="text" class="form-control" name="adresse" >

                                                                                            </div>

                                                                                            <div class="col-xs-6">

                                                                                                <label for="reference">Telephone Client</label>

                                                                                                <input type="text" class="form-control" name="telephone" >

                                                                                            </div>

                                                                                        </div>

                                                                                        <input type="hidden" name="idMutuellePagnet" id="idMutuellePagnet"  <?php echo  "value='".$mutuelle['idMutuellePagnet']."'" ; ?>>

                                                                                    </div>

                                                                                    <div class="modal-footer">

                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                        <button type="submit" name="btnFacture" class="btn btn-success">Confirmer</button>

                                                                                    </div>

                                                                                </form>

                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                        


                                                                        <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$mutuelle['idMutuellePagnet'] ;?>').submit();">

                                                                        Ticket de Caisse

                                                                        </button>


            

                                                                    <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$mutuelle['idMutuellePagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                        method="post" action="barcodeFacture.php" >

                                                                        <input type="hidden" name="idMutuellePagnet" id="idMutuellePagnet"  <?php echo  "value=".$mutuelle['idMutuellePagnet']."" ; ?> >

                                                                    </form>

            

                                                                    <table class="table ">

                                                                        <thead class="noImpr">

                                                                            <tr>

                                                                                <th></th>

                                                                                <th>Référence</th>

                                                                                <th>Quantité</th>

                                                                                <th>Forme</th>

                                                                                <th>Prix Public</th>

                                                                            </tr>

                                                                        </thead>

                                                                        <tbody>

                                                                            <?php

                                                                                $sql8="SELECT * FROM `".$nomtableLigne."` where idMutuellePagnet=".$mutuelle['idMutuellePagnet']." ORDER BY numligne DESC";

                                                                                $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                                                while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                                                    <tr>

                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$mutuelle['idMutuellePagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >

                                                                                        </td>

                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                                                        <td>

                                                                                        <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>

                                                                                        </td>

                                                                                        <td class="forme ">

                                                                                            <?php echo $ligne['forme']; ?>

                                                                                        </td>

                                                                                        <td>

                                                                                            <?php echo  $ligne['prixPublic']; ?>  <span class="factureFois" ></span>

                                                                                        </td>

                                                                                        <td>

                                                                                            <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal">

                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour

                                                                                            </button>

                                                                                        </td>

                                                                                    </tr>

                                                                                    <?php  

                                                                                }  

                                                                            ?>

                                                                        </tbody>

                                                                    </table>

                                                                                

                                                                    <!--*******************************Debut Total Facture****************************************-->

                                                                        <div class="col-sm-4 ">

                                                                            <div>

                                                                                <?php echo  '********************************************* <br/>'; ?>

                                                                                <?php echo  'TOTAL : '.$mutuelle['totalp'].'<br/>'; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($mutuelle['remise']!=0 && $mutuelle['remise']>0): ?>

                                                                                    <?php  echo 'Taux Imputation :'. $mutuelle['taux'].' %<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php echo  '<b>Net à payer Adherant : '.$mutuelle['apayerPagnet'].'</b><br/>'; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php echo  '<b>Net à payer Mutuelle  : '.$mutuelle['apayerMutuelle'].'</b><br/>'; ?>

                                                                            </div>
                                                                            <div>

                                                                                <?php if($mutuelle['versement']!=0): ?>

                                                                                    <?php  echo 'Espèces : '.$mutuelle['versement'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($mutuelle['restourne']!=0 && $mutuelle['restourne']>0): ?>

                                                                                    <?php  echo 'Rendu : '.$mutuelle['restourne'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                                <?php if($_SESSION['compte']==1):?>

                                                                            <div>

                                                                                <?php if($mutuelle['idCompte']!=0 && $mutuelle['idCompte']>0): 

                                                                                        $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$mutuelle['idCompte'];

                                                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());

                                                                                        $cpt = mysql_fetch_array($resPay2);

                                                                                    ?>

                                                                                    <?php  echo 'Compte : '. $cpt['nomCompte'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($mutuelle['avance']!=0 && $mutuelle['avance']>0): 

                                                                                    ?>

                                                                                    <?php  echo 'Avance : '.$mutuelle['avance'].'<br/>';?>

                                                                                    <?php  echo 'Reste : '.($mutuelle['apayerPagnet'] - $mutuelle['avance']).'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php if($mutuelle['avance']!=0 && $mutuelle['avance']>0): 

                                                                                        $sqlGetCompteAvance="SELECT * FROM `".$nomtableVersement."` v, `".$nomtableCompte."` c where v.idCompte=c.idCompte AND idMutuellePagnet = ".$mutuelle['idMutuellePagnet'];

                                                                                        $resAvance = mysql_query($sqlGetCompteAvance) or die ("persoonel requête 2".mysql_error());

                                                                                        $cptA = mysql_fetch_array($resAvance);

                                                                                    ?>

                                                                                    <?php  echo 'Compte avance : '.$cptA['nomCompte'].'<br/>';?>

                                                                                <?php endif; ?>

                                                                            </div>

                                                                            <?php endif; ?>

                                                                        </div>

                                                                        <div class="col-sm-4 ">

                                                                            <div>

                                                                                <?php echo  '********************************************* <br/>'; ?>

                                                                                <?php

                                                                                    $sqlE="SELECT * FROM `".$nomtableMutuelle."` where idMutuelle=".$mutuelle["idMutuelle"]." ";

                                                                                    $resE = mysql_query($sqlE) or die ("persoonel requête 2".mysql_error());

                                                                                    if($resE){

                                                                                        $mtlle=mysql_fetch_array($resE);

                                                                                        echo  'Mutuelle : '.$mtlle['nomMutuelle'].' ('.$mtlle['tauxMutuelle'].'%)<br/>';

                                                                                    }

                                                                                ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php echo  '<b> Adherant : '.$mutuelle['adherant'].'</b><br/>'; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php echo  '<b>Code Adherant  : '.$mutuelle['codeAdherant'].'</b><br/>'; ?>

                                                                            </div>

                                                                        </div>

                                                                        <div class="col-sm-3 ">

                                                                            <div>

                                                                                <?php echo  '********************************************* <br/>'; ?>

                                                                                <?php echo  '<b>Code Beneficiaire  : '.$mutuelle['codeBeneficiaire'].'</b><br/>'; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php echo  '<b> Numero Reçu : '.$mutuelle['numeroRecu'].'</b><br/>'; ?>

                                                                            </div>

                                                                            <div>

                                                                                <?php echo  '<b>Date Reçu  : '.$mutuelle['dateRecu'].'</b><br/>'; ?>

                                                                            </div>

                                                                        </div>

                                                                        <div class="col-sm-1" tyle="text-align:right;" >
                                                                            <br />
                                                                            <?php if($mutuelle['image']!=null && $mutuelle['image']!=' '){
                                                                                $format=substr($mutuelle['image'], -3); ?>
                                                                                <?php if($format=='pdf'){ ?>
                                                                                    <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvMutuelle".$mutuelle['idMutuellePagnet'] ; ?> onclick="imageUpMutuelle(<?php echo $mutuelle['idMutuellePagnet']; ?>,<?php echo $mutuelle['image']; ?>)" 	 />
                                                                                <?php }
                                                                                    else { 
                                                                                ?>
                                                                                    <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvMutuelle".$mutuelle['idMutuellePagnet'] ; ?> onclick="imageUpMutuelle(<?php echo $mutuelle['idMutuellePagnet']; ?>,<?php echo $mutuelle['image']; ?>)" 	 />
                                                                                <?php } ?>
                                                                            <?php }
                                                                                else { 
                                                                            ?>
                                                                                <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" <?php echo "data-target=#imageNvMutuelle".$mutuelle['idMutuellePagnet'] ; ?> onclick="imageUpMutuelle(<?php echo $mutuelle['idMutuellePagnet']; ?>,<?php echo $mutuelle['image']; ?>)" 	 />
                                                                            <?php } ?>
                                                                        </div>

                                                                    <!--*******************************Fin****************************************-->

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <?php

                                                    }

                                                    ?>

                                                    <div class="modal fade" <?php echo  "id=imageNvMutuelle".$mutuelle['idMutuellePagnet'] ; ?> role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header" style="padding:35px 50px;">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Mutuelle : <b>#<?php echo  $mutuelle['idMutuellePagnet'] ; ?></b></h4>
                                                                </div>
                                                                <form   method="post" enctype="multipart/form-data">
                                                                    <div class="modal-body" style="padding:40px 50px;">
                                                                        <input  type="text" style="display:none" name="idMutuelle" id="idMutuelle_Upd_Nv" <?php echo  "value=".$mutuelle['idMutuellePagnet']."" ; ?> />
                                                                        <div class="form-group" style="text-align:center;" >
                                                                            <?php 
                                                                                if($mutuelle['image']!=null && $mutuelle['image']!=' '){ 
                                                                                    $format=substr($mutuelle['image'], -3);
                                                                                    ?>
                                                                                        <input type="file" name="file" value="<?php echo  $mutuelle['image']; ?>" accept="image/*" id="input_file_Mutuelle<?php echo  $mutuelle['idMutuellePagnet']; ?>" onchange="showPreviewMutuelle(event,<?php echo  $mutuelle['idMutuellePagnet']; ?>);"/><br />
                                                                                        <?php if($format=='pdf'){ ?>
                                                                                            <iframe id="output_pdf_Mutuelle<?php echo  $mutuelle['idMutuellePagnet']; ?>" src="./PiecesJointes/<?php echo  $mutuelle['image']; ?>" width="100%" height="500px"></iframe>
                                                                                            <img style="display:none;" width="500px" height="500px" id="output_image_Mutuelle<?php echo  $mutuelle['idMutuellePagnet'];  ?>"/>
                                                                                        <?php }
                                                                                        else { ?>
                                                                                            <img  src="./PiecesJointes/<?php echo  $mutuelle['image']; ?>" width="500px" height="500px" id="output_image_Mutuelle<?php echo  $mutuelle['idMutuellePagnet']; ?>"/>
                                                                                            <iframe id="output_pdf_Mutuelle<?php echo  $mutuelle['idMutuellePagnet'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                                        <?php } ?>
                                                                                    <?php 
                                                                                }
                                                                                else{ ?>
                                                                                    <input type="file" name="file" accept="image/*" id="input_file_Mutuelle<?php echo  $mutuelle['idMutuellePagnet']; ?>" id="cover_image" onchange="showPreviewMutuelle(event,<?php echo  $mutuelle['idMutuellePagnet']; ?>);"/><br />
                                                                                    <img  style="display:none;" width="500px" height="500px" id="output_image_Mutuelle<?php echo  $mutuelle['idMutuellePagnet']; ?>"/>
                                                                                    <iframe id="output_pdf_Mutuelle<?php echo  $mutuelle['idMutuellePagnet'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                                <?php
                                                                                }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                            <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgMutuelle" id="btn_upload_Mutuelle<?php echo  $mutuelle['idMutuellePagnet']; ?>" >
                                                                                <span class="glyphicon glyphicon-upload"></span> Enregistrer
                                                                            </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
            
                                                    <?php

                                                }

                                            }

                                        ?>

                                    <?php $n=$n-1;   } ?>

                                <?php if($nbPaniers >= 11){ ?>

                                    <ul class="pagination paginationLoaderPh pull-right">

                                        <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->

                                        <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">

                                            <a href="#" data-page="<?= $currentPage - 1 ?>" class="page-link">Précédente</a>

                                        </li>

                                        <?php for($page = 1; $page <= $pages; $page++): ?>

                                            <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->

                                            <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">

                                                <a href="#" data-page="<?= $page ?>" class="page-link"><?= $page ?><img src="images/loading-gif3.gif" class="img-load-page" id="img-load-page<?= $page ?>" style="height: 20px;width: 20px;display:none;" alt="GIF" srcset=""></a>

                                            </li>

                                        <?php endfor ?>

                                            <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->

                                            <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">

                                            <a href="#" data-page="<?= $currentPage + 1 ?>" class="page-link">Suivante</a>

                                        </li>

                                    </ul>

                                <?php } ?>

                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                        <?php } ?>



                    </div>

                <!-- Fin de l'Accordion pour Tout les Paniers -->

            <?php } ?>



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

                <?php

                    if(isset($msg_paiement)) {

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

                                            <h4 class="modal-title">Alerte retard de paiement</h4>

                                        </div>

                                        <div class="modal-body">

                                            <p>'.$msg_paiement.'</p>

                                            

                                        </div>

                                        <div class="modal-footer">

                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

                                        </div>

                                        </div>

                                </div>

                            </div>';

                            

                    }

                ?>

            <!-- Fin PopUp d'Alerte sur le retard de Paiement -->