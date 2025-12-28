<?php



    session_start();

    if(!$_SESSION['iduser']){

    header('Location:../index.php'); 

    } 



    require('../connection.php');



    require('../declarationVariables.php');
    // var_dump($_SESSION['tampon']);
    
if ($_SESSION['tampon'] == 1) {
    require('loadContainerAjax-tampon.php');
} else {
    

    if($mois==1){
        $annee_paie=$annee - 1;
    }
    else{
        $annee_paie=$annee;
    }

    // $_SESSION['cptChoiceArray']=[];
    // var_dump($_SESSION['cptChoiceArray']);

    if ($_SESSION['compte'] == 1) {

        $sqlGetComptePay="SELECT * FROM `".$nomtableCompte."` where idCompte<>3 ORDER BY idCompte";

        $resPay = mysql_query($sqlGetComptePay) or die ("persoonel requête 2".mysql_error());

        $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte<>2 && idCompte<>3 && idCompte<>1000 ORDER BY idCompte";

        $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());
        // var_dump($cpt_array);

        $cpt_array = [];
        $cpt_array2 = [];

        while ($cpt = mysql_fetch_array($resPay)) {

            # code...

            $cpt_array[] = $cpt;  // var_dump($key);

        }

        while ($cpt = mysql_fetch_array($resPay2)) {

            # code...

            $cpt_array2[] = $cpt;  // var_dump($key);

        }

    }

        

    

// if($_SESSION['proprietaire']==1){



    //     $sqlApp="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE l.classe=5 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."'  ORDER BY p.idPagnet DESC";

    //     $resApp = mysql_query($sqlApp) or die ("persoonel requête 2".mysql_error());

    //     $T_App = 0 ;

    //     $S_App = 0;

    //     while ($pagnet = mysql_fetch_assoc($resApp)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_App = mysql_fetch_array($resS);

    //         $T_App = $S_App[0] + $T_App;

    //     }



    //     $sqlRC="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE l.classe=7 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."'  ORDER BY p.idPagnet DESC";

    //     $resRC = mysql_query($sqlRC) or die ("persoonel requête 2".mysql_error());

    //     $T_Rcaisse = 0 ;

    //     $S_Rcaisse = 0;

    //     while ($pagnet = mysql_fetch_assoc($resRC)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_Rcaisse = mysql_fetch_array($resS);

    //         $T_Rcaisse = $S_Rcaisse[0] + $T_Rcaisse;

    //     }



    //     $sqlV="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE (l.classe=0 || l.classe=1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0  ORDER BY p.idPagnet DESC";

    //     $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

    //     $T_ventes = 0 ;

    //     $S_ventes = 0;

    //     while ($pagnet = mysql_fetch_assoc($resV)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && type=0 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_ventes = mysql_fetch_array($resS);

    //         $T_ventes = $S_ventes[0] + $T_ventes;



    //         if($_SESSION['Pays']=='Canada'){ 

    //             $sqlSP="SELECT SUM(apayerPagnetTvaP)

    //             FROM `".$nomtablePagnet."`

    //             where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && type=0 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

    //             $resSP=mysql_query($sqlSP) or die ("select stock impossible =>".mysql_error());

    //             $StvaP_ventes = mysql_fetch_array($resSP);



    //             $sqlSR="SELECT SUM(apayerPagnetTvaR)

    //             FROM `".$nomtablePagnet."`

    //             where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && type=0 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

    //             $resSR=mysql_query($sqlSR) or die ("select stock impossible =>".mysql_error());

    //             $StvaR_ventes = mysql_fetch_array($resSR);



    //             $T_ventes = $StvaP_ventes[0] + $StvaR_ventes[0] + $T_ventes;

    //         }



    //     }



    //     $sqlBon="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE (l.classe=0 || l.classe=1) && p.idClient!=0  && p.verrouiller=1 && (p.type=0 || p.type=30) && p.datepagej ='".$dateString2."' ORDER BY p.idPagnet DESC";

    //     $resBon = mysql_query($sqlBon) or die ("persoonel requête 2".mysql_error());

    //     $T_bons = 0 ;

    //     $S_bons = 0;

    //     while ($pagnet = mysql_fetch_assoc($resBon)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where idClient!=0 &&  verrouiller=1 && (type=0 || type=30) && datepagej ='".$dateString2."' &&  idPagnet='".$pagnet['idPagnet']."' ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_bons = mysql_fetch_array($resS);

    //         $T_bons = $S_bons[0] + $T_bons;



    //         if($_SESSION['Pays']=='Canada'){ 

    //             $sqlSP="SELECT SUM(apayerPagnetTvaP)

    //             FROM `".$nomtablePagnet."`

    //             where idClient!=0 &&  verrouiller=1 && (type=0 || type=30) && datepagej ='".$dateString2."' &&  idPagnet='".$pagnet['idPagnet']."' ORDER BY idPagnet DESC";

    //             $resSP=mysql_query($sqlSP) or die ("select stock impossible =>".mysql_error());

    //             $StvaP_bons = mysql_fetch_array($resSP);



    //             $sqlSR="SELECT SUM(apayerPagnetTvaR)

    //             FROM `".$nomtablePagnet."`

    //             where idClient!=0 &&  verrouiller=1 && (type=0 || type=30) && datepagej ='".$dateString2."' &&  idPagnet='".$pagnet['idPagnet']."' ORDER BY idPagnet DESC";

    //             $resSR=mysql_query($sqlSR) or die ("select stock impossible =>".mysql_error());

    //             $StvaR_bons = mysql_fetch_array($resSR);



    //             $T_bons = $StvaP_bons[0] +  $StvaP_bons[0] + $T_bons;

    //         }

    //     }



    //     $sqlR="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE l.classe=0 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=1  ORDER BY p.idPagnet DESC";

    //     $resR = mysql_query($sqlR) or die ("persoonel requête 2".mysql_error());

    //     $T_Rpagnet = 0 ;

    //     $S_Rpagnet = 0;

    //     while ($pagnet = mysql_fetch_assoc($resR)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && type=1 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_Rpagnet = mysql_fetch_array($resS);

    //         $T_Rpagnet = $S_Rpagnet[0] + $T_Rpagnet;

    //     }



    //     $sqlTD="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE l.classe=3 && l.unitevente ='Depot' && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0   ORDER BY p.idPagnet DESC";

    //     $resTD = mysql_query($sqlTD) or die ("persoonel requête 2".mysql_error());

    //     $T_depot = 0 ;

    //     $S_depot = 0;

    //     while ($pagnet = mysql_fetch_assoc($resTD)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."' && type=0   ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_depot = mysql_fetch_array($resS);

    //         $T_depot = $S_depot[0] + $T_depot;

    //     }



    //     $sqlTR="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE l.classe=3 && l.unitevente ='Retrait'  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' &&type=0  ORDER BY p.idPagnet DESC";

    //     $resTR = mysql_query($sqlTR) or die ("persoonel requête 2".mysql_error());

    //     $T_retrait = 0 ;

    //     $S_retrait = 0;

    //     while ($pagnet = mysql_fetch_assoc($resTR)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."' && type=0  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_retrait = mysql_fetch_array($resS);

    //         $T_retrait = $S_retrait[0] + $T_retrait;

    //     }



    //     $sqlTF="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE l.classe=3 && l.unitevente ='Facture'   && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0  ORDER BY p.idPagnet DESC";

    //     $resTF = mysql_query($sqlTF) or die ("persoonel requête 2".mysql_error());

    //     $T_facture = 0 ;

    //     $S_facture = 0;

    //     while ($pagnet = mysql_fetch_assoc($resTF)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."' && type=0  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_facture = mysql_fetch_array($resS);

    //         $T_facture = $S_facture[0] + $T_facture;

    //     }



    //     $sqlTC="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE l.classe=4  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."'  ORDER BY p.idPagnet DESC";

    //     $resTC = mysql_query($sqlTC) or die ("persoonel requête 2".mysql_error());

    //     $T_credit = 0 ;

    //     $S_credit = 0;

    //     while ($pagnet = mysql_fetch_assoc($resTC)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_credit = mysql_fetch_array($resS);

    //         $T_credit = $S_credit[0] + $T_credit;

    //     }



    //     $sqlD="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE l.classe=2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."'  && p.type=0  ORDER BY p.idPagnet DESC";

    //     $resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());

    //     $T_depenses = 0 ;

    //     $S_depenses = 0;

    //     while ($pagnet = mysql_fetch_assoc($resD)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."'  && type=0  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_depenses = mysql_fetch_array($resS);

    //         $T_depenses = $S_depenses[0] + $T_depenses;

    //     }



    //     $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient!=0  &&  dateVersement  ='".$dateString2."'  ORDER BY idVersement DESC";

    //     $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());

    //     $T_versements = mysql_fetch_array($resP5) ;

    // }

    // else{

    //     $sql2="SELECT * FROM `".$nomtablePagnet."` where iduser='".$_SESSION['iduser']."' && idClient=0 && datepagej ='".$dateString2."' ORDER BY idPagnet DESC";

    //     $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());

    //     $total=0;

    //     while ($pagnet0 = mysql_fetch_assoc($res2))

    //     $total+=$pagnet0['apayerPagnet'];



    //     $sqlApp="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE p.iduser='".$_SESSION['iduser']."' && l.classe=5 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."'  ORDER BY p.idPagnet DESC";

    //     $resApp = mysql_query($sqlApp) or die ("persoonel requête 2".mysql_error());

    //     $T_App = 0 ;

    //     $S_App = 0;

    //     while ($pagnet = mysql_fetch_assoc($resApp)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where iduser='".$_SESSION['iduser']."' && idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_App = mysql_fetch_array($resS);

    //         $T_App = $S_App[0] + $T_App;

    //     }



    //     $sqlRC="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE p.iduser='".$_SESSION['iduser']."' && l.classe=7 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."'  ORDER BY p.idPagnet DESC";

    //     $resRC = mysql_query($sqlRC) or die ("persoonel requête 2".mysql_error());

    //     $T_Rcaisse = 0 ;

    //     $S_Rcaisse = 0;

    //     while ($pagnet = mysql_fetch_assoc($resRC)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where iduser='".$_SESSION['iduser']."' && idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_Rcaisse = mysql_fetch_array($resS);

    //         $T_Rcaisse = $S_Rcaisse[0] + $T_Rcaisse;

    //     }



    //     $sqlV="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE p.iduser='".$_SESSION['iduser']."' && (l.classe=0 || l.classe=1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0  ORDER BY p.idPagnet DESC";

    //     $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

    //     $T_ventes = 0 ;

    //     $S_ventes = 0;

    //     while ($pagnet = mysql_fetch_assoc($resV)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where iduser='".$_SESSION['iduser']."' && idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && type=0 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_ventes = mysql_fetch_array($resS);

    //         $T_ventes = $S_ventes[0] + $T_ventes;



    //         if($_SESSION['Pays']=='Canada'){ 

    //             $sqlSP="SELECT SUM(apayerPagnetTvaP)

    //             FROM `".$nomtablePagnet."`

    //             where iduser='".$_SESSION['iduser']."' && idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && type=0 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

    //             $resSP=mysql_query($sqlSP) or die ("select stock impossible =>".mysql_error());

    //             $StvaP_ventes = mysql_fetch_array($resSP);



    //             $sqlSR="SELECT SUM(apayerPagnetTvaR)

    //             FROM `".$nomtablePagnet."`

    //             where iduser='".$_SESSION['iduser']."' && idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && type=0 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

    //             $resSR=mysql_query($sqlSR) or die ("select stock impossible =>".mysql_error());

    //             $StvaR_ventes = mysql_fetch_array($resSR);



    //             $T_ventes = $StvaP_ventes[0] + $StvaR_ventes[0] + $T_ventes;

    //         }

    //     }



    //     $sqlR="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE p.iduser='".$_SESSION['iduser']."' && l.classe=0 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=1  ORDER BY p.idPagnet DESC";

    //     $resR = mysql_query($sqlR) or die ("persoonel requête 2".mysql_error());

    //     $T_Rpagnet = 0 ;

    //     $S_Rpagnet = 0;

    //     while ($pagnet = mysql_fetch_assoc($resR)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where iduser='".$_SESSION['iduser']."' && idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && type=1 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_Rpagnet = mysql_fetch_array($resS);

    //         $T_Rpagnet = $S_Rpagnet[0] + $T_Rpagnet;

    //     }



    //     $sqlTD="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE p.iduser='".$_SESSION['iduser']."' && l.classe=3 && l.unitevente ='Depot' && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."'  && p.type=0  ORDER BY p.idPagnet DESC";

    //     $resTD = mysql_query($sqlTD) or die ("persoonel requête 2".mysql_error());

    //     $T_depot = 0 ;

    //     $S_depot = 0;

    //     while ($pagnet = mysql_fetch_assoc($resTD)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where iduser='".$_SESSION['iduser']."' && idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."'  && type=0  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_depot = mysql_fetch_array($resS);

    //         $T_depot = $S_depot[0] + $T_depot;

    //     }



    //     $sqlTR="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE p.iduser='".$_SESSION['iduser']."' && l.classe=3 && l.unitevente ='Retrait' && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."'  && p.type=0  ORDER BY p.idPagnet DESC";

    //     $resTR = mysql_query($sqlTR) or die ("persoonel requête 2".mysql_error());

    //     $T_retrait = 0 ;

    //     $S_retrait = 0;

    //     while ($pagnet = mysql_fetch_assoc($resTR)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where iduser='".$_SESSION['iduser']."' && idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."' && type=0  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_retrait = mysql_fetch_array($resS);

    //         $T_retrait = $S_retrait[0] + $T_retrait;

    //     }



    //     $sqlTF="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE p.iduser='".$_SESSION['iduser']."' && l.classe=3 && l.unitevente ='Facture'  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."'  && p.type=0  ORDER BY p.idPagnet DESC";

    //     $resTF = mysql_query($sqlTF) or die ("persoonel requête 2".mysql_error());

    //     $T_facture = 0 ;

    //     $S_facture = 0;

    //     while ($pagnet = mysql_fetch_assoc($resTF)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where iduser='".$_SESSION['iduser']."' && idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."' && type=0  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_facture = mysql_fetch_array($resS);

    //         $T_facture = $S_facture[0] + $T_facture;

    //     }



    //     $sqlTC="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE p.iduser='".$_SESSION['iduser']."' && l.classe=4  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."'  ORDER BY p.idPagnet DESC";

    //     $resTC = mysql_query($sqlTC) or die ("persoonel requête 2".mysql_error());

    //     $T_credit = 0 ;

    //     $S_credit = 0;

    //     while ($pagnet = mysql_fetch_assoc($resTC)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where iduser='".$_SESSION['iduser']."' && idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_credit = mysql_fetch_array($resS);

    //         $T_credit = $S_credit[0] + $T_credit;

    //     }



    //     $sqlD="SELECT DISTINCT p.idPagnet

    //         FROM `".$nomtablePagnet."` p

    //         INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

    //         WHERE p.iduser='".$_SESSION['iduser']."' && l.classe=2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0 ORDER BY p.idPagnet DESC";

    //     $resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());

    //     $T_depenses = 0 ;

    //     $S_depenses = 0;

    //     while ($pagnet = mysql_fetch_assoc($resD)) {

    //         $sqlS="SELECT SUM(apayerPagnet)

    //         FROM `".$nomtablePagnet."`

    //         where iduser='".$_SESSION['iduser']."' && idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."'  && type=0  ORDER BY idPagnet DESC";

    //         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

    //         $S_depenses = mysql_fetch_array($resS);

    //         $T_depenses = $S_depenses[0] + $T_depenses;

    //     }



    //     $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient!=0  && iduser='".$_SESSION['iduser']."' && dateVersement  ='".$dateString2."'  ORDER BY idVersement DESC";

    //     $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());

    //     $T_versements = mysql_fetch_array($resP5) ;

    // }



// $T_caisse = $T_App + $T_ventes + $T_depot + $T_facture + $T_credit - $T_Rpagnet - $T_depenses - $T_retrait - $T_Rcaisse;



    ?>



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



<!--*******************************Debut Rechercher Produit****************************************-->

                     

    <!--*******************************Debut Rechercher Produit****************************************-->

    <?php if ($_SESSION['proprietaire']==1){ ?>

        <form  class="pull-right form-inline" id="searchProdForm" method="post" name="searchProdForm" >

        <div class="form-group">

            <img src="images/loading-gif3.gif" class="img-load-search form-group" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> 

        </div>    

        <div class="form-group">

            <input type="text" size="45" class="form-control form-group" name="produit" placeholder="Rechercher produit vendu..."  id="produitVenduB" autocomplete="off" />

        </div>    



                <!-- <span id="reponsePV"></span> -->

        </form>

    <?php } ?>

    <!--*******************************Fin Rechercher Produit****************************************-->

    <?php

        if (($_SESSION['type']=="Multi-service") && ($_SESSION['categorie']=="Detaillant")) { 

            ?>

            <form name="btnSavePagnetTransfert" method="post" id="addTransfert" class="pull-right" >

                <button type="submit" class="btn btn-warning noImpr" id="addPanier" name="btnSavePagnetTransfert">

                    <i class="glyphicon glyphicon-plus"></i> Transferts

                </button>

            </form>

          <?php  

        }										

    ?>

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
                    echo ' 
                        <form name="formulairePagnet" method="post" >
                            <button disabled="true" type="button" class="btn btn-success noImpr addPanier" id="addPanier" name="btnSavePagnetVente">
                                <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                                <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">                    
                            </button>
                        </form>
                    '; 
                } 
                else{
                    echo ' 
                        <form name="formulairePagnet" method="post" >
                            <button type="button" class="btn btn-success noImpr addPanier" id="addPanier" name="btnSavePagnetVente">
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
                        <button type="button" class="btn btn-success noImpr addPanier" id="addPanier" name="btnSavePagnetVente">
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
                    <button type="button" class="btn btn-success noImpr addPanier" id="addPanier" name="btnSavePagnetVente">
                        <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                        <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">                    
                    </button>
                </form>
            ';

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

                

                //Get page number from Ajax

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

                            $sqlC="SELECT COUNT(*) FROM `".$nomtablePagnet."` p

                            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                            where p.datepagej ='".$dateString2."' && p.verrouiller=1 && (p.type=0 || p.type=11 || p.type=30 || p.type=10) && l.designation='".$produit."' ORDER BY p.idPagnet DESC";

                            $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());

                            $nbre = mysql_fetch_array($resC) ;

                            $nbPaniers = (int) $nbre[0];

                        /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/



                        echo '<input type="hidden" id="produitAfter"  value="'.$produit.'"  >';



                        // On calcule le nombre de pages total

                        $pages = ceil($nbPaniers / $parPage);

                                    

                        $premier = ($currentPage * $parPage) - $parPage;



                        /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/

                            $sqlP1="SELECT * FROM `".$nomtablePagnet."` p

                            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                            where p.datepagej ='".$dateString2."' && p.verrouiller=1 && (p.type=0 || p.type=11 || p.type=30 || p.type=10 || p.type=1) && l.designation='".$produit."' ORDER BY p.idPagnet DESC LIMIT ".$premier.",".$parPage." ";

                            $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());

                        /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                    }

                    else{

                        /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/

                            $sqlC="SELECT COUNT(*) FROM `".$nomtablePagnet."` where datepagej ='".$dateString2."' && (type=0 || type=11 || type=30 || type=10) && verrouiller=1  ORDER BY idPagnet DESC";

                            $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());

                            $nbre = mysql_fetch_array($resC) ;

                            $nbPaniers = (int) $nbre[0];

                        /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/

    

                        // On calcule le nombre de pages total

                        $pages = ceil($nbPaniers / $parPage);

                                    

                        $premier = ($currentPage * $parPage) - $parPage;

    

                        /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/

                            $sqlP1="SELECT * FROM `".$nomtablePagnet."` where datepagej ='".$dateString2."' && (type=0 || type=11 || type=30 || type=10) && verrouiller=1 ORDER BY idPagnet DESC LIMIT ".$premier.",".$parPage." ";

                            $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());

                        /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                    }



                    /**Debut requete pour Lister les Paniers en cours d'Aujourd'hui (2 aux maximum) **/

                        $sqlP0="SELECT * FROM `".$nomtablePagnet."` where iduser='".$_SESSION['iduser']."' && datepagej ='".$dateString2."' && verrouiller=0 ORDER BY idPagnet DESC";

                        $resP0 = mysql_query($sqlP0) or die ("persoonel requête 2".mysql_error());

                    /**Fin requete pour Lister les Paniers en cours d'Aujourd'hui (2 aux maximum) **/



                    /**Debut requete pour Lister tout les Paniers d'Aujourd'hui  **/

                        $sql2="SELECT * FROM `".$nomtablePagnet."` where datepagej ='".$dateString2."' ORDER BY idPagnet DESC";

                        $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());

                    /**Fin requete pour Lister tout les Paniers d'Aujourd'hui  **/



                    /**Debut requete pour Rechercher le dernier Panier Ajouter  **/

                        $reqA="SELECT idPagnet from `".$nomtablePagnet."` order by idPagnet desc limit 1";

                        $resA=mysql_query($reqA) or die ("persoonel requête 2".mysql_error());

                    /**Fin requete pour Rechercher le dernier Panier Ajouter  **/ 

                }

                else{

                    if (isset($_POST['produit'])) {

                        $produit=@htmlspecialchars($_POST["produit"]);

                        /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/

                            $sqlC="SELECT COUNT(*) FROM `".$nomtablePagnet."` p

                            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                            where  p.iduser='".$_SESSION['iduser']."' && (p.type=0 || p.type=11 || p.type=30 || p.type=10) && p.datepagej ='".$dateString2."' && p.verrouiller=1 && l.designation='".$produit."' ORDER BY p.idPagnet DESC";

                            $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());

                            $nbre = mysql_fetch_array($resC) ;

                            $nbPaniers = (int) $nbre[0];

                        /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/



                        echo '<input type="hidden" id="produitAfter"  value="'.$produit.'"  >';



                        // On calcule le nombre de pages total

                        $pages = ceil($nbPaniers / $parPage);

                                    

                        $premier = ($currentPage * $parPage) - $parPage;



                        /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/

                            $sqlP1="SELECT * FROM `".$nomtablePagnet."` p

                            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                            where  p.iduser='".$_SESSION['iduser']."' && (p.type=0 || p.type=11 || p.type=30 || p.type=10 || p.type=1) && p.datepagej ='".$dateString2."' && p.verrouiller=1 && l.designation='".$produit."' ORDER BY p.idPagnet DESC LIMIT ".$premier.",".$parPage." ";

                            $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());

                        /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                    }

                    else{

                        /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/

                            $sqlC="SELECT COUNT(*) FROM `".$nomtablePagnet."` where  iduser='".$_SESSION['iduser']."' && (type=0 || type=11 || type=30 || type=10) && datepagej ='".$dateString2."' && verrouiller=1  ORDER BY idPagnet DESC";

                            $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());

                            $nbre = mysql_fetch_array($resC) ;

                            $nbPaniers = (int) $nbre[0];

                        /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/

    

                        // On calcule le nombre de pages total

                        $pages = ceil($nbPaniers / $parPage);

                                    

                        $premier = ($currentPage * $parPage) - $parPage;

    

                        /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/

                            $sqlP1="SELECT * FROM `".$nomtablePagnet."` where  iduser='".$_SESSION['iduser']."' && (type=0 || type=11 || type=30 || type=10) && datepagej ='".$dateString2."' && verrouiller=1  ORDER BY idPagnet DESC LIMIT 10 ";

                            $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());

                        /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                    }



                    /**Debut requete pour Lister les Paniers en cours d'Aujourd'hui (2 aux maximum) **/

                        $sqlP0="SELECT * FROM `".$nomtablePagnet."` where  iduser='".$_SESSION['iduser']."' && datepagej ='".$dateString2."' && verrouiller=0 ORDER BY idPagnet DESC";

                        $resP0 = mysql_query($sqlP0) or die ("persoonel requête 2".mysql_error());

                    /**Fin requete pour Lister les Paniers en cours d'Aujourd'hui (2 aux maximum) **/



                    /**Debut requete pour Lister tout les Paniers d'Aujourd'hui  **/

                        $sql2="SELECT * FROM `".$nomtablePagnet."` where  iduser='".$_SESSION['iduser']."' && datepagej ='".$dateString2."' ORDER BY idPagnet DESC";

                        $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());

                    /**Fin requete pour Lister tout les Paniers d'Aujourd'hui  **/



                    /**Debut requete pour Rechercher le dernier Panier Ajouter  **/

                        $reqA="SELECT idPagnet from `".$nomtablePagnet."` where  iduser='".$_SESSION['iduser']."' order by idPagnet desc limit 1";

                        $resA=mysql_query($reqA) or die ("persoonel requête 2".mysql_error());

                    /**Fin requete pour Rechercher le dernier Panier Ajouter  **/ 

                }

            

            ?>         

            

            <!-- Debut Boucle while concernant les Paniers en cours (2 aux maximum) -->  

                <?php 

                    $autofocus = 0;

                while ($pagnet = mysql_fetch_assoc($resP0)) {

                    $panierLast = mysql_fetch_array($resA);

                    $lastpanier = $panierLast[0];

                    $autofocus += 1;

                    ?>

                <?php

                    if($pagnet['type'] == 0 || $pagnet['type'] == 30){   ?>

                        <div class="panel <?= ($pagnet['type']==0) ? 'panel-primary' : 'panel-info'?>" id="panelPanier<?= $pagnet['idPagnet'];?>">

                            <div class="panel-heading">

                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                    <div class="right-arrow pull-right">+</div>

                                    <a class="row" href="#"> 

                                        <span class="noImpr col-md-2 col-sm-2 col-xs-12" id="panierEncours<?= $pagnet['idPagnet'];?>"> Panier <?php echo ': En cours...'; ?> </span>

                                        <span class="noImpr col-md-3 col-sm-3 col-xs-12">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                        <?php	$sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";

                                                $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                                                $TotalT = mysql_fetch_array($resT) ;

                                                ?>

                                        <?php if($_SESSION['Pays']=='Canada'){ 

                                            $sqlTP="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";

                                            $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());

                                            $TotalTvaP = mysql_fetch_array($resTP) ;

                                            $sqlTR="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";

                                            $resTR = mysql_query($sqlTR) or die ("persoonel requête 2".mysql_error());

                                            $TotalTvaR = mysql_fetch_array($resTR) ;

                                        ?> 

                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Sous-Total : <span <?php echo  "id=somme_TotalCN".$pagnet['idPagnet'] ; ?> ><?php echo number_format((($TotalT[0]-$pagnet['remise']) * $_SESSION['devise']), 2, ',', ' '); ?> </span></span>

                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" > Total : <span <?php echo  "id=somme_ApayerCN".$pagnet['idPagnet'] ; ?> ><?php echo number_format(((($TotalT[0] + $TotalTvaP[0] + $TotalTvaR[0] )-$pagnet['remise']) * $_SESSION['devise']), 2, ',', ' ') ; ?> </span></span>

                                        <?php }

                                        else{   ?>

                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total panier: <span <?php echo  "id=somme_Total".$pagnet['idPagnet']; ?> ><?php echo $TotalT[0]; ?>  </span></span>

                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total à payer: <span <?php echo  "id=somme_Apayer".$pagnet['idPagnet'] ; ?> ><?php echo ($TotalT[0]-$pagnet['remise']); ?> </span></span>                 

                                        <?php } ?> 

                                    </a>

                                </h4>

                            </div>

                            <div <?php echo "id=pagnet".$pagnet['idPagnet']."" ; ?> class="panel-collapse collapse in panelVente" > 

                                <div class="panel-body" >

                                    <div class="" id="btnContent<?= $pagnet['idPagnet'];?>" style="display:none;">

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

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

                                                                <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                        <!--*******************************Fin Retourner Pagnet****************************************-->



                                        <!--*******************************Debut Facture****************************************-->

                                        

                                            <?php if ($_SESSION['caissier']==1){ ?>

                                                

                                                <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet".$pagnet['idPagnet'] ; ?>>

                                                    Facture

                                                </button>

                                                

                                            <?php } ?>



                                            <div class="modal fade" <?php echo  "id=msg_fact_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                <div class="modal-dialog">

                                                    <!-- Modal content-->

                                                    <div class="modal-content">

                                                        <div class="modal-header panel-primary">

                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                            <h4 class="modal-title">Informations Client</h4>

                                                        </div>

                                                        <form class="form-inline noImpr"  method="post" action="pdfFacture.php" target="_blank" >

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

                                        <?php if ($_SESSION['caissier']==1){ ?>

                                        

                                            <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                Ticket de Caisse

                                            </button>

                                        

                                        <?php } ?>

                                        

                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                            method="post" action="barcodeFacture.php" >

                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                        </form>                                        

                                    </div>

                                    <div class="cache_btn_Terminer row" id="cache_btn_Terminer<?= $pagnet['idPagnet'];?>">

                                        <!--*******************************Debut Ajouter Ligne****************************************-->

                                        <form  class="form-inline noImpr ajouterProdForm col-md-4 col-sm-4 col-xs-12" id="ajouterProdFormB<?=$pagnet['idPagnet'];?>" onsubmit="return false" >

                                            <input type="text" class="inputbasic form-control codeBarreLigneVt" name="codeBarre" id="panier_<?=$pagnet['idPagnet'];?>" data-idPanier="<?= $pagnet['idPagnet'];?>" style="width:100%;" <?= ($pagnet['idPagnet']==$lastpanier) ? 'autofocus=""' : ''; ?> autocomplete="off"  required />

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo "value=".$pagnet['idPagnet']."" ; ?>>

                                                <!-- <span id="reponseV"></span> -->

                                            <!-- <button type="submit" name="btnEnregistrerCodeBarre"

                                            id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs " ><span class="glyphicon glyphicon-plus" ></span>

                                            </button> -->

                                        </form>

                                        <!--*******************************Fin Ajouter Ligne****************************************-->

                                        <div class="col-md-8 col-sm-8 col-xs-12 content2">

                                            <!--*******************************Debut Terminer Pagnet****************************************-->

                                            <form class="form-inline noImpr" id="factForm" method="post">

                                                <input type="number" <?php echo "id=val_remise".$pagnet['idPagnet'] ; ?> name="remise" onkeyup="modif_Remise(this.value, <?php echo  $pagnet['idPagnet']; ?>)"

                                                class="remise form-control col-md-2 col-sm-2 col-xs-3" <?= ($pagnet['remise']==0) ? 'placeholder="Remise...."' : 'value="'.$pagnet['remise'].'"'?>>

                                                <?php if ($_SESSION['compte']==1) { ?>


                                                    <select class="compte <?= $pagnet['idPagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3"  data-idPanier="<?= $pagnet['idPagnet'];?>" name="compte" onchange="showImageCompte(this.value,<?= $pagnet['idPagnet'];?>)"  <?php echo  "id=compte".$pagnet['idPagnet'] ; ?>>

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

                                                        <option id="idImageCompte<?= $key['idCompte'];?>"  data-image="<?= $key['image'];?>"  value="<?= $key['idCompte'];?>"><?= strtoupper($key['nomCompte']);?></option>

                                                    <?php } ?>

                                                </select>                                                        

                                                <!-- Modal paiement multiple-->
                                                <div class="modal fade" id="paiementMultipleModal-<?= $pagnet['idPagnet']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" align="">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" style="float:left" id="exampleModalLabel">Modal title</h5>
                                                            <button type="button" style="float:right" class="btn-close btn-danger" data-dismiss="modal"><b>&times;</b></button>
                                                        </div>
                                                        <div class="modal-body" style="text-align:left">
                                                        <?php foreach ($cpt_array2 as $key) { ?>
                                                            <div class="form-check row" style="margin:5px">
                                                                <!-- <div class="col-lg-1"></div> -->
                                                                <div class="col-lg-1">
                                                                    <input class="form-check-input checkCompte" name="checkCompte" onChange="checkedCompte(<?= $key['idCompte'];?>,<?= $pagnet['idPagnet'];?>)" type="checkbox" data-idCompte="<?= $key['idCompte'];?>" id="compte_<?= $key['idCompte']."_".$pagnet['idPagnet'];?>">
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <label class="form-check-label" for="compte_<?= $key['idCompte']."_".$pagnet['idPagnet'];?>">
                                                                        <?= strtoupper($key['nomCompte']);?>
                                                                    </label>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input type="number" class="form-control" style="display:none" name="montantCompte_<?= $key['idCompte']."_".$pagnet['idPagnet'];?>" id="montantCompte_<?= $key['idCompte']."_".$pagnet['idPagnet'];?>" placeholder="Montant <?= $key['nomCompte'];?>">
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        </div>
                                                        <code id="textAlert-<?= $pagnet['idPagnet']; ?>" style="display:none" align="center">Attention!!! <br> Le montant à payer et les montants entre les comptes ne sont pas conformes. <br></code>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                            <button type="button" onClick="validerCptMultiple(<?= $pagnet['idPagnet'];?>)" class="btn btn-primary btnValidCptMult" data-idCompte="<?= $key['idCompte'];?>">Valider</button>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                


                                                <?php if ($pagnet['type']=='30') {

                                                        

                                                        $sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$pagnet['idClient']."";

                                                        $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());

                                                        $client = mysql_fetch_assoc($res3);

                                                ?>

                                                        <input type="text" class="client clientInput form-control col-md-2 col-sm-2 col-xs-3" data-type="simple" data-idPanier="<?= $pagnet['idPagnet'];?>" name="clientInput" id="clientInput<?= $pagnet['idPagnet'];?>" autocomplete="off" value="<?= $client['prenom']." ".$client['nom']; ?>"/>

                                                        <input type="number" class="avanceInput form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $pagnet['idPagnet'];?>" name="avanceInput" id="avanceInput<?= $pagnet['idPagnet'];?>" autocomplete="off" value="<?= $pagnet['avance']; ?>"/>

                                                        <select class="compteAvance <?= $pagnet['idPagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $pagnet['idPagnet'];?>" name="compteAvance" onchange="showImageCompte(this.value,<?= $pagnet['idPagnet'];?>)"   <?php echo  "id=compteAvance".$pagnet['idPagnet'] ; ?>>

                                                            <!-- <option value="caisse">Caisse</option> -->

                                                            <?php 

                                                            foreach ($cpt_array as $key) {  

                                                                if($key['idCompte'] != 2){

                                                                ?>

                                                                    <option id="idImageCompte<?= $key['idCompte'];?>"  data-image="<?= $key['image'];?>"  value="<?= $key['idCompte'];?>"><?= strtoupper($key['nomCompte']);?></option>

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

                                                        <select class="compteAvance <?= $pagnet['idPagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-idPanier="<?= $pagnet['idPagnet'];?>" name="compteAvance" onchange="showImageCompte(this.value,<?= $pagnet['idPagnet'];?>)"   <?php echo  "id=compteAvance".$pagnet['idPagnet'] ; ?>>

                                                            <!-- <option value="caisse">Caisse</option> -->

                                                        <?php foreach ($cpt_array as $key) { 

                                                            if($key['idCompte'] != 2){

                                                            ?>

                                                                <option id="idImageCompte<?= $key['idCompte'];?>"  data-image="<?= $key['image'];?>"  value="<?= $key['idCompte'];?>"><?= strtoupper($key['nomCompte']);?></option>

                                                            <?php }

                                                            } ?>

                                                        </select> 

                                                <?php } ?> 



                                                <?php }?>

    

                                                <?php if($_SESSION['Pays']=='Canada'){  ?> 

                                                    <input type="number" name="versement" id="versement<?= $pagnet['idPagnet'];?>" data-idPanier="<?= $pagnet['idPagnet'];?>" <?= ((@$pagnet['idCompte']!=0 && @$pagnet['idCompte']!=1)) ? 'style="display:none;"' : '';?> <?= (@$pagnet['versement']!=0) ? 'value="'.$pagnet["versement"].'"' : 'placeholder="Espèce..."'; ?>

                                                    class="versement form-control col-md-2 col-sm-2 col-xs-3">


                                                <?php }

                                                else{   ?>

                                                    <input type="number" name="versement" id="versement<?= $pagnet['idPagnet'];?>" data-idPanier="<?= $pagnet['idPagnet'];?>" <?= ((@$pagnet['idCompte']!=0 && @$pagnet['idCompte']!=1)) ? 'style="display:none;"' : '';?> <?= (@$pagnet['versement']!=0) ? 'value="'.$pagnet["versement"].'"' : 'placeholder="Espèce..."'; ?>

                                                    class="versement form-control col-md-2 col-sm-2 col-xs-3">



                                                <?php } ?> 

                                                <input type="hidden" name="idPagnet"   <?php echo  "value=".$pagnet['idPagnet']."" ; ?>>

                                                <input type="hidden" name="totalp" <?php echo  "id=totalp".$pagnet['idPagnet']."" ; ?> value="<?php echo $pagnet['totalp']; ?>" >

                                                <!-- <div class="btnAction col-md-1 col-xs-12"> -->

                                                <button type="button" style="" data-print="<?= (@$_SESSION['printAfterEndCart']==1) ? '1' : '0';?>" name="btnImprimerFacture" data-idPanier="<?= $pagnet['idPagnet'];?>" <?php echo  "id=btnImprimerFacture".$pagnet['idPagnet']."" ; ?> class="btn btn-success btn_Termine_Panier btn_Termine_Panier<?= $pagnet['idPagnet'] ; ?> terminer" data-toggle="modal" ><span class="glyphicon glyphicon-ok"></span>

                                                    <img src="images/loading-gif3.gif" class="img-load-terminer" style="height: 30px;width: 30px;display:none;" alt="GIF" srcset=""></button>

                                                <button tabindex="1" type="button" class="btn btn-danger annuler" data-toggle="modal" <?php echo  "data-target=#msg_ann_pagnet".$pagnet['idPagnet'] ; ?>>

                                                    <span class="glyphicon glyphicon-remove"></span>

                                                </button>

                                                
                                                <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                    method="post" action="barcodeFacture.php" >

                                                    <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                </form> 

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

                                                                <button type="button" name="btnAnnulerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnAnnulerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-annulerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                            <!--*******************************Fin Annuler Pagnet****************************************-->

                                        </div>

                                    </div>

                                    <table  <?php echo  "id=tablePanier".$pagnet['idPagnet']."" ; ?> class="tabPanier table"  width="100%" >

                                        <thead>

                                            <tr>

                                                <th>Référence</th>

                                                <th>Quantité</th>

                                                <th>Unité vente</th>

                                                <!-- <th class="hidden-sm hidden-xs">Unité vente</th> -->

                                                <th>Prix Unité vente</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            <?php

                                            $sql8="SELECT * FROM `".$nomtableLigne."` l

                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation  

                                            where l.idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                            while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                <tr>

                                                    <td class="designation">

                                                        <?php

                                                            if($ligne['classe']==2){

                                                                echo '<input class="form-control" style="width: 100%"  type="text" value="'.$ligne['designation'].'"

                                                                    onkeyup="modif_DesignationBon(this.value,'.$ligne['numligne'].','.$pagnet['idPagnet'].')" />

                                                                ';

                                                            }

                                                            else{?>

                                                                <?php

                                                                    if($_SESSION['Pays']=='Canada' && $ligne['tva'] == 1){ 

                                                                        echo '<span style="font-size:16px;color:black" class="glyphicon glyphicon-star"> </span>&nbsp';

                                                                    }

                                                                ?>

                                                                <?php echo $ligne['designation']; 

                                                            }

                                                        ?>

                                                    </td>

                                                    <td>

                                                        <?php if ($ligne['classe']==3) { ?>

                                                                

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

                                                                

                                                                        if(($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")){

                                                                            ?>  

                                                                                <input class="quantite form-control filedReadonly<?= $pagnet['idPagnet'] ; ?>" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                            onkeyup="modif_Quantite(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  $designation['nbreArticleUniteStock']; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                    >

                                                                            <?php

                                                                        }

                                                                        else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){

                                                                            ?>  

                                                                                <input class="quantite form-control filedReadonly<?= $pagnet['idPagnet'] ; ?>" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                            onkeyup="modif_Quantite(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  '1'; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                    >

                                                                        <?php }

                                                                    }

                                                                    else{

                                                                        $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

                                                                        $resD=mysql_query($sqlD) or die ("select Designation impossible =>".mysql_error());

                                                                        $designation = mysql_fetch_assoc($resD);

                                                                

                                                                        if(($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")){

                                                                            ?>  

                                                                                <input class="quantite form-control filedReadonly<?= $pagnet['idPagnet'] ; ?>" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                            onkeyup="modif_QuantiteP(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  $designation['nbreArticleUniteStock']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                    >

                                                                            <?php

                                                                        }

                                                                        else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){

                                                                            ?>  

                                                                                <input class="quantite form-control filedReadonly<?= $pagnet['idPagnet'] ; ?>" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                            onkeyup="modif_QuantiteP(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  '1'; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                    >

                                                                        <?php }



                                                                    }

                                                                }

                                                                if($ligne['classe']==1) { ?> 

                                                                    <input class="quantite form-control filedReadonly<?= $pagnet['idPagnet'] ; ?>" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                    onkeyup="modif_QuantiteSD(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                            >

                                                                <?php  

                                                                }

                                                                if($ligne['classe']==5 || $ligne['classe']==7 ) {?>

                                                                    <?php echo 'Montant'; ?>

                                                                <?php }

                                                        } ?>                                     

                                                    </td>

                                                    <td class="unitevente "> 

                                                        <?php

                                                            if($ligne['classe']==0){

                                                                if($ligne['idStock']!=0){?>  

                                                                    <?php echo $ligne['unitevente']; ?>       

                                                                <?php 

                                                                }

                                                                else{

                                                                    ?> 

                                                                        <?php

                                                                            $reqP='SELECT * from  `'.$nomtableDesignation.'` where designation="'.$ligne['designation'].'"';

                                                                            $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                                                            if(mysql_num_rows($resP)){

                                                                            $produit = mysql_fetch_assoc($resP);

                                                                                if(($produit["uniteStock"]!='Article')&&($produit["uniteStock"]!='article')){

                                                                                    ?> 

                                                                                        <select name="uniteVente" <?php echo  "id=uniteVente".$ligne['numligne'].""; ?> class="form-control filedReadonly<?= $pagnet['idPagnet'] ; ?>" onchange="modif_UniteStock(this.value)" >  

                                                                                            <?php

                                                                                                $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                                                                                                $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                                                                                                $t_stock = mysql_fetch_array($res_t) ;

                                                                                                $quantiteCourant=$t_stock[0] - ($produit['nbreArticleUniteStock'] * $ligne['quantite']);

                                                                                                //if($quantiteCourant >= 0){

                                                                                                    if(($ligne["unitevente"]!='Article')&&($ligne["unitevente"]!='article')){

                                                                                                        echo '<option value="'.$produit["uniteStock"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'">'.$produit["uniteStock"].'</option>';

                                                                                                        echo '<option id="default'.$ligne["numligne"].'"  value="Article§'.$ligne["numligne"].'§'.$pagnet['idPagnet'].'">Article</option>';

                                                                                                    }

                                                                                                    else{

                                                                                                        echo '<option id="default'.$ligne["numligne"].'" value="Article§'.$ligne["numligne"].'§'.$pagnet['idPagnet'].'">Article</option>';

                                                                                                        echo '<option value="'.$produit["uniteStock"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'">'.$produit["uniteStock"].'</option>';

                                                                                                    }

                                                                                                /*    

                                                                                                }

                                                                                                else{

                                                                                                    echo '<option value="Article§'.$ligne["numligne"].'§'.$pagnet['idPagnet'].'">Article</option>';                                

                                                                                                }

                                                                                                */

                                                                                            

                                                                                            ?> 

                                                                                        </select>  

                                                                                    <?php 

                                                                                }

                                                                                else{

                                                                                    ?>

                                                                                        <select name="uniteVente" <?php echo  "id=uniteVente".$ligne['numligne'].""; ?> class="form-control filedReadonly<?= $pagnet['idPagnet'] ; ?>" onchange="modif_UniteStock(this.value)" >

                                                                                            <option <?php echo  "value=Article§".$ligne['numligne'].'§'.$pagnet['idPagnet']." "; ?>>Article</option>

                                                                                            

                                                                                        </select>

                                                                                    <?php 

                                                                                }

                                                                            }

                                                                        ?> 

                                                                    <?php 

                                                                }

                                                            }

                                                            else if($ligne['classe']!=0 && $ligne['classe']!=2){?>

                                                                <?php echo $ligne['unitevente']; ?>

                                                                <?php 

                                                            }

                                                        ?> 

                                                    </td>

                                                    <td>

                                                    <?php if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1){ ?>

                                                            <input <?php echo  "id=prixUniteStock".$ligne['numligne'].""; ?> class="prixunitevente form-control filedReadonly<?= $pagnet['idPagnet'] ; ?>" style="width: 70%"  type="number" <?php echo  "id=prixunitevente".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['prixunitevente'].""; ?>

                                                                    onkeyup="modif_Prix(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" >

                                                    <?php }

                                                    else if ($ligne['classe']==5 || $ligne['classe']==7) { ?>

                                                        <input  <?php echo  "id=prixUniteStock".$ligne['numligne'].""; ?> class="prixunitevente form-control filedReadonly<?= $pagnet['idPagnet'] ; ?>" style="width: 70%"  type="number" <?php echo  "id=prixunitevente".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['prixunitevente'].""; ?>

                                                                    onkeyup="modif_Prix(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" >

                                                    <?php }

                                                    else { ?>

                                                    <input disabled="true" <?php echo  "id=prixUniteStock".$ligne['numligne'].""; ?> class="prixunitevente form-control filedReadonly<?= $pagnet['idPagnet'] ; ?>" style="width: 70%"  type="number" <?php echo  "id=prixunitevente".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['prixunitevente'].""; ?>

                                                                    onkeyup="modif_Prix(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" >

                                                    <?php } ?>

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

                                                                            <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                            <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                            <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                            <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                            <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                        </div>

                                                                        <div class="modal-footer">

                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                            <button type="button" name="btnRetourAvant" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourAvant">Confirmer</button>

                                                                        </div>

                                                                    </form>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </td>

                                                </tr>

                                                <?php  

                                            }  ?>

                                        </tbody>

                                    </table>

                                    <div id="compteContent<?= $pagnet['idPagnet'];?>">
                                        <?php foreach ($_SESSION['cptChoiceArray'] as $choice) { 
                                            foreach ($choice as $key) {
                                                $items = explode('-', $key);
                                                $idPanier = $items[0];
                                                $idCompte = $items[1];
                                                $montant = $items[2];

                                                if($pagnet['idPagnet'] == $idPanier ){
                                                    
                                                    $sqlGetCompte3="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$idCompte;

                                                    $resPay3 = mysql_query($sqlGetCompte3) or die ("persoonel requête 2".mysql_error());

                                                    $cpt = mysql_fetch_array($resPay3);

                                                    echo "<br>".strtoupper($cpt['nomCompte'])." : ".$montant;
                                                } 
                                            } 
                                        } ?>
                                    </div>

                                      

                                    <div id="footerContent<?= $pagnet['idPagnet'];?>" style="display:none;">

                                        <div>

                                            <?php echo  '********************************************* <br/>'; ?>

                                            <?php echo  'TOTAL : <span id="total_p'.$pagnet['idPagnet'].'"></span> <br/>'; ?>

                                        </div>

                                        <div>

                                            <?php  echo 'Remise : <span id="remise_p'.$pagnet['idPagnet'].'"></span><br/>';?>                                            

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

                    else if($pagnet['type'] == 10){?>

                        <div class="panel panel-warning">

                            <div class="panel-heading">

                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                    <div class="right-arrow pull-right">+</div>

                                    <a class="row" href="#"> 

                                        <span class="noImpr col-md-2 col-sm-2 col-xs-12"> Panier <?php echo ': En cours...'; ?> </span>

                                        <span class="noImpr col-md-3 col-sm-3 col-xs-12">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                        <?php	$sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";

                                                $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                                                $TotalT = mysql_fetch_array($resT) ;

                                                ?>

                                        <?php if($_SESSION['Pays']=='Canada'){  

                                                $sqlTP="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";

                                                $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());

                                                $TotalTvaP = mysql_fetch_array($resTP) ;

                                                $sqlTR="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";

                                                $resTR = mysql_query($sqlTR) or die ("persoonel requête 2".mysql_error());

                                                $TotalTvaR = mysql_fetch_array($resTR) ;

                                        ?> 

                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Sous-Total : <span <?php echo  "id=somme_TotalCN".$pagnet['idPagnet'] ; ?> ><?php echo number_format((($TotalT[0]-$pagnet['remise']) * $_SESSION['devise']), 2, ',', ' '); ?> </span></span>

                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" > Total : <span <?php echo  "id=somme_ApayerCN".$pagnet['idPagnet'] ; ?> ><?php echo number_format(((($TotalT[0] + $TotalTvaP[0] + $TotalTvaR[0] )-$pagnet['remise']) * $_SESSION['devise']), 2, ',', ' ') ; ?> </span></span>

                                        <?php }

                                        else{   ?>

                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total panier: <span <?php echo  "id=somme_Total".$pagnet['idPagnet']; ?> ><?php echo $TotalT[0]; ?>  </span></span>

                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total à payer: <span <?php echo  "id=somme_Apayer".$pagnet['idPagnet'] ; ?> ><?php echo $TotalT[0]; ?> </span></span>                 

                                        <?php } ?> 

                                    </a>

                                </h4>

                            </div>

                            <div <?php echo "id=pagnet".$pagnet['idPagnet']."" ; ?> class="panel-collapse collapse in panelVente" > 

                                <div class="panel-body" >

                                    <div class="cache_btn_Terminer row">

                                        <!--*******************************Debut Ajouter Ligne****************************************-->

                                        <form  class="form-inline noImpr ajouterProdForm col-md-4 col-sm-4 col-xs-12" id="ajouterProdFormB<?=$pagnet['idPagnet'];?>" onsubmit="return false" >

                                            <input type="text" class="inputbasic form-control codeBarreLigneVt" name="codeBarre" id="panier_<?=$pagnet['idPagnet'];?>" data-idPanier="<?= $pagnet['idPagnet'];?>" style="width:100%;" autofocus="" autocomplete="off"  required />

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo "value=".$pagnet['idPagnet']."" ; ?>>

                                                <!-- <span id="reponseV"></span> -->

                                            <!-- <button type="submit" name="btnEnregistrerCodeBarre"

                                            id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs " ><span class="glyphicon glyphicon-plus" ></span>

                                            </button> -->

                                        </form>

                                        <!--*******************************Fin Ajouter Ligne****************************************-->

                                        <div class="col-md-8 col-sm-8 col-xs-12 content2">

                                            <!--*******************************Debut Terminer Pagnet****************************************-->

                                            <form class="form-inline noImpr" id="factForm" method="post">

                                                <input type="number" <?php echo "id=val_remise".$pagnet['idPagnet'] ; ?> name="remise" onkeyup="modif_Remise(this.value, <?php echo  $pagnet['idPagnet']; ?>)"

                                                class="remise form-control col-md-2 col-sm-2 col-xs-3" placeholder="Remise....">

                                    

                                                <input type="hidden" name="idPagnet"   <?php echo  "value=".$pagnet['idPagnet']."" ; ?>>

                                                <input type="hidden" name="totalp" <?php echo  "id=totalp".$pagnet['idPagnet']."" ; ?> value="<?php echo $pagnet['totalp']; ?>" >

                                                <!-- <div class="btnAction col-md-1 col-xs-12"> -->

                                                    <button type="boutton" style="" name="btnImprimerProforma" <?php echo  "id=btnImprimerFacture".$pagnet['idPagnet']."" ; ?> class="btn btn-success btn_Termine_Panier btn_Termine_Panier<?= $pagnet['idPagnet'] ; ?> terminer" data-toggle="modal" ><span class="glyphicon glyphicon-ok"></span></button>

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

                                                                <button type="button" name="btnAnnulerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnAnnulerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-annulerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                            <!--*******************************Fin Annuler Pagnet****************************************-->

                                        </div>

                                    </div>



                                    <table  <?php echo  "id=tablePanier".$pagnet['idPagnet']."" ; ?> class="tabPanier table"  width="100%" >

                                        <thead>

                                            <tr>

                                                <th>Référence</th>

                                                <th>Quantité</th>

                                                <th class="hidden-sm hidden-xs">Unité vente</th>

                                                <th>Prix Unité vente</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                        <?php

                                            $sql8="SELECT * FROM `".$nomtableLigne."`  l

                                            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation 

                                            where l.idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                            $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                            while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                <tr>

                                                    <td class="designation">

                                                        <?php

                                                            if($_SESSION['Pays']=='Canada' && $ligne['tva'] == 1){ 

                                                                echo '<span style="font-size:16px;color:black" class="glyphicon glyphicon-star"> </span>&nbsp';

                                                            }

                                                            echo $ligne['designation'];

                                                        ?>

                                                    </td>

                                                    <td>

                                                            <?php if (($ligne['unitevente']=='Transaction') || ($ligne['unitevente']=='Facture') || ($ligne['unitevente']=='Credit')) { ?>

                                                                    

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

                                                                    

                                                                            if(($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")){

                                                                                ?>  

                                                                                    <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                                onkeyup="modif_QuantiteRP(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  $designation['nbreArticleUniteStock']; ?>,<?php echo  $stock['quantiteStockinitial']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                        >

                                                                                <?php

                                                                            }

                                                                            else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){

                                                                                ?>  

                                                                                    <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                                onkeyup="modif_QuantiteRP(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  '1'; ?>,<?php echo  $stock['quantiteStockinitial']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                        >

                                                                            <?php }

                                                                        }

                                                                        else{

                                                                            $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

                                                                            $resD=mysql_query($sqlD) or die ("select Designation impossible =>".mysql_error());

                                                                            $designation = mysql_fetch_assoc($resD);

                                                                    

                                                                            if(($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")){

                                                                                ?>  

                                                                                    <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                                onkeyup="modif_QuantiteSD(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                        >

                                                                                <?php

                                                                            }

                                                                            else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){

                                                                                ?>  

                                                                                    <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                                onkeyup="modif_QuantiteSD(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                        >

                                                                            <?php }



                                                                        }

                                                                    }

                                                                    if($ligne['classe']==1 || $ligne['classe']==2) { ?> 

                                                                        <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                        onkeyup="modif_QuantiteSD(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                >

                                                                    <?php  

                                                                    }

                                                                    if($ligne['classe']==5 || $ligne['classe']==7 ) {?>

                                                                        <?php echo 'Montant'; ?>

                                                                    <?php }

                                                            } ?>

                                                    </td>

                                                    <td class="unitevente "> 

                                                        <?php

                                                            if($ligne['classe']==0){

                                                                if($ligne['idStock']!=0){?>  

                                                                    <?php echo $ligne['unitevente']; ?>       

                                                                <?php 

                                                                }

                                                                else{

                                                                    ?> 

                                                                        <?php

                                                                            if(($ligne["unitevente"]!='Article')&&($ligne["unitevente"]!='article')){

                                                                                ?> 

                                                                                    <select  name="uniteVente" class="form-control" onchange="modif_UniteStock(this.value)" >

                                                                                        <?php

                                                                                            $reqP='SELECT * from  `'.$nomtableStock.'` where designation="'.$ligne['designation'].'"';

                                                                                            $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                                                                            if(mysql_num_rows($resP)){

                                                                                                $produit = mysql_fetch_assoc($resP);

                                                                                                if(($produit["uniteStock"]!='Article')&&($produit["uniteStock"]!='article')){

                                                                                                    echo '<option value="'.$produit["uniteStock"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'">'.$produit["uniteStock"].'</option>';

                                                                                                }

                                                        

                                                                                            }

                                                                                        ?> 

                                                                                        <option <?php echo  "value=Article§".$ligne['numligne'].'§'.$pagnet['idPagnet']." "; ?>>Article</option>   

                                                                                    </select>  

                                                                                <?php 

                                                                            }

                                                                            else{

                                                                                ?>

                                                                                    <select  name="uniteVente" class="form-control" onchange="modif_UniteStock(this.value)" >

                                                                                        <option <?php echo  "value=Article§".$ligne['numligne'].'§'.$pagnet['idPagnet']." "; ?>>Article</option>

                                                                                        <?php

                                                                                            $reqP='SELECT * from  `'.$nomtableStock.'` where designation="'.$ligne['designation'].'"';

                                                                                            $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                                                                            if(mysql_num_rows($resP)){

                                                                                                $produit = mysql_fetch_assoc($resP);

                                                                                                if(($produit["uniteStock"]!='Article')&&($produit["uniteStock"]!='article')){

                                                                                                    echo '<option value="'.$produit["uniteStock"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'">'.$produit["uniteStock"].'</option>';

                                                                                                }

                                                        

                                                                                            }

                                                                                        ?>    

                                                                                    </select>

                                                                                <?php 

                                                                            }

                                                                        ?> 

                                                                    <?php 

                                                                }

                                                            }

                                                            else{?>

                                                                <?php echo $ligne['unitevente']; ?>

                                                                <?php 

                                                            }

                                                        ?> 

                                                    </td>

                                                    <td>

                                                        <input <?php echo  "id=prixUniteStock".$ligne['numligne'].""; ?> class="prixunitevente form-control" style="width: 70%"  type="number" <?php echo  "id=prixunitevente".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['prixunitevente'].""; ?>

                                                                    onkeyup="modif_Prix(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" >

                                                    </td>

                                                    <td>

                                                        <button type="submit" 	 class="btn btn-warning pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnAvant_ligne".$ligne['numligne'] ; ?>>

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

                                                                            <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                            <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                            <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                            <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                            <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                        </div>

                                                                        <div class="modal-footer">

                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                            <button type="button" name="btnRetourAvant" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourAvant">Confirmer</button>

                                                                        </div>

                                                                    </form>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </td>

                                                </tr>

                                                <?php  

                                            }  ?>



                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                        <?php

                    } 

                    else if($pagnet['type'] == 20){?>

                        <div class="panel panel-default">

                            <div class="panel-heading">

                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                    <div class="right-arrow pull-right">+</div>

                                    <a href="#"> Panier <?php echo ': En cours ...'; ?>

                                        <span class="spanDate noImpr"> </span>

                                        <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                    </a>

                                </h4>

                            </div>

                            <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?> class="panel-collapse collapse in" > 

                                <div class="panel-body" >

                                    <!--*******************************Debut Ajouter Ligne****************************************-->

                                        <form  class="form-inline pull-left noImpr ajouterProdForm" id="ajouterProdFormB" onsubmit="return false" >

                                            <input type="text" size="50" class="inputbasic form-control" name="codeBarre" id="codeBarreLigneVt" data-idPanier="<?= $pagnet['idPagnet'];?>" autofocus="" autocomplete="off"  required />

                                            <input type="hidden" name="idPagnet" id="idPagnet"

                                                <?php echo  "value=".$pagnet['idPagnet']."" ; ?> />

                                                <!-- <span id="reponseV"></span>

                                            <button type="submit" name="btnEnregistrerCodeBarre"

                                            id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs " ><span class="glyphicon glyphicon-plus" ></span>

                                            </button> -->

                                        </form>

                                    <!--*******************************Fin Ajouter Ligne****************************************-->



                                    <!--*******************************Debut Annuler Pagnet****************************************-->

                                        <button tabindex="1" type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_ann_pagnet".$pagnet['idPagnet'] ; ?>>

                                                <span class="glyphicon glyphicon-remove"></span>Annuler

                                        </button>



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

                                                            <button type="button" name="btnAnnulerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnAnnulerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-annulerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                        </div>

                                                    </form>

                                                </div>

                                            </div>

                                        </div>

                                    <!--*******************************Fin Annuler Pagnet****************************************-->



                                    <!--*******************************Debut Terminer Pagnet****************************************-->

                                        <form class="form-inline pull-right noImpr" style="margin-right:20px;" target="_blank" method="post" action="pdfCodeBarre.php">

                                            <input type="hidden" name="idPagnet"   <?php echo  "value=".$pagnet['idPagnet']."" ; ?>>

                                            <input type="hidden" name="totalp" <?php echo  "id=totalp".$pagnet['idPagnet']."" ; ?> value="<?php echo $pagnet['totalp']; ?>" >

                                            <button type="submit" style="margin-left:15px;" name="btnImprimerCodeBarre" <?php echo  "id=btnImprimerCodebarre".$pagnet['idPagnet']."" ; ?> class="btn btn-success pull-right" data-toggle="modal"  ><span class="glyphicon glyphicon-lock"></span>Terminer</button>

                                        </form>

                                    <!--*******************************Fin Terminer Pagnet****************************************-->



                                    <table  <?php echo  "id=tablePanier".$pagnet['idPagnet']."" ; ?> class="tabPanier table"  width="100%" >

                                        <thead>

                                            <tr>

                                                <th>Référence</th>

                                                <th class="hidden-sm hidden-xs">Prix Unité vente</th>

                                                <th>Code barre</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            <?php

                                                $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                    <tr>

                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                        <td class="hidden-sm hidden-xs"> <?php echo $ligne['prixunitevente']; ?> </td>

                                                        <td > 

                                                            <?php

                                                                $sql9="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation']." ";

                                                                $res9 = mysql_query($sql9) or die ("persoonel requête 2".mysql_error());

                                                                $code = mysql_fetch_assoc($res9);

                                                                if($code['codeBarreDesignation']!='' && $code['codeBarreDesignation']!=null){

                                                                    echo $code['codeBarreDesignation'];

                                                                }

                                                                else{

                                                                    echo 'Neant';

                                                                }

                                                            ?> 

                                                        </td>

                                                        <td>

                                                            <button type="submit" 	 class="btn btn-warning pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnAvant_ligne".$ligne['numligne'] ; ?>>

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

                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                            </div>

                                                                            <div class="modal-footer">

                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                <button type="button" name="btnRetourAvant" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourAvant">Confirmer</button>

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

                                </div>

                            </div>

                        </div>

                        <?php

                    } 

                    else{?>

                        <div class="panel panel-danger">

                            <div class="panel-heading">

                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                    <div class="right-arrow pull-right">+</div>

                                    <a class="row" href="#"> 

                                        <span class="noImpr col-md-2 col-sm-2 col-xs-12"> Panier <?php echo ': En cours...'; ?> </span>

                                        <span class="noImpr col-md-3 col-sm-3 col-xs-12">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                        <?php	$sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";

                                                $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                                                $TotalT = mysql_fetch_array($resT) ;

                                                ?>

                                        <?php if($_SESSION['Pays']=='Canada'){   

                                                $sqlTP="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";

                                                $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());

                                                $TotalTvaP = mysql_fetch_array($resTP) ;

                                                $sqlTR="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";

                                                $resTR = mysql_query($sqlTR) or die ("persoonel requête 2".mysql_error());

                                                $TotalTvaR = mysql_fetch_array($resTR) ;

                                        ?> 

                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Sous-Total : <span <?php echo  "id=somme_TotalCN".$pagnet['idPagnet'] ; ?> ><?php echo number_format((($TotalT[0]-$pagnet['remise']) * $_SESSION['devise']), 2, ',', ' '); ?> </span></span>

                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" > Total : <span <?php echo  "id=somme_ApayerCN".$pagnet['idPagnet'] ; ?> ><?php echo number_format(((($TotalT[0] + $TotalTvaP[0] + $TotalTvaR[0] )-$pagnet['remise']) * $_SESSION['devise']), 2, ',', ' ') ; ?> </span></span>

                                        <?php }

                                        else{   ?>

                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total panier: <span <?php echo  "id=somme_Total".$pagnet['idPagnet']; ?> ><?php echo $TotalT[0]; ?>  </span></span>

                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total à payer: <span <?php echo  "id=somme_Apayer".$pagnet['idPagnet'] ; ?> ><?php echo $TotalT[0]; ?> </span></span>                 

                                        <?php } ?> 

                                    </a>

                                </h4>

                            </div>

                            <div <?php echo "id=pagnet".$pagnet['idPagnet']."" ; ?> class="panel-collapse collapse in panelVente" > 

                                <div class="panel-body" >

                                    <div class="cache_btn_Terminer row">

                                        <!--*******************************Debut Ajouter Ligne****************************************-->

                                        <form  class="form-inline noImpr ajouterProdForm col-md-4 col-sm-4 col-xs-12" id="ajouterProdFormB<?=$pagnet['idPagnet'];?>" onsubmit="return false" >

                                            <input type="text" class="inputbasic form-control codeBarreLigneVt" name="codeBarre" id="panier_<?=$pagnet['idPagnet'];?>" data-idPanier="<?= $pagnet['idPagnet'];?>" style="width:100%;" autofocus="" autocomplete="off"  required />

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo "value=".$pagnet['idPagnet']."" ; ?>>

                                                <!-- <span id="reponseV"></span> -->

                                            <!-- <button type="submit" name="btnEnregistrerCodeBarre"

                                            id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs " ><span class="glyphicon glyphicon-plus" ></span>

                                            </button> -->

                                        </form>

                                        <!--*******************************Fin Ajouter Ligne****************************************-->

                                        <div class="col-md-8 col-sm-8 col-xs-12 content2">

                                            <!--*******************************Debut Terminer Pagnet****************************************-->

                                            <form class="form-inline noImpr" id="factForm" method="post">

                                                <input type="number" <?php echo "id=val_remise".$pagnet['idPagnet'] ; ?> name="remise" onkeyup="modif_Remise(this.value, <?php echo  $pagnet['idPagnet']; ?>)"

                                                class="remise form-control col-md-2 col-sm-2 col-xs-3" placeholder="Remise....">

                                                

                                                <?php if ($_SESSION['compte']==1) { ?>

                                                <select class="compte <?= $pagnet['idPagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3"  data-idPanier="<?= $pagnet['idPagnet'];?>" name="compte" onchange="showImageCompte(this.value,<?= $pagnet['idPagnet'];?>)"   <?php echo  "id=compte".$pagnet['idPagnet'] ; ?>>

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

                                                        <option id="idImageCompte<?= $key['idCompte'];?>"  data-image="<?= $key['image'];?>"  value="<?= $key['idCompte'];?>"><?= strtoupper($key['nomCompte']);?></option>

                                                    <?php } ?>

                                                </select>

                                                <?php } ?>



                                                <?php if($_SESSION['Pays']=='Canada'){  ?> 

                                                    <input type="number" name="versement" id="versement<?= $pagnet['idPagnet'];?>" data-idPanier="<?= $pagnet['idPagnet'];?>" <?= ((@$pagnet['idCompte']!=0 && @$pagnet['idCompte']!=1)) ? 'style="display:none;"' : '';?>

                                                    class="versement form-control col-md-2 col-sm-2 col-xs-3" placeholder="Espèce...">



                                                <?php }

                                                else{   ?>

                                                    <input type="number" name="versement" id="versement<?= $pagnet['idPagnet'];?>" data-idPanier="<?= $pagnet['idPagnet'];?>" <?= ((@$pagnet['idCompte']!=0 && @$pagnet['idCompte']!=1)) ? 'style="display:none;"' : '';?>

                                                    class="versement form-control col-md-2 col-sm-2 col-xs-3" placeholder="Espèce...">



                                                <?php } 

                                                

                                                    if ($pagnet['type']=='30') {

                                                        

                                                        $sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$pagnet['idClient']."";

                                                        $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());

                                                        $client = mysql_fetch_assoc($res3);

                                                ?>

                                                        <input type="text" class="client clientInput form-control col-md-2 col-sm-2 col-xs-3" data-type="simple" data-idPanier="<?= $pagnet['idPagnet'];?>" name="clientInput" id="clientInput<?= $pagnet['idPagnet'];?>" autocomplete="off" value="<?= $client['prenom']." ".$client['nom']; ?>"/>



                                                <?php

                                                        # code...

                                                    } else {

                                                        # code...                                                            

                                                ?> 

                                                        <input type="text" class="client clientInput form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-type="simple" data-idPanier="<?= $pagnet['idPagnet'];?>" name="clientInput" id="clientInput<?= $pagnet['idPagnet'];?>" autocomplete="off" placeholder="Client"/>



                                                <?php   }  ?>  



                                                <input type="hidden" name="idPagnet"   <?php echo  "value=".$pagnet['idPagnet']."" ; ?>>

                                                <input type="hidden" name="totalp" <?php echo  "id=totalp".$pagnet['idPagnet']."" ; ?> value="<?php echo $pagnet['totalp']; ?>" >

                                                <!-- <div class="btnAction col-md-1 col-xs-12"> -->

                                                <button type="button" style="" name="btnImprimerFacture" data-idPanier="<?= $pagnet['idPagnet'];?>" <?php echo  "id=btnImprimerFacture".$pagnet['idPagnet']."" ; ?> class="btn btn-success btn_Termine_Panier btn_Termine_Panier<?= $pagnet['idPagnet'] ; ?> terminer" data-toggle="modal" ><span class="glyphicon glyphicon-ok"></span></button>

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

                                                                <button type="button" name="btnAnnulerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnAnnulerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-annulerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                            <!--*******************************Fin Annuler Pagnet****************************************-->

                                        </div>

                                    </div>



                                    <table  <?php echo  "id=tablePanier".$pagnet['idPagnet']."" ; ?> class="tabPanier table"  width="100%" >

                                        <thead>

                                            <tr>

                                                <th>Référence</th>

                                                <th>Quantité</th>

                                                <th class="hidden-sm hidden-xs">Unité vente</th>

                                                <th>Prix Unité vente</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            <?php

                                                $sql8="SELECT * FROM `".$nomtableLigne."`  l

                                                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation 

                                                where l.idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                    <tr>

                                                        <td class="designation">

                                                            <?php

                                                                if($_SESSION['Pays']=='Canada' && $ligne['tva'] == 1){ 

                                                                    echo '<span style="font-size:16px;color:black" class="glyphicon glyphicon-star"> </span>&nbsp';

                                                                }

                                                                echo $ligne['designation'];

                                                            ?>

                                                        </td>

                                                        <td>

                                                            <?php if (($ligne['unitevente']=='Transaction') || ($ligne['unitevente']=='Facture') || ($ligne['unitevente']=='Credit')) { ?>

                                                                    

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

                                                                    

                                                                            if(($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")){

                                                                                ?>  

                                                                                    <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                                onkeyup="modif_QuantiteRP(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  $designation['nbreArticleUniteStock']; ?>,<?php echo  $stock['quantiteStockinitial']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                        >

                                                                                <?php

                                                                            }

                                                                            else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){

                                                                                ?>  

                                                                                    <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                                onkeyup="modif_QuantiteRP(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  '1'; ?>,<?php echo  $stock['quantiteStockinitial']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                        >

                                                                            <?php }

                                                                        }

                                                                        else{

                                                                            $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

                                                                            $resD=mysql_query($sqlD) or die ("select Designation impossible =>".mysql_error());

                                                                            $designation = mysql_fetch_assoc($resD);

                                                                    

                                                                            if(($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")){

                                                                                ?>  

                                                                                    <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                                onkeyup="modif_QuantiteSD(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                        >

                                                                                <?php

                                                                            }

                                                                            else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){

                                                                                ?>  

                                                                                    <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                                onkeyup="modif_QuantiteSD(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                        >

                                                                            <?php }



                                                                        }

                                                                    }

                                                                    if($ligne['classe']==1 || $ligne['classe']==2) { ?> 

                                                                        <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>

                                                                        onkeyup="modif_QuantiteSD(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                >

                                                                    <?php  

                                                                    }

                                                                    if($ligne['classe']==5 || $ligne['classe']==7 ) {?>

                                                                        <?php echo 'Montant'; ?>

                                                                    <?php }

                                                            } ?>

                                                        </td>

                                                        <td class="unitevente "> 

                                                            <?php

                                                                if($ligne['classe']==0){

                                                                    if($ligne['idStock']!=0){?>  

                                                                        <?php echo $ligne['unitevente']; ?>       

                                                                    <?php 

                                                                    }

                                                                    else{

                                                                        ?> 

                                                                            <?php

                                                                                if(($ligne["unitevente"]!='Article')&&($ligne["unitevente"]!='article')){

                                                                                    ?> 

                                                                                        <select  name="uniteVente" class="form-control" onchange="modif_UniteStock(this.value)" >

                                                                                            <?php

                                                                                                $reqP='SELECT * from  `'.$nomtableStock.'` where designation="'.$ligne['designation'].'"';

                                                                                                $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                                                                                if(mysql_num_rows($resP)){

                                                                                                    $produit = mysql_fetch_assoc($resP);

                                                                                                    if(($produit["uniteStock"]!='Article')&&($produit["uniteStock"]!='article')){

                                                                                                        echo '<option value="'.$produit["uniteStock"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'">'.$produit["uniteStock"].'</option>';

                                                                                                    }

                                                            

                                                                                                }

                                                                                            ?> 

                                                                                            <option <?php echo  "value=Article§".$ligne['numligne'].'§'.$pagnet['idPagnet']." "; ?>>Article</option>   

                                                                                        </select>  

                                                                                    <?php 

                                                                                }

                                                                                else{

                                                                                    ?>

                                                                                        <select  name="uniteVente" class="form-control" onchange="modif_UniteStock(this.value)" >

                                                                                            <option <?php echo  "value=Article§".$ligne['numligne'].'§'.$pagnet['idPagnet']." "; ?>>Article</option>

                                                                                            <?php

                                                                                                $reqP='SELECT * from  `'.$nomtableStock.'` where designation="'.$ligne['designation'].'"';

                                                                                                $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                                                                                if(mysql_num_rows($resP)){

                                                                                                    $produit = mysql_fetch_assoc($resP);

                                                                                                    if(($produit["uniteStock"]!='Article')&&($produit["uniteStock"]!='article')){

                                                                                                        echo '<option value="'.$produit["uniteStock"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'">'.$produit["uniteStock"].'</option>';

                                                                                                    }

                                                            

                                                                                                }

                                                                                            ?>    

                                                                                        </select>

                                                                                    <?php 

                                                                                }

                                                                            ?> 

                                                                        <?php 

                                                                    }

                                                                }

                                                                else{?>

                                                                    <?php echo $ligne['unitevente']; ?>

                                                                    <?php 

                                                                }

                                                            ?> 

                                                        </td>

                                                        <td>

                                                            <input <?php echo  "id=prixUniteStock".$ligne['numligne'].""; ?> class="prixunitevente form-control" style="width: 70%"  type="number" <?php echo  "id=prixunitevente".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['prixunitevente'].""; ?>

                                                                        onkeyup="modif_Prix(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" >

                                                        </td>

                                                        <td>

                                                            <button type="submit" 	 class="btn btn-warning pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnAvant_ligne".$ligne['numligne'] ; ?>>

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

                                                                                <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                            </div>

                                                                            <div class="modal-footer">

                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                <button type="button" name="btnRetourAvant" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourAvant">Confirmer</button>

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

                                </div>

                            </div>

                        </div>

                        <?php

                    }?>

                <?php   } ?>

            <!-- Fin Boucle while concernant les Paniers en cours (2 aux maximum) -->



            <?php if($_SESSION['proprietaire']==1){ ?> 

                <!-- Debut Boucle while concernant les Paniers Vendus -->

                <?php $n=$nbPaniers - (($currentPage * 10) - 10); 

                while ($pagnet = mysql_fetch_assoc($resP1)) {   ?>



                <?php	$idmax=mysql_result($resA,0); ?>



                    <?php

                    $sql="SELECT * FROM `".$nomtableLigne."` l

                    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation 

                    where idPagnet=".$pagnet['idPagnet']." ";

                    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                    $ligne = mysql_fetch_assoc($res) ;

                    if($ligne!=null){

                        if(($ligne['classe']==0 || $ligne['classe']==1) && ($pagnet['type']==0 || $pagnet['type']==30) ){?>

                            <div class="panel <?= ($pagnet['idClient']==0) ? 'panel-success' : 'panel-info'?>">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Panier <?php echo $n; ?>

                                            <span class="spanDate noImpr"> </span>

                                            <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                            <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                    $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                                                    $TotalT = mysql_fetch_array($resT);

                                                    $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                    $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());

                                                    $TotalP = mysql_fetch_array($resP);



                                                    $sql="SELECT * from `aaa-utilisateur` where idutilisateur='".$pagnet['iduser']."' ";

                                                    $res=mysql_query($sql);

                                                    $user=mysql_fetch_array($res);

                                            ?>

                                            <?php if($_SESSION['Pays']=='Canada'){  

                                                $sqlTP="SELECT apayerPagnetTvaP FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                $resTP=mysql_query($sqlTP) or die ("select stock impossible =>".mysql_error());

                                                $TotalTvaP = mysql_fetch_array($resTP);

                                                $sqlTR="SELECT apayerPagnetTvaR FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                $resTR=mysql_query($sqlTR) or die ("select stock impossible =>".mysql_error());

                                                $TotalTvaR = mysql_fetch_array($resTR);

                                            ?> 

                                                <span class="spanTotal noImpr" >Sous-Total: <span ><?php echo number_format(($TotalP[0] * $_SESSION['devise']), 2, ',', ' ') ; ?> </span></span>

                                                <span class="spanTotal noImpr" >Total : <span ><?php echo number_format((($TotalP[0] + $TotalTvaP[0] + $TotalTvaR[0]) * $_SESSION['devise']), 2, ',', ' ') ; ?> </span></span>

                                            <?php }

                                            else{   ?>

                                                <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                                <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                            <?php } ?> 

                                            <?php if($pagnet['identifiantP']==NULL || $pagnet['identifiantP']=='' ){?>
                                                <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>  
                                            <?php }  
                                            else{   ?>    
                                                <span class="spanDate noImpr"> Facture : #<?php echo substr($pagnet['identifiantP'], 8, 9);?></span>   
                                            <?php } ?>

                                            <span class="spanDate noImpr"> <?php echo substr(strtoupper($user['prenom']),0,3); ?></span>

                                        </a>

                                    </h4>

                                </div>

                                <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>

                                    <?php 

                                        if($idmax == $pagnet['idPagnet']){

                                            ?> class="panel-collapse collapse in" <?php

                                        }

                                        else  {

                                            ?> class="panel-collapse collapse " <?php

                                        } 

                                    ?>  >

                                    <div class="panel-body" >

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

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

                                                                <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                        

                                        <!--*******************************Debut Editer Pagnet****************************************-->

                                        <?php if ($_SESSION['proprietaire']==1 && $_SESSION['editionPanier']==1){ ?>

                                            <button type="button" class="btn btn-primary pull-left modeEditionBtnLoader btn_disabled_after_click" id="edit-<?= $pagnet['idPagnet'] ; ?>">

                                                <span class="glyphicon glyphicon-edit"></span> Editer<img src="images/loading-gif3.gif" class="img-load-editer" style="height: 30px;width: 30px;display:none;" alt="GIF" srcset="">

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

                                            <?php if ($_SESSION['caissier']==1){ ?>

                                            

                                                <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet".$pagnet['idPagnet'] ; ?>>

                                                    Facture

                                                </button>

                                            

                                            <?php } ?>



                                            <div class="modal fade" <?php echo  "id=msg_fact_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                <div class="modal-dialog">

                                                    <!-- Modal content-->

                                                    <div class="modal-content">

                                                        <div class="modal-header panel-primary">

                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                            <h4 class="modal-title">Informations Client</h4>

                                                        </div>

                                                        <form class=" noImpr"  method="post" action="pdfFactureEntreprise.php" target="_blank" >

                                                                <div class="modal-body">

                                                                    <div class="form-group">

                                                                        <label for="inputEmail3" class="control-label">Nom Complet </label>

                                                                        <input type="text" class="form-control" name="entreprise" required="" placeholder="Le nom complet ici...">

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

                                        <?php if ($_SESSION['caissier']==1){ ?>

                                        

                                            <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                Ticket de Caisse

                                            </button>

                                        

                                        <?php } ?>

                                        

                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                            method="post" action="barcodeFacture.php" >

                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                        </form>



                                        <table class="table ">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th>Référence</th>

                                                    <th>Quantité</th>

                                                    <th>Unité vente</th>

                                                    <th>Prix Unité vente</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php

                                                    $sql8="SELECT * FROM `".$nomtableLigne."`  l

                                                    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation 

                                                    where l.idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                    $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                    while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                        <tr>

                                                            <td class="designation">

                                                                <?php

                                                                    if($_SESSION['Pays']=='Canada' && $ligne['tva'] == 1){ 

                                                                        echo '<span style="font-size:16px;color:black" class="glyphicon glyphicon-star"> </span>&nbsp';

                                                                    }

                                                                    echo $ligne['designation'];

                                                                ?>

                                                            </td>

                                                            <td>

                                                                <?php if ($ligne['unitevente']=='Transaction'){ ?>

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

                                                            <td class="unitevente ">

                                                                <?php echo $ligne['unitevente']; ?>

                                                            </td>

                                                            <td>

                                                                <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>

                                                            </td>

                                                            <td>

                                                                <button  type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

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

                                                                                    <input type="hidden" name="unitevente" id="unitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                    <input type="hidden" name="prixunitevente" id="prixunitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                    <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                    <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                    <div class="row">

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Ancienne quantite </label>

                                                                                            <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        </div>

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Nouvelle quantite</label>

                                                                                            <input type="text" class="form-control inputRetourApres" name="quantite" data-numligne="<?= $ligne['numligne']; ?>" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        </div>

                                                                                    </div>

                                                                                </div>

                                                                                <div class="modal-footer">

                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                    <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres">Confirmer

                                                                                        <img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

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

                                            <?php if($_SESSION['Pays']=='Canada'){   ?> 

                                                <div>

                                                    <div>

                                                        <?php if ($pagnet['remise']!=0): ?>

                                                        <?php echo  'Total panier : '.number_format(($pagnet['totalp'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                        <?php endif; ?>

                                                    </div>

                                                    <div>

                                                        <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>

                                                            <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>

                                                        <?php endif; ?>

                                                    </div>

                                                    <div>

                                                        <?php echo  '********************************************* <br/>'; ?>

                                                        <?php echo  'Sous-total : '.number_format(($pagnet['apayerPagnet'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                    </div>

                                                    <div>

                                                        <?php echo  'TPS à 5% : '.number_format(($pagnet['apayerPagnetTvaP'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                    </div>

                                                    <div>

                                                        <?php echo  'TVQ à 9.975% : '.number_format(($pagnet['apayerPagnetTvaR'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                    </div>

                                                    <div>

                                                        <?php echo  '<b>Total : '.number_format((($pagnet['apayerPagnet'] + $pagnet['apayerPagnetTvaP'] + $pagnet['apayerPagnetTvaR'] ) * $_SESSION['devise']), 2, ',', ' ') .'</b><br/>'; ?>

                                                        <?php echo  '********************************************* <br/>'; ?>

                                                    </div>

                                                    <div>

                                                        <?php if($pagnet['versement']!=0): ?>

                                                            <?php  echo 'Espèces : '.$pagnet['versement'].'<br/>';?>

                                                            <?php  echo 'Rendu : '.number_format((($pagnet['versement'] - ( $pagnet['apayerPagnet'] + $pagnet['apayerPagnetTvaP'] + $pagnet['apayerPagnetTvaR'] )) * $_SESSION['devise']), 2, ',', ' ') .'<br/>';?>

                                                        <?php endif; ?>

                                                    </div>

                                                </div>

                                            <?php }

                                            else{   ?>

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

                                            <?php } ?> 

                                        <!--*******************************Fin Total Facture****************************************-->

                                    </div>

                                </div>

                            </div>

                        <?php

                        }

                        else if($ligne['classe']==0 && $pagnet['type']==11 ){?>

                            <div class="panel panel-warning">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Panier <?php echo $n; ?>

                                            <span class="spanDate noImpr"> </span>

                                            <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                            <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                    $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                                                    $TotalT = mysql_fetch_array($resT);

                                                    $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                    $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());

                                                    $TotalP = mysql_fetch_array($resP);



                                                    $sql="SELECT * from `aaa-utilisateur` where idutilisateur='".$pagnet['iduser']."' ";

                                                    $res=mysql_query($sql);

                                                    $user=mysql_fetch_array($res);

                                            ?>

                                            <?php if($_SESSION['Pays']=='Canada'){ 

                                                $sqlTP="SELECT apayerPagnetTvaP FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                $resTP=mysql_query($sqlTP) or die ("select stock impossible =>".mysql_error());

                                                $TotalTvaP = mysql_fetch_array($resTP);

                                                $sqlTR="SELECT apayerPagnetTvaR FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                $resTR=mysql_query($sqlTR) or die ("select stock impossible =>".mysql_error());

                                                $TotalTvaR = mysql_fetch_array($resTR);

                                            ?> 

                                                <span class="spanTotal noImpr" >Sous-Total: <span ><?php echo number_format(($TotalP[0] * $_SESSION['devise']), 2, ',', ' ') ; ?> </span></span>

                                                <span class="spanTotal noImpr" >Total : <span ><?php echo number_format((($TotalP[0] + $TotalTvaP[0] + $TotalTvaR[0]) * $_SESSION['devise']), 2, ',', ' ') ; ?> </span></span>

                                            <?php }

                                            else{   ?>

                                                <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                                <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                            <?php } ?> 

                                            <?php if($pagnet['identifiantP']==NULL || $pagnet['identifiantP']=='' ){?>
                                                <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>  
                                            <?php }  
                                            else{   ?>    
                                                <span class="spanDate noImpr"> Facture : #<?php echo substr($pagnet['identifiantP'], 8, 9);?></span>  
                                            <?php } ?>

                                            <span class="spanDate noImpr"> <?php echo substr(strtoupper($user['prenom']),0,3); ?></span>

                                        </a>

                                    </h4>

                                </div>

                                <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>

                                    <?php 

                                        if($idmax == $pagnet['idPagnet']){

                                            ?> class="panel-collapse collapse in" <?php

                                        }

                                        else  {

                                            ?> class="panel-collapse collapse " <?php

                                        }

                                    ?>  >

                                    <div class="panel-body" >

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

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

                                                                <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                        <!--*******************************Fin Retourner Pagnet****************************************-->



                                        <!--*******************************Debut Facture****************************************-->

                                        

                                            <?php if ($_SESSION['caissier']==1){ ?>

                                                

                                                <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet".$pagnet['idPagnet'] ; ?>>

                                                    Facture

                                                </button>

                                            

                                            <?php } ?>



                                            <div class="modal fade" <?php echo  "id=msg_fact_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                <div class="modal-dialog">

                                                    <!-- Modal content-->

                                                    <div class="modal-content">

                                                        <div class="modal-header panel-primary">

                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                            <h4 class="modal-title">Informations Client</h4>

                                                        </div>

                                                        <form class="form-inline noImpr"  method="post" action="pdfFacture.php" target="_blank" >

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

                                        <?php if ($_SESSION['caissier']==1){ ?>

                                            

                                            <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                Ticket de Caisse

                                            </button>

                                        

                                        <?php } ?>

                                        

                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                            method="post" action="barcodeFacture.php" >

                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                        </form>



                                        <table class="table ">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th>Référence</th>

                                                    <th>Quantité</th>

                                                    <th>Unité vente</th>

                                                    <th>Prix Unité vente</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php

                                                    $sql8="SELECT * FROM `".$nomtableLigne."`  l

                                                    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation 

                                                    where l.idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                    $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                    while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                        <tr>

                                                            <td class="designation">

                                                                <?php

                                                                    if($_SESSION['Pays']=='Canada' && $ligne['tva'] == 1){ 

                                                                        echo '<span style="font-size:16px;color:black" class="glyphicon glyphicon-star"> </span>&nbsp';

                                                                    }

                                                                    echo $ligne['designation'];

                                                                ?>

                                                            </td>

                                                            <td>

                                                                <?php if ($ligne['unitevente']=='Transaction'){ ?>

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

                                                            <td class="unitevente ">

                                                                <?php echo $ligne['unitevente']; ?>

                                                            </td>

                                                            <td>

                                                                <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>

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

                                                                                    <input type="hidden" name="unitevente" id="unitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                    <input type="hidden" name="prixunitevente" id="prixunitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                    <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                    <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                    <div class="row">

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Ancienne quantite </label>

                                                                                            <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        </div>

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Nouvelle quantite</label>

                                                                                            <input type="text" class="form-control inputRetourApres" name="quantite" data-numligne="<?= $ligne['numligne']; ?>" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        </div>

                                                                                    </div>

                                                                                </div>

                                                                                <div class="modal-footer">

                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                    <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres">Confirmer

                                                                                        <img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

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

                                        <?php if($_SESSION['Pays']=='Canada'){   ?> 

                                                <div>

                                                    <div>

                                                        <?php if ($pagnet['remise']!=0): ?>

                                                        <?php echo  'Total panier : '.number_format(($pagnet['totalp'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                        <?php endif; ?>

                                                    </div>

                                                    <div>

                                                        <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>

                                                            <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>

                                                        <?php endif; ?>

                                                    </div>

                                                    <div>

                                                        <?php echo  '********************************************* <br/>'; ?>

                                                        <?php echo  'Sous-total : '.number_format(($pagnet['apayerPagnet'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                    </div>

                                                    <div>

                                                        <?php echo  'TPS à 5% : '.number_format(($pagnet['apayerPagnetTvaP'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                    </div>

                                                    <div>

                                                        <?php echo  'TVQ à 9.975% : '.number_format(($pagnet['apayerPagnetTvaR'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                    </div>

                                                    <div>

                                                        <?php echo  '<b>Total : '.number_format((($pagnet['apayerPagnet'] + $pagnet['apayerPagnetTvaP'] + $pagnet['apayerPagnetTvaR'] ) * $_SESSION['devise']), 2, ',', ' ') .'</b><br/>'; ?>

                                                        <?php echo  '********************************************* <br/>'; ?>

                                                    </div>

                                                    <div>

                                                        <?php if($pagnet['versement']!=0): ?>

                                                            <?php  echo 'Espèces : '.$pagnet['versement'].'<br/>';?>

                                                            <?php  echo 'Rendu : '.number_format((($pagnet['versement'] - ( $pagnet['apayerPagnet'] + $pagnet['apayerPagnetTvaP'] + $pagnet['apayerPagnetTvaR'] )) * $_SESSION['devise']), 2, ',', ' ') .'<br/>';?>

                                                        <?php endif; ?>

                                                    </div>

                                                </div>

                                            <?php }

                                            else{   ?>

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

                                            <?php } ?> 

                                        <!--*******************************Fin Total Facture****************************************-->

                                    </div>

                                </div>

                            </div>

                        <?php

                        }

                        else if ($ligne['classe']==2 && $pagnet['type']==0 ) {?>

                            <div class="panel panel-danger">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Panier <?php echo $n; ?>

                                            <span class="spanDate noImpr"> </span>

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

                                <div   <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>

                                    <?php 

                                        if($idmax == $pagnet['idPagnet']){

                                            ?> class="panel-collapse collapse in" <?php

                                        }

                                        else  {

                                            ?> class="panel-collapse collapse " <?php

                                        }

                                     ?>  >

                                    <div class="panel-body" >

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

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

                                                                <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                            

                                        <?php if ($_SESSION['caissier']==1){ ?>

                                    

                                            <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                Ticket de Caisse

                                            </button>

                                        

                                        <?php } ?>

                                        

                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                            method="post" action="barcodeFacture.php" >

                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                        </form>



                                        <table class="table ">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th></th>

                                                    <th>Référence</th>

                                                    <th>Prix Unité vente</th>

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

                                                                <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>

                                                            </td>

                                                            <td>

                                                                <button type="submit" disabled="true"	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

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

                                                                                    <input type="hidden" name="unitevente" id="unitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                    <input type="hidden" name="prixunitevente" id="prixunitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                    <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                    <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                    <div class="row">

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Ancienne quantite </label>

                                                                                            <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        </div>

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Nouvelle quantite</label>

                                                                                            <input type="text" class="form-control inputRetourApres" name="quantite" data-numligne="<?= $ligne['numligne']; ?>" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        </div>

                                                                                    </div>

                                                                                </div>

                                                                                <div class="modal-footer">

                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                    <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres">Confirmer

                <img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

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

                                                                <?php  echo 'Reste : '.$pagnet['apayerPagnet']-$pagnet['avance'].'<br/>';?>

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

                            else if ($ligne['classe']==3 && $pagnet['type']==0 ) {?>

                                <div class="panel panel-warning">

                                    <div class="panel-heading">

                                        <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                            <div class="right-arrow pull-right">+</div>

                                            <a href="#"> Panier <?php echo $n; ?>

                                                <span class="spanDate noImpr"> </span>

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

                                            if($idmax == $pagnet['idPagnet']){

                                                ?> class="panel-collapse collapse in" <?php

                                            }

                                            else  {

                                                ?> class="panel-collapse collapse " <?php

                                            }

                                        ?>  >

                                        <div class="panel-body" >

                                            <!--*******************************Debut Retourner Pagnet****************************************-->

                                                <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

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

                                                                    <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                </div>

                                                            </form>

                                                        </div>

                                                    </div>

                                                </div>

                                            <!--*******************************Fin Retourner Pagnet****************************************-->



                                            <!--*******************************Debut Facture****************************************-->

                                                <?php if ($_SESSION['caissier']==1){ ?>

                                                    

                                                    <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet".$pagnet['idPagnet'] ; ?>>

                                                        Facture

                                                    </button>

                                                

                                                <?php } ?>



                                                <div class="modal fade" <?php echo  "id=msg_fact_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                    <div class="modal-dialog">

                                                        <!-- Modal content-->

                                                        <div class="modal-content">

                                                            <div class="modal-header panel-primary">

                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                <h4 class="modal-title">Informations Client</h4>

                                                            </div>

                                                            <form class="form-inline noImpr"  method="post" action="pdfFacture.php" target="_blank" >

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

                                            <?php if ($_SESSION['caissier']==1){ ?>

                                            

                                                <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                    Ticket de Caisse

                                                </button>

                                            

                                            <?php } ?>

                                            

                                            <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                method="post" action="barcodeFacture.php" >

                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                            </form>



                                            <table class="table ">

                                                <thead class="noImpr">

                                                    <tr>

                                                        <th>Référence</th>

                                                        <th>Quantité</th>

                                                        <th>Unité vente</th>

                                                        <th>Prix Unité vente</th>

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

                                                                </td>

                                                                <td class="unitevente ">

                                                                    <?php echo $ligne['unitevente']; ?>

                                                                </td>

                                                                <td>

                                                                    <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>

                                                                </td>

                                                                <td>

                                                                    <button type="submit" disabled="true"	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

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

                                                                                        <input type="hidden" name="unitevente" id="unitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                        <input type="hidden" name="quantite" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        <input type="hidden" name="prixunitevente" id="prixunitevente<?= $ligne['numligne']; ?>" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                        <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                        <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                    </div>

                                                                                    <div class="modal-footer">

                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                        <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres">Confirmer

                <img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

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

                                                                <?php  echo 'Reste : '.$pagnet['apayerPagnet']-$pagnet['avance'].'<br/>';?>

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

                            else if ($ligne['classe']==4) {?>

                                <div class="panel panel-warning">

                                    <div class="panel-heading">

                                        <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                            <div class="right-arrow pull-right">+</div>

                                            <a href="#"> Panier <?php echo $n; ?>

                                                <span class="spanDate noImpr"> </span>

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

                                                if($idmax == $pagnet['idPagnet']){

                                                    ?> class="panel-collapse collapse in" <?php

                                                }

                                                else  {

                                                    ?> class="panel-collapse collapse " <?php

                                                }

                                        ?>  >

                                        <div class="panel-body" >

                                            <!--*******************************Debut Retourner Pagnet****************************************-->

                                                <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

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

                                                                    <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                </div>

                                                            </form>

                                                        </div>

                                                    </div>

                                                </div>

                                            <!--*******************************Fin Retourner Pagnet****************************************-->



                                            <!--*******************************Debut Facture****************************************-->

                                                <?php if ($_SESSION['caissier']==1){ ?>

                                                    

                                                    <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet".$pagnet['idPagnet'] ; ?>>

                                                        Facture

                                                    </button>

                                                

                                                <?php } ?>



                                                <div class="modal fade" <?php echo  "id=msg_fact_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                    <div class="modal-dialog">

                                                        <!-- Modal content-->

                                                        <div class="modal-content">

                                                            <div class="modal-header panel-primary">

                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                <h4 class="modal-title">Informations Client</h4>

                                                            </div>

                                                            <form class="form-inline noImpr"  method="post" action="pdfFacture.php" target="_blank" >

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

                                            <?php if ($_SESSION['caissier']==1){ ?>

                                            

                                                <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                    Ticket de Caisse

                                                </button>

                                            

                                            <?php } ?>

                                            

                                            <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                method="post" action="barcodeFacture.php" >

                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                            </form>



                                            <table class="table ">

                                                <thead class="noImpr">

                                                    <tr>

                                                        <th>Référence</th>

                                                        <th>Quantité</th>

                                                        <th>Unité vente</th>

                                                        <th>Prix Unité vente</th>

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

                                                                    <?php 

                                                                    if ($ligne['unitevente']=='Credit'){ ?>

                                                                        <?php if ($ligne['quantite']==0): ?>

                                                                                <?php echo  'Especes'; ?> <span class="factureFois"></span>

                                                                            <?php endif; ?>

                                                                            <?php if ($ligne['quantite']!=0) : ?>

                                                                                <?php

                                                                                    $reqT="SELECT * from `aaa-transaction` where idTransaction='".$ligne['quantite']."'";

                                                                                    $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());

                                                                                    $transaction = mysql_fetch_assoc($resT);

                                                                                    echo $transaction['nomTransaction']; ?> 

                                                                                    <span class="factureFois"></span>

                                                                            <?php endif; ?>

                                                                    <?php } ?>

                                                                </td>

                                                                <td class="unitevente ">

                                                                    <?php echo $ligne['unitevente']; ?>

                                                                </td>

                                                                <td>

                                                                    <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>

                                                                </td>

                                                                <td>

                                                                    <button type="submit" disabled="true"	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

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

                                                                                        <input type="hidden" name="unitevente" id="unitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                        <input type="hidden" name="quantite" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        <input type="hidden" name="prixunitevente" id="prixunitevente<?= $ligne['numligne']; ?>" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                        <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                        <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                    </div>

                                                                                    <div class="modal-footer">

                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                        <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres">Confirmer

                <img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

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



                                            <!--*******************************Debut Facture****************************************-->

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

                                                                <?php  echo 'Reste : '.$pagnet['apayerPagnet']-$pagnet['avance'].'<br/>';?>

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

                                            <!--*******************************Fin Facture****************************************-->

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

                                            if($idmax == $pagnet['idPagnet']){

                                                ?> class="panel-collapse collapse in" <?php

                                            }

                                            else  {

                                                ?> class="panel-collapse collapse " <?php

                                            }

                                        ?>  >

                                        <div class="panel-body" >

                                            <!--*******************************Debut Retourner Pagnet****************************************-->

                                                <?php if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1):?>

                                                    <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

                                                            <span class="glyphicon glyphicon-remove"></span>Retourner

                                                    </button>

                                                <?php endif; ?>



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

                                                                    <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                </div>

                                                            </form>

                                                        </div>

                                                    </div>

                                                </div>

                                            <!--*******************************Fin Retourner Pagnet****************************************-->

                                            <?php if ($_SESSION['caissier']==1){ ?>

                                            

                                                <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                    Ticket de Caisse

                                                </button>

                                            

                                            <?php } ?>

                                            

                                            <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                method="post" action="barcodeFacture.php" >

                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                            </form>



                                            <table class="table ">

                                                <thead class="noImpr">

                                                    <tr>

                                                        <th>Référence</th>

                                                        <th>Quantité</th>

                                                        <th>Unité vente</th>

                                                        <th>Prix Unité vente</th>

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

                                                                <td class="unitevente ">

                                                                    <?php echo $ligne['unitevente']; ?>

                                                                </td>

                                                                <td>

                                                                    <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>

                                                                </td>

                                                                <td>

                                                                    <?php if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1):?>

                                                                        <button type="submit" disabled="true"	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

                                                                                <span class="glyphicon glyphicon-remove"></span>Retour

                                                                        </button>

                                                                    <?php endif; ?>



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

                                                                                        <input type="hidden" name="unitevente" id="unitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                        <input type="hidden" name="quantite" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        <input type="hidden" name="prixunitevente" id="prixunitevente<?= $ligne['numligne']; ?>" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                        <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                        <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                    </div>

                                                                                    <div class="modal-footer">

                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                        <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres">Confirmer

                <img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

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

                                                                <?php  echo 'Reste : '.$pagnet['apayerPagnet']-$pagnet['avance'].'<br/>';?>

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

                                            <!--*******************************Debut Total Facture****************************************-->

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

                                                if($idmax == $pagnet['idPagnet']){

                                                    ?> class="panel-collapse collapse in" <?php

                                                }

                                                else  {

                                                    ?> class="panel-collapse collapse " <?php

                                                }

                                        ?>  >

                                        <div class="panel-body" >

                                            <!--*******************************Debut Retourner Pagnet****************************************-->

                                                <?php if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1):?>

                                                    <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

                                                            <span class="glyphicon glyphicon-remove"></span>Retourner

                                                    </button>

                                                <?php endif; ?>



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

                                                                    <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                                </div>

                                                            </form>

                                                        </div>

                                                    </div>

                                                </div>

                                            <!--*******************************Fin Retourner Pagnet****************************************-->

                                            <?php if ($_SESSION['caissier']==1){ ?>

                                            

                                                <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                    Ticket de Caisse

                                                </button>

                                            

                                            <?php } ?>

                                            

                                            <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                method="post" action="barcodeFacture.php" >

                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                            </form>



                                            <table class="table ">

                                                <thead class="noImpr">

                                                    <tr>

                                                        <th>Référence</th>

                                                        <th>Quantité</th>

                                                        <th>Unité vente</th>

                                                        <th>Prix Unité vente</th>

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

                                                                <td class="unitevente ">

                                                                    <?php echo $ligne['unitevente']; ?>

                                                                </td>

                                                                <td>

                                                                    <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>

                                                                </td>

                                                                <td>

                                                                    <?php if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1):?>

                                                                        <button type="submit" disabled="true"	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

                                                                                <span class="glyphicon glyphicon-remove"></span>Retour

                                                                        </button>

                                                                    <?php endif; ?>



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

                                                                                        <input type="hidden" name="unitevente" id="unitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                        <input type="hidden" name="quantite" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        <input type="hidden" name="prixunitevente" id="prixunitevente<?= $ligne['numligne']; ?>" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                        <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                        <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                    </div>

                                                                                    <div class="modal-footer">

                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                        <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres">Confirmer

                <img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

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

                                                                <?php  echo 'Reste : '.$pagnet['apayerPagnet']-$pagnet['avance'].'<br/>';?>

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

                            if(($ligne['classe']==0 || $ligne['classe']==1) && $pagnet['type']==10 ){?>

                                <div class="panel panel-warning">

                                    <div class="panel-heading">

                                        <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                            <div class="right-arrow pull-right">+</div>

                                            <a href="#"> Panier <?php echo $n; ?>

                                                <span class="spanDate noImpr"> </span>

                                                <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                                <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                        $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                                                        $TotalT = mysql_fetch_array($resT);

                                                        $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                        $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());

                                                        $TotalP = mysql_fetch_array($resP);



                                                        $sql="SELECT * from `aaa-utilisateur` where idutilisateur='".$pagnet['iduser']."' ";

                                                        $res=mysql_query($sql);

                                                        $user=mysql_fetch_array($res);

                                                ?>

                                                <?php if($_SESSION['Pays']=='Canada'){ 

                                                    $sqlTP="SELECT apayerPagnetTvaP FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                    $resTP=mysql_query($sqlTP) or die ("select stock impossible =>".mysql_error());

                                                    $TotalTvaP = mysql_fetch_array($resTP);

                                                    $sqlTR="SELECT apayerPagnetTvaR FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                    $resTR=mysql_query($sqlTR) or die ("select stock impossible =>".mysql_error());

                                                    $TotalTvaR = mysql_fetch_array($resTR);

                                                ?> 

                                                    <span class="spanTotal noImpr" >Sous-Total: <span ><?php echo number_format(($TotalP[0] * $_SESSION['devise']), 2, ',', ' ') ; ?> </span></span>

                                                    <span class="spanTotal noImpr" >Total : <span ><?php echo number_format((($TotalP[0] + $TotalTvaP[0] + $TotalTvaR[0]) * $_SESSION['devise']), 2, ',', ' ') ; ?> </span></span>

                                                <?php }

                                                else{   ?>

                                                    <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                                    <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                <?php } ?> 
                                                
                                                <?php if($pagnet['identifiantP']==NULL || $pagnet['identifiantP']=='' ){?>
                                                    <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>  
                                                <?php }  
                                                else{   ?>    
                                                    <span class="spanDate noImpr"> Facture : #<?php echo substr($pagnet['identifiantP'], 8, 9);?></span>  
                                                <?php } ?> 

                                                <span class="spanDate noImpr"> <?php echo substr(strtoupper($user['prenom']),0,3); ?></span>

                                            </a>  

                                        </h4>

                                    </div>

                                    <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>

                                        <?php 

                                            if($idmax == $pagnet['idPagnet']){

                                                ?> class="panel-collapse collapse in" <?php

                                            }

                                            else  {

                                                ?> class="panel-collapse collapse " <?php

                                            }

                                        ?>  >

                                        <div class="panel-body" >

                                            <!--*******************************Debut Facture****************************************-->                                                    

                                                    <?php if ($_SESSION['caissier']==1){ ?>

                                                

                                                        <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_factEntreprise_pagnet".$pagnet['idPagnet'] ; ?>>

                                                            Facture Entreprise

                                                        </button>



                                                        <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_factClient_pagnet".$pagnet['idPagnet'] ; ?>>

                                                            Facture Client

                                                        </button>

                                                    

                                                    <?php } ?>



                                                    <div class="modal fade" <?php echo  "id=msg_factClient_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                        <div class="modal-dialog">

                                                            <!-- Modal content-->

                                                            <div class="modal-content">

                                                                <div class="modal-header panel-primary">

                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                    <h4 class="modal-title">Informations Client</h4>

                                                                </div>

                                                                <form class="form-inline noImpr"  method="post" action="pdfFactureProforma.php" target="_blank" >

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



                                                    <div class="modal fade" <?php echo  "id=msg_factEntreprise_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                        <div class="modal-dialog">

                                                            <!-- Modal content-->

                                                            <div class="modal-content">

                                                                <div class="modal-header panel-primary">

                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                    <h4 class="modal-title">Informations Entreprise</h4>

                                                                </div>

                                                                <form class=" noImpr"  method="post" action="pdfFactureProformaEntreprise.php" target="_blank" >

                                                                    <div class="modal-body">

                                                                        <div class="form-group">

                                                                            <label for="inputEmail3" class="control-label">Nom Entreprise </label>

                                                                            <input type="text" class="form-control" name="entreprise" required="" placeholder="Le nom de l'Entreprise ici...">

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



                                            <table class="table ">

                                                <thead class="noImpr">

                                                    <tr>

                                                        <th>Référence</th>

                                                        <th>Quantité</th>

                                                        <th>Unité vente</th>

                                                        <th>Prix Unité vente</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    <?php

                                                        $sql8="SELECT * FROM `".$nomtableLigne."` l

                                                        INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation 

                                                        where l.idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                        $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                        while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                            <tr>

                                                                <td class="designation">

                                                                    <?php

                                                                        if($_SESSION['Pays']=='Canada' && $ligne['tva'] == 1){ 

                                                                            echo '<span style="font-size:16px;color:black" class="glyphicon glyphicon-star"> </span>&nbsp';

                                                                        }

                                                                        echo $ligne['designation'];

                                                                    ?>

                                                                </td>

                                                                <td>

                                                                    <?php if ($ligne['unitevente']=='Transaction'){ ?>

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

                                                                <td class="unitevente ">

                                                                    <?php echo $ligne['unitevente']; ?>

                                                                </td>

                                                                <td>

                                                                    <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>

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

                                                                                        <input type="hidden" name="unitevente" id="unitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                        <input type="hidden" name="prixunitevente" id="prixunitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                        <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                        <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                        <div class="row">

                                                                                            <div class="col-xs-6">

                                                                                                <label for="reference">Ancienne quantite </label>

                                                                                                <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                            </div>

                                                                                            <div class="col-xs-6">

                                                                                                <label for="reference">Nouvelle quantite</label>

                                                                                                <input type="text" class="form-control inputRetourApres" name="quantite" data-numligne="<?= $ligne['numligne']; ?>" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                            </div>

                                                                                        </div>

                                                                                    </div>

                                                                                    <div class="modal-footer">

                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                        <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres">Confirmer

                <img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

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

                                            <?php if($_SESSION['Pays']=='Canada'){   ?> 

                                                    <div>

                                                        <div>

                                                            <?php if ($pagnet['remise']!=0): ?>

                                                            <?php echo  'Total panier : '.number_format(($pagnet['totalp'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                            <?php endif; ?>

                                                        </div>

                                                        <div>

                                                            <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>

                                                                <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>

                                                            <?php endif; ?>

                                                        </div>

                                                        <div>

                                                            <?php echo  '********************************************* <br/>'; ?>

                                                            <?php echo  'Sous-total : '.number_format(($pagnet['apayerPagnet'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                        </div>

                                                        <div>

                                                            <?php echo  'TPS à 5% : '.number_format(($pagnet['apayerPagnetTvaP'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                        </div>

                                                        <div>

                                                            <?php echo  'TVQ à 9.975% : '.number_format(($pagnet['apayerPagnetTvaR'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                        </div>

                                                        <div>

                                                            <?php echo  '<b>Total : '.number_format((($pagnet['apayerPagnet'] + $pagnet['apayerPagnetTvaP'] + $pagnet['apayerPagnetTvaR'] ) * $_SESSION['devise']), 2, ',', ' ') .'</b><br/>'; ?>

                                                            <?php echo  '********************************************* <br/>'; ?>

                                                        </div>

                                                        <div>

                                                            <?php if($pagnet['versement']!=0): ?>

                                                                <?php  echo 'Espèces : '.$pagnet['versement'].'<br/>';?>

                                                                <?php  echo 'Rendu : '.number_format((($pagnet['versement'] - ( $pagnet['apayerPagnet'] + $pagnet['apayerPagnetTvaP'] + $pagnet['apayerPagnetTvaR'] )) * $_SESSION['devise']), 2, ',', ' ') .'<br/>';?>

                                                            <?php endif; ?>

                                                        </div>

                                                    </div>

                                                <?php }

                                                else{   ?>

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

                                                <?php } ?> 

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



                    ?>		



                <?php $n=$n-1;   } ?>

                <?php if($nbPaniers >= 11){ ?>

                <ul class="pagination paginationLoader pull-right">

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

            <?php }

             else { ?> 



                <!-- Debut Boucle while concernant les Paniers Vendus -->

                <?php $n=10; while ($pagnet = mysql_fetch_assoc($resP1)) {   ?>



                <?php	$idmax=mysql_result($resA,0); ?>



                    <?php

                    $sql="SELECT * FROM `".$nomtableLigne."` l

                    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation 

                    where idPagnet=".$pagnet['idPagnet']." ";

                    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                    $ligne = mysql_fetch_assoc($res) ;

                    if($ligne!=null){

                        if(($ligne['classe']==0 || $ligne['classe']==1) && ($pagnet['type']==0 || $pagnet['type']==30) ){?>

                            <div class="panel <?= ($pagnet['idClient']==0) ? 'panel-success' : 'panel-info'?>">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                    <div class="right-arrow pull-right">+</div>

                                    <a href="#"> Panier <?php echo $n; ?>

                                        <span class="spanDate noImpr"> </span>

                                        <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                        <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                                                $TotalT = mysql_fetch_array($resT);

                                                $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());

                                                $TotalP = mysql_fetch_array($resP);



                                                $sql="SELECT * from `aaa-utilisateur` where idutilisateur='".$pagnet['iduser']."' ";

                                                $res=mysql_query($sql);

                                                $user=mysql_fetch_array($res);

                                        ?>

                                        <?php if($_SESSION['Pays']=='Canada'){  

                                            $sqlTP="SELECT apayerPagnetTvaP FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                            $resTP=mysql_query($sqlTP) or die ("select stock impossible =>".mysql_error());

                                            $TotalTvaP = mysql_fetch_array($resTP);

                                            $sqlTR="SELECT apayerPagnetTvaR FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                            $resTR=mysql_query($sqlTR) or die ("select stock impossible =>".mysql_error());

                                            $TotalTvaR = mysql_fetch_array($resTR);

                                        ?> 

                                                <span class="spanTotal noImpr" >Sous-Total: <span ><?php echo number_format(($TotalP[0] * $_SESSION['devise']), 2, ',', ' ') ; ?> </span></span>

                                                <span class="spanTotal noImpr" >Total : <span ><?php echo number_format((($TotalP[0] + $TotalTvaP[0] + $TotalTvaR[0]) * $_SESSION['devise']), 2, ',', ' ') ; ?> </span></span>

                                        <?php }

                                        else{   ?>

                                            <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                            <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                        <?php } ?> 

                                        <?php if($pagnet['identifiantP']==NULL || $pagnet['identifiantP']=='' ){?>
                                            <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>  
                                        <?php }  
                                        else{   ?>    
                                            <span class="spanDate noImpr"> Facture : #<?php echo substr($pagnet['identifiantP'], 8, 9);?></span>  
                                        <?php } ?>

                                        <span class="spanDate noImpr"> <?php echo substr(strtoupper($user['prenom']),0,3); ?></span>

                                    </a>

                                    </h4>

                                </div>

                                <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>

                                    <?php 

                                        if($idmax == $pagnet['idPagnet']){

                                            ?> class="panel-collapse collapse in" <?php

                                        }

                                        else  {

                                            ?> class="panel-collapse collapse " <?php

                                        }

                                    ?>  >

                                    <div class="panel-body" >

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

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

                                                                <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                        <!--*******************************Fin Retourner Pagnet****************************************-->



                                        <!--*******************************Debut Facture****************************************-->

                                        

                                            <?php if ($_SESSION['caissier']==1){ ?>

                                                

                                                <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet".$pagnet['idPagnet'] ; ?>>

                                                    Facture

                                                </button>

                                                

                                            <?php } ?>



                                            <div class="modal fade" <?php echo  "id=msg_fact_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                <div class="modal-dialog">

                                                    <!-- Modal content-->

                                                    <div class="modal-content">

                                                        <div class="modal-header panel-primary">

                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                            <h4 class="modal-title">Informations Client</h4>

                                                        </div>

                                                        <form class="form-inline noImpr"  method="post" action="pdfFacture.php" target="_blank" >

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

                                        <?php if ($_SESSION['caissier']==1){ ?>

                                        

                                            <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                Ticket de Caisse

                                            </button>

                                        

                                        <?php } ?>

                                        

                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                            method="post" action="barcodeFacture.php" >

                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                        </form>



                                        <table class="table ">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th>Référence</th>

                                                    <th>Quantité</th>

                                                    <th>Unité vente</th>

                                                    <th>Prix Unité vente</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php

                                                    $sql8="SELECT * FROM `".$nomtableLigne."` l

                                                    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation 

                                                    where l.idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                    $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                    while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                        <tr>

                                                            <td class="designation">

                                                                <?php

                                                                    if($_SESSION['Pays']=='Canada' && $ligne['tva'] == 1){ 

                                                                        echo '<span style="font-size:16px;color:black" class="glyphicon glyphicon-star"> </span>&nbsp';

                                                                    }

                                                                    echo $ligne['designation'];

                                                                ?>

                                                            </td>

                                                            <td>

                                                                <?php if ($ligne['unitevente']=='Transaction'){ ?>

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

                                                            <td class="unitevente ">

                                                                <?php echo $ligne['unitevente']; ?>

                                                            </td> 

                                                            <td> 

                                                                <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>

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

                                                                                    <input type="hidden" name="unitevente" id="unitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                    <input type="hidden" name="prixunitevente" id="prixunitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                    <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                    <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                    <div class="row">

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Ancienne quantite </label>

                                                                                            <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        </div>

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Nouvelle quantite</label>

                                                                                            <input type="text" class="form-control inputRetourApres" name="quantite" data-numligne="<?= $ligne['numligne']; ?>" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        </div>

                                                                                    </div>

                                                                                </div>

                                                                                <div class="modal-footer">

                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                    <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres">Confirmer

            <img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

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

                                            <?php if($_SESSION['Pays']=='Canada'){   ?> 

                                                <div>

                                                    <div>

                                                        <?php if ($pagnet['remise']!=0): ?>

                                                        <?php echo  'Total panier : '.number_format(($pagnet['totalp'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                        <?php endif; ?>

                                                    </div>

                                                    <div>

                                                        <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>

                                                            <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>

                                                        <?php endif; ?>

                                                    </div>

                                                    <div>

                                                        <?php echo  '********************************************* <br/>'; ?>

                                                        <?php echo  'Sous-total : '.number_format(($pagnet['apayerPagnet'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                    </div>

                                                    <div>

                                                        <?php echo  'TPS à 5% : '.number_format(($pagnet['apayerPagnetTvaP'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                    </div>

                                                    <div>

                                                        <?php echo  'TVQ à 9.975% : '.number_format(($pagnet['apayerPagnetTvaR'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                    </div>

                                                    <div>

                                                        <?php echo  '<b>Total : '.number_format((($pagnet['apayerPagnet'] + $pagnet['apayerPagnetTvaP'] + $pagnet['apayerPagnetTvaR'] ) * $_SESSION['devise']), 2, ',', ' ') .'</b><br/>'; ?>

                                                        <?php echo  '********************************************* <br/>'; ?>

                                                    </div>

                                                    <div>

                                                        <?php if($pagnet['versement']!=0): ?>

                                                            <?php  echo 'Espèces : '.$pagnet['versement'].'<br/>';?>

                                                            <?php  echo 'Rendu : '.number_format((($pagnet['versement'] - ( $pagnet['apayerPagnet'] + $pagnet['apayerPagnetTvaP'] + $pagnet['apayerPagnetTvaR'] )) * $_SESSION['devise']), 2, ',', ' ') .'<br/>';?>

                                                        <?php endif; ?>

                                                    </div>

                                                </div>

                                            <?php }

                                            else{   ?>

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

                                            <?php } ?> 

                                        <!--*******************************Fin Total Facture****************************************-->

                                    </div>

                                </div>

                            </div>

                        <?php

                        }

                        else if ($ligne['classe']==2 && $pagnet['type']==0 ) {?>

                            <div class="panel panel-danger">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Panier <?php echo $n; ?>

                                            <span class="spanDate noImpr"> </span>

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

                                        if($idmax == $pagnet['idPagnet']){

                                            ?> class="panel-collapse collapse in" <?php

                                        }

                                        else  {

                                            ?> class="panel-collapse collapse " <?php

                                        }



                                    ?>  >

                                    <div class="panel-body" >

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

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

                                                                <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                        <?php if ($_SESSION['caissier']==1){ ?>

                                    

                                            <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                Ticket de Caisse

                                            </button>

                                        

                                        <?php } ?>

                                        

                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                            method="post" action="barcodeFacture.php" >

                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                        </form>



                                        <table class="table ">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th></th>

                                                    <th>Référence</th>

                                                    <th>Prix Unité vente</th>

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

                                                                <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>

                                                            </td>

                                                            <td>

                                                                <button type="submit" disabled="true"	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

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

                                                                                    <input type="hidden" name="unitevente" id="unitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                    <input type="hidden" name="prixunitevente" id="prixunitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                    <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                    <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                    <div class="row">

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Ancienne quantite </label>

                                                                                            <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        </div>

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Nouvelle quantite</label>

                                                                                            <input type="text" class="form-control inputRetourApres" name="quantite" data-numligne="<?= $ligne['numligne']; ?>" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        </div>

                                                                                    </div>

                                                                                </div>

                                                                                <div class="modal-footer">

                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                    <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres">Confirmer

            <img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

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

                                                            <?php  echo 'Reste : '.$pagnet['apayerPagnet']-$pagnet['avance'].'<br/>';?>

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

                        else if ($ligne['classe']==3 && $pagnet['type']==0 ) {?>

                            <div class="panel panel-warning">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Panier <?php echo $n; ?>

                                            <span class="spanDate noImpr"> </span>

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

                                <div  <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>

                                    <?php 

                                        if($idmax == $pagnet['idPagnet']){

                                            ?> class="panel-collapse collapse in" <?php

                                        }

                                        else  {

                                            ?> class="panel-collapse collapse " <?php

                                        }

                                    ?>  >

                                    <div class="panel-body" >

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

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

                                                                <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                        <!--*******************************Fin Retourner Pagnet****************************************-->



                                        <!--*******************************Debut Facture****************************************-->

                                            <?php if ($_SESSION['caissier']==1){ ?>

                                            

                                                <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet".$pagnet['idPagnet'] ; ?>>

                                                    Facture

                                                </button>

                                            

                                            <?php } ?>



                                            <div class="modal fade" <?php echo  "id=msg_fact_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                <div class="modal-dialog">

                                                    <!-- Modal content-->

                                                    <div class="modal-content">

                                                        <div class="modal-header panel-primary">

                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                            <h4 class="modal-title">Informations Client</h4>

                                                        </div>

                                                        <form class="form-inline noImpr"  method="post" action="pdfFacture.php" target="_blank" >

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

                                            

                                        <?php if ($_SESSION['caissier']==1){ ?>

                                        

                                            <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                Ticket de Caisse

                                            </button>

                                        

                                        <?php } ?>

                                        

                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                            method="post" action="barcodeFacture.php" >

                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                        </form>



                                        <table class="table ">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th>Référence</th>

                                                    <th>Quantité</th>

                                                    <th>Unité vente</th>

                                                    <th>Prix Unité vente</th>

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

                                                            </td>

                                                            <td class="unitevente ">

                                                                <?php echo $ligne['unitevente']; ?>

                                                            </td>

                                                            <td> 

                                                                <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>

                                                            </td>

                                                            <td>

                                                                <button type="submit" disabled="true"	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

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

                                                                                    <input type="hidden" name="unitevente" id="unitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                    <input type="hidden" name="quantite" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                    <input type="hidden" name="prixunitevente" id="prixunitevente<?= $ligne['numligne']; ?>" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                    <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                    <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                </div>

                                                                                <div class="modal-footer">

                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                    <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres">Confirmer

            <img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

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

                                                            <?php  echo 'Reste : '.$pagnet['apayerPagnet']-$pagnet['avance'].'<br/>';?>

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

                        else if ($ligne['classe']==4) {?>

                            <div class="panel panel-warning">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Panier <?php echo $n; ?>

                                            <span class="spanDate noImpr"> </span>

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

                                        if($idmax == $pagnet['idPagnet']){

                                            ?> class="panel-collapse collapse in" <?php

                                        }

                                        else  {

                                            ?> class="panel-collapse collapse " <?php

                                        }

                                    ?>  >

                                    <div class="panel-body" >

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                            <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

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

                                                                <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                        <!--*******************************Fin Retourner Pagnet****************************************-->



                                        <!--*******************************Debut Facture****************************************-->

                                            <?php if ($_SESSION['caissier']==1){ ?>

                                            

                                                <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet".$pagnet['idPagnet'] ; ?>>

                                                    Facture

                                                </button>

                                            

                                            <?php } ?>



                                            <div class="modal fade" <?php echo  "id=msg_fact_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                <div class="modal-dialog">

                                                    <!-- Modal content-->

                                                    <div class="modal-content">

                                                        <div class="modal-header panel-primary">

                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                            <h4 class="modal-title">Informations Client</h4>

                                                        </div>

                                                        <form class="form-inline noImpr"  method="post" action="pdfFacture.php" target="_blank" >

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

                                        

                                        <?php if ($_SESSION['caissier']==1){ ?>

                                        

                                            <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                Ticket de Caisse

                                            </button>

                                        

                                        <?php } ?>

                                        

                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                            method="post" action="barcodeFacture.php" >

                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                        </form>



                                        <table class="table ">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th>Référence</th>

                                                    <th>Quantité</th>

                                                    <th>Unité vente</th>

                                                    <th>Prix Unité vente</th>

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

                                                                <?php 

                                                                if ($ligne['unitevente']=='Credit'){ ?>

                                                                    <?php if ($ligne['quantite']==0): ?>

                                                                            <?php echo  'Especes'; ?> <span class="factureFois"></span>

                                                                        <?php endif; ?>

                                                                        <?php if ($ligne['quantite']!=0) : ?>

                                                                            <?php

                                                                                $reqT="SELECT * from `aaa-transaction` where idTransaction='".$ligne['quantite']."'";

                                                                                $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());

                                                                                $transaction = mysql_fetch_assoc($resT);

                                                                                echo $transaction['nomTransaction']; ?> 

                                                                                <span class="factureFois"></span>

                                                                        <?php endif; ?>

                                                                <?php } ?>

                                                            </td>

                                                            <td class="unitevente ">

                                                                <?php echo $ligne['unitevente']; ?>

                                                            </td>

                                                            <td>

                                                                <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>

                                                            </td>

                                                            <td>

                                                                <button type="submit" disabled="true"	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

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

                                                                                    <input type="hidden" name="unitevente" id="unitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                    <input type="hidden" name="quantite" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                    <input type="hidden" name="prixunitevente" id="prixunitevente<?= $ligne['numligne']; ?>" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                    <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                    <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                </div>

                                                                                <div class="modal-footer">

                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                    <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres">Confirmer

            <img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

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

                                                            <?php  echo 'Reste : '.$pagnet['apayerPagnet']-$pagnet['avance'].'<br/>';?>

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

                                <div  <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>

                                    <?php 

                                        if($idmax == $pagnet['idPagnet']){

                                            ?> class="panel-collapse collapse in" <?php

                                        }

                                        else  {

                                            ?> class="panel-collapse collapse " <?php

                                        }

                                    ?>  >

                                    <div class="panel-body" >

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                            <?php if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1):?>

                                                <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

                                                        <span class="glyphicon glyphicon-remove"></span>Retourner

                                                </button>

                                            <?php endif; ?>



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

                                                                <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                        

                                        <?php if ($_SESSION['caissier']==1){ ?>

                                        

                                            <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                Ticket de Caisse

                                            </button>

                                        

                                        <?php } ?>

                                        

                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                            method="post" action="barcodeFacture.php" >

                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                        </form>



                                        <table class="table ">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th>Référence</th>

                                                    <th>Quantité</th>

                                                    <th>Unité vente</th>

                                                    <th>Prix Unité vente</th>

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

                                                            <td class="unitevente ">

                                                                <?php echo $ligne['unitevente']; ?>

                                                            </td>

                                                            <td>

                                                                <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>

                                                            </td>

                                                            <td>

                                                                <?php if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1):?>

                                                                    <button type="submit" disabled="true"	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

                                                                            <span class="glyphicon glyphicon-remove"></span>Retour

                                                                    </button>

                                                                <?php endif; ?>



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

                                                                                    <input type="hidden" name="unitevente" id="unitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                    <input type="hidden" name="quantite" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                    <input type="hidden" name="prixunitevente" id="prixunitevente<?= $ligne['numligne']; ?>" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                    <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                    <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                </div>

                                                                                <div class="modal-footer">

                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                    <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres">Confirmer

            <img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

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

                                                            <?php  echo 'Reste : '.$pagnet['apayerPagnet']-$pagnet['avance'].'<br/>';?>

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

                                        if($idmax == $pagnet['idPagnet']){

                                            ?> class="panel-collapse collapse in" <?php

                                        }

                                        else  {

                                            ?> class="panel-collapse collapse " <?php

                                        }

                                    ?>  >

                                    <div class="panel-body" >

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                            <?php if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1):?>

                                                <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

                                                        <span class="glyphicon glyphicon-remove"></span>Retourner

                                                </button>

                                            <?php endif; ?>



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

                                                                <button type="button" name="btnRetournerPagnet" data-idPanier="<?= $pagnet['idPagnet'];?>" class="btn btn-success btnRetournerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-retournerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                        <?php if ($_SESSION['caissier']==1){ ?>

                                            

                                            <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                Ticket de Caisse

                                            </button>

                                        

                                        <?php } ?>

                                        

                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                            method="post" action="barcodeFacture.php" >

                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                        </form>

                                        

                                        <table class="table ">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th>Référence</th>

                                                    <th>Quantité</th>

                                                    <th>Unité vente</th>

                                                    <th>Prix Unité vente</th>

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

                                                            <td class="unitevente ">

                                                                <?php echo $ligne['unitevente']; ?>

                                                            </td>

                                                            <td>

                                                                <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>

                                                            </td>

                                                            <td>

                                                                <?php if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1):?>

                                                                    <button type="submit" disabled="true" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

                                                                            <span class="glyphicon glyphicon-remove"></span>Retour

                                                                    </button>

                                                                <?php endif; ?>



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

                                                                                    <input type="hidden" name="unitevente" id="unitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                    <input type="hidden" name="quantite" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                    <input type="hidden" name="prixunitevente" id="prixunitevente<?= $ligne['numligne']; ?>" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                    <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                    <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                </div>

                                                                                <div class="modal-footer">

                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                    <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres">Confirmer

            <img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

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

                                                            <?php  echo 'Reste : '.$pagnet['apayerPagnet']-$pagnet['avance'].'<br/>';?>

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

                        if(($ligne['classe']==0 || $ligne['classe']==1) && $pagnet['type']==10 ){?>

                            <div class="panel panel-warning">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Panier <?php echo $n; ?>

                                            <span class="spanDate noImpr"> </span>

                                            <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                            <?php   $sqlT="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                    $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                                                    $TotalT = mysql_fetch_array($resT);

                                                    $sqlP="SELECT apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                    $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());

                                                    $TotalP = mysql_fetch_array($resP);



                                                    $sql="SELECT * from `aaa-utilisateur` where idutilisateur='".$pagnet['iduser']."' ";

                                                    $res=mysql_query($sql);

                                                    $user=mysql_fetch_array($res);

                                            ?>

                                            <?php if($_SESSION['Pays']=='Canada'){  

                                                $sqlTP="SELECT apayerPagnetTvaP FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                $resTP=mysql_query($sqlTP) or die ("select stock impossible =>".mysql_error());

                                                $TotalTvaP = mysql_fetch_array($resTP);

                                                $sqlTR="SELECT apayerPagnetTvaR FROM `".$nomtablePagnet."` where idPagnet=".$pagnet['idPagnet']." ";

                                                $resTR=mysql_query($sqlTR) or die ("select stock impossible =>".mysql_error());

                                                $TotalTvaR = mysql_fetch_array($resTR);

                                            ?> 

                                                <span class="spanTotal noImpr" >Sous-Total: <span ><?php echo number_format(($TotalP[0] * $_SESSION['devise']), 2, ',', ' ') ; ?> </span></span>

                                                <span class="spanTotal noImpr" >Total : <span ><?php echo number_format((($TotalP[0] + $TotalTvaP[0] + $TotalTvaR[0]) * $_SESSION['devise']), 2, ',', ' ') ; ?> </span></span>

                                            <?php }

                                            else{   ?>

                                                    <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                                    <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                            <?php } ?> 

                                            <?php if($pagnet['identifiantP']==NULL || $pagnet['identifiantP']=='' ){?>
                                                <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>  
                                            <?php }  
                                            else{   ?>    
                                                <span class="spanDate noImpr"> Facture : #<?php echo substr($pagnet['identifiantP'], 8, 9);?></span>  
                                            <?php } ?>

                                            <span class="spanDate noImpr"> <?php echo substr(strtoupper($user['prenom']),0,3); ?></span>

                                        </a>

                                    </h4>

                                </div>

                                <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>

                                    <?php 

                                        if($idmax == $pagnet['idPagnet']){

                                            ?> class="panel-collapse collapse in" <?php

                                        }

                                        else  {

                                            ?> class="panel-collapse collapse " <?php

                                        }

                                     ?>  >

                                    <div class="panel-body" >

                                        <!--*******************************Debut Facture****************************************-->

                                        

                                            <?php if ($_SESSION['caissier']==1){ ?>

                                            

                                                <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_factEntreprise_pagnet".$pagnet['idPagnet'] ; ?>>

                                                    Facture Entreprise

                                                </button>



                                                <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_factClient_pagnet".$pagnet['idPagnet'] ; ?>>

                                                    Facture Client

                                                </button>

                                                

                                            <?php } ?>



                                            <div class="modal fade" <?php echo  "id=msg_factClient_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                <div class="modal-dialog">

                                                    <!-- Modal content-->

                                                    <div class="modal-content">

                                                        <div class="modal-header panel-primary">

                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                            <h4 class="modal-title">Informations Client</h4>

                                                        </div>

                                                        <form class="form-inline noImpr"  method="post" action="pdfFactureProforma.php" target="_blank" >

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



                                            <div class="modal fade" <?php echo  "id=msg_factEntreprise_pagnet".$pagnet['idPagnet'] ; ?> role="dialog">

                                                <div class="modal-dialog">

                                                    <!-- Modal content-->

                                                    <div class="modal-content">

                                                        <div class="modal-header panel-primary">

                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                            <h4 class="modal-title">Informations Entreprise</h4>

                                                        </div>

                                                        <form class=" noImpr"  method="post" action="pdfFactureProformaEntreprise.php" target="_blank" >

                                                            <div class="modal-body">

                                                                <div class="form-group">

                                                                    <label for="inputEmail3" class="control-label">Nom Entreprise </label>

                                                                    <input type="text" class="form-control" name="entreprise" required="" placeholder="Le nom de l'Entreprise ici...">

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



                                        <table class="table ">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th>Référence</th>

                                                    <th>Quantité</th>

                                                    <th>Unité vente</th>

                                                    <th>Prix Unité vente</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php

                                                    $sql8="SELECT * FROM `".$nomtableLigne."` l

                                                    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation 

                                                    where l.idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                    $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                    while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                        <tr>

                                                            <td class="designation">

                                                                <?php

                                                                    if($_SESSION['Pays']=='Canada' && $ligne['tva'] == 1){ 

                                                                        echo '<span style="font-size:16px;color:black" class="glyphicon glyphicon-star"> </span>&nbsp';

                                                                    }

                                                                    echo $ligne['designation'];

                                                                ?>

                                                            </td>

                                                            <td>

                                                                <?php if ($ligne['unitevente']=='Transaction'){ ?>

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

                                                            <td class="unitevente ">

                                                                <?php echo $ligne['unitevente']; ?>

                                                            </td>

                                                            <td>

                                                                <?php echo  $ligne['prixunitevente']; ?>  <span class="factureFois" ></span>

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

                                                                                    <input type="hidden" name="unitevente" id="unitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                    <input type="hidden" name="prixunitevente" id="prixunitevente<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixunitevente'].""; ?> >

                                                                                    <input type="hidden" name="prixtotal" id="prixtotal<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['prixtotal'].""; ?> >

                                                                                    <input type="hidden" name="totalp" id="totalp<?= $ligne['numligne']; ?>" <?php echo  "value=".$pagnet['totalp'].""; ?> >

                                                                                    <div class="row">

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Ancienne quantite </label>

                                                                                            <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        </div>

                                                                                        <div class="col-xs-6">

                                                                                            <label for="reference">Nouvelle quantite</label>

                                                                                            <input type="text" class="form-control inputRetourApres" name="quantite" data-numligne="<?= $ligne['numligne']; ?>" id="quantite<?= $ligne['numligne']; ?>" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        </div>

                                                                                    </div>

                                                                                </div>

                                                                                <div class="modal-footer">

                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                    <button type="button" name="btnRetourApres" data-numligne="<?= $ligne['numligne']; ?>" class="btn btn-success btnRetourApres">Confirmer

            <img src="images/loading-gif3.gif" class="img-load-retourApres" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> </button>

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

                                            <?php if($_SESSION['Pays']=='Canada'){   ?> 

                                                <div>

                                                    <div>

                                                        <?php if ($pagnet['remise']!=0): ?>

                                                        <?php echo  'Total panier : '.number_format(($pagnet['totalp'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                        <?php endif; ?>

                                                    </div>

                                                    <div>

                                                        <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>

                                                            <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>

                                                        <?php endif; ?>

                                                    </div>

                                                    <div>

                                                        <?php echo  '********************************************* <br/>'; ?>

                                                        <?php echo  'Sous-total : '.number_format(($pagnet['apayerPagnet'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                    </div>

                                                    <div>

                                                        <?php echo  'TPS à 5% : '.number_format(($pagnet['apayerPagnetTvaP'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                    </div>

                                                    <div>

                                                        <?php echo  'TVQ à 9.975% : '.number_format(($pagnet['apayerPagnetTvaR'] * $_SESSION['devise']), 2, ',', ' ').'<br/>'; ?>

                                                    </div>

                                                    <div>

                                                        <?php echo  '<b>Total : '.number_format((($pagnet['apayerPagnet'] + $pagnet['apayerPagnetTvaP'] + $pagnet['apayerPagnetTvaR'] ) * $_SESSION['devise']), 2, ',', ' ') .'</b><br/>'; ?>

                                                        <?php echo  '********************************************* <br/>'; ?>

                                                    </div>

                                                    <div>

                                                        <?php if($pagnet['versement']!=0): ?>

                                                            <?php  echo 'Espèces : '.$pagnet['versement'].'<br/>';?>

                                                            <?php  echo 'Rendu : '.number_format((($pagnet['versement'] - ( $pagnet['apayerPagnet'] + $pagnet['apayerPagnetTvaP'] + $pagnet['apayerPagnetTvaR'] )) * $_SESSION['devise']), 2, ',', ' ') .'<br/>';?>

                                                        <?php endif; ?>

                                                    </div>

                                                </div>

                                            <?php }

                                            else{   ?>

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

                                            <?php } ?> 

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



                    ?>		



                <?php $n=$n-1;   } ?>

                <!-- Fin Boucle while concernant les Paniers Vendus  -->

            <?php } ?>

            <div class="modal fade"  id="imageNvCompte" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Compte : <b># <span id="idCompte_View"></span></b></h4>
                        </div>
                        <form   method="post" enctype="multipart/form-data">
                            <div class="modal-body" style="padding:0px 150px;">
                                <div class="form-group" style="text-align:center;" >
                                    <img style="display:none;" width="300px" height="400px" id="output_image_Compte"/>
                                    <iframe style="display:none;" id="output_pdf_Compte" width="100%" height="100%"></iframe>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="col-sm-6 "> 
                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="mot_Fermer">Fermer</span></button>
                                </div>
                                <h4> Montant a payer : <b> <span id="idCompte_Montant"></span> FCFA</b></h4>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    <!-- Fin de l'Accordion pour Tout les Paniers -->

<script >
    function showImageCompte(idCompte,idPanier) {
        var nom = $('#idImageCompte'+idCompte).text();
        var montant = $('#somme_Apayer'+idPanier).text();
        $('#idCompte_View').text(nom);
        $('#idCompte_Montant').text(montant);
        var file = $('#idImageCompte'+idCompte).attr("data-image");
        if(file!=null && file!=''){
            $('#imageNvCompte').modal('show');
            var format = file.substr(file.length - 3);
            if(format=='pdf'){
                document.getElementById('output_pdf_Compte').style.display = "block";
                document.getElementById('output_image_Compte').style.display = "none";
                document.getElementById("output_pdf_Compte").src="./compte/images/"+file;
            }
            else{
                document.getElementById('output_image_Compte').style.display = "block";
                document.getElementById('output_pdf_Compte').style.display = "none";
                document.getElementById("output_image_Compte").src="./compte/images/"+file;
            }
        }
        else{
            document.getElementById('output_pdf_Compte').style.display = "none";
            document.getElementById('output_image_Compte').style.display = "none";
        }
    }
</script>



    <?php /*****************************/

    echo''.

    '<script>$(document).ready(function(){$("#AjoutLigne").click(function(){$("#AjoutLigneModal").modal();});});</script></body></html>';

}
    ?>



