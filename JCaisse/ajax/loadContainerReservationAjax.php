<?php



    session_start();

    if(!$_SESSION['iduser']){

    header('Location:../index.php'); 

    } 



    require('../connection.php');



    require('../declarationVariables.php');

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

                   <!--  <input type="text" class="form-control" placeholder="Recherche Reservation..." id="designationInfo" name="designation" autocomplete="off"  size="100"/>
 -->

                </div>

            </form>

        </div>

        <h2>Les reservations du : <?php echo $dateString2; ?></h2>

    </div>



<!--*******************************Debut Rechercher Produit****************************************-->

                     

    <!--*******************************Debut Rechercher Produit****************************************-->

    <?php if ($_SESSION['proprietaire']==1){ ?>

        <form  class="pull-right form-inline" id="searchProdForm" method="post" name="searchProdForm" >

        <div class="form-group">

            <img src="images/loading-gif3.gif" class="img-load-search form-group" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> 

        </div>    

        <div class="form-group">

           <!--  <input type="text" size="45" class="form-control form-group" name="produit" placeholder="Rechercher produit vendu..."  id="inpt_Search_ListerVentes" autocomplete="off" />
 -->
        </div>    



                <!-- <span id="reponsePV"></span> -->

        </form>

    <?php } ?>

    <?php  

        $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."' and YEAR(datePs)='".$annee."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";

        $res0=mysql_query($sql0);
        $ps = mysql_fetch_array($res0) ;
        $idPS = @$ps['idPS'];
        $montantFixePayement = @$ps['montantFixePayement'];


        if(mysql_num_rows($res0)){

            if($jour > 0){

                if($jour > 4){
                    echo ' 
                        <form name="formulairePagnet" method="post" >
                            <button disabled="true" type="button" class="btn btn-success noImpr addReservation" id="addReservation" >
                                <i class="glyphicon glyphicon-plus"></i>Ajouter une Reservation
                                <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">                    
                            </button>
                        </form>
                    '; 
                } 
                else{
                    echo ' 
                        <form name="formulairePagnet" method="post" >
                            <button type="button" class="btn btn-success noImpr addReservation" id="addReservation" >
                                <i class="glyphicon glyphicon-plus"></i>Ajouter une Reservation
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
                        <button type="button" class="btn btn-success noImpr addReservation" id="addReservation" >
                            <i class="glyphicon glyphicon-plus"></i>Ajouter une Reservation
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
                    <button type="button" class="btn btn-success noImpr addReservation" id="addReservation" >
                        <i class="glyphicon glyphicon-plus"></i>Ajouter une Reservation
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

    <div class="table-responsive">
            <div id="listerReservations"><!-- content will be loaded here --></div>
    </div>

    <div id="action_Vente" class="modal fade"  role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Retourner <span id="action_Reservation_titre"></span></h4>
                </div>
                  <div class="modal-body">
                    <div class="form-group">					    
                        <input type="hidden" class="form-control" id="inpt_spm_Reservation_idReservation"  >
                        <input type="hidden" class="form-control" id="inpt_spm_Reservation_numligne"  >
                    </div>
                    <span id="action_Reservation_contenu"></span>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 "> 
                      <button style="display:none" type="button" id="btn_retourner_Ligne" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Retourner</span> </button>
                      <button style="display:none" type="button" id="btn_supprimer_Ligne" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
                      <button style="display:none" type="button" id="btn_retourner_Reservation" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Retourner</span> </button>
                    </div>
                    <div class="col-sm-6 "> 
                      <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                    </div>
                  </div>
            </div>
        </div>
    </div>

    <div id="message_Vente" class="modal fade"  role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="message_Vente_titre" ></h4>
                </div>
                  <div class="modal-body">

                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-12 "> 
                      <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                    </div>
                  </div>
            </div>
        </div>
    </div>


    <!-- Fin de l'Accordion pour Tout les Paniers -->

    <script type="text/javascript" src="./scripts/insertionReservation.js"></script>

    <?php /*****************************/

    echo''.

    '<script>$(document).ready(function(){$("#AjoutLigne").click(function(){$("#AjoutLigneModal").modal();});});</script></body></html>';

    ?>



