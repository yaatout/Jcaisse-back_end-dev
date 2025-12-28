<?php

/*

Résumé :

Commentaire :

Version : 2.0

see also :

Auteur : Ibrahima DIOP; ,EL hadji mamadou korka

Date de création : 20/03/2016

Date derni�re modification : 20/04/2016; 15-04-2018

*/

session_start();

if(!$_SESSION['iduser']){

  header('Location:../index.php');

  }



require('../connection.php');



require('../declarationVariables.php');



$date = new DateTime();

$timezone = new DateTimeZone('Africa/Dakar');

$date->setTimezone($timezone);



$annee =$date->format('Y');

$mois =$date->format('m');

$jour =$date->format('d');

$heureString=$date->format('H:i:s');

$dateString=$annee.'-'.$mois.'-'.$jour ;

/*

$sql="select * from `".$nomtableStock."` where quantiteStockCourant!=0 order by idStock DESC";

$res=mysql_query($sql);

*/



$dateJour=@$_GET['dateJour'];

$dateJour_J=explode('-', $dateJour);

$dateAnnee=$dateJour_J[2].'-'.$dateJour_J[1].'-'.$dateJour_J[0];



if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 

  $sql="SELECT DISTINCT u.idutilisateur

  FROM `aaa-utilisateur` u

  INNER JOIN `aaa-acces` a ON a.idutilisateur = u.idutilisateur

  WHERE a.idBoutique ='".$_SESSION['idBoutique']."' && a.activer=1 ORDER BY u.idutilisateur DESC";

  $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

}

else{

  $sql="SELECT DISTINCT u.idutilisateur

  FROM `aaa-utilisateur` u

  INNER JOIN `aaa-acces` a ON a.idutilisateur = u.idutilisateur

  WHERE a.idBoutique ='".$_SESSION['idBoutique']."' && a.activer=1 ORDER BY u.idutilisateur DESC";

  $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

}



$data=array();

$i=1;

while($personnel=mysql_fetch_array($res)){



  $sqlP = mysql_query("SELECT EXISTS (SELECT * FROM `".$nomtablePagnetTampon."` WHERE idUser=".$personnel["idutilisateur"]." && datepagej='".$dateJour."') AS panier_exist");

  $reqP = mysql_fetch_array($sqlP);



  // var_dump($reqP['panier_exist']);

  $sqlV = mysql_query("SELECT EXISTS (SELECT * FROM `".$nomtableVersement."` WHERE idUser=".$personnel["idutilisateur"]." && (dateVersement  ='".$dateJour."' || dateVersement  ='".$dateAnnee."')) AS versement_exist");

  $reqV = mysql_fetch_array($sqlV); 



  // var_dump($reqV);

  if (($reqP['panier_exist'] == true) || ($reqV['versement_exist'] == true)) { 

    $sqlN="SELECT * from `aaa-utilisateur` where idutilisateur='".$personnel["idutilisateur"]."'";

    $resN=mysql_query($sqlN);

    $N_personnel = mysql_fetch_array($resN);

  

    $sqlApp="SELECT SUM(apayerPagnet)

      FROM `".$nomtablePagnetTampon."` p

      INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

      WHERE l.classe=5 && p.idClient=0 && p.type<>2  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.idUser='".$personnel["idutilisateur"]."'  ORDER BY p.idPagnet DESC";

    $resApp = mysql_query($sqlApp) or die ("persoonel requête 2".mysql_error());

    $S_App0 = mysql_fetch_array($resApp);

    $T_App0 = $S_App0[0] ;

  

  

    $sqlRC="SELECT SUM(apayerPagnet)

      FROM `".$nomtablePagnetTampon."` p

      INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

      WHERE l.classe=7  && p.idUser='".$personnel["idutilisateur"]."' && p.idClient=0 && p.type=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  ORDER BY p.idPagnet DESC";

    $resRC = mysql_query($sqlRC) or die ("persoonel requête 2".mysql_error());

    $S_Rcaisse0 = mysql_fetch_array($resRC);

    $T_Rcaisse0 = $S_Rcaisse0[0];

  

  

    if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 

      $T_ventes0 = 0 ;

      $T_ventesMobile0 = 0 ;

      

      if ($_SESSION['compte']==1) {

        # code...

      

        /************ Début ventes compte caisse ***************/

          $sqlP="SELECT *

          FROM `".$nomtablePagnetTampon."` p

          INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

          WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."'  && p.idClient=0  && p.verrouiller=1 && (p.idCompte=1 || p.idCompte=0) && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";

          $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());

          $T_ventesP = 0 ;

          while ($pagnet = mysql_fetch_array($resP)) {

            $T_ventesP = $T_ventesP + $pagnet['prixtotal'];

          }

      

          $sqlM="SELECT *

            FROM `".$nomtableMutuellePagnet."` m

            INNER JOIN `".$nomtableLigneTampon."` l ON l.idMutuellePagnet = m.idMutuellePagnet

            WHERE l.classe=0  && m.idUser='".$personnel["idutilisateur"]."'  && m.idClient=0  && m.verrouiller=1 && (m.idCompte=1 || m.idCompte=0) && m.datepagej ='".$dateJour."'  && m.type=0  ORDER BY m.idMutuellePagnet DESC";

          $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());

          $T_ventesM = 0 ;

          while ($mutuelle = mysql_fetch_array($resM)) {

            $T_ventesM = $T_ventesM + $mutuelle['apayerPagnet'];

            

        }

    

        $T_ventes0 = $T_ventesP + $T_ventesM ;

      /************ Fin ventes compte caisse ***************/



      /************ Début ventes compte mobile ***************/

          $sqlP="SELECT *

          FROM `".$nomtablePagnetTampon."` p

          INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

          WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."'  && p.idClient=0  && p.verrouiller=1 && p.idCompte<>0 && p.idCompte<>1 && p.idCompte<>2 && p.idCompte<>3 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";

          $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());

          $T_ventesP = 0 ;

          while ($pagnet = mysql_fetch_array($resP)) {

            $T_ventesP = $T_ventesP + $pagnet['prixtotal'];

          }

      

          $sqlM="SELECT *

            FROM `".$nomtableMutuellePagnet."` m

            INNER JOIN `".$nomtableLigneTampon."` l ON l.idMutuellePagnet = m.idMutuellePagnet

            WHERE l.classe=0  && m.idUser='".$personnel["idutilisateur"]."'  && m.idClient=0  && m.verrouiller=1 && m.idCompte<>0 && m.idCompte<>1 && m.idCompte<>2 && m.idCompte<>3 && m.datepagej ='".$dateJour."'  && m.type=0  ORDER BY m.idMutuellePagnet DESC";

          $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());

          $T_ventesM = 0 ;

          $taux = $mutuelle['taux'];

          while ($mutuelle = mysql_fetch_array($resM)) {

            $taux = $mutuelle['taux'];
            
            $T_ventesM = $T_ventesM + $mutuelle['prixtotal'] - ($mutuelle['prixtotal']* $taux / 100);

            // $T_ventesM = $T_ventesM + ($mutuelle['prixtotal']* $taux / 100);

          }

      

          $T_ventesMobile0 = $T_ventesP + $T_ventesM ;

        

        /************ Fin ventes compte mobile ***************/



      } else {

        # code...

      

        $sqlP="SELECT *

        FROM `".$nomtablePagnetTampon."` p

        INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

        WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."'  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";

        $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());

        $T_ventesP = 0 ;

        while ($pagnet = mysql_fetch_array($resP)) {

          $T_ventesP = $T_ventesP + $pagnet['prixtotal'];

        }

    

        $sqlM="SELECT *

          FROM `".$nomtableMutuellePagnet."` m

          INNER JOIN `".$nomtableLigneTampon."` l ON l.idMutuellePagnet = m.idMutuellePagnet

          WHERE l.classe=0  && m.idUser='".$personnel["idutilisateur"]."'  && m.idClient=0  && m.verrouiller=1 && m.datepagej ='".$dateJour."'  && m.type=0  ORDER BY m.idMutuellePagnet DESC";

        $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());

        $T_ventesM = 0 ;

        $taux = 0;

        while ($mutuelle = mysql_fetch_array($resM)) {

          $taux = $mutuelle['taux'];

          $T_ventesM = $T_ventesM + $mutuelle['prixtotal'] - ($mutuelle['prixtotal']* $taux / 100);
          // $T_ventesM = $T_ventesM + ($mutuelle['prixtotal']* $taux / 100);
        }

    

        $T_ventes0 = $T_ventesP + $T_ventesM ;

      }

    }

    else{

      $T_ventes0 = 0 ;

      $T_ventesMobile0 = 0 ;



      if ($_SESSION['compte']==1) {

        /************ Début ventes compte caisse ***************/

        $sqlV="SELECT SUM(prixtotal)

        FROM `".$nomtablePagnetTampon."` p

        INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

        WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."' && (p.idCompte=1 || p.idCompte=0) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";

        $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

        $S_ventes0 = mysql_fetch_array($resV);

        $T_ventes0 = $S_ventes0[0] ;

        /************ Fin ventes compte caisse ***************/



        /************ Début ventes compte mobile ***************/

        $sqlVMobile="SELECT SUM(prixtotal)

        FROM `".$nomtablePagnetTampon."` p

        INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

        WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."' && p.idCompte<>0 && p.idCompte<>1 && p.idCompte<>2 && p.idCompte<>3 && p.idCompte<>1000 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";

        $resVMobile = mysql_query($sqlVMobile) or die ("persoonel requête 2".mysql_error());

        $S_ventesMobile0 = mysql_fetch_array($resVMobile);

        $T_ventesMobile0 = $S_ventesMobile0[0] ;

        /************ Fin ventes compte mobile ***************/

        /************ Début ventes compte multiple ***************/
        $mntCpt = 0;
        $sqlV="SELECT *

        FROM  `".$nomtablePagnetTampon."` p WHERE p.idClient=0  && p.verrouiller=1 && p.idCompte=1000 && p.datepagej ='".$dateJour."' && p.type=0 ";

        $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

        while ($pagnet = mysql_fetch_array($resV)) {

            $idsComptes = explode('_', $pagnet['idsCpteMultiple']);
            // var_dump($idsComptes);
            foreach ($idsComptes as $key) {
                $sqlM="SELECT *
                FROM  `".$nomtableComptemouvement."` WHERE idCompte=".$key."  && mouvementLink=".$pagnet['idPagnet']."";

                $resM = mysql_query($sqlM) or die ("persoonel requête Mv".mysql_error());
                $cptMv = mysql_fetch_array($resM);
                $mntCpt = $cptMv['montant'];

                if ($key == '1' || $key == 1) {
                    $T_ventes0 = $T_ventes0 + $mntCpt;
                } else {
                    $T_ventesMobile0 = $T_ventesMobile0 + $mntCpt;
                }
            }

        }

        /************ Fin ventes compte multiple ***************/


        if($_SESSION['Pays']=='Canada'){ 

              /************ Début ventes compte caisse ***************/

                $sqlVP="SELECT SUM(prixtotalTvaP)

                FROM `".$nomtablePagnetTampon."` p

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

                WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."' && (p.idCompte=1 || p.idCompte=0) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";

                $resVP = mysql_query($sqlVP) or die ("persoonel requête 2".mysql_error());

                $StvaP_ventes0 = mysql_fetch_array($resVP);



                $sqlVR="SELECT SUM(prixtotalTvaR)

                FROM `".$nomtablePagnetTampon."` p

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

                WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."' && (p.idCompte=1 || p.idCompte=0) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";

                $resVR = mysql_query($sqlVR) or die ("persoonel requête 2".mysql_error());

                $StvaR_ventes0 = mysql_fetch_array($resVR);



                $T_ventes0 = $T_ventes0 + $StvaP_ventes0[0] + $StvaR_ventes0[0] ;

              /************ Fin ventes compte caisse ***************/



              /************ Début ventes compte mobile ***************/

                $sqlVPMobile="SELECT SUM(apayerPagnetTvaP)

                FROM `".$nomtablePagnetTampon."` p

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

                WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."' && p.idCompte<>0 && p.idCompte<>1 && p.idCompte<>2 && p.idCompte<>3 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";

                $resVPMobile = mysql_query($sqlVPMobile) or die ("persoonel requête 2".mysql_error());

                $StvaP_ventesMobile0 = mysql_fetch_array($resVPMobile);



                $sqlVRMobile="SELECT SUM(apayerPagnetTvaR)

                FROM `".$nomtablePagnetTampon."` p

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

                WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."' && p.idCompte<>0 && p.idCompte<>1 && p.idCompte<>2 && p.idCompte<>3 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";

                $resVRMobile = mysql_query($sqlVRMobile) or die ("persoonel requête 2".mysql_error());

                $StvaR_ventesMobile0 = mysql_fetch_array($resVRMobile);



                $T_ventesMobile0 = $T_ventesMobile0 + $StvaP_ventesMobile0[0] + $StvaR_ventesMobile0[0] ;

              /************ Fin ventes compte mobile ***************/

        }

      }

      else{

          $sqlV="SELECT SUM(prixtotal)

          FROM `".$nomtablePagnetTampon."` p

          INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

          WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."' && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";

          $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

          $S_ventes0 = mysql_fetch_array($resV);

          $T_ventes0 = $S_ventes0[0] ;



          if($_SESSION['Pays']=='Canada'){ 

            $sqlVP="SELECT SUM(prixtotalTvaP)

            FROM `".$nomtablePagnetTampon."` p

            INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

            WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."' && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";

            $resVP = mysql_query($sqlVP) or die ("persoonel requête 2".mysql_error());

            $StvaP_ventes0 = mysql_fetch_array($resVP);



            $sqlVR="SELECT SUM(prixtotalTvaR)

            FROM `".$nomtablePagnetTampon."` p

            INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

            WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."' && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";

            $resVR = mysql_query($sqlVR) or die ("persoonel requête 2".mysql_error());

            $StvaR_ventes0 = mysql_fetch_array($resVR);

            

            $T_ventes0 = $T_ventes0 + $StvaP_ventes0[0] + $StvaR_ventes0[0] ;



          }

      }

    }  



    $T_Rventes0 = 0;

    $T_RventesMobile0 = 0;

    if ($_SESSION['compte']==1) {

      $sqlR="SELECT SUM(apayerPagnet)

        FROM `".$nomtablePagnetTampon."` p

        INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

        WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."' && (p.idCompte=1 || p.idCompte=0) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=1  ORDER BY p.idPagnet DESC";

      $resR = mysql_query($sqlR) or die ("persoonel requête 2".mysql_error());

      $S_Rventes0 = mysql_fetch_array($resR);

      $T_Rventes0 = $S_Rventes0[0];



      $sqlRMobile="SELECT SUM(apayerPagnet)

        FROM `".$nomtablePagnetTampon."` p

        INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

        WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."' && p.idCompte<>0 && p.idCompte<>1 && p.idCompte<>2 && p.idCompte<>3 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=1  ORDER BY p.idPagnet DESC";

      $resRMobile = mysql_query($sqlRMobile) or die ("persoonel requête 2".mysql_error());

      $S_RventesMobile0 = mysql_fetch_array($resRMobile);

      $T_RventesMobile0 = $S_RventesMobile0[0];      



    }else{

      $sqlR="SELECT SUM(apayerPagnet)

        FROM `".$nomtablePagnetTampon."` p

        INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

        WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."'  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=1  ORDER BY p.idPagnet DESC";

      $resR = mysql_query($sqlR) or die ("persoonel requête 2".mysql_error());

      $S_Rventes0 = mysql_fetch_array($resR);

      $T_Rventes0 = $S_Rventes0[0];

      

    }

    if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
      $sqlSv="SELECT *

      FROM `".$nomtableDesignation."` d

      INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

      INNER JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

      WHERE l.classe = 1  && p.idUser='".$personnel["idutilisateur"]."' && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 ";

      $resSv = mysql_query($sqlSv) or die ("persoonel requête 2".mysql_error());

      $T_servicesP = 0;

      while ($pagnet = mysql_fetch_array($resSv)) {

        $T_servicesP = $T_servicesP + $pagnet['prixtotal'];

      }


      $sqlSvM="SELECT *

      FROM `".$nomtableDesignation."` d

      INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation
      
      INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet

      WHERE l.classe = 1 && m.idUser='".$personnel["idutilisateur"]."' && m.idClient=0  && m.verrouiller=1 && m.datepagej ='".$dateJour."' && m.type=0 ";

      // echo $sqlSvM;

      $resSvM = mysql_query($sqlSvM) or die ("persoonel requête 2".mysql_error());

      $T_venteServM = 0;
      $taux = 0;

      while ($mutuelle = mysql_fetch_array($resSvM)) {
          $taux = $mutuelle['taux'];
          
          $T_venteServM = $T_venteServM + $mutuelle['prixtotal'] - ($mutuelle['prixtotal']* $taux / 100);
          // $T_venteServM = $T_venteServM + ($mutuelle['prixtotal']* $taux / 100);

      }
      //******************** fin vente service mutuelle***************** */
      // var_dump($taux) ; 
      $T_services0 = 0;
      $T_services0 = $T_servicesP + $T_venteServM;
    }
    else{

      $sqlSv="SELECT *

      FROM `".$nomtableDesignation."` d

      INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

      INNER JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

      WHERE l.classe = 1  && p.idUser='".$personnel["idutilisateur"]."' && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 ";

      $resSv = mysql_query($sqlSv) or die ("persoonel requête 2".mysql_error());

      $T_services0 = 0;

      while ($pagnet = mysql_fetch_array($resSv)) {

        $T_services0 = $T_services0 + $pagnet['prixtotal'];

      }
    }

  

    $sqlR="SELECT DISTINCT p.idPagnet

      FROM `".$nomtablePagnetTampon."` p

      INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

      WHERE (l.classe=0 || l.classe=1)  && p.idUser='".$personnel["idutilisateur"]."'  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";

    $resR = mysql_query($sqlR) or die ("persoonel requête 2".mysql_error());

    $T_remises0 = 0 ;

    $S_remises0 = 0;

    while ($pagnet = mysql_fetch_array($resR)) {

      $sqlS="SELECT remise

      FROM `".$nomtablePagnetTampon."`

      where idClient=0 && idUser='".$personnel["idutilisateur"]."' && verrouiller=1 && datepagej ='".$dateJour."' && type=0 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

      $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

      $S_remises0 = mysql_fetch_array($resS);

      $T_remises0 = $S_remises0[0] + $T_remises0;

    }

  

    $sqlD="SELECT SUM(apayerPagnet)

      FROM `".$nomtablePagnetTampon."` p

      INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

      WHERE l.classe=2 && p.idClient=0 && p.idUser='".$personnel["idutilisateur"]."' && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";

    $resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());

    $S_depenses0 = mysql_fetch_array($resD);

    $T_depenses0 = $S_depenses0[0] ;





    $T_versementClientMobile = 0 ;  

    if ($_SESSION['compte']==1) {

      $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient!=0 && idUser='".$personnel["idutilisateur"]."' && (dateVersement  ='".$dateJour."' || dateVersement  ='".$dateAnnee."') && (idCompte=1 || idCompte=0)  ORDER BY idVersement DESC";

      $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());

      $T_versementClient = mysql_fetch_array($resP5) ;

      

      $sqlP5Mobile="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient!=0 && idUser='".$personnel["idutilisateur"]."' && (dateVersement  ='".$dateJour."' || dateVersement  ='".$dateAnnee."') && (idCompte<>0 && idCompte<>1 && idCompte<>2 && idCompte<>3) ORDER BY idVersement DESC";

      $resP5Mobile = mysql_query($sqlP5Mobile) or die ("persoonel requête 2".mysql_error());

      $T_versementClientMobile = mysql_fetch_array($resP5Mobile) ;



    } else {

      # code...

      $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient!=0 && idUser='".$personnel["idutilisateur"]."' && (dateVersement  ='".$dateJour."' || dateVersement  ='".$dateAnnee."')  ORDER BY idVersement DESC";

      $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());

      $T_versementClient = mysql_fetch_array($resP5) ;

    }

  

    $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where idFournisseur!=0 && idUser='".$personnel["idutilisateur"]."' && (dateVersement  ='".$dateJour."' || dateVersement  ='".$dateAnnee."')  ORDER BY idVersement DESC";

    $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());

    $T_versementFournisseur = mysql_fetch_array($resP5) ;

  

    $Total0=$T_App0 + $T_ventes0 - $T_Rventes0 + $T_ventesMobile0 - $T_RventesMobile0 + $T_versementClient[0] + $T_versementClientMobile[0] - $T_versementFournisseur[0] - $T_Rcaisse0 + $T_services0 - $T_remises0 - $T_depenses0;

  

    $rows = array();

  

    $rows[] = '<b>'.$N_personnel["prenom"].' &nbsp; '.strtoupper($N_personnel["nom"]).'</b>';

    $rows[] = '<b>'.number_format(($T_App0  * $_SESSION['devise']), 0, ',', ' ').'</b>';

    $rows[] = '<b>'.number_format(($T_Rcaisse0  * $_SESSION['devise']), 0, ',', ' ').' </b>';

    $rows[] = '<b>'.number_format((($T_ventes0 - $T_Rventes0)  * $_SESSION['devise']), 2, ',', ' ').'</b>';



    if ($_SESSION['compte']==1) { 

      $rows[] = '<b>'.number_format((($T_ventesMobile0 - $T_RventesMobile0)  * $_SESSION['devise']), 2, ',', ' ').'</b>';

    }

    $rows[] = '<b>'.number_format(($T_services0  * $_SESSION['devise']), 0, ',', ' ').'</b>';

    $rows[] = '<b>'.number_format(($T_versementClient[0]  * $_SESSION['devise']), 0, ',', ' ').'</b>';



    if ($_SESSION['compte']==1) { 

      $rows[] = '<b>'.number_format(($T_versementClientMobile[0]  * $_SESSION['devise']), 0, ',', ' ').'</b>';

    }

    $rows[] = '<b>'.number_format(($T_versementFournisseur[0]  * $_SESSION['devise']), 0, ',', ' ').'</b>';

    $rows[] = '<b>'.number_format(($T_depenses0  * $_SESSION['devise']), 0, ',', ' ').'</b>';

    $rows[] = '<span style="color:orange"><b>'.number_format(($T_remises0  * $_SESSION['devise']), 0, ',', ' ').'</b></span>';

    $rows[] = '<b>'.number_format(($Total0  * $_SESSION['devise']), 2, ',', ' ').' </b>';

  

    $data[] = $rows;

    $i=$i + 1;

  } 



 

}





$results = ["sEcho" => 1,

          "iTotalRecords" => count($data),

          "iTotalDisplayRecords" => count($data),

          "aaData" => $data ];



echo json_encode($results);



?>

