<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP
Date de modification:05/04/2016
*/
session_start();
if($_SESSION['iduser']){

require('connection.php');

require('declarationVariables.php');

require('entetehtml.php');
/**********************/

$type             =@htmlentities($_POST["type"]);

$dateInventaire1     =@$_POST["dateInventaire"];

$annuler          =@$_POST["annuler"];
/***************/

$numligne2       =@$_GET["numligne"];
/**********************/
//$date = new DateTime('25-02-2011');
$date = new DateTime($dateInventaire1);
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');
$dateInventaire=$jour.'-'.$mois.'-'.$annee;

/**********************/
$numligne         =@$_POST["numligne"];
$type             =@htmlentities($_POST["type"]);
$designation      =@htmlentities($_POST["designation"]);
$unitevente		  =@$_POST["unitevente"];
$prix             =@$_POST["prix"];
$quantite         =@$_POST["quantite"];
$remise           =@$_POST["remise"];
$prixtotal        =@$_POST["prixt"];

$modifier         =@$_POST["modifier"];
$annuler          =@$_POST["annuler"];
/***************/

$numligne2       =@$_GET["numligne"];
/**********************/



echo '

<!-- Debut Style CSS des Tabs -->
    <style>
        /***
        Bootstrap Line Tabs by @keenthemes
        A component of Metronic Theme - #1 Selling Bootstrap 3 Admin Theme in Themeforest: http://j.mp/metronictheme
        Licensed under MIT
        ***/

        /* Tabs panel */
        .tabbable-panel {
        border:3px solid #eee;
        padding: 10px;
        }

        /* Default mode */
        .tabbable-line > .nav-tabs {
        border: none;
        margin: 0px;
        }
        .tabbable-line > .nav-tabs > li {
        margin-right: 2px;
        }
        .tabbable-line > .nav-tabs > li > a {
        border: 0;
        margin-right: 0;
        color: #737373;
        }
        .tabbable-line > .nav-tabs > li > a > i {
        color: #a6a6a6;
        }
        .tabbable-line > .nav-tabs > li.open, .tabbable-line > .nav-tabs > li:hover {
        border-bottom: 4px solid #fbcdcf;
        }
        .tabbable-line > .nav-tabs > li.open > a, .tabbable-line > .nav-tabs > li:hover > a {
        border: 0;
        background: none !important;
        color: #333333;
        }
        .tabbable-line > .nav-tabs > li.open > a > i, .tabbable-line > .nav-tabs > li:hover > a > i {
        color: #a6a6a6;
        }
        .tabbable-line > .nav-tabs > li.open .dropdown-menu, .tabbable-line > .nav-tabs > li:hover .dropdown-menu {
        margin-top: 0px;
        }
        .tabbable-line > .nav-tabs > li.active {
        border-bottom: 4px solid #f3565d;
        position: relative;
        }
        .tabbable-line > .nav-tabs > li.active > a {
        border: 0;
        color: #333333;
        }
        .tabbable-line > .nav-tabs > li.active > a > i {
        color: #404040;
        }
        .tabbable-line > .tab-content {
        margin-top: -3px;
        background-color: #fff;
        border: 0;
        border-top: 1px solid #eee;
        padding: 15px 0;
        }
        .portlet .tabbable-line > .tab-content {
        padding-bottom: 0;
        }

        /* Below tabs mode */

        .tabbable-line.tabs-below > .nav-tabs > li {
        border-top: 4px solid transparent;
        }
        .tabbable-line.tabs-below > .nav-tabs > li > a {
        margin-top: 0;
        }
        .tabbable-line.tabs-below > .nav-tabs > li:hover {
        border-bottom: 0;
        border-top: 4px solid #fbcdcf;
        }
        .tabbable-line.tabs-below > .nav-tabs > li.active {
        margin-bottom: -2px;
        border-bottom: 0;
        border-top: 4px solid #f3565d;
        }
        .tabbable-line.tabs-below > .tab-content {
        margin-top: -10px;
        border-top: 0;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        }
    </style>
<!-- Fin Style CSS des Tabs -->

<!-- Debut Javascript de l"Accordion pour Tout les Paniers -->
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
<!-- Fin Javascript de l"Accordion pour Tout les Paniers -->

';


/**Debut Button Retourner Pagnet**/
if (isset($_POST['btnRetournerPagnet'])) {

    $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));
    
    $sqlP="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
    $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());
    $pagnet = mysql_fetch_assoc($resP) ;

    $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
    $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
    //$ligne = mysql_fetch_assoc($resL) ;

    while ($ligne=mysql_fetch_array($resL)){

        $sqlS="SELECT * FROM `".$nomtableStock."` where idStock=".$ligne['idStock'];
        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

        if(mysql_num_rows($resS)){

            $stock = mysql_fetch_array($resS) or die ("select stock impossible =>".mysql_error());
            $idDesignation=$stock["idDesignation"];

            $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$idDesignation;
            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
            $designation = mysql_fetch_array($resD);

            if(mysql_num_rows($resD)){

                $quantiteStockCourant=$stock['quantiteStockCourant'];
                $uniteStock=$stock['uniteStock'];

                if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {

                    $quantiteCourant=$quantiteStockCourant + ($designation['nbreArticleUniteStock'] * $ligne['quantite']);

                    if($quantiteStockCourant >= 0){

                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteCourant." where idStock=".$ligne['idStock'];
                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                        
                        //on fait la suppression de cette ligne dans la table ligne
                        $sql3="DELETE FROM `".$nomtableLigne."` where numligne=".$ligne['numligne']."  ";
                        $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

                    }

                }
                else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){

                    $quantiteCourant=$quantiteStockCourant + $ligne['quantite'];

                    if($quantiteStockCourant >= 0){

                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteCourant." where idStock=".$ligne['idStock'];
                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                        
                        //on fait la suppression de cette ligne dans la table ligne
                        $sql3="DELETE FROM `".$nomtableLigne."` where numligne=".$ligne['numligne']."  ";
                        $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

                    }

                }

            }

        }

    }

    // suppression du pagnet aprés su^ppression de ses lignes
    $sqlR="DELETE FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet;
    $resR=@mysql_query($sqlR) or die ("mise à jour client  impossible".mysql_error());
}
/**Fin Button Retourner Pagnet**/

/**Debut Button Retourner Ligne d'un Pagnet**/
if (isset($_POST['btnRetourApres'])) {

    $numligne=$_POST['numligne'];
    $idStock=$_POST['idStock'];
    $designation=$_POST['designation'];
    $idPagnet=$_POST['idPagnet'];
    $quantite=$_POST['quantite'];
    $unitevente=$_POST['unitevente'];
    $prixunitevente=$_POST['prixunitevente'];
    $prixtotal=$_POST['prixtotal'];
    $totalp=$_POST['totalp'];

    $sqlL="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($resL) ;

    $sqlS="SELECT * FROM `".$nomtableStock."` where idStock=".$ligne['idStock'];
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    $sqlP="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
    $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());
    $pagnet = mysql_fetch_assoc($resP) ;

    $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' && numligne<>".$numligne."";
    $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
    $TotalT = mysql_fetch_array($resT) ;

    $difference=$TotalT[0] - $pagnet["remise"];

    if($difference > 0){
        if(mysql_num_rows($resS)){

            $stock = mysql_fetch_array($resS) or die ("select stock impossible =>".mysql_error());
            $idDesignation=$stock["idDesignation"];

            $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$idDesignation;
            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
            $designation = mysql_fetch_array($resD);

            if(mysql_num_rows($resD)){

            $quantiteStockCourant=$stock['quantiteStockCourant'];
            $uniteStock=$stock['uniteStock'];

                if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {

                $quantiteCourant=$quantiteStockCourant + ($designation['nbreArticleUniteStock'] * $ligne['quantite']);

                if($quantiteStockCourant >= 0){

                    $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteCourant." where idStock=".$ligne['idStock'];
                    $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                    $sqlD="DELETE FROM `".$nomtableLigne."` where numligne=".$numligne;
                    $resD=@mysql_query($sqlD) or die ("mise à jour client  impossible".mysql_error());

                }

                }
                else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){

                    $quantiteCourant=$quantiteStockCourant + $ligne['quantite'];

                if($quantiteStockCourant >= 0){

                    $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteCourant." where idStock=".$ligne['idStock'];
                    $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                    $sqlD="DELETE FROM `".$nomtableLigne."` where numligne=".$numligne;
                    $resD=@mysql_query($sqlD) or die ("mise à jour client  impossible".mysql_error());

                }

                }

            }

        }

        $newPrix=$totalp-($quantite*$prixunitevente);

        $sql19="SELECT remise,versement FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
        $res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());
        $pagnet = mysql_fetch_array($res19) ;

        $apayerPagnet=$newPrix-$pagnet['remise'];
        $restourne=$pagnet['versement']-$apayerPagnet;

        $sql16="update `".$nomtablePagnet."` set totalp=".$newPrix.",apayerPagnet=".$apayerPagnet.",
                                            restourne=".$restourne." where idPagnet='".$idPagnet."'";
        $res16=mysql_query($sql16) or die ("update Pagnet impossible =>".mysql_error());
    }
    else {
        $msg_info="<p>IMPOSSIBLE.</br></br> Vous ne pouvez pas retourner cette ligne du pagnet numéro ".$idPagnet." , car c'est la(les) dernière(s) ligne(s). Cela risque de fausser les calculs surtout quand vous avez effectué des remises.</br></br>Il faut retourner tout le pagnet.</p>";
    }

}
/**Fin Button Retourner Ligne d'un Pagnet**/

function totalVenteCategorie($idCategorie, $totalCategorie, $dateInventaire)
{

$nomtableCategorie   =$_SESSION['nomB']."-categorie";
$nomtableLigne=$_SESSION['nomB']."-lignepj";
$nomtableDesignation=$_SESSION['nomB']."-designation";
$nomtablePagnet=$_SESSION['nomB']."-pagnet";

$date = new DateTime();
$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateString2=$jour.'-'.$mois.'-'.$annee;

$sql1="SELECT * FROM `".$nomtableCategorie."` where idcategorie=".$idCategorie;
//echo $sql1;
$res1 = mysql_query($sql1) or die ("categorie requête 1".mysql_error());
$tab1 = mysql_fetch_array($res1) ;
$nomCategorie1 = $tab1["nomcategorie"];
$total1=0;
$sql2="SELECT SUM( prixtotal ) as total FROM `".$nomtableDesignation."`,`".$nomtableLigne."`,`".$nomtablePagnet."`
WHERE `".$nomtableDesignation."`.designation = `".$nomtableLigne."`.designation
AND   `".$nomtablePagnet."`.idPagnet = `".$nomtableLigne."`.idPagnet
AND   `".$nomtablePagnet."`.datepagej ='".$dateInventaire."'
AND   `".$nomtablePagnet."`.idClient =0
AND `".$nomtableDesignation."`.categorie ='".$nomCategorie1."'";

$res2 = mysql_query($sql2) or die ("categorie requête 1".mysql_error());
//echo $sql2.'</br>';
if(mysql_num_rows($res2))
        $tab2 = mysql_fetch_array($res2) ;
        $total1 += $tab2["total"] ;

$total2=0;
$sql3="SELECT nomcategorie FROM `".$nomtableCategorie."` where categorieParent=".$idCategorie;
$res3=mysql_query($sql3);
if(mysql_num_rows($res3)){
    	while($tab3=mysql_fetch_array($res3)) {
                    $nomCategorie1 = $tab3["nomcategorie"];
                    $sql4="SELECT SUM( prixtotal ) as total FROM `".$nomtableDesignation."`,`".$nomtableLigne."`,`".$nomtablePagnet."`
                    WHERE `".$nomtableDesignation."`.designation = `".$nomtableLigne."`.designation
                    AND   `".$nomtablePagnet."`.idPagnet = `".$nomtableLigne."`.idPagnet
                    AND   `".$nomtablePagnet."`.datepagej ='".$dateInventaire."'
                    AND   `".$nomtablePagnet."`.idClient =0
                    AND `".$nomtableDesignation."`.categorie ='".$nomCategorie1."'";

                    $res4 = mysql_query($sql4) or die ("categorie requête 2".mysql_error());
                    //echo $sql4.'</br>';
                    $tab4 = mysql_fetch_array($res4) ;
                    $total2 += $tab4["total"] ;

    	}
}
return $total1+$total2 ;
}


echo'<body onLoad="process()"><header>';

require('header.php');

echo'<div class="container" >';

if(!$annuler){
if((!@$_POST["dateInventaire"])&&(!@$_GET["datenext"])&&(!@$_GET["dateprevious"])){
$datehier = date('d-m-Y', strtotime('-1 days'));
$datehier_Y = date('Y-m-d', strtotime('-1 days'));

echo'<section><div class="container">'; ?>

<?php
//-----------------------------------------------------------------------------------

$inttialisation=0;

        $sqlP1="SELECT *
            FROM `".$nomtablePagnet."` p
            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
            WHERE (d.classe = 0 OR d.classe = 1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$datehier."'  ORDER BY p.idPagnet DESC";
        $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
        $T_ventes = 0 ;
        $S_ventes = 0;
        while ($pagnet = mysql_fetch_array($resP1)) {  
            $sqlS="SELECT SUM(apayerPagnet)
            FROM `".$nomtablePagnet."` 
            where idClient=0 &&  verrouiller=1 && datepagej ='".$datehier."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";
            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
            $S_ventes = mysql_fetch_array($resS);
            $T_ventes = $S_ventes[0] + $T_ventes;
        }

        $sqlP3="SELECT *
            FROM `".$nomtablePagnet."` p
            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
            WHERE d.classe = 2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$datehier."'  ORDER BY p.idPagnet DESC";
        $resP3 = mysql_query($sqlP3) or die ("persoonel requête 2".mysql_error());
        $T_depenses = 0 ;
        $S_depenses = 0;
        while ($pagnet = mysql_fetch_array($resP3)) {  
            $sqlS="SELECT SUM(prixtotal)
            FROM `".$nomtableLigne."` l
            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
            WHERE d.classe = 2  && l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
            $S_depenses = mysql_fetch_array($resS);
            $T_depenses = $S_depenses[0] + $T_depenses;
        }

        $sqlP4="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient!=0 &&  verrouiller=1 && datepagej ='".$datehier."'  ORDER BY idPagnet DESC";
            $resP4 = mysql_query($sqlP4) or die ("persoonel requête 2".mysql_error());
        $T_bons = mysql_fetch_array($resP4) ;

        $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where  dateVersement  ='".$datehier."'  ORDER BY idVersement DESC";
            $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());
        $T_versements = mysql_fetch_array($resP5) ;

       ?>
       <table >
        <tr> <td>
            
            <div aria-label="navigation">
                <ul class="pager">

                    <li class="previous"><a href="historiquecaisse.php?dateprevious=<?php echo date('d-m-Y', strtotime('-2 days'));?>" title="Précédent">Précédent</a></li>
                    <li><input type="date"  id="jour_date"  onchange="date_jour('jour_date');"  <?php echo '  max="'.date('Y-m-d', strtotime('-1 days')).'" ';?> value="<?php echo date('Y-m-d', strtotime($datehier));?>" name="dateInventaire" required  /></li>
                    <li class="next"><a href="historiquecaisse.php?datenext=<?php echo date('d-m-Y', strtotime('+0 days'));?>" title="Suivant">Suivant</a></li>
                </ul>
            </div>
            
        </td></tr>

        <tr> <td>
        <div class="container">
             <div class="col-md-12">
                 <div class="panel panel-default">
                     <div class="panel-heading">
                            <h3 class="panel-title">DEITAIL DU TOTAL DE LA CAISSE DU <?php echo $datehier; ?></h3>
                     </div>
                     <div class="panel-body">
                            <table class="table table-bordered table-responsive ">
                                    <tr>
                                        <td>Ventes  
                                            <?php
                                            $sql8="select * from `".$nomtableDesignation."` where classe=1";
                                            $res8=mysql_query($sql8);
                                            if(mysql_num_rows($res8)){
                                            echo ' et Services ';
                                            }?>
                                        </td>
                                        <td><?php
                                         if($T_ventes != null){
                                            echo $T_ventes; 
                                         }
                                         else{
                                            echo 0; 
                                         }
                                         ?>
                                                &nbsp;&nbsp;<a href="" data-toggle="modal" data-target="#myModal">Details</a>
<?php

$sql5="SELECT * FROM `".$nomtableCategorie."`";
$res5=mysql_query($sql5);
if(mysql_num_rows($res5)){
                                             ?>
                        <!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"><b> Ventes par Catégorie</b> </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <table class="table table-bordered table-responsive ">


                 <?php $totalCategorie=0;
                       while($tab5=mysql_fetch_array($res5)) {

                          if(totalVenteCategorie($tab5["idcategorie"], $totalCategorie, $datehier)!=0) { ?>

                            <tr>
                                <td><b><?php echo $tab5["nomcategorie"] ; ?> : </b></td>
                                <td align="right"><b><?php echo totalVenteCategorie($tab5["idcategorie"], $totalCategorie,$datehier); ?></b>&nbsp;FCFA</td>
                            </tr>

                      <?php }

                      }
                          ?>
                    <tr>
                        <td><b>REMISE : </b></td>
                        <td align="right"><b><?php
                        $T_remise =0;
                        $sqlS="SELECT SUM(remise)FROM `".$nomtablePagnet."` WHERE datepagej ='".$datehier."'";
                		$resS=mysql_query($sqlS) or die ("Somme remise impossible =>".mysql_error());
                		$S_remise = mysql_fetch_array($resS);
                		$T_remise = $S_remise[0] ;
                        echo $T_remise; ?></b>&nbsp;FCFA</td>
                    </tr>

         </table>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
      </div>

    </div>
  </div>
</div>
 <?php }     ?>
                                                </td>
                                            </tr>
                                    <tr>
                                        <td>Versements clients :</td>
                                        <td><?php
                                            if($T_versements[0] != null){
                                                echo $T_versements[0]; 
                                            }
                                            else{
                                                echo 0; 
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                        $sql8="select * from `".$nomtableDesignation."` where classe=2";
                                        $res8=mysql_query($sql8);
                                        if(mysql_num_rows($res8)){	?>
                                                <tr>
                                                    <td>Depenses :</td>
                                                    <td><?php
                                                        if($T_depenses != null){
                                                            echo $T_depenses; 
                                                        }
                                                        else{
                                                            echo 0; 
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>

                                                    <?php } ?>
                                                <tr class="danger">
                                                    <td> <b> Total caisse : </b></td>
                                                    <td><b><?php
                                                        if($T_ventes != null || $T_versements[0] !=null || $T_depenses!= null){
                                                            echo $T_ventes + $T_versements[0] - $T_depenses; 
                                                        }
                                                        else{
                                                            echo 0; 
                                                        }
                                                        ?></b>
                                                    </td>
                                                </tr>
                                                <tr class="warning">
                                                    <td><b> Total Bon :  </b></td>
                                                    <td><b><?php
                                                        if($T_bons[0] != null){
                                                            echo $T_bons[0]; 
                                                        }
                                                        else{
                                                            echo 0; 
                                                        }
                                                        ?></b>
                                                    </td>
                                                </tr>
                            </table>
                            <br>
                     </div>
                 </div>
            </div>
        </div>


 </td></tr> </table>

        <!-- Debut Container Details Journal -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="tabbable-panel">
                        <div class="tabbable-line">
                            <ul class="nav nav-tabs ">
                                <li class="active">
                                    <a href="#tab_default_1" data-toggle="tab">
                                    Ventes
                                        <?php
                                        $sql8="select * from `".$nomtableDesignation."` where classe=1";
                                        $res8=mysql_query($sql8);
                                        if(mysql_num_rows($res8)){
                                        echo ' et Services ';
                                        }?>
                                    </a>
                                </li>
                                
                                <?php
                                        $sql8="select * from `".$nomtableDesignation."` where classe=2";
                                        $res8=mysql_query($sql8);
                                        if(mysql_num_rows($res8)){
                                        echo '   <li>
                                        <a href="#tab_default_3" data-toggle="tab">
                                        Depenses </a>
                                    </li> ';
                                        }?>
                                
                                <li>
                                    <a href="#tab_default_4" data-toggle="tab">
                                    Bon clients </a>
                                </li>
                                <li>
                                    <a href="#tab_default_5" data-toggle="tab">
                                    Versements clients </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_default_1">       
                                    <!-- Debut de l'Accordion pour Toutes les Ventes -->
                                        <div class="panel-group" id="accordion">

                                            <?php

                                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                                                $sqlP1="SELECT *
                                                FROM `".$nomtablePagnet."` p
                                                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                                                INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                WHERE (d.classe = 0 or d.classe = 1)  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$datehier."'  ORDER BY p.idPagnet DESC";
                                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                                                $sqlC="SELECT COUNT(*) 
                                                FROM `".$nomtablePagnet."` p
                                                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                                                INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                WHERE (d.classe = 0 or d.classe = 1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$datehier."'  ORDER BY p.idPagnet DESC";
                                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                                $nbre = mysql_fetch_array($resC) ; 
                                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ ?>       

                                            <!-- Debut Boucle while concernant les Paniers Vendus -->
                                                <?php $n=$nbre[0]; while ($pagnet = mysql_fetch_array($resP1)) {   ?>

                                                    <?php 
                                                    if($pagnet['remise'] != 0){?>
                                                       <div class="panel panel-danger">
                                                            <div class="panel-heading">
                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#vente".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
                                                                <div class="right-arrow pull-right">+</div>
                                                                <a href="#"> Panier <?php echo $n; ?>
                                                                    <span class="spanDate noImpr"> </span>
                                                                    <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                    <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>
                                                                    <?php  if ($pagnet['verrouiller']==1): ?>
                                                                    <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                            $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());
                                                                            $TotalT = mysql_fetch_array($resT);
                                                                            $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                            $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());
                                                                            $TotalP = mysql_fetch_array($resP);?>
                                                                    <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>
                                                                    <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>
                                                                <?php endif; ?>
                                                                <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>
                                                                </a>
                                                                </h4>
                                                            </div>
                                                            <div class="panel-collapse collapse " <?php echo  "id=vente".$pagnet['idPagnet']."" ; ?> >
                                                                <div class="panel-body" >
                                                                        
                                                                        <?php if ($pagnet['verrouiller']==1):  ?>

                                                                            <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_vente".$pagnet['idPagnet'] ; ?>>
                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                            </button>

                                                                            <div class="modal fade" <?php echo  "id=msg_rtrn_vente".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                                <div class="modal-dialog">
                                                                                    <!-- Modal content-->
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header panel-primary">
                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                        </div>
                                                                                        <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                            <div class="modal-body">
                                                                                                <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                            <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                            Facture
                                                                            </button><br>

                                                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                                method="post" action="barcodeFacture.php" >
                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                            </form>

                                                                        <?php endif; ?>

                                                                        <div  class="divFacture" style="display:none;">
                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                                <?php echo  '*********************************************'; ?>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <table class="table ">
                                                                        <thead class="noImpr">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Référence</th>
                                                                            <th>Quantité</th>
                                                                            <th>Unité vente</th>
                                                                            <th>Prix Unité vente</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sql8="SELECT * 
                                                                            FROM `".$nomtableLigne."` l
                                                                            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                            WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                            while ($ligne = mysql_fetch_array($res8)) {
                                                                                if ($ligne['classe'] == 0) {?>
                                                                                    <tr class="info">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                if ($ligne['classe'] == 1) {?>
                                                                                    <tr class="active">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                else {?>
                                                                                    <tr>
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                
                                                                            }  ?>

                                                                            </tbody>
                                                                            </table>

                                                                            <div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <div class="divFacture" style="display:none;">
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                    <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                    <?php echo  '<br/>********************************************* '; ?>
                                                                                    A BIENTOT !
                                                                                </div>
                                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div> <?php 
                                                    }	
                                                    else { ?>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading">
                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#vente".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
                                                                <div class="right-arrow pull-right">+</div>
                                                                <a href="#"> Panier <?php echo $n; ?>
                                                                    <span class="spanDate noImpr"> </span>
                                                                    <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                    <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>
                                                                    <?php  if ($pagnet['verrouiller']==1): ?>
                                                                    <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                            $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());
                                                                            $TotalT = mysql_fetch_array($resT);
                                                                            $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                            $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());
                                                                            $TotalP = mysql_fetch_array($resP);?>
                                                                    <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>
                                                                    <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>
                                                                <?php endif; ?>
                                                                <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                                </a>
                                                                </h4>
                                                            </div>
                                                            <div class="panel-collapse collapse " <?php echo  "id=vente".$pagnet['idPagnet']."" ; ?> >
                                                                <div class="panel-body" >
                                                                        
                                                                        <?php if ($pagnet['verrouiller']==1):  ?>

                                                                            <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_vente".$pagnet['idPagnet'] ; ?>>
                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                            </button>

                                                                            <div class="modal fade" <?php echo  "id=msg_rtrn_vente".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                                <div class="modal-dialog">
                                                                                    <!-- Modal content-->
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header panel-primary">
                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                        </div>
                                                                                        <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                            <div class="modal-body">
                                                                                                <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                            <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                            Facture
                                                                            </button><br>

                                                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                                method="post" action="barcodeFacture.php" >
                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                            </form>

                                                                        <?php endif; ?>

                                                                        <div  class="divFacture" style="display:none;">
                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                                <?php echo  '*********************************************'; ?>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <table class="table ">
                                                                        <thead class="noImpr">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Référence</th>
                                                                            <th>Quantité</th>
                                                                            <th>Unité vente</th>
                                                                            <th>Prix Unité vente</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sql8="SELECT * 
                                                                            FROM `".$nomtableLigne."` l
                                                                            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                            WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                            while ($ligne = mysql_fetch_array($res8)) {
                                                                                if ($ligne['classe'] == 0) {?>
                                                                                    <tr class="info">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                if ($ligne['classe'] == 1) {?>
                                                                                    <tr class="active">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                else {?>
                                                                                    <tr>
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                
                                                                            }  ?>

                                                                            </tbody>
                                                                            </table>

                                                                            <div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <div class="divFacture" style="display:none;">
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                    <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                    <?php echo  '<br/>********************************************* '; ?>
                                                                                    A BIENTOT !
                                                                                </div>
                                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div><?php
                                                    }	
                                                    ?>

                                                <?php $n=$n-1;   } ?>
                                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                                        </div>
                                    <!-- Fin de l'Accordion pour Toutes les Ventes -->
                                </div>
                                <div class="tab-pane" id="tab_default_3">
                                    <!-- Debut de l'Accordion pour Toutes les Depenses -->
                                        <div class="panel-group" id="accordion">

                                            <?php

                                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                                                $sqlP1="SELECT *
                                                FROM `".$nomtablePagnet."` p
                                                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                                                INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                WHERE d.classe = 2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$datehier."'  ORDER BY p.idPagnet DESC";
                                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                                                $sqlC="SELECT COUNT(*) 
                                                FROM `".$nomtablePagnet."` p
                                                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                                                INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                WHERE d.classe = 2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$datehier."'  ORDER BY p.idPagnet DESC";
                                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                                $nbre = mysql_fetch_array($resC) ; 
                                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ ?>       

                                            <!-- Debut Boucle while concernant les Paniers Vendus -->
                                                <?php $n=$nbre[0]; while ($pagnet = mysql_fetch_array($resP1)) {   ?>

                                                    <div class="panel panel-warning">
                                                        <div class="panel-heading">
                                                            <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#depenses".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
                                                            <div class="right-arrow pull-right">+</div>
                                                            <a href="#"> Panier <?php echo $n; ?>
                                                                <span class="spanDate noImpr"> </span>
                                                                <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>
                                                                <?php   $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                        $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());
                                                                        $TotalP = mysql_fetch_array($resP);
                                                                        $sqlS="SELECT SUM(prixtotal)
                                                                        FROM `".$nomtableLigne."` l
                                                                        INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                        INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                        WHERE d.classe = 2  && l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                                                                        $TotalS = mysql_fetch_array($resS); ?>
                                                                <span class="spanTotal noImpr" >Total à payer Panier: <span ><?php echo $TotalP[0]; ?> </span></span>        
                                                                <span class="spanTotal noImpr" >Total Depenses: <span ><?php echo $TotalS[0]; ?> </span></span>
                                                                <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                            </a>
                                                            </h4>
                                                        </div>
                                                        <div class="panel-collapse collapse " <?php echo  "id=depenses".$pagnet['idPagnet']."" ; ?> >
                                                            <div class="panel-body" >
                                                                    
                                                                    <?php if ($pagnet['verrouiller']==1):  ?>

                                                                    <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                    <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_depense".$pagnet['idPagnet'] ; ?>>
                                                                            <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                    </button>

                                                                    <div class="modal fade" <?php echo  "id=msg_rtrn_depense".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                        <div class="modal-dialog">
                                                                            <!-- Modal content-->
                                                                            <div class="modal-content">
                                                                                <div class="modal-header panel-primary">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    <h4 class="modal-title">Confirmation</h4>
                                                                                </div>
                                                                                <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                    <div class="modal-body">
                                                                                        <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                        <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                        <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                    <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                    Facture
                                                                    </button><br>

                                                                    <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                        method="post" action="barcodeFacture.php" >
                                                                        <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                    </form>

                                                                    <?php endif; ?>

                                                                    <div  class="divFacture" style="display:none;">
                                                                        <?php if ($pagnet['verrouiller']==1): ?>
                                                                            <?php echo  '********************************************* <br/>'; ?>
                                                                            <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                            <?php echo  '*********************************************'; ?>
                                                                        <?php endif; ?>
                                                                    </div>

                                                                    <table class="table ">
                                                                    <thead class="noImpr">
                                                                    <tr>
                                                                        <th></th>
                                                                        <th>Référence</th>
                                                                        <th>Quantité</th>
                                                                        <th>Unité vente</th>
                                                                        <th>Prix Unité vente</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                        $sql8="SELECT * 
                                                                        FROM `".$nomtableLigne."` l
                                                                        INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                        INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                        WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                        $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                        while ($ligne = mysql_fetch_array($res8)) {
                                                                            if ($ligne['classe'] == 2) {?>
                                                                                <tr class="info">
                                                                                    <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                    </td>
                                                                                    <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                    <td><?php
                                                                                    if ($pagnet['verrouiller']==1): ?>
                                                                                        <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                        <?php endif; ?>
                                                                                    </td>
                                                                                    <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                    <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                        <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                        <?php endif; ?>
                                                                                    </td>

                                                                                    <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                    <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php if ($pagnet['verrouiller']==1): ?>
                                                                                        <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_depense".$ligne['numligne'] ; ?>>
                                                                                                <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                        </button>

                                                                                        <div class="modal fade" <?php echo  "id=msg_rtrnApres_depense".$ligne['numligne'] ; ?> role="dialog">
                                                                                            <div class="modal-dialog">
                                                                                                <!-- Modal content-->
                                                                                                <div class="modal-content">
                                                                                                    <div class="modal-header panel-primary">
                                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                        <h4 class="modal-title">Confirmation</h4>
                                                                                                    </div>
                                                                                                    <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                        <div class="modal-body">
                                                                                                            <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                            <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                            <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                            <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                            <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                            <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                            <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                            <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                            <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                            <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                        </div>
                                                                                                        <div class="modal-footer">
                                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                            <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                        </div>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <?php endif; ?>

                                                                                    </td>
                                                                                </tr>
                                                                            <?php  }
                                                                            else {?>
                                                                                <tr>
                                                                                    <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                    </td>
                                                                                    <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                    <td><?php
                                                                                    if ($pagnet['verrouiller']==1): ?>
                                                                                        <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                        <?php endif; ?>
                                                                                    </td>
                                                                                    <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                    <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                        <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                        <?php endif; ?>
                                                                                    </td>

                                                                                    <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                    <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php if ($pagnet['verrouiller']==1): ?>
                                                                                        <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_depense".$ligne['numligne'] ; ?>>
                                                                                                <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                        </button>

                                                                                        <div class="modal fade" <?php echo  "id=msg_rtrnApres_depense".$ligne['numligne'] ; ?> role="dialog">
                                                                                            <div class="modal-dialog">
                                                                                                <!-- Modal content-->
                                                                                                <div class="modal-content">
                                                                                                    <div class="modal-header panel-primary">
                                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                        <h4 class="modal-title">Confirmation</h4>
                                                                                                    </div>
                                                                                                    <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                        <div class="modal-body">
                                                                                                            <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                            <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                            <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                            <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                            <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                            <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                            <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                            <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                            <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                            <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                        </div>
                                                                                                        <div class="modal-footer">
                                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                            <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                        </div>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <?php endif; ?>

                                                                                    </td>
                                                                                </tr>
                                                                            <?php  }
                                                                            
                                                                        }  ?>

                                                                        </tbody>
                                                                        </table>

                                                                        <div>
                                                                            <div>
                                                                                <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div>
                                                                                <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                    <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div>
                                                                                <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                <?php endif; ?>
                                                                            </div>
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
                                                                            <div class="divFacture" style="display:none;">
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                <?php echo  '<br/>********************************************* '; ?>
                                                                                A BIENTOT !
                                                                            </div>
                                                                        </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php $n=$n-1;   } ?>
                                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                                        </div>
                                    <!-- Fin de l'Accordion pour Toutes les Depenses -->                                    
                                </div>
                                <div class="tab-pane" id="tab_default_4">
                                    <!-- Debut de l'Accordion pour Tout les Bons -->
                                        <div class="panel-group" id="accordion">

                                            <?php

                                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                                                $sqlP1="SELECT * FROM `".$nomtablePagnet."` where idClient!=0 &&  verrouiller=1 && datepagej ='".$datehier."'  ORDER BY idPagnet DESC";
                                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                                                $sqlC="SELECT COUNT(*) FROM `".$nomtablePagnet."` where idClient!=0 && verrouiller=1 && datepagej ='".$datehier."'  ORDER BY idPagnet DESC";
                                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                                $nbre = mysql_fetch_array($resC) ;
                                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ ?>       

                                            <!-- Debut Boucle while concernant les Paniers Vendus -->
                                                <?php $n=$nbre[0]; while ($pagnet = mysql_fetch_array($resP1)) {   ?>

                                                    <?php 
                                                    if($pagnet['remise'] != 0){?>
                                                        <div class="panel panel-danger">
                                                            <div class="panel-heading">
                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#bon".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
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
                                                                    <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                                </a>
                                                                </h4>
                                                            </div>
                                                            <div class="panel-collapse collapse " <?php echo  "id=bon".$pagnet['idPagnet']."" ; ?> >
                                                                <div class="panel-body" >
                                                                        
                                                                        <?php if ($pagnet['verrouiller']==1):  ?>

                                                                        <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                        <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_bon".$pagnet['idPagnet'] ; ?>>
                                                                                <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                        </button>

                                                                        <div class="modal fade" <?php echo  "id=msg_rtrn_bon".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                            <div class="modal-dialog">
                                                                                <!-- Modal content-->
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header panel-primary">
                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                        <h4 class="modal-title">Confirmation</h4>
                                                                                    </div>
                                                                                    <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                        <div class="modal-body">
                                                                                            <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                            <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                        <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                        Facture
                                                                        </button><br>

                                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                            method="post" action="barcodeFacture.php" >
                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                        </form>

                                                                        <?php endif; ?>

                                                                        <div  class="divFacture" style="display:none;">
                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                                <?php echo  '*********************************************'; ?>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <table class="table ">
                                                                        <thead class="noImpr">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Référence</th>
                                                                            <th>Quantité</th>
                                                                            <th>Unité vente</th>
                                                                            <th>Prix Unité vente</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sql8="SELECT * 
                                                                            FROM `".$nomtableLigne."` l
                                                                            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                            WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                            while ($ligne = mysql_fetch_array($res8)) {
                                                                                if ($ligne['classe'] == 0) {?>
                                                                                    <tr class="info">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_bon".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_bon".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                else {?>
                                                                                    <tr>
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_bon".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_bon".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                
                                                                            }  ?>

                                                                            </tbody>
                                                                            </table>

                                                                            <div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <div class="divFacture" style="display:none;">
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                    <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                    <?php echo  '<br/>********************************************* '; ?>
                                                                                    A BIENTOT !
                                                                                </div>
                                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div><?php 
                                                    }	
                                                    else { ?>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading">
                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#bon".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
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
                                                                    <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                                </a>
                                                                </h4>
                                                            </div>
                                                            <div class="panel-collapse collapse " <?php echo  "id=bon".$pagnet['idPagnet']."" ; ?> >
                                                                <div class="panel-body" >
                                                                        
                                                                        <?php if ($pagnet['verrouiller']==1):  ?>

                                                                        <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                        <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_bon".$pagnet['idPagnet'] ; ?>>
                                                                                <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                        </button>

                                                                        <div class="modal fade" <?php echo  "id=msg_rtrn_bon".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                            <div class="modal-dialog">
                                                                                <!-- Modal content-->
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header panel-primary">
                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                        <h4 class="modal-title">Confirmation</h4>
                                                                                    </div>
                                                                                    <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                        <div class="modal-body">
                                                                                            <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                            <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                        <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                        Facture
                                                                        </button><br>

                                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                            method="post" action="barcodeFacture.php" >
                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                        </form>

                                                                        <?php endif; ?>

                                                                        <div  class="divFacture" style="display:none;">
                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                                <?php echo  '*********************************************'; ?>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <table class="table ">
                                                                        <thead class="noImpr">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Référence</th>
                                                                            <th>Quantité</th>
                                                                            <th>Unité vente</th>
                                                                            <th>Prix Unité vente</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sql8="SELECT * 
                                                                            FROM `".$nomtableLigne."` l
                                                                            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                            WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                            while ($ligne = mysql_fetch_array($res8)) {
                                                                                if ($ligne['classe'] == 0) {?>
                                                                                    <tr class="info">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_bon".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_bon".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                else {?>
                                                                                    <tr>
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_bon".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_bon".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                
                                                                            }  ?>

                                                                            </tbody>
                                                                            </table>

                                                                            <div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <div class="divFacture" style="display:none;">
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                    <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                    <?php echo  '<br/>********************************************* '; ?>
                                                                                    A BIENTOT !
                                                                                </div>
                                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div><?php
                                                    }	
                                                    ?>

                                                <?php $n=$n-1;   } ?>
                                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                                        </div>
                                    <!-- Fin de l'Accordion pour Tout les Bon -->  
                                </div>
                                <div class="tab-pane" id="tab_default_5">
                                    <!-- Debut de l'Accordion pour Tout les Versements -->
                                        <div class="panel-group" id="accordion">

                                            <?php
                                            
                                            /**Debut requete pour Lister les Versements d'Aujourd'hui  **/
                                                $sqlP1="SELECT * FROM `".$nomtableVersement."` where  dateVersement  ='".$datehier."'  ORDER BY idVersement DESC";
                                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                            /**Fin requete pour Lister les Versements d'Aujourd'hui  **/

                                            /**Debut requete pour Compter les Versements d'Aujourd'hui  **/
                                                $sqlC="SELECT COUNT(*) FROM `".$nomtableVersement."` where  dateVersement  ='".$datehier."'  ORDER BY idVersement DESC";
                                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                                $nbre = mysql_fetch_array($resC) ;
                                            /**Fin requete pour Compter les Versements d'Aujourd'hui  **/

                                            ?>         

                                            <!-- Debut Boucle while concernant les Versements  -->
                                                <?php $n=$nbre[0]; while ($versement = mysql_fetch_array($resP1)) {   ?>

                                                    <div style="padding-top : 2px;" class="panel panel-warning">
                                                        <div class="panel-heading">
                                                            <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#versement".$versement['idVersement']."" ; ?>  class="panel-title expand">
                                                            <div class="right-arrow pull-right">+</div>
                                                            <a href="#"> Versement <?php echo $n; ?>

                                                            <span class="spanDate noImpr"> </span>
                                                            <span class="spanDate noImpr"> Date: <?php echo $versement['dateVersement']; ?> </span>
                                                            <span class="spanDate noImpr">Heure: <?php echo $versement['heureVersement']; ?></span>
                                                            <span class="spanDate noImpr">Montant: <?php echo $versement['montant']; ?></span>

                                                            </a>
                                                            </h4>
                                                        </div>
                                                        <div class="panel-collapse collapse " <?php echo  "id=versement".$versement['idVersement']."" ; ?> >
                                                            <div class="panel-body" >

                                                                <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_anl_versement".$versement['idVersement'] ; ?>	 >
                                                                    <span class="glyphicon glyphicon-remove"></span>Annuler
                                                                </button>
                                                                
                                                                <div class="modal fade" <?php echo  "id=msg_anl_versement".$versement['idVersement'] ; ?> role="dialog">
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
                                                                                    <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?>>
                                                                                    <input type="hidden" name="montant" id="montant"  <?php echo  "value=".$versement['montant']."" ; ?>>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                    <button type="submit" name="btnAnnulerVersement" class="btn btn-success">Confirmer</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <form class="form-inline pull-right noImpr" style="margin-right:20px;"
                                                                    method="post" action="barcodeFacture.php" target="_blank"  >
                                                                    <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?> >
                                                                    <button type="submit" class="btn btn-success pull-right" data-toggle="modal" name="barcodeFactureV">
                                                                    <span class="glyphicon glyphicon-lock"></span>Facture
                                                                    </button>
                                                                </form>
                                                                    
                                                                <div <?php echo  "id=divImpVer".$versement['idVersement']."" ; ?>  >
                                                                    <div >
                                                                        Date : <?php 
                                                                        $date1=$versement['dateVersement'];
                                                                        $date2 = new DateTime($date1);
                                                                        //R�cup�ration de l'ann�e
                                                                        $annee =$date2->format('Y');
                                                                        //R�cup�ration du mois
                                                                        $mois =$date2->format('m');
                                                                        //R�cup�ration du jours
                                                                        $jour =$date2->format('d');
                                                                        $date=$jour.'-'.$mois.'-'.$annee;
                                                                                        
                                                                        echo $date; ?> <br/>Heure : <?php 

                                                                        $heureV=$versement['heureVersement'];

                                                                        echo $heureV; ?><br/>
                                                                        <?php echo  '********************************************* <br/>'; ?>
                                                                        <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                        <?php echo  $_SESSION['adresseB'] ;?>
                                                                        <?php echo  '<br/>*********************************************'; ?>
                                                                    </div>
                                                                    <div> </div>
                                                                    <div><b>Montant : <?php echo  $versement['montant']; ?></b></div>
                                                                    <div class="divFacture" style="display:none;">
                                                                    N° <?php echo $versement['idVersement']; ?> <?php echo "-".$idClient  ?>
                                                                    <span class="spanDate"> <?php echo $versement['dateVersement']; ?> </span>
                                                                </div>
                                                                <div  align="center"> A BIENTOT !</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php $n=$n-1;   } ?>
                                            <!-- Fin Boucle while concernant les Versements   -->

                                        </div>
                                    <!-- Fin de l'Accordion pour Tout les Versements -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin Container Details Journal -->

<?php

echo'</section>'.
'<script>$(document).ready(function(){$("#AjoutLigne").click(function(){$("#AjoutLigneModal").modal();});});</script></body></html>';

}
else if (@$_POST["dateInventaire"]){





echo'<section><div class="container">';

//-----------------------------------------------------------------------------------

$inttialisation=0;

                $sqlP1="SELECT *
                    FROM `".$nomtablePagnet."` p
                    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                    INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                    WHERE (d.classe = 0 OR d.classe = 1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                $T_ventes = 0 ;
                $S_ventes = 0;
                while ($pagnet = mysql_fetch_array($resP1)) {  
                    $sqlS="SELECT SUM(apayerPagnet)
                    FROM `".$nomtablePagnet."` 
                    where idClient=0 &&  verrouiller=1 && datepagej ='".$dateInventaire."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";
                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                    $S_ventes = mysql_fetch_array($resS);
                    $T_ventes = $S_ventes[0] + $T_ventes;
                }

                $sqlP3="SELECT *
                    FROM `".$nomtablePagnet."` p
                    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                    INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                    WHERE d.classe = 2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                $resP3 = mysql_query($sqlP3) or die ("persoonel requête 2".mysql_error());
                $T_depenses = 0 ;
                $S_depenses = 0;
                while ($pagnet = mysql_fetch_array($resP3)) {  
                    $sqlS="SELECT SUM(prixtotal)
                    FROM `".$nomtableLigne."` l
                    INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                    WHERE d.classe = 2  && l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                    $S_depenses = mysql_fetch_array($resS);
                    $T_depenses = $S_depenses[0] + $T_depenses;
                }

                $sqlP4="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateInventaire."'  ORDER BY idPagnet DESC";
                    $resP4 = mysql_query($sqlP4) or die ("persoonel requête 2".mysql_error());
                $T_bons = mysql_fetch_array($resP4) ;

                $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where  dateVersement  ='".$dateInventaire."'  ORDER BY idVersement DESC";
                    $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());
                $T_versements = mysql_fetch_array($resP5) ;

        ?>

            <?php $dateDepartTimestamp = strtotime($dateInventaire); ?>
            <div aria-label="navigation">
                <ul class="pager">
                    <li class="previous"><a href="historiquecaisse.php?dateprevious=<?php echo date('d-m-Y', strtotime('-1 days',$dateDepartTimestamp));?>" title="Précédent">Précédent</a></li>
                    <li><input type="date" id="jour_date"  onchange="date_jour('jour_date');"  <?php echo ' max="'.date('Y-m-d', strtotime('-1 days')).'" ';?> value="<?php echo date('Y-m-d', strtotime($dateInventaire));?>"  name="dateInventaire" required  /></li>
                    <li class="next"><a href="historiquecaisse.php?datenext=<?php echo date('d-m-Y', strtotime('+1 days',$dateDepartTimestamp));?>" title="Suivant">Suivant</a></li>
                </ul>
            </div>
            
        <div class="container" >
             <div class="col-md-12">
                 <div class="panel panel-default">
                     <div class="panel-heading">
                            <h3 class="panel-title">DEITAIL DU TOTAL DE LA CAISSE DU <?php echo $dateInventaire; ?></h3>
                     </div>
                    <div class="panel-body">
                        <table class="table table-bordered table-responsive ">
                            <tr>
                                <td>Ventes  
                                    <?php
                                    $sql8="select * from `".$nomtableDesignation."` where classe=1";
                                    $res8=mysql_query($sql8);
                                    if(mysql_num_rows($res8)){
                                    echo ' et Services ';
                                    }?>
                                </td>
                                <td><?php
                                    if($T_ventes != null){
                                    echo $T_ventes; 
                                    }
                                    else{
                                    echo 0; 
                                    }
                                    ?>
                                                &nbsp;&nbsp;<a href="" data-toggle="modal" data-target="#myModal">Details</a>
<?php

$sql5="SELECT * FROM `".$nomtableCategorie."`";
$res5=mysql_query($sql5);
if(mysql_num_rows($res5)){
                                             ?>
                        <!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"><b> Ventes par Catégorie</b> </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <table class="table table-bordered table-responsive ">


                 <?php $totalCategorie=0;
                       while($tab5=mysql_fetch_array($res5)) {

                          if(totalVenteCategorie($tab5["idcategorie"], $totalCategorie,$dateInventaire)!=0) { ?>

                            <tr>
                                <td><b><?php echo $tab5["nomcategorie"] ; ?> : </b></td>
                                <td align="right"><b><?php echo totalVenteCategorie($tab5["idcategorie"], $totalCategorie,$dateInventaire); ?></b>&nbsp;FCFA</td>
                            </tr>

                      <?php }

                      }
                          ?>
                    <tr>
                        <td><b>REMISE : </b></td>
                        <td align="right"><b><?php
                        $T_remise =0;
                        $sqlS="SELECT SUM(remise)FROM `".$nomtablePagnet."` WHERE datepagej ='".$dateInventaire."'";
                		$resS=mysql_query($sqlS) or die ("Somme remise impossible =>".mysql_error());
                		$S_remise = mysql_fetch_array($resS);
                		$T_remise = $S_remise[0] ;
                        echo $T_remise; ?></b>&nbsp;FCFA</td>
                    </tr>

         </table>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
      </div>

    </div>
  </div>
</div>
 <?php }     ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Versements clients :</td>
                                <td><?php
                                    if($T_versements[0] != null){
                                        echo $T_versements[0]; 
                                    }
                                    else{
                                        echo 0; 
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                                $sql8="select * from `".$nomtableDesignation."` where classe=2";
                                $res8=mysql_query($sql8);
                                if(mysql_num_rows($res8)){	?>
                                                <tr>
                                                    <td>Depenses :</td>
                                                    <td><?php
                                                        if($T_depenses != null){
                                                            echo $T_depenses; 
                                                        }
                                                        else{
                                                            echo 0; 
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>

                                                    <?php } ?>
                                                <tr class="danger">
                                                    <td> <b> Total caisse : </b></td>
                                                    <td><b><?php
                                                        if($T_ventes != null || $T_versements[0] !=null || $T_depenses!= null){
                                                            echo $T_ventes + $T_versements[0] - $T_depenses; 
                                                        }
                                                        else{
                                                            echo 0; 
                                                        }
                                                        ?></b>
                                                    </td>
                                                </tr>
                                                <tr class="warning">
                                                    <td><b> Total Bon :  </b></td>
                                                    <td><b><?php
                                                        if($T_bons[0] != null){
                                                            echo $T_bons[0]; 
                                                        }
                                                        else{
                                                            echo 0; 
                                                        }
                                                        ?></b>
                                                    </td>
                                                </tr>
                        </table>
                        <?php echo '
                        <br>
                     </div>
               </div>
            </div></div>';
/**********************************************************************/
?>
    
        <!-- Debut Container Details Journal -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="tabbable-panel">
                        <div class="tabbable-line">
                            <ul class="nav nav-tabs ">
                                <li class="active">
                                    <a href="#tab_default_1" data-toggle="tab">
                                        Ventes
                                        <?php
                                        $sql8="select * from `".$nomtableDesignation."` where classe=1";
                                        $res8=mysql_query($sql8);
                                        if(mysql_num_rows($res8)){
                                        echo ' et Services ';
                                        }?> 
                                    </a>
                                </li>
                                <?php
                                        $sql8="select * from `".$nomtableDesignation."` where classe=2";
                                        $res8=mysql_query($sql8);
                                        if(mysql_num_rows($res8)){
                                        echo '   <li>
                                        <a href="#tab_default_3" data-toggle="tab">
                                        Depenses </a>
                                    </li> ';
                                        }?>
                                
                                <li>
                                    <a href="#tab_default_4" data-toggle="tab">
                                    Bon clients </a>
                                </li>
                                <li>
                                    <a href="#tab_default_5" data-toggle="tab">
                                    Versements clients </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_default_1">       
                                    <!-- Debut de l'Accordion pour Toutes les Ventes -->
                                        <div class="panel-group" id="accordion">

                                            <?php

                                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                                                $sqlP1="SELECT *
                                                FROM `".$nomtablePagnet."` p
                                                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                                                INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                WHERE (d.classe = 0 or d.classe = 1)  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                                                $sqlC="SELECT COUNT(*) 
                                                FROM `".$nomtablePagnet."` p
                                                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                                                INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                WHERE (d.classe = 0 or d.classe = 1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                                $nbre = mysql_fetch_array($resC) ; 
                                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ ?>       

                                            <!-- Debut Boucle while concernant les Paniers Vendus -->
                                                <?php $n=$nbre[0]; while ($pagnet = mysql_fetch_array($resP1)) {   ?>

                                                    <?php 
                                                    if($pagnet['remise'] != 0){?>
                                                    <div class="panel panel-danger">
                                                            <div class="panel-heading">
                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#vente".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
                                                                <div class="right-arrow pull-right">+</div>
                                                                <a href="#"> Panier <?php echo $n; ?>
                                                                    <span class="spanDate noImpr"> </span>
                                                                    <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                    <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>
                                                                    <?php  if ($pagnet['verrouiller']==1): ?>
                                                                    <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                            $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());
                                                                            $TotalT = mysql_fetch_array($resT);
                                                                            $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                            $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());
                                                                            $TotalP = mysql_fetch_array($resP);?>
                                                                    <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>
                                                                    <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>
                                                                <?php endif; ?>
                                                                <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                                </a>
                                                                </h4>
                                                            </div>
                                                            <div class="panel-collapse collapse " <?php echo  "id=vente".$pagnet['idPagnet']."" ; ?> >
                                                                <div class="panel-body" >
                                                                        
                                                                        <?php if ($pagnet['verrouiller']==1):  ?>

                                                                            <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_vente".$pagnet['idPagnet'] ; ?>>
                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                            </button>

                                                                            <div class="modal fade" <?php echo  "id=msg_rtrn_vente".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                                <div class="modal-dialog">
                                                                                    <!-- Modal content-->
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header panel-primary">
                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                        </div>
                                                                                        <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                            <div class="modal-body">
                                                                                                <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                            <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                            Facture
                                                                            </button><br>

                                                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                                method="post" action="barcodeFacture.php" >
                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                            </form>

                                                                        <?php endif; ?>

                                                                        <div  class="divFacture" style="display:none;">
                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                                <?php echo  '*********************************************'; ?>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <table class="table ">
                                                                        <thead class="noImpr">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Référence</th>
                                                                            <th>Quantité</th>
                                                                            <th>Unité vente</th>
                                                                            <th>Prix Unité vente</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sql8="SELECT * 
                                                                            FROM `".$nomtableLigne."` l
                                                                            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                            WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                            while ($ligne = mysql_fetch_array($res8)) {
                                                                                if ($ligne['classe'] == 0) {?>
                                                                                    <tr class="info">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                if ($ligne['classe'] == 1) {?>
                                                                                    <tr class="active">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                else {?>
                                                                                    <tr>
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                
                                                                            }  ?>

                                                                            </tbody>
                                                                            </table>

                                                                            <div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <div class="divFacture" style="display:none;">
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                    <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                    <?php echo  '<br/>********************************************* '; ?>
                                                                                    A BIENTOT !
                                                                                </div>
                                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div> <?php 
                                                    }	
                                                    else { ?>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading">
                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#vente".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
                                                                <div class="right-arrow pull-right">+</div>
                                                                <a href="#"> Panier <?php echo $n; ?>
                                                                    <span class="spanDate noImpr"> </span>
                                                                    <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                    <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>
                                                                    <?php  if ($pagnet['verrouiller']==1): ?>
                                                                    <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                            $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());
                                                                            $TotalT = mysql_fetch_array($resT);
                                                                            $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                            $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());
                                                                            $TotalP = mysql_fetch_array($resP);?>
                                                                    <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>
                                                                    <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>
                                                                <?php endif; ?>
                                                                <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                                </a>
                                                                </h4>
                                                            </div>
                                                            <div class="panel-collapse collapse " <?php echo  "id=vente".$pagnet['idPagnet']."" ; ?> >
                                                                <div class="panel-body" >
                                                                        
                                                                        <?php if ($pagnet['verrouiller']==1):  ?>

                                                                            <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_vente".$pagnet['idPagnet'] ; ?>>
                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                            </button>

                                                                            <div class="modal fade" <?php echo  "id=msg_rtrn_vente".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                                <div class="modal-dialog">
                                                                                    <!-- Modal content-->
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header panel-primary">
                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                        </div>
                                                                                        <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                            <div class="modal-body">
                                                                                                <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                            <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                            Facture
                                                                            </button><br>

                                                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                                method="post" action="barcodeFacture.php" >
                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                            </form>

                                                                        <?php endif; ?>

                                                                        <div  class="divFacture" style="display:none;">
                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                                <?php echo  '*********************************************'; ?>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <table class="table ">
                                                                        <thead class="noImpr">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Référence</th>
                                                                            <th>Quantité</th>
                                                                            <th>Unité vente</th>
                                                                            <th>Prix Unité vente</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sql8="SELECT * 
                                                                            FROM `".$nomtableLigne."` l
                                                                            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                            WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                            while ($ligne = mysql_fetch_array($res8)) {
                                                                                if ($ligne['classe'] == 0) {?>
                                                                                    <tr class="info">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                if ($ligne['classe'] == 1) {?>
                                                                                    <tr class="active">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                else {?>
                                                                                    <tr>
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                
                                                                            }  ?>

                                                                            </tbody>
                                                                            </table>

                                                                            <div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <div class="divFacture" style="display:none;">
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                    <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                    <?php echo  '<br/>********************************************* '; ?>
                                                                                    A BIENTOT !
                                                                                </div>
                                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div><?php
                                                    }	
                                                    ?>

                                                <?php $n=$n-1;   } ?>
                                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                                        </div>
                                    <!-- Fin de l'Accordion pour Toutes les Ventes -->
                                </div>
                                <div class="tab-pane" id="tab_default_3">
                                    <!-- Debut de l'Accordion pour Toutes les Depenses -->
                                        <div class="panel-group" id="accordion">

                                            <?php

                                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                                                $sqlP1="SELECT *
                                                FROM `".$nomtablePagnet."` p
                                                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                                                INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                WHERE d.classe = 2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                                                $sqlC="SELECT COUNT(*) 
                                                FROM `".$nomtablePagnet."` p
                                                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                                                INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                WHERE d.classe = 2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                                $nbre = mysql_fetch_array($resC) ; 
                                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ ?>       

                                            <!-- Debut Boucle while concernant les Paniers Vendus -->
                                                <?php $n=$nbre[0]; while ($pagnet = mysql_fetch_array($resP1)) {   ?>

                                                    <div class="panel panel-warning">
                                                        <div class="panel-heading">
                                                            <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#depenses".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
                                                            <div class="right-arrow pull-right">+</div>
                                                            <a href="#"> Panier <?php echo $n; ?>
                                                                <span class="spanDate noImpr"> </span>
                                                                <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>
                                                                <?php   $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                        $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());
                                                                        $TotalP = mysql_fetch_array($resP);
                                                                        $sqlS="SELECT SUM(prixtotal)
                                                                        FROM `".$nomtableLigne."` l
                                                                        INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                        INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                        WHERE d.classe = 2  && l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                                                                        $TotalS = mysql_fetch_array($resS); ?>
                                                                <span class="spanTotal noImpr" >Total à payer Panier: <span ><?php echo $TotalP[0]; ?> </span></span>        
                                                                <span class="spanTotal noImpr" >Total Depenses: <span ><?php echo $TotalS[0]; ?> </span></span>
                                                                <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                            </a>
                                                            </h4>
                                                        </div>
                                                        <div class="panel-collapse collapse " <?php echo  "id=depenses".$pagnet['idPagnet']."" ; ?> >
                                                            <div class="panel-body" >
                                                                    
                                                                    <?php if ($pagnet['verrouiller']==1):  ?>

                                                                    <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                    <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_depense".$pagnet['idPagnet'] ; ?>>
                                                                            <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                    </button>

                                                                    <div class="modal fade" <?php echo  "id=msg_rtrn_depense".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                        <div class="modal-dialog">
                                                                            <!-- Modal content-->
                                                                            <div class="modal-content">
                                                                                <div class="modal-header panel-primary">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    <h4 class="modal-title">Confirmation</h4>
                                                                                </div>
                                                                                <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                    <div class="modal-body">
                                                                                        <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                        <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                        <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                    <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                    Facture
                                                                    </button><br>

                                                                    <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                        method="post" action="barcodeFacture.php" >
                                                                        <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                    </form>

                                                                    <?php endif; ?>

                                                                    <div  class="divFacture" style="display:none;">
                                                                        <?php if ($pagnet['verrouiller']==1): ?>
                                                                            <?php echo  '********************************************* <br/>'; ?>
                                                                            <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                            <?php echo  '*********************************************'; ?>
                                                                        <?php endif; ?>
                                                                    </div>

                                                                    <table class="table ">
                                                                    <thead class="noImpr">
                                                                    <tr>
                                                                        <th></th>
                                                                        <th>Référence</th>
                                                                        <th>Quantité</th>
                                                                        <th>Unité vente</th>
                                                                        <th>Prix Unité vente</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                        $sql8="SELECT * 
                                                                        FROM `".$nomtableLigne."` l
                                                                        INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                        INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                        WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                        $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                        while ($ligne = mysql_fetch_array($res8)) {
                                                                            if ($ligne['classe'] == 2) {?>
                                                                                <tr class="info">
                                                                                    <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                    </td>
                                                                                    <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                    <td><?php
                                                                                    if ($pagnet['verrouiller']==1): ?>
                                                                                        <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                        <?php endif; ?>
                                                                                    </td>
                                                                                    <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                    <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                        <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                        <?php endif; ?>
                                                                                    </td>

                                                                                    <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                    <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php if ($pagnet['verrouiller']==1): ?>
                                                                                        <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_depense".$ligne['numligne'] ; ?>>
                                                                                                <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                        </button>

                                                                                        <div class="modal fade" <?php echo  "id=msg_rtrnApres_depense".$ligne['numligne'] ; ?> role="dialog">
                                                                                            <div class="modal-dialog">
                                                                                                <!-- Modal content-->
                                                                                                <div class="modal-content">
                                                                                                    <div class="modal-header panel-primary">
                                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                        <h4 class="modal-title">Confirmation</h4>
                                                                                                    </div>
                                                                                                    <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                        <div class="modal-body">
                                                                                                            <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                            <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                            <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                            <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                            <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                            <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                            <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                            <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                            <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                            <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                        </div>
                                                                                                        <div class="modal-footer">
                                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                            <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                        </div>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <?php endif; ?>

                                                                                    </td>
                                                                                </tr>
                                                                            <?php  }
                                                                            else {?>
                                                                                <tr>
                                                                                    <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                    </td>
                                                                                    <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                    <td><?php
                                                                                    if ($pagnet['verrouiller']==1): ?>
                                                                                        <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                        <?php endif; ?>
                                                                                    </td>
                                                                                    <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                    <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                        <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                        <?php endif; ?>
                                                                                    </td>

                                                                                    <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                    <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php if ($pagnet['verrouiller']==1): ?>
                                                                                        <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_depense".$ligne['numligne'] ; ?>>
                                                                                                <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                        </button>

                                                                                        <div class="modal fade" <?php echo  "id=msg_rtrnApres_depense".$ligne['numligne'] ; ?> role="dialog">
                                                                                            <div class="modal-dialog">
                                                                                                <!-- Modal content-->
                                                                                                <div class="modal-content">
                                                                                                    <div class="modal-header panel-primary">
                                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                        <h4 class="modal-title">Confirmation</h4>
                                                                                                    </div>
                                                                                                    <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                        <div class="modal-body">
                                                                                                            <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                            <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                            <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                            <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                            <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                            <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                            <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                            <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                            <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                            <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                        </div>
                                                                                                        <div class="modal-footer">
                                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                            <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                        </div>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <?php endif; ?>

                                                                                    </td>
                                                                                </tr>
                                                                            <?php  }
                                                                            
                                                                        }  ?>

                                                                        </tbody>
                                                                        </table>

                                                                        <div>
                                                                            <div>
                                                                                <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div>
                                                                                <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                    <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div>
                                                                                <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                <?php endif; ?>
                                                                            </div>
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
                                                                            <div class="divFacture" style="display:none;">
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                <?php echo  '<br/>********************************************* '; ?>
                                                                                A BIENTOT !
                                                                            </div>
                                                                        </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php $n=$n-1;   } ?>
                                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                                        </div>
                                    <!-- Fin de l'Accordion pour Toutes les Depenses -->                                    
                                </div>
                                <div class="tab-pane" id="tab_default_4">
                                    <!-- Debut de l'Accordion pour Tout les Bons -->
                                        <div class="panel-group" id="accordion">

                                            <?php

                                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                                                $sqlP1="SELECT * FROM `".$nomtablePagnet."` where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateInventaire."'  ORDER BY idPagnet DESC";
                                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                                                $sqlC="SELECT COUNT(*) FROM `".$nomtablePagnet."` where idClient!=0 && verrouiller=1 && datepagej ='".$dateInventaire."'  ORDER BY idPagnet DESC";
                                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                                $nbre = mysql_fetch_array($resC) ;
                                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ ?>       

                                            <!-- Debut Boucle while concernant les Paniers Vendus -->
                                                <?php $n=$nbre[0]; while ($pagnet = mysql_fetch_array($resP1)) {   ?>

                                                    <?php 
                                                    if($pagnet['remise'] != 0){?>
                                                        <div class="panel panel-danger">
                                                            <div class="panel-heading">
                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#bon".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
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
                                                                    <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                                </a>
                                                                </h4>
                                                            </div>
                                                            <div class="panel-collapse collapse " <?php echo  "id=bon".$pagnet['idPagnet']."" ; ?> >
                                                                <div class="panel-body" >
                                                                        
                                                                        <?php if ($pagnet['verrouiller']==1):  ?>

                                                                        <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                        <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_bon".$pagnet['idPagnet'] ; ?>>
                                                                                <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                        </button>

                                                                        <div class="modal fade" <?php echo  "id=msg_rtrn_bon".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                            <div class="modal-dialog">
                                                                                <!-- Modal content-->
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header panel-primary">
                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                        <h4 class="modal-title">Confirmation</h4>
                                                                                    </div>
                                                                                    <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                        <div class="modal-body">
                                                                                            <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                            <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                        <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                        Facture
                                                                        </button><br>

                                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                            method="post" action="barcodeFacture.php" >
                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                        </form>

                                                                        <?php endif; ?>

                                                                        <div  class="divFacture" style="display:none;">
                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                                <?php echo  '*********************************************'; ?>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <table class="table ">
                                                                        <thead class="noImpr">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Référence</th>
                                                                            <th>Quantité</th>
                                                                            <th>Unité vente</th>
                                                                            <th>Prix Unité vente</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sql8="SELECT * 
                                                                            FROM `".$nomtableLigne."` l
                                                                            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                            WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                            while ($ligne = mysql_fetch_array($res8)) {
                                                                                if ($ligne['classe'] == 0) {?>
                                                                                    <tr class="info">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_bon".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_bon".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                else {?>
                                                                                    <tr>
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_bon".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_bon".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                
                                                                            }  ?>

                                                                            </tbody>
                                                                            </table>

                                                                            <div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <div class="divFacture" style="display:none;">
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                    <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                    <?php echo  '<br/>********************************************* '; ?>
                                                                                    A BIENTOT !
                                                                                </div>
                                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div><?php 
                                                    }	
                                                    else { ?>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading">
                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#bon".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
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
                                                                    <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                                </a>
                                                                </h4>
                                                            </div>
                                                            <div class="panel-collapse collapse " <?php echo  "id=bon".$pagnet['idPagnet']."" ; ?> >
                                                                <div class="panel-body" >
                                                                        
                                                                        <?php if ($pagnet['verrouiller']==1):  ?>

                                                                        <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                        <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_bon".$pagnet['idPagnet'] ; ?>>
                                                                                <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                        </button>

                                                                        <div class="modal fade" <?php echo  "id=msg_rtrn_bon".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                            <div class="modal-dialog">
                                                                                <!-- Modal content-->
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header panel-primary">
                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                        <h4 class="modal-title">Confirmation</h4>
                                                                                    </div>
                                                                                    <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                        <div class="modal-body">
                                                                                            <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                            <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                        <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                        Facture
                                                                        </button><br>

                                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                            method="post" action="barcodeFacture.php" >
                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                        </form>

                                                                        <?php endif; ?>

                                                                        <div  class="divFacture" style="display:none;">
                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                                <?php echo  '*********************************************'; ?>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <table class="table ">
                                                                        <thead class="noImpr">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Référence</th>
                                                                            <th>Quantité</th>
                                                                            <th>Unité vente</th>
                                                                            <th>Prix Unité vente</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sql8="SELECT * 
                                                                            FROM `".$nomtableLigne."` l
                                                                            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                            WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                            while ($ligne = mysql_fetch_array($res8)) {
                                                                                if ($ligne['classe'] == 0) {?>
                                                                                    <tr class="info">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_bon".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_bon".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                else {?>
                                                                                    <tr>
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_bon".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_bon".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                
                                                                            }  ?>

                                                                            </tbody>
                                                                            </table>

                                                                            <div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <div class="divFacture" style="display:none;">
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                    <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                    <?php echo  '<br/>********************************************* '; ?>
                                                                                    A BIENTOT !
                                                                                </div>
                                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div><?php
                                                    }	
                                                    ?>

                                                <?php $n=$n-1;   } ?>
                                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                                        </div>
                                    <!-- Fin de l'Accordion pour Tout les Bons -->  
                                </div>
                                <div class="tab-pane" id="tab_default_5">
                                    <!-- Debut de l'Accordion pour Tout les Versements -->
                                        <div class="panel-group" id="accordion">

                                            <?php
                                            
                                            /**Debut requete pour Lister les Versements d'Aujourd'hui  **/
                                                $sqlP1="SELECT * FROM `".$nomtableVersement."` where  dateVersement  ='".$dateInventaire."'  ORDER BY idVersement DESC";
                                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                            /**Fin requete pour Lister les Versements d'Aujourd'hui  **/

                                            /**Debut requete pour Compter les Versements d'Aujourd'hui  **/
                                                $sqlC="SELECT COUNT(*) FROM `".$nomtableVersement."` where  dateVersement ='".$dateInventaire."'  ORDER BY idVersement DESC";
                                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                                $nbre = mysql_fetch_array($resC) ;
                                            /**Fin requete pour Compter les Versements d'Aujourd'hui  **/

                                            ?>         

                                            <!-- Debut Boucle while concernant les Versements  -->
                                                <?php $n=$nbre[0]; while ($versement = mysql_fetch_array($resP1)) {   ?>

                                                    <div style="padding-top : 2px;" class="panel panel-warning">
                                                        <div class="panel-heading">
                                                            <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#versement".$versement['idVersement']."" ; ?>  class="panel-title expand">
                                                            <div class="right-arrow pull-right">+</div>
                                                            <a href="#"> Versement <?php echo $n; ?>

                                                            <span class="spanDate noImpr"> </span>
                                                            <span class="spanDate noImpr"> Date: <?php echo $versement['dateVersement']; ?> </span>
                                                            <span class="spanDate noImpr">Heure: <?php echo $versement['heureVersement']; ?></span>
                                                            <span class="spanDate noImpr">Montant: <?php echo $versement['montant']; ?></span>

                                                            </a>
                                                            </h4>
                                                        </div>
                                                        <div class="panel-collapse collapse " <?php echo  "id=versement".$versement['idVersement']."" ; ?> >
                                                            <div class="panel-body" >

                                                                <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_anl_versement".$versement['idVersement'] ; ?>	 >
                                                                    <span class="glyphicon glyphicon-remove"></span>Annuler
                                                                </button>
                                                                
                                                                <div class="modal fade" <?php echo  "id=msg_anl_versement".$versement['idVersement'] ; ?> role="dialog">
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
                                                                                    <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?>>
                                                                                    <input type="hidden" name="montant" id="montant"  <?php echo  "value=".$versement['montant']."" ; ?>>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                    <button type="submit" name="btnAnnulerVersement" class="btn btn-success">Confirmer</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <form class="form-inline pull-right noImpr" style="margin-right:20px;"
                                                                    method="post" action="barcodeFacture.php" target="_blank"  >
                                                                    <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?> >
                                                                    <button type="submit" class="btn btn-success pull-right" data-toggle="modal" name="barcodeFactureV">
                                                                    <span class="glyphicon glyphicon-lock"></span>Facture
                                                                    </button>
                                                                </form>
                                                                    
                                                                <div <?php echo  "id=divImpVer".$versement['idVersement']."" ; ?>  >
                                                                    <div >
                                                                        Date : <?php 
                                                                        $date1=$versement['dateVersement'];
                                                                        $date2 = new DateTime($date1);
                                                                        //R�cup�ration de l'ann�e
                                                                        $annee =$date2->format('Y');
                                                                        //R�cup�ration du mois
                                                                        $mois =$date2->format('m');
                                                                        //R�cup�ration du jours
                                                                        $jour =$date2->format('d');
                                                                        $date=$jour.'-'.$mois.'-'.$annee;
                                                                                        
                                                                        echo $date; ?> <br/>Heure : <?php 

                                                                        $heureV=$versement['heureVersement'];

                                                                        echo $heureV; ?><br/>
                                                                        <?php echo  '********************************************* <br/>'; ?>
                                                                        <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                        <?php echo  $_SESSION['adresseB'] ;?>
                                                                        <?php echo  '<br/>*********************************************'; ?>
                                                                    </div>
                                                                    <div> </div>
                                                                    <div><b>Montant : <?php echo  $versement['montant']; ?></b></div>
                                                                    <div class="divFacture" style="display:none;">
                                                                    N° <?php echo $versement['idVersement']; ?> <?php echo "-".$idClient  ?>
                                                                    <span class="spanDate"> <?php echo $versement['dateVersement']; ?> </span>
                                                                </div>
                                                                <div  align="center"> A BIENTOT !</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php $n=$n-1;   } ?>
                                            <!-- Fin Boucle while concernant les Versements   -->

                                        </div>
                                    <!-- Fin de l'Accordion pour Tout les Versements -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin Container Details Journal -->


<?php

echo'</section>'.
'<script>$(document).ready(function(){$("#AjoutLigne").click(function(){$("#AjoutLigneModal").modal();});});</script></body></html>';

}else if (@$_GET["datenext"]){

$dateInventaire=@$_GET["datenext"];

echo'<section><div class="container">';

//-----------------------------------------------------------------------------------

 $inttialisation=0;

                    $sqlP1="SELECT *
                        FROM `".$nomtablePagnet."` p
                        INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                        INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                        INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                        WHERE (d.classe = 0 OR d.classe = 1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                    $T_ventes = 0 ;
                    $S_ventes = 0;
                    while ($pagnet = mysql_fetch_array($resP1)) {  
                        $sqlS="SELECT SUM(apayerPagnet)
                        FROM `".$nomtablePagnet."` 
                        where idClient=0 &&  verrouiller=1 && datepagej ='".$dateInventaire."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";
                        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                        $S_ventes = mysql_fetch_array($resS);
                        $T_ventes = $S_ventes[0] + $T_ventes;
                    }

                    $sqlP3="SELECT *
                        FROM `".$nomtablePagnet."` p
                        INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                        INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                        INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                        WHERE d.classe = 2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                    $resP3 = mysql_query($sqlP3) or die ("persoonel requête 2".mysql_error());
                    $T_depenses = 0 ;
                    $S_depenses = 0;
                    while ($pagnet = mysql_fetch_array($resP3)) {  
                        $sqlS="SELECT SUM(prixtotal)
                        FROM `".$nomtableLigne."` l
                        INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                        INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                        WHERE d.classe = 2  && l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                        $S_depenses = mysql_fetch_array($resS);
                        $T_depenses = $S_depenses[0] + $T_depenses;
                    }

                    $sqlP4="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateInventaire."'  ORDER BY idPagnet DESC";
                        $resP4 = mysql_query($sqlP4) or die ("persoonel requête 2".mysql_error());
                    $T_bons = mysql_fetch_array($resP4) ;

                    $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where  dateVersement  ='".$dateInventaire."'  ORDER BY idVersement DESC";
                        $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());
                    $T_versements = mysql_fetch_array($resP5) ;



       ?>


            <?php $dateDepartTimestamp = strtotime($dateInventaire); ?>
            <div aria-label="navigation">
              <ul class="pager">
                <li class="previous"><a href="historiquecaisse.php?dateprevious=<?php echo date('d-m-Y', strtotime('-1 days',$dateDepartTimestamp));?>" title="Précédent">Précédent</a></li>
                <li><input type="date" id="jour_date"  onchange="date_jour('jour_date');"  <?php echo ' max="'.date('Y-m-d', strtotime('-1 days')).'" ';?> value="<?php echo date('Y-m-d', strtotime($dateInventaire));?>"  name="dateInventaire" required /></li> 
                 <?php if (date('d-m-Y', strtotime('+1 days',$dateDepartTimestamp)) > date('d-m-Y')){ ?>  
                 <li class="next disabled">
                 <a href="#" title="Suivant">Suivant</a></li>
                     <?php }else{ ?>
                 <li class="next">
                 <a href="historiquecaisse.php?datenext=<?php echo date('d-m-Y', strtotime('+1 days',$dateDepartTimestamp));?>" title="Suivant">Suivant</a></li>
                 <?php } ?>
              </ul>
            </div>
        <div class="container" >
             <div class="col-md-12">
                 <div class="panel panel-default">
                     <div class="panel-heading">
                            <h3 class="panel-title">DEITAIL DU TOTAL DE LA CAISSE DU <?php echo $dateInventaire; ?></h3>
                     </div>
                    <div class="panel-body">
                        <table class="table table-bordered table-responsive ">
                            <tr>
                                <td>Ventes  
                                    <?php
                                    $sql8="select * from `".$nomtableDesignation."` where classe=1";
                                    $res8=mysql_query($sql8);
                                    if(mysql_num_rows($res8)){
                                    echo ' et Services ';
                                    }?>
                                </td>
                                <td><?php
                                    if($T_ventes != null){
                                    echo $T_ventes; 
                                    }
                                    else{
                                    echo 0; 
                                    }
                                    ?>
                                    &nbsp;&nbsp;<a href="" data-toggle="modal" data-target="#myModal">Details</a>
<?php

$sql5="SELECT * FROM `".$nomtableCategorie."`";
$res5=mysql_query($sql5);
if(mysql_num_rows($res5)){
                                             ?>
                        <!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"><b> Ventes par Catégorie</b> </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <table class="table table-bordered table-responsive ">


                 <?php $totalCategorie=0;
                       while($tab5=mysql_fetch_array($res5)) {

                          if(totalVenteCategorie($tab5["idcategorie"], $totalCategorie,$dateInventaire)!=0) { ?>

                            <tr>
                                <td><b><?php echo $tab5["nomcategorie"] ; ?> : </b></td>
                                <td align="right"><b><?php echo totalVenteCategorie($tab5["idcategorie"], $totalCategorie,$dateInventaire); ?></b>&nbsp;FCFA</td>
                            </tr>

                      <?php }

                      }
                          ?>
                    <tr>
                        <td><b>REMISE : </b></td>
                        <td align="right"><b><?php
                        $T_remise =0;
                        $sqlS="SELECT SUM(remise)FROM `".$nomtablePagnet."` WHERE datepagej ='".$dateInventaire."'";
                		$resS=mysql_query($sqlS) or die ("Somme remise impossible =>".mysql_error());
                		$S_remise = mysql_fetch_array($resS);
                		$T_remise = $S_remise[0] ;
                        echo $T_remise; ?></b>&nbsp;FCFA</td>
                    </tr>

         </table>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
      </div>

    </div>
  </div>
</div>
 <?php }     ?>

                                </td>
                            </tr>
                            <tr>
                                <td>Versements clients :</td>
                                <td><?php
                                    if($T_versements[0] != null){
                                        echo $T_versements[0]; 
                                    }
                                    else{
                                        echo 0; 
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                                        $sql8="select * from `".$nomtableDesignation."` where classe=2";
                                        $res8=mysql_query($sql8);
                                        if(mysql_num_rows($res8)){	?>
                                                <tr>
                                                    <td>Depenses :</td>
                                                    <td><?php
                                                        if($T_depenses != null){
                                                            echo $T_depenses; 
                                                        }
                                                        else{
                                                            echo 0; 
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>

                                                    <?php } ?>
                                                <tr class="danger">
                                                    <td> <b> Total caisse : </b></td>
                                                    <td><b><?php
                                                        if($T_ventes != null || $T_versements[0] !=null || $T_depenses!= null){
                                                            echo $T_ventes + $T_versements[0] - $T_depenses; 
                                                        }
                                                        else{
                                                            echo 0; 
                                                        }
                                                        ?></b>
                                                    </td>
                                                </tr>
                                                <tr class="warning">
                                                    <td><b> Total Bon :  </b></td>
                                                    <td><b><?php
                                                        if($T_bons[0] != null){
                                                            echo $T_bons[0]; 
                                                        }
                                                        else{
                                                            echo 0; 
                                                        }
                                                        ?></b>
                                                    </td>
                                                </tr>
                        </table>
                        <?php echo '
                            <br>
                     </div>
               </div>
            </div></div>';



/**********************************************************************/
?>

        <!-- Debut Container Details Journal -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="tabbable-panel">
                        <div class="tabbable-line">
                            <ul class="nav nav-tabs ">
                                <li class="active">
                                    <a href="#tab_default_1" data-toggle="tab">
                                        Ventes 
                                        <?php
                                        $sql8="select * from `".$nomtableDesignation."` where classe=1";
                                        $res8=mysql_query($sql8);
                                        if(mysql_num_rows($res8)){
                                        echo ' et Services ';
                                        }?>
                                    </a>
                                </li>
                                <?php
                                        $sql8="select * from `".$nomtableDesignation."` where classe=2";
                                        $res8=mysql_query($sql8);
                                        if(mysql_num_rows($res8)){
                                        echo '   <li>
                                        <a href="#tab_default_3" data-toggle="tab">
                                        Depenses </a>
                                    </li> ';
                                        }?>
                                
                                <li>
                                    <a href="#tab_default_4" data-toggle="tab">
                                    Bon clients </a>
                                </li>
                                <li>
                                    <a href="#tab_default_5" data-toggle="tab">
                                    Versements clients </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_default_1">       
                                    <!-- Debut de l'Accordion pour Toutes les Ventes -->
                                        <div class="panel-group" id="accordion">

                                            <?php

                                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                                                $sqlP1="SELECT *
                                                FROM `".$nomtablePagnet."` p
                                                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                                                INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                WHERE (d.classe = 0 or d.classe = 1)  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                                                $sqlC="SELECT COUNT(*) 
                                                FROM `".$nomtablePagnet."` p
                                                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                                                INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                WHERE (d.classe = 0 or d.classe = 1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                                $nbre = mysql_fetch_array($resC) ; 
                                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ ?>       

                                            <!-- Debut Boucle while concernant les Paniers Vendus -->
                                                <?php $n=$nbre[0]; while ($pagnet = mysql_fetch_array($resP1)) {   ?>

                                                    <?php 
                                                    if($pagnet['remise'] != 0){?>
                                                    <div class="panel panel-danger">
                                                            <div class="panel-heading">
                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#vente".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
                                                                <div class="right-arrow pull-right">+</div>
                                                                <a href="#"> Panier <?php echo $n; ?>
                                                                    <span class="spanDate noImpr"> </span>
                                                                    <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                    <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>
                                                                    <?php  if ($pagnet['verrouiller']==1): ?>
                                                                    <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                            $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());
                                                                            $TotalT = mysql_fetch_array($resT);
                                                                            $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                            $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());
                                                                            $TotalP = mysql_fetch_array($resP);?>
                                                                    <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>
                                                                    <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>
                                                                <?php endif; ?>
                                                                <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                                </a>
                                                                </h4>
                                                            </div>
                                                            <div class="panel-collapse collapse " <?php echo  "id=vente".$pagnet['idPagnet']."" ; ?> >
                                                                <div class="panel-body" >
                                                                        
                                                                        <?php if ($pagnet['verrouiller']==1):  ?>

                                                                            <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_vente".$pagnet['idPagnet'] ; ?>>
                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                            </button>

                                                                            <div class="modal fade" <?php echo  "id=msg_rtrn_vente".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                                <div class="modal-dialog">
                                                                                    <!-- Modal content-->
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header panel-primary">
                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                        </div>
                                                                                        <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                            <div class="modal-body">
                                                                                                <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                            <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                            Facture
                                                                            </button><br>

                                                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                                method="post" action="barcodeFacture.php" >
                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                            </form>

                                                                        <?php endif; ?>

                                                                        <div  class="divFacture" style="display:none;">
                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                                <?php echo  '*********************************************'; ?>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <table class="table ">
                                                                        <thead class="noImpr">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Référence</th>
                                                                            <th>Quantité</th>
                                                                            <th>Unité vente</th>
                                                                            <th>Prix Unité vente</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sql8="SELECT * 
                                                                            FROM `".$nomtableLigne."` l
                                                                            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                            WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                            while ($ligne = mysql_fetch_array($res8)) {
                                                                                if ($ligne['classe'] == 0) {?>
                                                                                    <tr class="info">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                if ($ligne['classe'] == 1) {?>
                                                                                    <tr class="active">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                else {?>
                                                                                    <tr>
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                
                                                                            }  ?>

                                                                            </tbody>
                                                                            </table>

                                                                            <div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <div class="divFacture" style="display:none;">
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                    <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                    <?php echo  '<br/>********************************************* '; ?>
                                                                                    A BIENTOT !
                                                                                </div>
                                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div> <?php 
                                                    }	
                                                    else { ?>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading">
                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#vente".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
                                                                <div class="right-arrow pull-right">+</div>
                                                                <a href="#"> Panier <?php echo $n; ?>
                                                                    <span class="spanDate noImpr"> </span>
                                                                    <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                    <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>
                                                                    <?php  if ($pagnet['verrouiller']==1): ?>
                                                                    <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                            $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());
                                                                            $TotalT = mysql_fetch_array($resT);
                                                                            $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                            $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());
                                                                            $TotalP = mysql_fetch_array($resP);?>
                                                                    <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>
                                                                    <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>
                                                                <?php endif; ?>
                                                                <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                                </a>
                                                                </h4>
                                                            </div>
                                                            <div class="panel-collapse collapse " <?php echo  "id=vente".$pagnet['idPagnet']."" ; ?> >
                                                                <div class="panel-body" >
                                                                        
                                                                        <?php if ($pagnet['verrouiller']==1):  ?>

                                                                            <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_vente".$pagnet['idPagnet'] ; ?>>
                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                            </button>

                                                                            <div class="modal fade" <?php echo  "id=msg_rtrn_vente".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                                <div class="modal-dialog">
                                                                                    <!-- Modal content-->
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header panel-primary">
                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                        </div>
                                                                                        <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                            <div class="modal-body">
                                                                                                <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                            <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                            Facture
                                                                            </button><br>

                                                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                                method="post" action="barcodeFacture.php" >
                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                            </form>

                                                                        <?php endif; ?>

                                                                        <div  class="divFacture" style="display:none;">
                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                                <?php echo  '*********************************************'; ?>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <table class="table ">
                                                                        <thead class="noImpr">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Référence</th>
                                                                            <th>Quantité</th>
                                                                            <th>Unité vente</th>
                                                                            <th>Prix Unité vente</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sql8="SELECT * 
                                                                            FROM `".$nomtableLigne."` l
                                                                            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                            WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                            while ($ligne = mysql_fetch_array($res8)) {
                                                                                if ($ligne['classe'] == 0) {?>
                                                                                    <tr class="info">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                if ($ligne['classe'] == 1) {?>
                                                                                    <tr class="active">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                else {?>
                                                                                    <tr>
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                
                                                                            }  ?>

                                                                            </tbody>
                                                                            </table>

                                                                            <div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <div class="divFacture" style="display:none;">
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                    <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                    <?php echo  '<br/>********************************************* '; ?>
                                                                                    A BIENTOT !
                                                                                </div>
                                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div><?php
                                                    }	
                                                    ?>

                                                <?php $n=$n-1;   } ?>
                                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                                        </div>
                                    <!-- Fin de l'Accordion pour Toutes les Ventes -->
                                </div>
                                <div class="tab-pane" id="tab_default_3">
                                    <!-- Debut de l'Accordion pour Toutes les Depenses -->
                                        <div class="panel-group" id="accordion">

                                            <?php

                                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                                                $sqlP1="SELECT *
                                                FROM `".$nomtablePagnet."` p
                                                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                                                INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                WHERE d.classe = 2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                                                $sqlC="SELECT COUNT(*) 
                                                FROM `".$nomtablePagnet."` p
                                                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                                                INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                WHERE d.classe = 2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                                $nbre = mysql_fetch_array($resC) ; 
                                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ ?>       

                                            <!-- Debut Boucle while concernant les Paniers Vendus -->
                                                <?php $n=$nbre[0]; while ($pagnet = mysql_fetch_array($resP1)) {   ?>

                                                    <div class="panel panel-warning">
                                                        <div class="panel-heading">
                                                            <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#depenses".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
                                                            <div class="right-arrow pull-right">+</div>
                                                            <a href="#"> Panier <?php echo $n; ?>
                                                                <span class="spanDate noImpr"> </span>
                                                                <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>
                                                                <?php   $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                        $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());
                                                                        $TotalP = mysql_fetch_array($resP);
                                                                        $sqlS="SELECT SUM(prixtotal)
                                                                        FROM `".$nomtableLigne."` l
                                                                        INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                        INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                        WHERE d.classe = 2  && l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                                                                        $TotalS = mysql_fetch_array($resS); ?>
                                                                <span class="spanTotal noImpr" >Total à payer Panier: <span ><?php echo $TotalP[0]; ?> </span></span>        
                                                                <span class="spanTotal noImpr" >Total Depenses: <span ><?php echo $TotalS[0]; ?> </span></span>
                                                                <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                            </a>
                                                            </h4>
                                                        </div>
                                                        <div class="panel-collapse collapse " <?php echo  "id=depenses".$pagnet['idPagnet']."" ; ?> >
                                                            <div class="panel-body" >
                                                                    
                                                                    <?php if ($pagnet['verrouiller']==1):  ?>

                                                                    <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                    <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_depense".$pagnet['idPagnet'] ; ?>>
                                                                            <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                    </button>

                                                                    <div class="modal fade" <?php echo  "id=msg_rtrn_depense".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                        <div class="modal-dialog">
                                                                            <!-- Modal content-->
                                                                            <div class="modal-content">
                                                                                <div class="modal-header panel-primary">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    <h4 class="modal-title">Confirmation</h4>
                                                                                </div>
                                                                                <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                    <div class="modal-body">
                                                                                        <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                        <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                        <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                    <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                    Facture
                                                                    </button><br>

                                                                    <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                        method="post" action="barcodeFacture.php" >
                                                                        <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                    </form>

                                                                    <?php endif; ?>

                                                                    <div  class="divFacture" style="display:none;">
                                                                        <?php if ($pagnet['verrouiller']==1): ?>
                                                                            <?php echo  '********************************************* <br/>'; ?>
                                                                            <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                            <?php echo  '*********************************************'; ?>
                                                                        <?php endif; ?>
                                                                    </div>

                                                                    <table class="table ">
                                                                    <thead class="noImpr">
                                                                    <tr>
                                                                        <th></th>
                                                                        <th>Référence</th>
                                                                        <th>Quantité</th>
                                                                        <th>Unité vente</th>
                                                                        <th>Prix Unité vente</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                        $sql8="SELECT * 
                                                                        FROM `".$nomtableLigne."` l
                                                                        INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                        INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                        WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                        $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                        while ($ligne = mysql_fetch_array($res8)) {
                                                                            if ($ligne['classe'] == 2) {?>
                                                                                <tr class="info">
                                                                                    <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                    </td>
                                                                                    <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                    <td><?php
                                                                                    if ($pagnet['verrouiller']==1): ?>
                                                                                        <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                        <?php endif; ?>
                                                                                    </td>
                                                                                    <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                    <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                        <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                        <?php endif; ?>
                                                                                    </td>

                                                                                    <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                    <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php if ($pagnet['verrouiller']==1): ?>
                                                                                        <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_depense".$ligne['numligne'] ; ?>>
                                                                                                <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                        </button>

                                                                                        <div class="modal fade" <?php echo  "id=msg_rtrnApres_depense".$ligne['numligne'] ; ?> role="dialog">
                                                                                            <div class="modal-dialog">
                                                                                                <!-- Modal content-->
                                                                                                <div class="modal-content">
                                                                                                    <div class="modal-header panel-primary">
                                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                        <h4 class="modal-title">Confirmation</h4>
                                                                                                    </div>
                                                                                                    <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                        <div class="modal-body">
                                                                                                            <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                            <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                            <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                            <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                            <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                            <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                            <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                            <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                            <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                            <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                        </div>
                                                                                                        <div class="modal-footer">
                                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                            <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                        </div>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <?php endif; ?>

                                                                                    </td>
                                                                                </tr>
                                                                            <?php  }
                                                                            else {?>
                                                                                <tr>
                                                                                    <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                    </td>
                                                                                    <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                    <td><?php
                                                                                    if ($pagnet['verrouiller']==1): ?>
                                                                                        <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                        <?php endif; ?>
                                                                                    </td>
                                                                                    <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                    <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                        <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                        <?php endif; ?>
                                                                                    </td>

                                                                                    <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                    <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php if ($pagnet['verrouiller']==1): ?>
                                                                                        <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_depense".$ligne['numligne'] ; ?>>
                                                                                                <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                        </button>

                                                                                        <div class="modal fade" <?php echo  "id=msg_rtrnApres_depense".$ligne['numligne'] ; ?> role="dialog">
                                                                                            <div class="modal-dialog">
                                                                                                <!-- Modal content-->
                                                                                                <div class="modal-content">
                                                                                                    <div class="modal-header panel-primary">
                                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                        <h4 class="modal-title">Confirmation</h4>
                                                                                                    </div>
                                                                                                    <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                        <div class="modal-body">
                                                                                                            <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                            <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                            <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                            <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                            <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                            <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                            <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                            <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                            <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                            <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                        </div>
                                                                                                        <div class="modal-footer">
                                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                            <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                        </div>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <?php endif; ?>

                                                                                    </td>
                                                                                </tr>
                                                                            <?php  }
                                                                            
                                                                        }  ?>

                                                                        </tbody>
                                                                        </table>

                                                                        <div>
                                                                            <div>
                                                                                <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div>
                                                                                <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                    <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div>
                                                                                <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                <?php endif; ?>
                                                                            </div>
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
                                                                            <div class="divFacture" style="display:none;">
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                <?php echo  '<br/>********************************************* '; ?>
                                                                                A BIENTOT !
                                                                            </div>
                                                                        </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php $n=$n-1;   } ?>
                                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                                        </div>
                                    <!-- Fin de l'Accordion pour Toutes les Depenses -->                                    
                                </div>
                                <div class="tab-pane" id="tab_default_4">
                                    <!-- Debut de l'Accordion pour Tout les Bons -->
                                        <div class="panel-group" id="accordion">

                                            <?php

                                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                                                $sqlP1="SELECT * FROM `".$nomtablePagnet."` where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateInventaire."'  ORDER BY idPagnet DESC";
                                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                                                $sqlC="SELECT COUNT(*) FROM `".$nomtablePagnet."` where idClient!=0 && verrouiller=1 && datepagej ='".$dateInventaire."'  ORDER BY idPagnet DESC";
                                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                                $nbre = mysql_fetch_array($resC) ;
                                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ ?>       

                                            <!-- Debut Boucle while concernant les Paniers Vendus -->
                                                <?php $n=$nbre[0]; while ($pagnet = mysql_fetch_array($resP1)) {   ?>

                                                    <?php 
                                                    if($pagnet['remise'] != 0){?>
                                                        <div class="panel panel-danger">
                                                            <div class="panel-heading">
                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#bon".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
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
                                                                    <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                                </a>
                                                                </h4>
                                                            </div>
                                                            <div class="panel-collapse collapse " <?php echo  "id=bon".$pagnet['idPagnet']."" ; ?> >
                                                                <div class="panel-body" >
                                                                        
                                                                        <?php if ($pagnet['verrouiller']==1):  ?>

                                                                        <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                        <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_bon".$pagnet['idPagnet'] ; ?>>
                                                                                <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                        </button>

                                                                        <div class="modal fade" <?php echo  "id=msg_rtrn_bon".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                            <div class="modal-dialog">
                                                                                <!-- Modal content-->
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header panel-primary">
                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                        <h4 class="modal-title">Confirmation</h4>
                                                                                    </div>
                                                                                    <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                        <div class="modal-body">
                                                                                            <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                            <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                        <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                        Facture
                                                                        </button><br>

                                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                            method="post" action="barcodeFacture.php" >
                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                        </form>

                                                                        <?php endif; ?>

                                                                        <div  class="divFacture" style="display:none;">
                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                                <?php echo  '*********************************************'; ?>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <table class="table ">
                                                                        <thead class="noImpr">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Référence</th>
                                                                            <th>Quantité</th>
                                                                            <th>Unité vente</th>
                                                                            <th>Prix Unité vente</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sql8="SELECT * 
                                                                            FROM `".$nomtableLigne."` l
                                                                            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                            WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                            while ($ligne = mysql_fetch_array($res8)) {
                                                                                if ($ligne['classe'] == 0) {?>
                                                                                    <tr class="info">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_bon".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_bon".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                else {?>
                                                                                    <tr>
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_bon".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_bon".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                
                                                                            }  ?>

                                                                            </tbody>
                                                                            </table>

                                                                            <div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <div class="divFacture" style="display:none;">
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                    <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                    <?php echo  '<br/>********************************************* '; ?>
                                                                                    A BIENTOT !
                                                                                </div>
                                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div><?php 
                                                    }	
                                                    else { ?>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading">
                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#bon".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
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
                                                                    <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                                </a>
                                                                </h4>
                                                            </div>
                                                            <div class="panel-collapse collapse " <?php echo  "id=bon".$pagnet['idPagnet']."" ; ?> >
                                                                <div class="panel-body" >
                                                                        
                                                                        <?php if ($pagnet['verrouiller']==1):  ?>

                                                                        <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                        <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_bon".$pagnet['idPagnet'] ; ?>>
                                                                                <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                        </button>

                                                                        <div class="modal fade" <?php echo  "id=msg_rtrn_bon".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                            <div class="modal-dialog">
                                                                                <!-- Modal content-->
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header panel-primary">
                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                        <h4 class="modal-title">Confirmation</h4>
                                                                                    </div>
                                                                                    <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                        <div class="modal-body">
                                                                                            <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                            <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                        <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                        Facture
                                                                        </button><br>

                                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                            method="post" action="barcodeFacture.php" >
                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                        </form>

                                                                        <?php endif; ?>

                                                                        <div  class="divFacture" style="display:none;">
                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                                <?php echo  '*********************************************'; ?>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <table class="table ">
                                                                        <thead class="noImpr">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Référence</th>
                                                                            <th>Quantité</th>
                                                                            <th>Unité vente</th>
                                                                            <th>Prix Unité vente</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sql8="SELECT * 
                                                                            FROM `".$nomtableLigne."` l
                                                                            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                            WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                            while ($ligne = mysql_fetch_array($res8)) {
                                                                                if ($ligne['classe'] == 0) {?>
                                                                                    <tr class="info">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_bon".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_bon".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                else {?>
                                                                                    <tr>
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_bon".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_bon".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                
                                                                            }  ?>

                                                                            </tbody>
                                                                            </table>

                                                                            <div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <div class="divFacture" style="display:none;">
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                    <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                    <?php echo  '<br/>********************************************* '; ?>
                                                                                    A BIENTOT !
                                                                                </div>
                                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div><?php
                                                    }	
                                                    ?>

                                                <?php $n=$n-1;   } ?>
                                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                                        </div>
                                    <!-- Fin de l'Accordion pour Tout les Bons -->  
                                </div>
                                <div class="tab-pane" id="tab_default_5">
                                    <!-- Debut de l'Accordion pour Tout les Versements -->
                                        <div class="panel-group" id="accordion">

                                            <?php
                                            
                                            /**Debut requete pour Lister les Versements d'Aujourd'hui  **/
                                                $sqlP1="SELECT * FROM `".$nomtableVersement."` where  dateVersement  ='".$dateInventaire."'  ORDER BY idVersement DESC";
                                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                            /**Fin requete pour Lister les Versements d'Aujourd'hui  **/

                                            /**Debut requete pour Compter les Versements d'Aujourd'hui  **/
                                                $sqlC="SELECT COUNT(*) FROM `".$nomtableVersement."` where  dateVersement ='".$dateInventaire."'  ORDER BY idVersement DESC";
                                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                                $nbre = mysql_fetch_array($resC) ;
                                            /**Fin requete pour Compter les Versements d'Aujourd'hui  **/

                                            ?>         

                                            <!-- Debut Boucle while concernant les Versements  -->
                                                <?php $n=$nbre[0]; while ($versement = mysql_fetch_array($resP1)) {   ?>

                                                    <div style="padding-top : 2px;" class="panel panel-warning">
                                                        <div class="panel-heading">
                                                            <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#versement".$versement['idVersement']."" ; ?>  class="panel-title expand">
                                                            <div class="right-arrow pull-right">+</div>
                                                            <a href="#"> Versement <?php echo $n; ?>

                                                            <span class="spanDate noImpr"> </span>
                                                            <span class="spanDate noImpr"> Date: <?php echo $versement['dateVersement']; ?> </span>
                                                            <span class="spanDate noImpr">Heure: <?php echo $versement['heureVersement']; ?></span>
                                                            <span class="spanDate noImpr">Montant: <?php echo $versement['montant']; ?></span>

                                                            </a>
                                                            </h4>
                                                        </div>
                                                        <div class="panel-collapse collapse " <?php echo  "id=versement".$versement['idVersement']."" ; ?> >
                                                            <div class="panel-body" >

                                                                <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_anl_versement".$versement['idVersement'] ; ?>	 >
                                                                    <span class="glyphicon glyphicon-remove"></span>Annuler
                                                                </button>
                                                                
                                                                <div class="modal fade" <?php echo  "id=msg_anl_versement".$versement['idVersement'] ; ?> role="dialog">
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
                                                                                    <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?>>
                                                                                    <input type="hidden" name="montant" id="montant"  <?php echo  "value=".$versement['montant']."" ; ?>>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                    <button type="submit" name="btnAnnulerVersement" class="btn btn-success">Confirmer</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <form class="form-inline pull-right noImpr" style="margin-right:20px;"
                                                                    method="post" action="barcodeFacture.php" target="_blank"  >
                                                                    <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?> >
                                                                    <button type="submit" class="btn btn-success pull-right" data-toggle="modal" name="barcodeFactureV">
                                                                    <span class="glyphicon glyphicon-lock"></span>Facture
                                                                    </button>
                                                                </form>
                                                                    
                                                                <div <?php echo  "id=divImpVer".$versement['idVersement']."" ; ?>  >
                                                                    <div >
                                                                        Date : <?php 
                                                                        $date1=$versement['dateVersement'];
                                                                        $date2 = new DateTime($date1);
                                                                        //R�cup�ration de l'ann�e
                                                                        $annee =$date2->format('Y');
                                                                        //R�cup�ration du mois
                                                                        $mois =$date2->format('m');
                                                                        //R�cup�ration du jours
                                                                        $jour =$date2->format('d');
                                                                        $date=$jour.'-'.$mois.'-'.$annee;
                                                                                        
                                                                        echo $date; ?> <br/>Heure : <?php 

                                                                        $heureV=$versement['heureVersement'];

                                                                        echo $heureV; ?><br/>
                                                                        <?php echo  '********************************************* <br/>'; ?>
                                                                        <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                        <?php echo  $_SESSION['adresseB'] ;?>
                                                                        <?php echo  '<br/>*********************************************'; ?>
                                                                    </div>
                                                                    <div> </div>
                                                                    <div><b>Montant : <?php echo  $versement['montant']; ?></b></div>
                                                                    <div class="divFacture" style="display:none;">
                                                                    N° <?php echo $versement['idVersement']; ?> <?php echo "-".$idClient  ?>
                                                                    <span class="spanDate"> <?php echo $versement['dateVersement']; ?> </span>
                                                                </div>
                                                                <div  align="center"> A BIENTOT !</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php $n=$n-1;   } ?>
                                            <!-- Fin Boucle while concernant les Versements   -->

                                        </div>
                                    <!-- Fin de l'Accordion pour Tout les Versements -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin Container Details Journal -->

<?php

echo'</section>'.
'<script>$(document).ready(function(){$("#AjoutLigne").click(function(){$("#AjoutLigneModal").modal();});});</script></body></html>';



}else if (@$_GET["dateprevious"]){

$dateInventaire=@$_GET["dateprevious"];

echo'<section><div class="container">';

//-----------------------------------------------------------------------------------

$inttialisation=0;

                $sqlP1="SELECT *
                    FROM `".$nomtablePagnet."` p
                    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                    INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                    WHERE (d.classe = 0 OR d.classe = 1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                $T_ventes = 0 ;
                $S_ventes = 0;
                while ($pagnet = mysql_fetch_array($resP1)) {  
                    $sqlS="SELECT SUM(apayerPagnet)
                    FROM `".$nomtablePagnet."` 
                    where idClient=0 &&  verrouiller=1 && datepagej ='".$dateInventaire."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";
                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                    $S_ventes = mysql_fetch_array($resS);
                    $T_ventes = $S_ventes[0] + $T_ventes;
                }

                $sqlP3="SELECT *
                    FROM `".$nomtablePagnet."` p
                    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                    INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                    WHERE d.classe = 2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                $resP3 = mysql_query($sqlP3) or die ("persoonel requête 2".mysql_error());
                $T_depenses = 0 ;
                $S_depenses = 0;
                while ($pagnet = mysql_fetch_array($resP3)) {  
                    $sqlS="SELECT SUM(prixtotal)
                    FROM `".$nomtableLigne."` l
                    INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                    WHERE d.classe = 2  && l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                    $S_depenses = mysql_fetch_array($resS);
                    $T_depenses = $S_depenses[0] + $T_depenses;
                }

                $sqlP4="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateInventaire."'  ORDER BY idPagnet DESC";
                    $resP4 = mysql_query($sqlP4) or die ("persoonel requête 2".mysql_error());
                $T_bons = mysql_fetch_array($resP4) ;

                $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where  dateVersement  ='".$dateInventaire."'  ORDER BY idVersement DESC";
                    $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());
                $T_versements = mysql_fetch_array($resP5) ;

         ?>
             <?php $dateDepartTimestamp = strtotime($dateInventaire); ?>
            <div aria-label="navigation">
              <ul class="pager">
              <?php

$date = new DateTime($_SESSION['dateCB']);
//$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');

$heureString=$date->format('H:i:s');

$dateCreation1=$annee.'-'.$mois.'-'.$jour;

$dateCreation2=$jour.'-'.$mois.'-'.$annee;
/*
echo date($dateCreation2);
echo '---';
echo date('d-m-Y', strtotime('+0 days',$dateDepartTimestamp));  */

              if (date('d-m-Y', strtotime('+0 days',$dateDepartTimestamp)) == date($dateCreation2)){ ?>
                <li class="previous disabled"><a href="#" title="Précédent">Précédent</a></li>
              <?php }else{ ?>
               <li class="previous"><a href="historiquecaisse.php?dateprevious=<?php echo date('d-m-Y', strtotime('-1 days',$dateDepartTimestamp));?>" title="Précédent">Précédent</a></li>
               <?php } ?>
               <li><input type="date" id="jour_date"  onchange="date_jour('jour_date');" <?php echo ' max="'.date('Y-m-d', strtotime('-1 days')).'" ';?> value="<?php echo date('Y-m-d', strtotime($dateInventaire));?>" name="dateInventaire" required  /></li>
                <li class="next"><a href="historiquecaisse.php?datenext=<?php echo date('d-m-Y', strtotime('+1 days',$dateDepartTimestamp));?>" title="Suivant">Suivant</a></li>
              </ul>
            </div>

            <div class="container" >
             <div class="col-md-12">
                 <div class="panel panel-default">
                     <div class="panel-heading">
                            <h3 class="panel-title">DEITAIL DU TOTAL DE LA CAISSE DU <?php echo $dateInventaire; ?></h3>
                     </div>
                    <div class="panel-body">
                        <table class="table table-bordered table-responsive ">
                            <tr>
                                <td>Ventes  
                                    <?php
                                    $sql8="select * from `".$nomtableDesignation."` where classe=1";
                                    $res8=mysql_query($sql8);
                                    if(mysql_num_rows($res8)){
                                    echo ' et Services ';
                                    }?>
                                </td>
                                <td><?php
                                    if($T_ventes != null){
                                    echo $T_ventes; 
                                    }
                                    else{
                                    echo 0; 
                                    }
                                   ?>
                                                &nbsp;&nbsp;<a href="" data-toggle="modal" data-target="#myModal">Details</a>
<?php

$sql5="SELECT * FROM `".$nomtableCategorie."`";
$res5=mysql_query($sql5);
if(mysql_num_rows($res5)){
                                             ?>
                        <!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"><b> Ventes par Catégorie</b> </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <table class="table table-bordered table-responsive ">


                 <?php $totalCategorie=0;
                       while($tab5=mysql_fetch_array($res5)) {

                          if(totalVenteCategorie($tab5["idcategorie"], $totalCategorie,$dateInventaire)!=0) { ?>

                            <tr>
                                <td><b><?php echo $tab5["nomcategorie"] ; ?> : </b></td>
                                <td align="right"><b><?php echo totalVenteCategorie($tab5["idcategorie"], $totalCategorie,$dateInventaire); ?></b>&nbsp;FCFA</td>
                            </tr>

                      <?php }

                      }
                          ?>
                    <tr>
                        <td><b>REMISE : </b></td>
                        <td align="right"><b><?php
                        $T_remise =0;
                        $sqlS="SELECT SUM(remise)FROM `".$nomtablePagnet."` WHERE datepagej ='".$dateInventaire."'";
                		$resS=mysql_query($sqlS) or die ("Somme remise impossible =>".mysql_error());
                		$S_remise = mysql_fetch_array($resS);
                		$T_remise = $S_remise[0] ;
                        echo $T_remise; ?></b>&nbsp;FCFA</td>
                    </tr>

         </table>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
      </div>

    </div>
  </div>
</div>
 <?php }     ?>

                                </td>
                            </tr>
                            <tr>
                                <td>Versements clients :</td>
                                <td><?php
                                    if($T_versements[0] != null){
                                        echo $T_versements[0]; 
                                    }
                                    else{
                                        echo 0; 
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                                    $sql8="select * from `".$nomtableDesignation."` where classe=2";
                                    $res8=mysql_query($sql8);
                                    if(mysql_num_rows($res8)){	?>
                                            <tr>
                                                <td>Depenses :</td>
                                                <td><?php
                                                    if($T_depenses != null){
                                                        echo $T_depenses; 
                                                    }
                                                    else{
                                                        echo 0; 
                                                    }
                                                    ?>
                                                </td>
                                            </tr>

                                                <?php } ?>
                                            <tr class="danger">
                                                <td> <b> Total caisse : </b></td>
                                                <td><b><?php
                                                    if($T_ventes != null || $T_versements[0] !=null || $T_depenses!= null){
                                                        echo $T_ventes + $T_versements[0] - $T_depenses; 
                                                    }
                                                    else{
                                                        echo 0; 
                                                    }
                                                    ?></b>
                                                </td>
                                            </tr>
                                            <tr class="warning">
                                                <td><b> Total Bon :  </b></td>
                                                <td><b><?php
                                                    if($T_bons[0] != null){
                                                        echo $T_bons[0]; 
                                                    }
                                                    else{
                                                        echo 0; 
                                                    }
                                                    ?></b>
                                                </td>
                                            </tr>
                        </table>

                    <?php echo '
                    <br>
                     </div>
               </div>
            </div></div>';



/**********************************************************************/
?>

        <!-- Debut Container Details Journal -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="tabbable-panel">
                        <div class="tabbable-line">
                            <ul class="nav nav-tabs ">
                                <li class="active">
                                    <a href="#tab_default_1" data-toggle="tab">
                                        Ventes 
                                        <?php
                                        $sql8="select * from `".$nomtableDesignation."` where classe=1";
                                        $res8=mysql_query($sql8);
                                        if(mysql_num_rows($res8)){
                                        echo ' et Services ';
                                        }?>
                                    </a>
                                </li>
                                <?php
                                        $sql8="select * from `".$nomtableDesignation."` where classe=2";
                                        $res8=mysql_query($sql8);
                                        if(mysql_num_rows($res8)){
                                        echo '   <li>
                                        <a href="#tab_default_3" data-toggle="tab">
                                        Depenses </a>
                                    </li> ';
                                        }?>
                                
                                <li>
                                    <a href="#tab_default_4" data-toggle="tab">
                                    Bon clients </a>
                                </li>
                                <li>
                                    <a href="#tab_default_5" data-toggle="tab">
                                    Versements clients </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_default_1">       
                                    <!-- Debut de l'Accordion pour Toutes les Ventes -->
                                        <div class="panel-group" id="accordion">

                                            <?php

                                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                                                $sqlP1="SELECT *
                                                FROM `".$nomtablePagnet."` p
                                                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                                                INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                WHERE (d.classe = 0 or d.classe = 1)  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                                                $sqlC="SELECT COUNT(*) 
                                                FROM `".$nomtablePagnet."` p
                                                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                                                INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                WHERE (d.classe = 0 or d.classe = 1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                                $nbre = mysql_fetch_array($resC) ; 
                                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ ?>       

                                            <!-- Debut Boucle while concernant les Paniers Vendus -->
                                                <?php $n=$nbre[0]; while ($pagnet = mysql_fetch_array($resP1)) {   ?>

                                                    <?php 
                                                    if($pagnet['remise'] != 0){?>
                                                    <div class="panel panel-danger">
                                                            <div class="panel-heading">
                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#vente".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
                                                                <div class="right-arrow pull-right">+</div>
                                                                <a href="#"> Panier <?php echo $n; ?>
                                                                    <span class="spanDate noImpr"> </span>
                                                                    <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                    <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>
                                                                    <?php  if ($pagnet['verrouiller']==1): ?>
                                                                    <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                            $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());
                                                                            $TotalT = mysql_fetch_array($resT);
                                                                            $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                            $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());
                                                                            $TotalP = mysql_fetch_array($resP);?>
                                                                    <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>
                                                                    <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>
                                                                <?php endif; ?>
                                                                <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                                </a>
                                                                </h4>
                                                            </div>
                                                            <div class="panel-collapse collapse " <?php echo  "id=vente".$pagnet['idPagnet']."" ; ?> >
                                                                <div class="panel-body" >
                                                                        
                                                                        <?php if ($pagnet['verrouiller']==1):  ?>

                                                                            <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_vente".$pagnet['idPagnet'] ; ?>>
                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                            </button>

                                                                            <div class="modal fade" <?php echo  "id=msg_rtrn_vente".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                                <div class="modal-dialog">
                                                                                    <!-- Modal content-->
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header panel-primary">
                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                        </div>
                                                                                        <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                            <div class="modal-body">
                                                                                                <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                            <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                            Facture
                                                                            </button><br>

                                                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                                method="post" action="barcodeFacture.php" >
                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                            </form>

                                                                        <?php endif; ?>

                                                                        <div  class="divFacture" style="display:none;">
                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                                <?php echo  '*********************************************'; ?>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <table class="table ">
                                                                        <thead class="noImpr">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Référence</th>
                                                                            <th>Quantité</th>
                                                                            <th>Unité vente</th>
                                                                            <th>Prix Unité vente</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sql8="SELECT * 
                                                                            FROM `".$nomtableLigne."` l
                                                                            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                            WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                            while ($ligne = mysql_fetch_array($res8)) {
                                                                                if ($ligne['classe'] == 0) {?>
                                                                                    <tr class="info">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                if ($ligne['classe'] == 1) {?>
                                                                                    <tr class="active">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                else {?>
                                                                                    <tr>
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                
                                                                            }  ?>

                                                                            </tbody>
                                                                            </table>

                                                                            <div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <div class="divFacture" style="display:none;">
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                    <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                    <?php echo  '<br/>********************************************* '; ?>
                                                                                    A BIENTOT !
                                                                                </div>
                                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div> <?php 
                                                    }	
                                                    else { ?>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading">
                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#vente".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
                                                                <div class="right-arrow pull-right">+</div>
                                                                <a href="#"> Panier <?php echo $n; ?>
                                                                    <span class="spanDate noImpr"> </span>
                                                                    <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                    <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>
                                                                    <?php  if ($pagnet['verrouiller']==1): ?>
                                                                    <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                            $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());
                                                                            $TotalT = mysql_fetch_array($resT);
                                                                            $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                            $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());
                                                                            $TotalP = mysql_fetch_array($resP);?>
                                                                    <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>
                                                                    <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>
                                                                <?php endif; ?>
                                                                <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                                </a>
                                                                </h4>
                                                            </div>
                                                            <div class="panel-collapse collapse " <?php echo  "id=vente".$pagnet['idPagnet']."" ; ?> >
                                                                <div class="panel-body" >
                                                                        
                                                                        <?php if ($pagnet['verrouiller']==1):  ?>

                                                                            <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_vente".$pagnet['idPagnet'] ; ?>>
                                                                                    <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                            </button>

                                                                            <div class="modal fade" <?php echo  "id=msg_rtrn_vente".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                                <div class="modal-dialog">
                                                                                    <!-- Modal content-->
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header panel-primary">
                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                        </div>
                                                                                        <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                            <div class="modal-body">
                                                                                                <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                            <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                            Facture
                                                                            </button><br>

                                                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                                method="post" action="barcodeFacture.php" >
                                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                            </form>

                                                                        <?php endif; ?>

                                                                        <div  class="divFacture" style="display:none;">
                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                                <?php echo  '*********************************************'; ?>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <table class="table ">
                                                                        <thead class="noImpr">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Référence</th>
                                                                            <th>Quantité</th>
                                                                            <th>Unité vente</th>
                                                                            <th>Prix Unité vente</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sql8="SELECT * 
                                                                            FROM `".$nomtableLigne."` l
                                                                            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                            WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                            while ($ligne = mysql_fetch_array($res8)) {
                                                                                if ($ligne['classe'] == 0) {?>
                                                                                    <tr class="info">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                if ($ligne['classe'] == 1) {?>
                                                                                    <tr class="active">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                else {?>
                                                                                    <tr>
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_vente".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_vente".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                
                                                                            }  ?>

                                                                            </tbody>
                                                                            </table>

                                                                            <div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <div class="divFacture" style="display:none;">
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                    <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                    <?php echo  '<br/>********************************************* '; ?>
                                                                                    A BIENTOT !
                                                                                </div>
                                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div><?php
                                                    }	
                                                    ?>

                                                <?php $n=$n-1;   } ?>
                                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                                        </div>
                                    <!-- Fin de l'Accordion pour Toutes les Ventes -->
                                </div>
                                <div class="tab-pane" id="tab_default_3">
                                    <!-- Debut de l'Accordion pour Toutes les Depenses -->
                                        <div class="panel-group" id="accordion">

                                            <?php

                                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                                                $sqlP1="SELECT *
                                                FROM `".$nomtablePagnet."` p
                                                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                                                INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                WHERE d.classe = 2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                                                $sqlC="SELECT COUNT(*) 
                                                FROM `".$nomtablePagnet."` p
                                                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
                                                INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                WHERE d.classe = 2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateInventaire."'  ORDER BY p.idPagnet DESC";
                                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                                $nbre = mysql_fetch_array($resC) ; 
                                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ ?>       

                                            <!-- Debut Boucle while concernant les Paniers Vendus -->
                                                <?php $n=$nbre[0]; while ($pagnet = mysql_fetch_array($resP1)) {   ?>

                                                    <div class="panel panel-warning">
                                                        <div class="panel-heading">
                                                            <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#depenses".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
                                                            <div class="right-arrow pull-right">+</div>
                                                            <a href="#"> Panier <?php echo $n; ?>
                                                                <span class="spanDate noImpr"> </span>
                                                                <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>
                                                                <?php   $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                        $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());
                                                                        $TotalP = mysql_fetch_array($resP);
                                                                        $sqlS="SELECT SUM(prixtotal)
                                                                        FROM `".$nomtableLigne."` l
                                                                        INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                        INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                        WHERE d.classe = 2  && l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                                                                        $TotalS = mysql_fetch_array($resS); ?>
                                                                <span class="spanTotal noImpr" >Total à payer Panier: <span ><?php echo $TotalP[0]; ?> </span></span>        
                                                                <span class="spanTotal noImpr" >Total Depenses: <span ><?php echo $TotalS[0]; ?> </span></span>
                                                                <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                            </a>
                                                            </h4>
                                                        </div>
                                                        <div class="panel-collapse collapse " <?php echo  "id=depenses".$pagnet['idPagnet']."" ; ?> >
                                                            <div class="panel-body" >
                                                                    
                                                                    <?php if ($pagnet['verrouiller']==1):  ?>

                                                                    <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                    <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_depense".$pagnet['idPagnet'] ; ?>>
                                                                            <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                    </button>

                                                                    <div class="modal fade" <?php echo  "id=msg_rtrn_depense".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                        <div class="modal-dialog">
                                                                            <!-- Modal content-->
                                                                            <div class="modal-content">
                                                                                <div class="modal-header panel-primary">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    <h4 class="modal-title">Confirmation</h4>
                                                                                </div>
                                                                                <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                    <div class="modal-body">
                                                                                        <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                        <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                        <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                    <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                    Facture
                                                                    </button><br>

                                                                    <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                        method="post" action="barcodeFacture.php" >
                                                                        <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                    </form>

                                                                    <?php endif; ?>

                                                                    <div  class="divFacture" style="display:none;">
                                                                        <?php if ($pagnet['verrouiller']==1): ?>
                                                                            <?php echo  '********************************************* <br/>'; ?>
                                                                            <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                            <?php echo  '*********************************************'; ?>
                                                                        <?php endif; ?>
                                                                    </div>

                                                                    <table class="table ">
                                                                    <thead class="noImpr">
                                                                    <tr>
                                                                        <th></th>
                                                                        <th>Référence</th>
                                                                        <th>Quantité</th>
                                                                        <th>Unité vente</th>
                                                                        <th>Prix Unité vente</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                        $sql8="SELECT * 
                                                                        FROM `".$nomtableLigne."` l
                                                                        INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                        INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                        WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                        $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                        while ($ligne = mysql_fetch_array($res8)) {
                                                                            if ($ligne['classe'] == 2) {?>
                                                                                <tr class="info">
                                                                                    <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                    </td>
                                                                                    <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                    <td><?php
                                                                                    if ($pagnet['verrouiller']==1): ?>
                                                                                        <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                        <?php endif; ?>
                                                                                    </td>
                                                                                    <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                    <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                        <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                        <?php endif; ?>
                                                                                    </td>

                                                                                    <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                    <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php if ($pagnet['verrouiller']==1): ?>
                                                                                        <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_depense".$ligne['numligne'] ; ?>>
                                                                                                <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                        </button>

                                                                                        <div class="modal fade" <?php echo  "id=msg_rtrnApres_depense".$ligne['numligne'] ; ?> role="dialog">
                                                                                            <div class="modal-dialog">
                                                                                                <!-- Modal content-->
                                                                                                <div class="modal-content">
                                                                                                    <div class="modal-header panel-primary">
                                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                        <h4 class="modal-title">Confirmation</h4>
                                                                                                    </div>
                                                                                                    <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                        <div class="modal-body">
                                                                                                            <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                            <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                            <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                            <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                            <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                            <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                            <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                            <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                            <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                            <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                        </div>
                                                                                                        <div class="modal-footer">
                                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                            <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                        </div>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <?php endif; ?>

                                                                                    </td>
                                                                                </tr>
                                                                            <?php  }
                                                                            else {?>
                                                                                <tr>
                                                                                    <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                    </td>
                                                                                    <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                    <td><?php
                                                                                    if ($pagnet['verrouiller']==1): ?>
                                                                                        <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                        <?php endif; ?>
                                                                                    </td>
                                                                                    <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                    <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                        <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                        <?php endif; ?>
                                                                                    </td>

                                                                                    <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                    <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php if ($pagnet['verrouiller']==1): ?>
                                                                                        <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_depense".$ligne['numligne'] ; ?>>
                                                                                                <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                        </button>

                                                                                        <div class="modal fade" <?php echo  "id=msg_rtrnApres_depense".$ligne['numligne'] ; ?> role="dialog">
                                                                                            <div class="modal-dialog">
                                                                                                <!-- Modal content-->
                                                                                                <div class="modal-content">
                                                                                                    <div class="modal-header panel-primary">
                                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                        <h4 class="modal-title">Confirmation</h4>
                                                                                                    </div>
                                                                                                    <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                        <div class="modal-body">
                                                                                                            <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                            <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                            <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                            <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                            <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                            <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                            <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                            <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                            <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                            <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                        </div>
                                                                                                        <div class="modal-footer">
                                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                            <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                        </div>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <?php endif; ?>

                                                                                    </td>
                                                                                </tr>
                                                                            <?php  }
                                                                            
                                                                        }  ?>

                                                                        </tbody>
                                                                        </table>

                                                                        <div>
                                                                            <div>
                                                                                <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div>
                                                                                <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                    <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div>
                                                                                <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                <?php endif; ?>
                                                                            </div>
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
                                                                            <div class="divFacture" style="display:none;">
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                <?php echo  '<br/>********************************************* '; ?>
                                                                                A BIENTOT !
                                                                            </div>
                                                                        </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php $n=$n-1;   } ?>
                                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                                        </div>
                                    <!-- Fin de l'Accordion pour Toutes les Depenses -->                                    
                                </div>
                                <div class="tab-pane" id="tab_default_4">
                                    <!-- Debut de l'Accordion pour Tout les Bons -->
                                        <div class="panel-group" id="accordion">

                                            <?php

                                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                                                $sqlP1="SELECT * FROM `".$nomtablePagnet."` where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateInventaire."'  ORDER BY idPagnet DESC";
                                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                                                $sqlC="SELECT COUNT(*) FROM `".$nomtablePagnet."` where idClient!=0 && verrouiller=1 && datepagej ='".$dateInventaire."'  ORDER BY idPagnet DESC";
                                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                                $nbre = mysql_fetch_array($resC) ;
                                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ ?>       

                                            <!-- Debut Boucle while concernant les Paniers Vendus -->
                                                <?php $n=$nbre[0]; while ($pagnet = mysql_fetch_array($resP1)) {   ?>

                                                    <?php 
                                                    if($pagnet['remise'] != 0){?>
                                                        <div class="panel panel-danger">
                                                            <div class="panel-heading">
                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#bon".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
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
                                                                    <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                                </a>
                                                                </h4>
                                                            </div>
                                                            <div class="panel-collapse collapse " <?php echo  "id=bon".$pagnet['idPagnet']."" ; ?> >
                                                                <div class="panel-body" >
                                                                        
                                                                        <?php if ($pagnet['verrouiller']==1):  ?>

                                                                        <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                        <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_bon".$pagnet['idPagnet'] ; ?>>
                                                                                <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                        </button>

                                                                        <div class="modal fade" <?php echo  "id=msg_rtrn_bon".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                            <div class="modal-dialog">
                                                                                <!-- Modal content-->
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header panel-primary">
                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                        <h4 class="modal-title">Confirmation</h4>
                                                                                    </div>
                                                                                    <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                        <div class="modal-body">
                                                                                            <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                            <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                        <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                        Facture
                                                                        </button><br>

                                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                            method="post" action="barcodeFacture.php" >
                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                        </form>

                                                                        <?php endif; ?>

                                                                        <div  class="divFacture" style="display:none;">
                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                                <?php echo  '*********************************************'; ?>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <table class="table ">
                                                                        <thead class="noImpr">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Référence</th>
                                                                            <th>Quantité</th>
                                                                            <th>Unité vente</th>
                                                                            <th>Prix Unité vente</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sql8="SELECT * 
                                                                            FROM `".$nomtableLigne."` l
                                                                            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                            WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                            while ($ligne = mysql_fetch_array($res8)) {
                                                                                if ($ligne['classe'] == 0) {?>
                                                                                    <tr class="info">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_bon".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_bon".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                else {?>
                                                                                    <tr>
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_bon".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_bon".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                
                                                                            }  ?>

                                                                            </tbody>
                                                                            </table>

                                                                            <div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <div class="divFacture" style="display:none;">
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                    <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                    <?php echo  '<br/>********************************************* '; ?>
                                                                                    A BIENTOT !
                                                                                </div>
                                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div><?php 
                                                    }	
                                                    else { ?>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading">
                                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#bon".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
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
                                                                    <span class="spanDate noImpr"> Numero : #<?php echo $pagnet['idPagnet']; ?></span>
                                                                </a>
                                                                </h4>
                                                            </div>
                                                            <div class="panel-collapse collapse " <?php echo  "id=bon".$pagnet['idPagnet']."" ; ?> >
                                                                <div class="panel-body" >
                                                                        
                                                                        <?php if ($pagnet['verrouiller']==1):  ?>

                                                                        <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                        <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_bon".$pagnet['idPagnet'] ; ?>>
                                                                                <span class="glyphicon glyphicon-remove"></span>Retourner
                                                                        </button>

                                                                        <div class="modal fade" <?php echo  "id=msg_rtrn_bon".$pagnet['idPagnet'] ; ?> role="dialog">
                                                                            <div class="modal-dialog">
                                                                                <!-- Modal content-->
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header panel-primary">
                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                        <h4 class="modal-title">Confirmation</h4>
                                                                                    </div>
                                                                                    <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                        <div class="modal-body">
                                                                                            <p>Voulez-vous retourner le panier numéro <?php echo $pagnet['idPagnet'] ; ?></p>
                                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value='".$pagnet['idPagnet']."'" ; ?>>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                            <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                        <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                                        Facture
                                                                        </button><br>

                                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>   target="_blank" style="margin-right:20px;"
                                                                            method="post" action="barcodeFacture.php" >
                                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                        </form>

                                                                        <?php endif; ?>

                                                                        <div  class="divFacture" style="display:none;">
                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                <?php echo  '********************************************* <br/>'; ?>
                                                                                <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                                <?php echo  '*********************************************'; ?>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <table class="table ">
                                                                        <thead class="noImpr">
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Référence</th>
                                                                            <th>Quantité</th>
                                                                            <th>Unité vente</th>
                                                                            <th>Prix Unité vente</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sql8="SELECT * 
                                                                            FROM `".$nomtableLigne."` l
                                                                            INNER JOIN `".$nomtableStock."` s ON s.idStock = l.idStock 
                                                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                                                                            WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                                                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                                            while ($ligne = mysql_fetch_array($res8)) {
                                                                                if ($ligne['classe'] == 0) {?>
                                                                                    <tr class="info">
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_bon".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_bon".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                else {?>
                                                                                    <tr>
                                                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                                                        </td>
                                                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                                                        <td><?php
                                                                                        if ($pagnet['verrouiller']==1): ?>
                                                                                            <?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>
                                                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                                                            <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($pagnet['verrouiller']==1): ?>
                                                                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_bon".$ligne['numligne'] ; ?>>
                                                                                                    <span class="glyphicon glyphicon-remove"></span>Retour
                                                                                            </button>

                                                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_bon".$ligne['numligne'] ; ?> role="dialog">
                                                                                                <div class="modal-dialog">
                                                                                                    <!-- Modal content-->
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header panel-primary">
                                                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                            <h4 class="modal-title">Confirmation</h4>
                                                                                                        </div>
                                                                                                        <form class="form-inline noImpr" id="factForm" method="post">
                                                                                                            <div class="modal-body">
                                                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                                <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                                <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                                <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >
                                                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >
                                                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                                <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <?php endif; ?>

                                                                                        </td>
                                                                                    </tr>
                                                                                <?php  }
                                                                                
                                                                            }  ?>

                                                                            </tbody>
                                                                            </table>

                                                                            <div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    <?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php if ($pagnet['verrouiller']==1): ?>
                                                                                    <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <div class="divFacture" style="display:none;">
                                                                                    <?php echo  '********************************************* <br/>'; ?>
                                                                                    Bon <?php echo $pagnet['idPagnet']; ?>
                                                                                    <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                                                    <?php echo  '<br/>********************************************* '; ?>
                                                                                    A BIENTOT !
                                                                                </div>
                                                                            </div>
                                                                </div>
                                                            </div>
                                                        </div><?php
                                                    }	
                                                    ?>

                                                <?php $n=$n-1;   } ?>
                                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                                        </div>
                                    <!-- Fin de l'Accordion pour Tout les Bons -->  
                                </div>
                                <div class="tab-pane" id="tab_default_5">
                                    <!-- Debut de l'Accordion pour Tout les Versements -->
                                        <div class="panel-group" id="accordion">

                                            <?php
                                            
                                            /**Debut requete pour Lister les Versements d'Aujourd'hui  **/
                                                $sqlP1="SELECT * FROM `".$nomtableVersement."` where  dateVersement  ='".$dateInventaire."'  ORDER BY idVersement DESC";
                                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                            /**Fin requete pour Lister les Versements d'Aujourd'hui  **/

                                            /**Debut requete pour Compter les Versements d'Aujourd'hui  **/
                                                $sqlC="SELECT COUNT(*) FROM `".$nomtableVersement."` where  dateVersement ='".$dateInventaire."'  ORDER BY idVersement DESC";
                                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                                $nbre = mysql_fetch_array($resC) ;
                                            /**Fin requete pour Compter les Versements d'Aujourd'hui  **/

                                            ?>         

                                            <!-- Debut Boucle while concernant les Versements  -->
                                                <?php $n=$nbre[0]; while ($versement = mysql_fetch_array($resP1)) {   ?>

                                                    <div style="padding-top : 2px;" class="panel panel-warning">
                                                        <div class="panel-heading">
                                                            <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#versement".$versement['idVersement']."" ; ?>  class="panel-title expand">
                                                            <div class="right-arrow pull-right">+</div>
                                                            <a href="#"> Versement <?php echo $n; ?>

                                                            <span class="spanDate noImpr"> </span>
                                                            <span class="spanDate noImpr"> Date: <?php echo $versement['dateVersement']; ?> </span>
                                                            <span class="spanDate noImpr">Heure: <?php echo $versement['heureVersement']; ?></span>
                                                            <span class="spanDate noImpr">Montant: <?php echo $versement['montant']; ?></span>

                                                            </a>
                                                            </h4>
                                                        </div>
                                                        <div class="panel-collapse collapse " <?php echo  "id=versement".$versement['idVersement']."" ; ?> >
                                                            <div class="panel-body" >

                                                                <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_anl_versement".$versement['idVersement'] ; ?>	 >
                                                                    <span class="glyphicon glyphicon-remove"></span>Annuler
                                                                </button>
                                                                
                                                                <div class="modal fade" <?php echo  "id=msg_anl_versement".$versement['idVersement'] ; ?> role="dialog">
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
                                                                                    <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?>>
                                                                                    <input type="hidden" name="montant" id="montant"  <?php echo  "value=".$versement['montant']."" ; ?>>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                    <button type="submit" name="btnAnnulerVersement" class="btn btn-success">Confirmer</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <form class="form-inline pull-right noImpr" style="margin-right:20px;"
                                                                    method="post" action="barcodeFacture.php" target="_blank"  >
                                                                    <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?> >
                                                                    <button type="submit" class="btn btn-success pull-right" data-toggle="modal" name="barcodeFactureV">
                                                                    <span class="glyphicon glyphicon-lock"></span>Facture
                                                                    </button>
                                                                </form>
                                                                    
                                                                <div <?php echo  "id=divImpVer".$versement['idVersement']."" ; ?>  >
                                                                    <div >
                                                                        Date : <?php 
                                                                        $date1=$versement['dateVersement'];
                                                                        $date2 = new DateTime($date1);
                                                                        //R�cup�ration de l'ann�e
                                                                        $annee =$date2->format('Y');
                                                                        //R�cup�ration du mois
                                                                        $mois =$date2->format('m');
                                                                        //R�cup�ration du jours
                                                                        $jour =$date2->format('d');
                                                                        $date=$jour.'-'.$mois.'-'.$annee;
                                                                                        
                                                                        echo $date; ?> <br/>Heure : <?php 

                                                                        $heureV=$versement['heureVersement'];

                                                                        echo $heureV; ?><br/>
                                                                        <?php echo  '********************************************* <br/>'; ?>
                                                                        <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
                                                                        <?php echo  $_SESSION['adresseB'] ;?>
                                                                        <?php echo  '<br/>*********************************************'; ?>
                                                                    </div>
                                                                    <div> </div>
                                                                    <div><b>Montant : <?php echo  $versement['montant']; ?></b></div>
                                                                    <div class="divFacture" style="display:none;">
                                                                    N° <?php echo $versement['idVersement']; ?> <?php echo "-".$idClient  ?>
                                                                    <span class="spanDate"> <?php echo $versement['dateVersement']; ?> </span>
                                                                </div>
                                                                <div  align="center"> A BIENTOT !</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php $n=$n-1;   } ?>
                                            <!-- Fin Boucle while concernant les Versements   -->

                                        </div>
                                    <!-- Fin de l'Accordion pour Tout les Versements -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin Container Details Journal -->

<?php

echo'</section>'.
'<script>$(document).ready(function(){$("#AjoutLigne").click(function(){$("#AjoutLigneModal").modal();});});</script></body></html>';

}
}else
{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="insertionLigne.php"</script>';
echo'</head>';
echo'</html>';
}
}
else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="../index.php"</script>';
echo'</head>';
echo'</html>';
}
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