<?php

session_start();
if(!$_SESSION['iduser']){ 
  header('Location:../index.php');
  }

require('../connection.php');

require('../declarationVariables.php');

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

$msg_info='';



/**Debut Button Ajouter Pagnet**/

    if (isset($_POST['btnSavePagnetVente'])) {

        $paieMois=$annee-$mois;

        $annee=2022;

        $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."' and YEAR(datePs)='".$annee."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";

        $res0=mysql_query($sql0);

        if(mysql_num_rows($res0)){

            if($jour > 4){

                $paie=mysql_fetch_array($res0);

                $periode_paie=explode("-", $paie['datePS']);

                $mois_periode=$periode_paie[1]; $annee_periode=$periode_paie[0];

                $periode=calendrierMois($mois_periode);

                $msg_paiement="<b><span style='color:red;'>VOUS NE POUVEZ PAS CREER UN PANIER.</span></b></br></br>

                <span style='color:green;'>VEUILLEZ CONTACTER LE PROPRIETAIRE POUR VERIFIER SI LE PAIEMENT DU MOIS DE </span><span style='color:red;'>".$periode." ".$annee_periode."</span><span style='color:green;'> DE LA PLATEFORME JCAISSE EST EFFECTUE.</span>";

            }

            else{

                $sql1="select * from `".$nomtableJournal."` where annee=".$annee." and mois=".$mois;

                $res1=mysql_query($sql1);

        

                $query = mysql_query("SELECT * FROM `".$nomtablePagnet."` where iduser='".$_SESSION['iduser']."' && idClient=0 && datepagej ='".$dateString2."' && verrouiller=0");

                $nbre_fois=mysql_num_rows($query);

        

                if(mysql_num_rows($res1)){

                    $sql2="select * from `".$nomtablePage."` where datepage='".$dateString."'";

                    $res2=mysql_query($sql2);

                    if(mysql_num_rows($res2)){

        

                        if($nbre_fois<2){

                            $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,taux,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0,0)";

                            $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());

                        }

        

                    }

                    else{

                        $sql0="insert into `".$nomtablePage."` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('".$dateString."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0)";

                        $res0=@mysql_query($sql0) or die ("insertion page journal impossible-1".mysql_error());

        

                        if($nbre_fois<2){

                            $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,taux,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0,0)";

                            $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error() );

                        }

        

                    }

                }

                else{

                    $sql0="insert into `".$nomtableJournal."` (mois,annee,totalVente,totalVersement,totalBon,totalFrais) values(".$mois.",".$annee.",0,0,0,0)";

                    $res0=@mysql_query($sql0) or die ("insertion journal impossible-1".mysql_error());

        

                    $sql2="insert into `".$nomtablePage."` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('".$dateString."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0)";

                    $res2=@mysql_query($sql2) or die ("insertion page journal impossible-2".mysql_error());

        

                    if($nbre_fois<2){

                            $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,taux,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0,0)";

                            $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error() );

                    }

        

                }

            }

        }

        else{

            $sql1="select * from `".$nomtableJournal."` where annee=".$annee." and mois=".$mois;

            $res1=mysql_query($sql1);



            $query = mysql_query("SELECT * FROM `".$nomtablePagnet."` where iduser='".$_SESSION['iduser']."' && idClient=0 && datepagej ='".$dateString2."' && verrouiller=0");

            $nbre_fois=mysql_num_rows($query);



            if(mysql_num_rows($res1)){

                $sql2="select * from `".$nomtablePage."` where datepage='".$dateString."'";

                $res2=mysql_query($sql2);

                if(mysql_num_rows($res2)){



                    if($nbre_fois<2){

                        $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,taux,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0,0)";

                        $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());

                    }



                }

                else{

                    $sql0="insert into `".$nomtablePage."` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('".$dateString."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0)";

                    $res0=@mysql_query($sql0) or die ("insertion page journal impossible-1".mysql_error());



                    if($nbre_fois<2){

                        $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,taux,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0,0)";

                        $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error() );

                    }



                }

            }

            else{

                $sql0="insert into `".$nomtableJournal."` (mois,annee,totalVente,totalVersement,totalBon,totalFrais) values(".$mois.",".$annee.",0,0,0,0)";

                $res0=@mysql_query($sql0) or die ("insertion journal impossible-1".mysql_error());



                $sql2="insert into `".$nomtablePage."` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('".$dateString."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0)";

                $res2=@mysql_query($sql2) or die ("insertion page journal impossible-2".mysql_error());



                if($nbre_fois<2){

                        $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,taux,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0,0)";

                        $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error() );

                }



            } 

        }



    }

/**Fin Button Ajouter Pagnet**/

/**Debut Button Ajouter Imputation**/

   else if (isset($_POST['btnSavePagnetImputation'])) {



        $paieMois=$annee-$mois;

        $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."'  and YEAR(datePs)='".$annee."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";

        $res0=mysql_query($sql0);

        if(mysql_num_rows($res0)){

            if($jour > 4){

                $paie=mysql_fetch_array($res0);

                $periode_paie=explode("-", $paie['datePS']);

                $mois_periode=$periode_paie[1]; $annee_periode=$periode_paie[0];

                $periode=calendrierMois($mois_periode);

                $msg_paiement="<b><span style='color:red;'>VOUS NE POUVEZ PAS CREER UN PANIER.</span></b></br></br>

                <span style='color:green;'>VEUILLEZ CONTACTER LE PROPRIETAIRE POUR VERIFIER SI LE PAIEMENT DU MOIS DE </span><span style='color:red;'>".$periode." ".$annee_periode."</span><span style='color:green;'> DE LA PLATEFORME JCAISSE EST EFFECTUE.</span>";

            }

            else{



                $query = mysql_query("SELECT * FROM `".$nomtableMutuellePagnet."` where iduser='".$_SESSION['iduser']."' && idClient=0 && datepagej ='".$dateString2."' && verrouiller=0");

                $nbre_fois=mysql_num_rows($query);

        

                if($nbre_fois<2){

                    $sql6="insert into `".$nomtableMutuellePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,idMutuelle,taux,remise,verrouiller,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0)";

                    $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());

                }

            }

        }

        else{



            $query = mysql_query("SELECT * FROM `".$nomtableMutuellePagnet."` where iduser='".$_SESSION['iduser']."' && idClient=0 && datepagej ='".$dateString2."' && verrouiller=0");

            $nbre_fois=mysql_num_rows($query);



            if($nbre_fois<2){

                $sql6="insert into `".$nomtableMutuellePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,idMutuelle,taux,remise,verrouiller,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0)";

                $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());

            }

        }



    }

/**Fin Button Ajouter Imputation**/


/**Debut Button Terminer Pagnet**/

 else if (isset($_POST['btnImprimerFacture'])) {

        if (isset($_POST['remise']) || isset($_POST['versement'])) {

            // code...

            $idPagnet=@$_POST['idPagnet'];

            if(@$_POST['remise']==''){
                $remise=0;
            }
            else{
                $remise=@$_POST['remise'];
            }
            // $apayerPagnet=$totalp-$remise;
            // if(@$_POST['versement']==''){
            //     $versement=0;
            //     $monaie=0;
            // }
            // else{
            //     $versement=@$_POST['versement'];
            //     $monaie=$versement-$apayerPagnet;
            // }


            $sql="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";

            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

            $pagnet = mysql_fetch_assoc($res) ;



            $sqlL="SELECT * FROM `".$nomtableLigne."` WHERE idPagnet='".$idPagnet."' ";

            $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

            

            /*****Debut Nombre de Ligne ajoutée****/

            $query = mysql_query("SELECT * FROM `".$nomtableLigne."` WHERE idPagnet='".$idPagnet."' ");

            $nbre_fois=mysql_num_rows($query);

            /*****Fin Nombre de Ligne ajoutée****/



            /*****Debut Difference entre Total Panier et Remise****/

            $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` WHERE idPagnet='".$idPagnet."' ";

            $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

            $TotalT = mysql_fetch_array($resT) ;



            $difference=$TotalT[0] - $remise;

            /*****Fin Difference entre Total Panier et Remise****/

            if($pagnet['verrouiller']==0){

                if($nbre_fois>0){

                    if($difference>=0){

                        mysql_query("SET AUTOCOMMIT=0;");

                        mysql_query("START TRANSACTION;");

                        $i=0;

                        $j=0;

                        while ($ligne=mysql_fetch_assoc($resL)){

                            if($ligne['classe']==0){

                                $sqlS="SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation='".$ligne['idDesignation']."' ";

                                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                                $designation = mysql_fetch_assoc($resS) ;

                                    if(mysql_num_rows($resS)){

                                        $restant=$ligne['quantite'];

                                        $sqlD="SELECT * FROM `".$nomtableStock."` WHERE idDesignation='".$ligne['idDesignation']."' ORDER BY idStock ASC ";

                                        $resD=mysql_query($sqlD) or die ("select designation impossible =>".mysql_error());

                                        $stock0 = mysql_fetch_assoc($resD);

                                        $quantiteinitial=$stock0['quantiteStockCourant'] - $restant;

                                        if($quantiteinitial >= 0){

                                            $sqlS0="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$quantiteinitial."' WHERE idStock='".$stock0['idStock']."' ";

                                            $resS0=mysql_query($sqlS0) or die ("update stock impossible =>".mysql_error());

                                            if($resS0){

                                                $i=$i + 1;;

                                            }

                                        }

                                        else{

                                            $sqlE="SELECT * FROM `".$nomtableStock."` WHERE idDesignation='".$ligne['idDesignation']."' ORDER BY idStock ASC ";

                                            $resE=mysql_query($sqlE) or die ("select designation impossible =>".mysql_error());

                                            $k=0;

                                            $l=0;

                                            while ($stock = mysql_fetch_assoc($resE)) {

                                                if($restant>= 0){

                                                    $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                                                    if($quantiteStockCourant > 0){

                                                        $sqlS1="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$quantiteStockCourant."' WHERE idStock='".$stock['idStock']."' ";

                                                        $resS1=mysql_query($sqlS1) or die ("update stock impossible =>".mysql_error());

                                                        if($resS1){

                                                            $k=$k + 1;

                                                        }

                                                    }

                                                    else{

                                                        $sqlS2="UPDATE `".$nomtableStock."` SET quantiteStockCourant=0 WHERE idStock='".$stock['idStock']."' ";

                                                        $resS2=mysql_query($sqlS2) or die ("update stock impossible =>".mysql_error());

                                                        if($resS2){

                                                            $k=$k + 1;

                                                        }

                                                    }

                                                    $restant= $restant - $stock['quantiteStockCourant'] ;

                                                    $l=$l + 1;

                                                }

                                            }

                                            if($k==$l){

                                                $i=$i + 1;

                                            }

                                        }

                                    }

                            }

                            if($ligne['classe']==1 ||  $ligne['classe']==2 || $ligne['classe']==5 || $ligne['classe']==7){

                                $i=$i + 1;;

                            }

                            $j=$j + 1;

                        }



                        $totalp=$TotalT[0];

                        // if(@$_POST['remise']==''){

                        //     $remise=0;

                        // }

                        // else{

                        //     $remise=@$_POST['remise'];

                        // }

                        $apayerPagnet=$totalp-$remise- (($totalp * $pagnet['taux'])/100);

                        if(@$_POST['versement']==''){

                            $versement=0;

                            $monaie=0;

                        }

                        else{

                            $versement=@$_POST['versement'];

                            $monaie=$versement-$apayerPagnet;

                        }



                        if ($_SESSION['compte']==1) {

                            if(isset($_POST['compte'])) {

                                $idCompte=$_POST['compte'];

                                

                                $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ORDER BY numLigne LIMIT 1 ";

                                $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

                                $lignes = mysql_fetch_array($resL) ;

                                if ($lignes['classe'] == '2') {

                                    

                                    $description="Dépenses";

                                    $operation='retrait';

                    

                                    $sql8="UPDATE `".$nomtablePagnet."` set idCompte=".$idCompte." where  idPagnet=".$idPagnet;

                                    $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());

                    

                                    $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$apayerPagnet." where  idCompte=".$idCompte;

                                    $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

                    

                                    $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser) values(".$apayerPagnet.",'".$operation."',".$idCompte.",'".$description."','".$dateHeures."','".$dateHeures."',".$idPagnet.",".$_SESSION['iduser'].")";

                                    $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error());

                                    

                                }else {

                                    

                                    if(isset($_POST['avanceInput']) && $_POST['avanceInput'] > 0 && $idCompte == '2'){

                                        // $avanceInput=$_POST['avanceInput'];

                                        $montant=htmlspecialchars(trim($_POST['avanceInput']));

                                        $compteAvance=htmlspecialchars(trim($_POST['compteAvance']));

                                        $idClient=$pagnet['idClient'];

                                        $dateVersement=$dateString2;

                                        

                                        $sql30="UPDATE `".$nomtablePagnet."` set avance=".$montant." where idPagnet=".$idPagnet;

                                        $res30=mysql_query($sql30) or die ("update avance pagnet impossible =>".mysql_error());



                                        $sql2="insert into `".$nomtableVersement."` (idClient,paiement,montant,dateVersement,heureVersement,idCompte,idPagnet,iduser) values(".$idClient.",'avance client',".$montant.",'".$dateVersement."','".$heureString."',".$compteAvance.",".$idPagnet.",".$_SESSION['iduser'].")";

                                        $res2=mysql_query($sql2) or die ("insertion Versement impossible =>".mysql_error());

                                        // $solde=$montant+$client['solde'];

                                        $reqv="SELECT idVersement from `".$nomtableVersement."` order by idVersement desc limit 1";

                                        $resv=mysql_query($reqv) or die ("persoonel requête 2".mysql_error());

                                        $v = mysql_fetch_array($resv);

                                        $idVersement=$v['idVersement'];



                                        $sql3="UPDATE `".$nomtableClient."` set solde=solde-".$montant." where idClient=".$idClient;

                                        $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());



                                        $operation='depot';

                                        $description="Avance bon";



                                        $sql7="UPDATE `".$nomtableCompte."` set montantCompte=montantCompte + ".$montant." where idCompte=".$compteAvance;

                                        $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());



                                        $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,mouvementLink,idUser) values(".$montant.",'".$operation."',".$compteAvance.",'".$description."','".$dateHeures."','".$dateHeures."',".$idVersement.",".$idPagnet.",".$_SESSION['iduser'].")";

                                        $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

                                        

                                        $operation2='retrait';

                                        $idCompteBon='2';

                                        $description2="Bon encaissé";



                                        $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$montant." where  idCompte=".$idCompteBon;

                                        $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());



                                        $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,mouvementLink,idUser) values(".$montant.",'".$operation2."',".$idCompteBon.",'".$description2."','".$dateHeures."','".$dateHeures."',".$idVersement.",".$idPagnet.",".$_SESSION['iduser'].")";

                                        $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

                                            

                                    }

                                    $operation='depot';

                                    if ($idCompte == '2') {

                                        $description="Bon";

                                        $idClient=$pagnet['idClient'];



                                        $sql3="UPDATE `".$nomtableClient."` set solde=solde+".$apayerPagnet." where idClient=".$idClient;

                                        $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());

                                        

                                        $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where verrouiller='1' && idClient=".$idClient." && (type=0 || type=30) ";

                                        $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());

                                        $TotalP = mysql_fetch_array($res18) ;

        

                                        $sql19="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." && type=1 ";

                                        $res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());

                                        $TotalR = mysql_fetch_array($res19) ;

        

                                        $total=$TotalP[0] - $TotalR[0];

                                        

                                        $sql20="UPDATE `".$nomtableBon."` set montant=".$total.", date='".$dateString."' where idClient=".$idClient;

                                        $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());



                                    } else {

                                        $description="Encaissement vente";

                                    }

                    

                                    $sql8="UPDATE `".$nomtablePagnet."`  set  idCompte=".$idCompte." where  idPagnet=".$idPagnet;

                                    $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());

                    

                                    $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte + ".$apayerPagnet." where  idCompte=".$idCompte;

                                    $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

                    

                                    $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser) values(".$apayerPagnet.",'".$operation."',".$idCompte.",'".$description."','".$dateHeures."','".$dateHeures."',".$idPagnet.",".$_SESSION['iduser'].")";

                                    $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error());



                                }

                            

                            }

                            if ($remise != 0) {

                                $operation='depot';

                                $idCompteRemise = '3';

                                $description="Remise vente";

                

                                $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte + ".$remise." where  idCompte=".$idCompteRemise;

                                $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

                

                                $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser) values(".$remise.",'".$operation."',".$idCompteRemise.",'".$description."','".$dateHeures."','".$dateHeures."',".$idPagnet.",".$_SESSION['iduser'].")";

                                $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error());

                

                            }



                        }



                        $sql3="UPDATE `".$nomtablePagnet."` SET verrouiller='1', remise='".$remise."',totalp='".$totalp."',apayerPagnet='".$apayerPagnet."',versement='".$versement."',restourne='".$monaie."' WHERE idPagnet='".$idPagnet."' ";

                        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());



                        if(($i==$j) && $res3){

                            mysql_query("COMMIT;");

                        }

                        else{

                            mysql_query("ROLLBACK;");

                            echo "<p>PROBLEME CONNECTION INTERNET .</br></br> Veuillez terminer le panier numéro ".$idPagnet.".</p>";

                        }

                        

                    }

                    else {

                        echo "<p>IMPOSSIBLE.</br></br> Avant de terminer le panier numéro ".$idPagnet.", il faut verifier la remise.</p>";

                    }

                    

                }

                else {

                    echo "<p>IMPOSSIBLE.</br></br> Avant de terminer le panier numéro ".$idPagnet.", il faut au moins ajouter un produit.</p>";

                }

            }



        }else 

        {}

    }

/**Fin Button /**Debut Button Terminer Pagnet**/


/**Debut Button Annuler Pagnet**/

    if (isset($_POST['btnAnnulerPagnet']) ) {

        $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));
    
        $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";

        $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

        while ($ligne=mysql_fetch_assoc($resL)){

            //on fait la suppression de cette ligne dans la table ligne

            $sql3="DELETE FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";

            $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

        }
        // suppression du pagnet aprés su^ppression de ses lignes

        $sqlR="DELETE FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet;

        $resR=@mysql_query($sqlR) or die ("mise à jour client  impossible".mysql_error());

        echo "1";

    }

/**Fin Button Annuler Pagnet**/

/**Debut Button Retourner Pagnet**/

   else if (isset($_POST['btnRetournerPagnet'])) {

        $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));

        $sqlP="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";

        $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());

        $pagnet = mysql_fetch_assoc($resP) ;

        if($pagnet['type']==0 || $pagnet['type']==30){

            $sqlL="SELECT * FROM `".$nomtableLigne."` WHERE idPagnet='".$idPagnet."' ";

            $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

            mysql_query("SET AUTOCOMMIT=0;");

            mysql_query("START TRANSACTION;");

            $i=0;

            $j=0;

            while ($ligne=mysql_fetch_assoc($resL)){

                if($ligne['classe']==0){

                    $sqlS="SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation='".$ligne['idDesignation']."' ";

                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                    $designation = mysql_fetch_assoc($resS) ;

                        if(mysql_num_rows($resS)){

                            $sqlD="SELECT * FROM `".$nomtableStock."` WHERE idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";

                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                            $retour=$ligne['quantite'];

                            $k=0;

                            $l=0;

                            while ($stock = mysql_fetch_assoc($resD)) {

                                if($retour >= 0){

                                    $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                    if($stock['quantiteStockinitial'] >= $quantiteStockCourant){

                                        $sqlS="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$quantiteStockCourant."' WHERE idStock='".$stock['idStock']."' ";

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                        if($resS){

                                            $k=$k + 1;

                                        }

                                    }

                                    else{

                                        $sqlS="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$stock['quantiteStockinitial']."' WHERE idStock='".$stock['idStock']."' ";

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                        if($resS){

                                            $k=$k + 1;

                                        }

                                        

                                    }

                                    $retour=($retour + $stock['quantiteStockCourant']) - $stock['quantiteStockinitial'] ;

                                    $l=$l + 1;

                                }

                            }

                            if($k==$l){

                                $i=$i + 1;

                            }

                        }

                }

                if($ligne['classe']==1 ||  $ligne['classe']==2 || $ligne['classe']==5 || $ligne['classe']==7){

                    $i=$i + 1;

                }

                $j=$j + 1;

            }



            // suppression du pagnet aprés su^ppression de ses lignes

            $sqlRP="UPDATE `".$nomtablePagnet."` set type=2 where idPagnet=".$idPagnet;

            $resRP=@mysql_query($sqlRP) or die ("mise à jour client  impossible".mysql_error());



            if ($_SESSION['compte']==1) {

                $description="Retour panier";

                $operation='retrait';

            

                $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$pagnet['apayerPagnet']." where  idCompte=".$pagnet['idCompte'];

                $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

                

                if ($pagnet['remise']!='' && $pagnet['remise']!=0) {

                    $sql8="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$pagnet['remise']." where  idCompte=3";

                    $res8=@mysql_query($sql8) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

                }

                //Annulation des mouvements relatifs à ce panier

                $sql="DELETE FROM `".$nomtableComptemouvement."` where  mouvementLink=".$idPagnet."";

                $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());



                if ($pagnet['avance'] != 0) {

                    

                    $sql0="DELETE FROM `".$nomtableVersement."` where  idPagnet=".$idPagnet."";

                    $res0=@mysql_query($sql0) or die ("mise à jour idClient ".mysql_error());

                }



                if ($pagnet['idCompte'] == 2) {

                    # code...

                    /************************************** UPDATE BON Et DU REMISE******************************************/

                    $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where verrouiller='1' && idClient=".$pagnet['idClient']." && (type=0 || type=30) ";

                    $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());

                    $TotalP = mysql_fetch_array($res18) ;



                    $sql19="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$pagnet['idClient']." && type=1 ";

                    $res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());

                    $TotalR = mysql_fetch_array($res19) ;



                    $total=$TotalP[0] - $TotalR[0];

                    

                    $sql20="UPDATE `".$nomtableBon."` set montant=".$total.", date='".$dateString."' where idClient=".$pagnet['idClient'];

                    $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());

                    

                    $sql3="UPDATE `".$nomtableClient."` set solde=solde-".$pagnet['apayerPagnet']." where idClient=".$pagnet['idClient'];

                    $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());



                }

            }

            if(($i==$j) && $resRP){

                mysql_query("COMMIT;");

            }

            else{

                mysql_query("ROLLBACK;");

                echo "<p>PROBLEME CONNECTION INTERNET .</br></br> Veuillez retourner le panier numéro ".$idPagnet.".</p>";

            }

        }
        echo '1';
    }

/**Fin Button Retourner Pagnet**/

/**Debut Button Annuler Ligne d'un Pagnet**/

   else if (isset($_POST['btnRetourAvant'])) {

        $numligne=$_POST['numligne'];

        // $forme=$_POST['forme'];

        //on fait la suppression de cette ligne dans la table ligne

        $sql3="DELETE FROM `".$nomtableLigne."` where numligne=".$numligne;

        $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

        echo '1';

    }

/**Fin Button Annuler Ligne d'un Pagnet**/


/**Debut Button Retourner Ligne d'un Pagnet**/

   else if (isset($_POST['btnRetourApres'])) {

        $numligne=$_POST['numligne'];

        $idStock=$_POST['idStock'];

        $designation=$_POST['designation'];

        $idPagnet=$_POST['idPagnet'];

        $quantite=$_POST['quantite'];

        $forme=$_POST['forme'];

        $prixPublic=$_POST['prixPublic'];

        $prixtotal=$_POST['prixtotal'];

        $totalp=$_POST['totalp'];


        $sqlL="SELECT * FROM `".$nomtableLigne."` WHERE numligne='".$numligne."' ";

        $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

        $ligne = mysql_fetch_assoc($resL) ;


        $sqlP="SELECT * FROM `".$nomtablePagnet."` WHERE idPagnet='".$idPagnet."' ";

        $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());

        $pagnet = mysql_fetch_assoc($resP) ;



        $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` WHERE idPagnet='".$idPagnet."' && numligne<>'".$numligne."' ";

        $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

        $TotalT = mysql_fetch_array($resT) ;



        $difference=$TotalT[0] + ($quantite * $ligne['prixPublic']) - $pagnet["remise"];

        $valide=0;

        if($difference >= 0 && $ligne['quantite'] >= $quantite){

            mysql_query("SET AUTOCOMMIT=0;");

            mysql_query("START TRANSACTION;");

            $i=0;

            $j=0;

            $k=0;

            if($ligne['classe']==0){

                $sqlS="SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation='".$ligne['idDesignation']."' ";

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $designation = mysql_fetch_assoc($resS) ;

                if(mysql_num_rows($resS)){

                    $sqlD="SELECT * FROM `".$nomtableStock."` WHERE idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";

                    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                    $retour=$ligne['quantite'] - $quantite;

                    while ($stock = mysql_fetch_assoc($resD)) {

                        if($retour >= 0){

                            $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                            if($stock['quantiteStockinitial'] >= $quantiteStockCourant){

                                $sqlS="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$quantiteStockCourant."' WHERE idStock=".$stock['idStock'];

                                $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                if($resS){

                                    $i=$i + 1;

                                }

                            }

                            else{

                                $sqlS="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$stock['quantiteStockinitial']."' WHERE idStock=".$stock['idStock'];

                                $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                if($resS){

                                    $i=$i + 1;

                                }

                            }

                            $retour=($retour + $stock['quantiteStockCourant']) - $stock['quantiteStockinitial'] ;  

                            $j=$j + 1;

                        }

                    }

                    if($quantite==0){

                        $sqlD="DELETE FROM `".$nomtableLigne."` WHERE numligne='".$numligne."' ";

                        $resD=@mysql_query($sqlD) or die ("mise à jour client  impossible".mysql_error());

                        if($resD){

                            $k=1;

                        }   

                    }

                    else{

                        $prixTotal=$quantite * $ligne['prixPublic'];

                        $sql3="UPDATE `".$nomtableLigne."` SET quantite='".$quantite."', prixtotal='".$prixTotal."'  WHERE numligne='".$ligne['numligne']."'  ";

                        $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

                        if($res3){

                            $k=1;

                        }

                    }

                }

            }

            if($ligne['classe']==1 || $ligne['classe']==2){

                if($quantite==0){

                    $sqlD="DELETE FROM `".$nomtableLigne."` WHERE numligne='".$numligne."' ";

                    $resD=@mysql_query($sqlD) or die ("mise à jour client  impossible".mysql_error());

                    if($resD){

                        $i=$i + 1;

                        $j=$j + 1;

                    }

                }

                else{

                    $prixTotal=$quantite * $ligne['prixPublic'];

                    $sql3="UPDATE `".$nomtableLigne."` SET quantite='".$quantite."', prixtotal='".$prixTotal."'  WHERE numligne='".$ligne['numligne']."'  ";

                    $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

                    if($res3){

                        $i=$i + 1;

                        $j=$j + 1;

                    }

                }

            }

            if($ligne['classe']==5 || $ligne['classe']==7){

            }



            $sqlTP="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` WHERE idPagnet=".$idPagnet." ";

            $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());

            $TotalTP = mysql_fetch_array($resTP) ;

            $apayerPagnet=0;

            $k=0;

            if($TotalTP[0]!=null){

                $apayerPagnet=$TotalTP[0];

                $restourne=0;

                if($pagnet['remise']!=0){

                    $apayerPagnet=$TotalTP[0] - $pagnet['remise'];

                }

                if($pagnet['versement']!=0){

                    $restourne=$apayerPagnet - $pagnet['versement'];

                }

                $sql16="UPDATE `".$nomtablePagnet."` SET totalp='".$TotalTP[0]."',apayerPagnet='".$apayerPagnet."',

                                                    restourne='".$restourne."' WHERE idPagnet='".$idPagnet."' ";

                $res16=mysql_query($sql16) or die ("update Pagnet impossible =>".mysql_error());

                if($res16){

                    $k=1;

                }

                

            }

            else{

                $sqlR="DELETE FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet;

                $resR=@mysql_query($sqlR) or die ("mise à jour client  impossible".mysql_error());

                if($resR){

                    $k=1;

                }

            }


            if ($_SESSION['compte']==1) {

                /********* Début retour ligne compte **********/

                $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$prixtotal." where idCompte=".$pagnet['idCompte'];

                //var_dump($sql7);

                $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

            

                $sql8="UPDATE `".$nomtableComptemouvement."` set  montant= ".$apayerPagnet." where idCompte<>3 and mouvementLink=".$pagnet['idPagnet'];

                //var_dump($sql7);

                $res8=@mysql_query($sql8) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

            

                /******** Fin retour ligne compte ***********/

                    # code...

                if ($pagnet['idCompte'] == 2) {

                    # code...

                    /************************************** UPDATE BON Et DU REMISE******************************************/

                    $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where verrouiller='1' && idClient=".$pagnet['idClient']." && (type=0 || type=30) ";

                    $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());

                    $TotalP = mysql_fetch_array($res18) ;



                    $sql19="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$pagnet['idClient']." && type=1 ";

                    $res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());

                    $TotalR = mysql_fetch_array($res19) ;

                    $total=$TotalP[0] - $TotalR[0];

                    
                    $sql20="UPDATE `".$nomtableBon."` set montant=".$total.", date='".$dateString."' where idClient=".$pagnet['idClient'];

                    $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());

                    $sql3="UPDATE `".$nomtableClient."` set solde=solde-".$prixtotal." where idClient=".$pagnet['idClient'];

                    $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());

                }

            }

            if(($i!=0 && $j!=0 && $k!=0) && ($i==$j)){

                mysql_query("COMMIT;");

            }

            else{

                mysql_query("ROLLBACK;");

                echo "<p>PROBLEME CONNECTION INTERNET .</br></br> Veuillez retourner la ligne ".$numligne.".</p>";

            }

        }

        else {

            echo "<p>IMPOSSIBLE.</br></br> Vous ne pouvez pas retourner cette ligne du pagnet numéro ".$idPagnet." , car c'est la(les) dernière(s) ligne(s). Cela risque de fausser les calculs surtout quand vous avez effectué des remises.</br></br>Il faut retourner tout le pagnet.</p>";

        }

        echo '1';

    }

/**Fin Button Retourner Ligne d'un Pagnet**/


/**Debut Button Terminer Imputation**/

else if(isset($_POST['terminerImputation'])){

    // $idPanier=htmlspecialchars(trim($_POST['idPanier']));
  
    $msg_info='';
  
    $idMutuellePagnet=@$_POST['idMutuellePagnet'];
  
    $codeBeneficiaire=htmlspecialchars(trim($_POST['codeBeneficiaire']));
  
    $numeroRecu=htmlspecialchars(trim($_POST['numeroRecu']));
  
    $dateRecu=htmlspecialchars(trim($_POST['dateRecu']));

    $codeAdherant=htmlspecialchars(trim($_POST['codeAdherant']));
    $nomAdherant=htmlspecialchars(trim($_POST['nomAdherant']));
    // $dateRecu=htmlspecialchars(trim($_POST['dateRecu']));
  
  
    $sql="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";
  
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  
    $pagnet = mysql_fetch_assoc($res) ;
  
    if($pagnet!=null){
  
        $sql0="SELECT * FROM `".$nomtableMutuelle."` where idMutuelle=".$pagnet['idMutuelle']." ";
  
        $res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());
  
        $mutuelle = mysql_fetch_assoc($res0);

        // echo $codeAdherant."/".$nomAdherant."/".$codeBeneficiaire."/".$numeroRecu."/".$dateRecu;
  
        if($mutuelle!=null && ($codeAdherant!=null && $codeAdherant!=' ') && ($nomAdherant!=null && $nomAdherant!=' ') && ($codeBeneficiaire!='' && $numeroRecu!='' && $dateRecu!='')){
  
            $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ";
  
            $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
  
            $TotalP = mysql_fetch_array($resT) ;
  
  
  
            $totalp=$TotalP[0];
  
            $apayerMutuelle= ($totalp * $pagnet['taux']) / 100;
  
            $apayerPagnet= $totalp - $apayerMutuelle;
  
            if(@$_POST['versement']==''){

                $versement=0;
  
                $monaie=0;
  
            }
  
            else{
  
                $versement=@$_POST['versement'];
  
                $monaie=$versement-$apayerPagnet;
  
            }
  
            //$msg_info="<p>$totalp</p> <p>$remise</p> <p>$versement</p> <p>$apayerPagnet</p>";
  
  
  
            $sqlL="SELECT * FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ";
  
            $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
  
            //$ligne = mysql_fetch_assoc($resL) ;
  
  
  
            /*****Debut Nombre de Panier ouvert****/
  
            $query = mysql_query("SELECT * FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ");
  
            $nbre_fois=mysql_num_rows($query);
  
            /*****Fin Nombre de Panier ouvert****/
  
  
  
            /*****Debut Difference entre Total Panier et Remise****/
  
            $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ";
  
            $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
  
            $TotalT = mysql_fetch_array($resT) ;
  
  
            $difference=$TotalT[0];
  
            /*****Fin Difference entre Total Panier et Remise****/
  
  
            if($pagnet['verrouiller']==0){
  
                if($nbre_fois>0){
  
                    if($difference>=0){
  
                        mysql_query("SET AUTOCOMMIT=0;");
  
                        mysql_query("START TRANSACTION;");
  
                        $i=0;
  
                        $j=0;
  
                        while ($ligne=mysql_fetch_assoc($resL)){
  
                            if($ligne['classe']==0){
  
                                $sqlS="SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation='".$ligne['idDesignation']."' ";
  
                                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  
                                $designation = mysql_fetch_assoc($resS) ;
  
                                    if(mysql_num_rows($resS)){
  
                                        $restant=$ligne['quantite'];
  
                                        $sqlD="SELECT * FROM `".$nomtableStock."` WHERE idDesignation='".$ligne['idDesignation']."' ORDER BY idStock ASC ";
  
                                        $resD=mysql_query($sqlD) or die ("select designation impossible =>".mysql_error());
  
                                        $stock0 = mysql_fetch_assoc($resD);
  
                                        $quantiteinitial=$stock0['quantiteStockCourant'] - $restant;
  
                                        if($quantiteinitial >= 0){
  
                                            $sqlS0="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$quantiteinitial."' WHERE idStock='".$stock0['idStock']."' ";
  
                                            $resS0=mysql_query($sqlS0) or die ("update stock impossible =>".mysql_error());
  
                                            if($resS0){
  
                                                $i=$i + 1;;
  
                                            }
  
                                        }
  
                                        else{
  
                                            $sqlE="SELECT * FROM `".$nomtableStock."` WHERE idDesignation='".$ligne['idDesignation']."' ORDER BY idStock ASC ";
  
                                            $resE=mysql_query($sqlE) or die ("select designation impossible =>".mysql_error());
  
                                            $k=0;
  
                                            $l=0;
  
                                            while ($stock = mysql_fetch_assoc($resE)) {
  
                                                if($restant>= 0){
  
                                                    $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
  
                                                    if($quantiteStockCourant > 0){
  
                                                        $sqlS1="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$quantiteStockCourant."' WHERE idStock='".$stock['idStock']."' ";
  
                                                        $resS1=mysql_query($sqlS1) or die ("update stock impossible =>".mysql_error());
  
                                                        if($resS1){
  
                                                            $k=$k + 1;
  
                                                        }
  
                                                    }
  
                                                    else{
  
                                                        $sqlS2="UPDATE `".$nomtableStock."` SET quantiteStockCourant=0 WHERE idStock='".$stock['idStock']."' ";
  
                                                        $resS2=mysql_query($sqlS2) or die ("update stock impossible =>".mysql_error());
  
                                                        if($resS2){
  
                                                            $k=$k + 1;
  
                                                        }
  
                                                    }
  
                                                    $restant= $restant - $stock['quantiteStockCourant'] ;
  
                                                    $l=$l + 1;
  
                                                }
  
                                            }
  
                                            if($k==$l){
  
                                                $i=$i + 1;
  
                                            }
  
                                        }
  
                                    }
  
                            }
  
                            else{
  
                                $i=$i + 1;  
  
                            }
  
                            $j=$j + 1;
  
                        }
  
                        $sql3="UPDATE `".$nomtableMutuellePagnet."` set codeAdherant='".$codeAdherant."',codeBeneficiaire='".$codeBeneficiaire."',numeroRecu='".$numeroRecu."',dateRecu='".$dateRecu."',
  
                        verrouiller='1',totalp=".$totalp.",apayerPagnet=".$apayerPagnet.",apayerMutuelle=".$apayerMutuelle.",versement='".$versement."',restourne='".$monaie."' where idMutuellePagnet=".$idMutuellePagnet;
  
                        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
  
            
                        if ($_SESSION['compte']==1) {
  
                          if(isset($_POST['compte'])) {
  
                              $idCompte=$_POST['compte'];
  
                              
  
                              $sqlL="SELECT * FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ORDER BY numLigne LIMIT 1 ";
  
                              $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
  
                              $lignes = mysql_fetch_array($resL) ;
  
                              if ($lignes['classe'] == '2') {
  
                                  
  
                                  $description="Dépenses";
  
                                  $operation='retrait';
  
                  
  
                                  $sql8="UPDATE `".$nomtableMutuellePagnet."` set idCompte=".$idCompte." where  idPagnet=".$idMutuellePagnet;
  
                                  $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());
  
                  
  
                                  $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$apayerPagnet." where  idCompte=".$idCompte;
  
                                  $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());
  
                  
  
                                  $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser) values(".$apayerPagnet.",'".$operation."',".$idCompte.",'".$description."','".$dateHeures."','".$dateHeures."',".$idPagnet.",".$_SESSION['iduser'].")";
  
                                  $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error());
  
                                  
  
                              }else {
  
                                      
  
                                if(isset($_POST['avanceInput']) && $_POST['avanceInput'] > 0 && $idCompte == '2'){
  
                                    // $avanceInput=$_POST['avanceInput'];
  
                                    $montant=htmlspecialchars(trim($_POST['avanceInput']));
  
                                    $compteAvance=htmlspecialchars(trim($_POST['compteAvance']));
  
                                    $idClient=$pagnet['idClient'];
  
                                    $dateVersement=$dateString2;
  
                                    
  
                                    $sql30="UPDATE `".$nomtableMutuellePagnet."` set avance=".$montant." where idMutuellePagnet=".$idMutuellePagnet;
  
                                    $res30=mysql_query($sql30) or die ("update avance pagnet impossible =>".mysql_error());
  
  
  
                                    $sql2="insert into `".$nomtableVersement."` (idClient,paiement,montant,dateVersement,heureVersement,idCompte,idMutuellePagnet,iduser) values(".$idClient.",'avance client',".$montant.",'".$dateVersement."','".$heureString."',".$compteAvance.",".$idMutuellePagnet.",".$_SESSION['iduser'].")";
  
                                    $res2=mysql_query($sql2) or die ("insertion Versement impossible =>".mysql_error());
  
                                    // $solde=$montant+$client['solde'];
  
                                    $reqv="SELECT idVersement from `".$nomtableVersement."` order by idVersement desc limit 1";
  
                                    $resv=mysql_query($reqv) or die ("persoonel requête 2".mysql_error());
  
                                    $v = mysql_fetch_array($resv);
  
                                    $idVersement=$v['idVersement'];
  
  
  
                                    $sql3="UPDATE `".$nomtableClient."` set solde=solde-".$montant." where idClient=".$idClient;
  
                                    $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());
  
  
  
                                    $operation='depot';
  
                                    $description="Avance bon mutuelle";
  
  
  
                                    $sql7="UPDATE `".$nomtableCompte."` set montantCompte=montantCompte + ".$montant." where idCompte=".$compteAvance;
  
                                    $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());
  
  
  
                                    $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,idMutuellePagnet,idUser) values(".$montant.",'".$operation."',".$compteAvance.",'".$description."','".$dateHeures."','".$dateHeures."',".$idVersement.",".$idMutuellePagnet.",".$_SESSION['iduser'].")";
  
                                    $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );
  
                                    
  
                                    $operation2='retrait';
  
                                    $idCompteBon='2';
  
                                    $description2="Bon mutuelle encaissé";
  
  
  
                                    $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$montant." where  idCompte=".$idCompteBon;
  
                                    $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());
  
  
  
                                    $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,idMutuellePagnet,idUser) values(".$montant.",'".$operation2."',".$idCompteBon.",'".$description2."','".$dateHeures."','".$dateHeures."',".$idVersement.",".$idMutuellePagnet.",".$_SESSION['iduser'].")";
  
                                    $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );
  
                                        
  
                                }
  
  
  
                              $operation='depot';
  
                              if ($idCompte == '2') {
  
                                  $description="Bon imputation adhérant";
  
                                  $idClient=$pagnet['idClient'];
  
  
  
                                  $sql3="UPDATE `".$nomtableClient."` set solde=solde+".$apayerPagnet." where idClient=".$idClient;
  
                                  $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());
  
                                  
  
                                  $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtableMutuellePagnet."` where verrouiller='1' && idClient=".$idClient." && (type=0 || type=30) ";
  
                                  $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());
  
                                  $TotalP = mysql_fetch_array($res18) ;
  
  
  
                                  $sql19="SELECT SUM(apayerPagnet) FROM `".$nomtableMutuellePagnet."` where idClient=".$idClient." && type=1 ";
  
                                  $res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());
  
                                  $TotalR = mysql_fetch_array($res19) ;
  
  
  
                                  $total=$TotalP[0] - $TotalR[0];
  
                                  
  
                                  $sql20="UPDATE `".$nomtableBon."` set montant=".$total.", date='".$dateString."' where idClient=".$idClient;
  
                                  $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());
  
  
  
                              } else {
  
                                  $description="Encaissement imputation adhérant";
  
                              }
  
              
  
                              $sql8="UPDATE `".$nomtableMutuellePagnet."`  set  idCompte=".$idCompte." where  idMutuellePagnet=".$idMutuellePagnet;
  
                              $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());
  
              
  
                              $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte + ".$apayerPagnet." where  idCompte=".$idCompte;
  
                              $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());
  
              
  
                              $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idMutuellePagnet,idUser) values(".$apayerPagnet.",'".$operation."',".$idCompte.",'".$description."','".$dateHeures."','".$dateHeures."',".$idMutuellePagnet.",".$_SESSION['iduser'].")";
  
                              $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error());
  
                            }                      
  
                          }
  
                        }
  
  
                        if(($i==$j) && $res3){
  
                            mysql_query("COMMIT;");
  
                        }
  
                        else{
  
                            mysql_query("ROLLBACK;");
  
                            $msg_info="PROBLEME CONNECTION INTERNET .</br></br> Veuillez terminer le bon numéro ".$idMutuellePagnet.".";
  
                        }
  
                    }
  
                    else {
  
                        $msg_info="IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idMutuellePagnet.", il faut verifier la remise.";
  
                    }
  
                    
  
                }
  
                else {
  
                    $msg_info="IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idMutuellePagnet.", il faut au moins ajouter un produit.";
  
                }
  
            }

  
        }
  
        else{
  
             $msg_info="IMPOSSIBLE.</br></br> Il manque des informations pour la mutuelle de sante.";
  
        }
  
    }
  
    else{
  
        $msg_info="IMPOSSIBLE.</br></br> Vous n'avez pas choisi la mutuelle de sante.";
  
    }
  
  
    echo $msg_info;
  
  }

/**Fin Button Terminer Imputation**/  

/**Debut Button Annuler Imputation**/   

else if (isset($_POST['btnAnnulerImputation']) ) {

        $idMutuellePagnet=htmlspecialchars(trim($_POST['idMutuellePagnet']));

        

        $sqlL="SELECT * FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ";

        $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

        

        while ($ligne=mysql_fetch_assoc($resL)){



            //on fait la suppression de cette ligne dans la table ligne

            $sql3="DELETE FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ";

            $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());


        }


        // suppression du pagnet aprés su^ppression de ses lignes

        $sqlR="DELETE FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet;

        $resR=@mysql_query($sqlR) or die ("mise à jour client  impossible".mysql_error());

        echo '1';
    }

/**Fin Button Annuler Imputation**/


/**Debut Button Retourner Imputation**/

else if (isset($_POST['btnRetournerImputation'])) {

    $idMutuellePagnet=htmlspecialchars(trim($_POST['idMutuellePagnet']));


    $sql="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";

    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

    $pagnet = mysql_fetch_assoc($res) ;


    if($pagnet['type']==0 || $pagnet['type']==30){

        $sqlL="SELECT * FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ";

        $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

        //$ligne = mysql_fetch_assoc($resL) ;

        mysql_query("SET AUTOCOMMIT=0;");

        mysql_query("START TRANSACTION;");

        $i=0;

        $j=0;

        while ($ligne=mysql_fetch_assoc($resL)){

            if($ligne['classe']==0){

                $sqlS="SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation='".$ligne['idDesignation']."' ";

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $designation = mysql_fetch_assoc($resS) ;

                    if(mysql_num_rows($resS)){

                        $sqlD="SELECT * FROM `".$nomtableStock."` WHERE idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";

                        $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                        $retour=$ligne['quantite'];

                        $k=0;

                        $l=0;

                        while ($stock = mysql_fetch_assoc($resD)) {

                            if($retour >= 0){

                                $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                if($stock['quantiteStockinitial'] >= $quantiteStockCourant){

                                    $sqlS="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$quantiteStockCourant."' WHERE idStock='".$stock['idStock']."' ";

                                    $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    if($resS){

                                        $k=$k + 1;

                                    }

                                }

                                else{

                                    $sqlS="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$stock['quantiteStockinitial']."' WHERE idStock='".$stock['idStock']."' ";

                                    $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    if($resS){

                                        $k=$k + 1;

                                    }

                                    

                                }

                                $retour=($retour + $stock['quantiteStockCourant']) - $stock['quantiteStockinitial'] ;

                                $l=$l + 1;

                            }

                        }

                        if($k==$l){

                            $i=$i + 1;

                        }

                    }

            }

            else {

                $i=$i + 1;

            }

            $j=$j + 1;

        }

        

        $sqlRP="UPDATE `".$nomtableMutuellePagnet."` set type=2 where idMutuellePagnet=".$idMutuellePagnet;

        $resRP=@mysql_query($sqlRP) or die ("mise à jour client  impossible".mysql_error());

        

        if ($_SESSION['compte']==1) {

            $description="Retour panier imputation";

            $operation='retrait';

        

            $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$pagnet['apayerPagnet']." where  idCompte=".$pagnet['idCompte'];

            $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

            

            if ($pagnet['remise']!='' && $pagnet['remise']!=0) {

                $sql8="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$pagnet['remise']." where  idCompte=3";

                $res8=@mysql_query($sql8) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

            }

            //Annulation des mouvements relatifs à ce panier

            $sql="DELETE FROM `".$nomtableComptemouvement."` where  idMutuellePagnet=".$idMutuellePagnet."";

            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());



            if ($pagnet['avance'] != 0) {

                

                $sql0="DELETE FROM `".$nomtableVersement."` where  idMutuellePagnet=".$idMutuellePagnet."";

                $res0=@mysql_query($sql0) or die ("mise à jour idClient ".mysql_error());

            }



            if ($pagnet['idCompte'] == 2) {

                # code...

                /************************************** UPDATE BON Et DU REMISE******************************************/

                $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where verrouiller='1' && idClient=".$pagnet['idClient']." && (type=0 || type=30) ";

                $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());

                $TotalP = mysql_fetch_array($res18) ;



                $sql19="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." && type=1 ";

                $res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());

                $TotalR = mysql_fetch_array($res19) ;



                $total=$TotalP[0] - $TotalR[0];

                

                $sql20="UPDATE `".$nomtableBon."` set montant=".$total.", date='".$dateString."' where idClient=".$pagnet['idClient'];

                $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());

                

                $sql3="UPDATE `".$nomtableClient."` set solde=solde-".$pagnet['apayerPagnet']." where idClient=".$pagnet['idClient'];

                $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());



            }

        }



        if(($i==$j) && $resRP){

            mysql_query("COMMIT;");

        }

        else{

            mysql_query("ROLLBACK;");

            echo "<p>PROBLEME CONNECTION INTERNET .</br></br> Veuillez retourner le bon numéro ".$idMutuellePagnet.".</p>";

        }

    }

    echo '1';

}

/**Fin Button Retourner Imputation**/

