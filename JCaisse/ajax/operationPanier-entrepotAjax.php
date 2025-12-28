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

function find_p_with_position($pns,$des) {
    foreach($pns as $index => $p) {
        if(($p['idDesignation'] == $des)){
            return $index;
        }
    } 
    return FALSE;
  }



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

                            $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0)";

                            $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());

                        }

                    }

                    else{

                        $sql0="insert into `".$nomtablePage."` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('".$dateString."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0)";

                        $res0=@mysql_query($sql0) or die ("insertion page journal impossible-1".mysql_error());

        

                        if($nbre_fois<2){

                            $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0)";

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

                            $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0)";

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

                        $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0)";

                        $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());

                    }

                }

                else{

                    $sql0="insert into `".$nomtablePage."` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('".$dateString."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0)";

                    $res0=@mysql_query($sql0) or die ("insertion page journal impossible-1".mysql_error());



                    if($nbre_fois<2){

                        $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0)";

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

                        $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0)";

                        $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error() );

                }



            }

        }

    }

/**Fin Button Ajouter Pagnet**/


/**Debut Button Terminer Pagnet**/

    else if (isset($_POST['btnImprimerFacture'])) {

        $idPagnet=@$_POST['idPagnet'];

        $sql="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";

        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

        $pagnet = mysql_fetch_assoc($res) ;
        
        $reference = array();
        $ligneIns = array();

        $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet;
        // var_dump($sqlL);
        $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
        $resL2 = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
        
        if ($pagnet['type']==0 || $pagnet['type']==30) {

            while ($ligne=mysql_fetch_assoc($resL2)) {

                $sqlGetStock="SELECT idDesignation,designation,nbreArticleUniteStock,SUM(`quantiteStockCourant`) as stockTotal FROM `".$nomtableEntrepotStock."` where idEntrepot=".$ligne['idEntrepot']." and idDesignation=".$ligne['idDesignation']." GROUP BY `idDesignation`";
                $refJcaisse = mysql_query($sqlGetStock) or die ("persoonel requête 1".mysql_error());
                $stock = mysql_fetch_array($refJcaisse);
        
                // while ($key = mysql_fetch_array($refJcaisse)) {
                //   $reference[] = $key;
                // }
                // var_dump($reference);
                // if (find_p_with_position($reference, $ligne['idDesignation']) !==FALSE) {
                //     $c = 0;
                //     $i=find_p_with_position($reference, $ligne['idDesignation']);
                    $qtLigne = $ligne['quantite'] * $stock['nbreArticleUniteStock'];
                    // var_dump($qtLigne);
                    // var_dump($reference[$i]['stockTotal']);
                    if ($qtLigne > $stock['stockTotal']) {
                        $ligneIns[] = $ligne['numligne'];
                        // $res=implode('-/-', $ligneIns);
                    }
                    //  else {
                    //     $ligneIns[] = $ligne['idDesignation'];
                    //     $res=implode('-*//*-', $ligneIns);
                    // }

                // }

            }
        }

        if (count($ligneIns) > 0) {
            # code...
            // foreach ($ligneIns as $key) {
                
            // }
            // var_dump('$ligneIns');
            $ins = "0<>".implode('<>', $ligneIns);

            echo $ins;

        }
        else {
            if (isset($_POST['remise']) || isset($_POST['versement'])) {

                // code...

                $idPagnet=@$_POST['idPagnet'];



                $sql="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";

                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                $pagnet = mysql_fetch_assoc($res) ;



                $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";

                $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                $TotalP = mysql_fetch_array($resT) ;


                $totalp=$TotalP[0];

                if(@$_POST['remise']==''){

                    $remise=0;

                }

                else{

                    $remise=@$_POST['remise'];

                }

                $apayerPagnet=$totalp-$remise;

                if(@$_POST['versement']==''){

                    $versement=0;

                    $monaie=0;

                }

                else{

                    $versement=@$_POST['versement'];

                    $monaie=$versement-$apayerPagnet;

                }



                if(@$_POST['livreur']==''){

                    $livreur='';

                }

                else{

                    $livreur=@$_POST['livreur'];

                }



                //$msg_info="<p>$totalp</p> <p>$remise</p> <p>$versement</p> <p>$apayerPagnet</p>";



                $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";

                $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

                //$ligne = mysql_fetch_assoc($resL) ;


                /*****Debut Nombre de Panier ouvert****/

                $query = mysql_query("SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." LIMIT 1 ");

                $nbre_fois=mysql_num_rows($query);

                /*****Fin Nombre de Panier ouvert****/



                $difference=$apayerPagnet;

                /*****Fin Difference entre Total Panier et Remise****/


                if($pagnet['verrouiller']==0){

                    if($nbre_fois>0){

                        if($difference>=0){

                            if($pagnet['type']==0 || $pagnet['type']==30){

                                while ($ligne=mysql_fetch_assoc($resL)){

                                    if($ligne['classe']==0){

                                        $sqlS="SELECT idDesignation,designation,uniteStock,nbreArticleUniteStock FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

                                        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                                        $designation = mysql_fetch_assoc($resS) ;

                                            if(mysql_num_rows($resS)){

                                                if ($ligne['unitevente']==$designation['uniteStock']) {

                                                    $sqlD="SELECT idEntrepotStock,quantiteStockCourant FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."' AND idEntrepot='".$ligne['idEntrepot']."' AND quantiteStockCourant<>0 ORDER BY idEntrepotStock ASC ";

                                                    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                                                    $restant=$ligne['quantite'] * $designation['nbreArticleUniteStock'];

                                                    while ($stock = mysql_fetch_assoc($resD)) {

                                                        if($restant>= 0){

                                                            $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                                                            if($quantiteStockCourant > 0){

                                                                $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                                                                $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                                            }

                                                            else{

                                                                $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=0 where idEntrepotStock=".$stock['idEntrepotStock'];

                                                                $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                                            }

                                                            $restant= $restant - $stock['quantiteStockCourant'] ;

                                                        }

                                                    }

            

                                                }

                                                else if ($ligne['unitevente']=='Demi Gros') {

                                                    $sqlD="SELECT idEntrepotStock,quantiteStockCourant FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."' AND idEntrepot='".$ligne['idEntrepot']."' AND quantiteStockCourant<>0 ORDER BY idEntrepotStock ASC ";

                                                    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                                                    $restant=$ligne['quantite'] * ($designation['nbreArticleUniteStock']/2);

                                                    while ($stock = mysql_fetch_assoc($resD)) {

                                                        if($restant>= 0){

                                                            $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                                                            if($quantiteStockCourant > 0){

                                                                $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                                                                $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                                            }

                                                            else{

                                                                $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=0 where idEntrepotStock=".$stock['idEntrepotStock'];

                                                                $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                                            }

                                                            $restant= $restant - $stock['quantiteStockCourant'] ;

                                                        }

                                                    }

            

                                                }

                                                else {

                                                    $sqlD="SELECT idEntrepotStock,quantiteStockCourant FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."' AND idEntrepot='".$ligne['idEntrepot']."' AND quantiteStockCourant<>0 ORDER BY idEntrepotStock ASC ";

                                                    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                                                    $restant=$ligne['quantite'];

                                                    while ($stock = mysql_fetch_assoc($resD)) {

                                                        if($restant>= 0){

                                                            $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                                                            if($quantiteStockCourant > 0){

                                                                $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                                                                $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                                            }

                                                            else{

                                                                $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=0 where idEntrepotStock=".$stock['idEntrepotStock'];

                                                                $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                                            }

                                                            $restant= $restant - $stock['quantiteStockCourant'] ;

                                                        }

                                                    }

            

                                                }

                                            }

                                    }

                                }



                                $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1', remise=".$remise.",totalp=".$totalp.",apayerPagnet=".$apayerPagnet.",versement=".$versement.",restourne=".$monaie.",livreur='".$livreur."' where idPagnet=".$idPagnet;

                                $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());



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

                                                

                                                $sql18="SELECT SUM(apayerPagnet-avance) FROM `".$nomtablePagnet."` where verrouiller='1' && idClient=".$idClient." && (type=0 || type=30) ";

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

                            }

                        }

                        else {

                            echo "<p>IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idPagnet.", il faut verifier la remise.</p>";

                        }

                    }

                    else {

                        echo "<p>IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idPagnet.", il faut au moins ajouter un produit.</p>";

                    }

                }



            }
        }
    }

/**Fin Button Terminer Pagnet**/


/**Debut Button Annuler Pagnet**/

    else if (isset($_POST['btnAnnulerPagnet']) ) {



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

        echo '1';

    }

/**Fin Button Annuler Pagnet**/


/**Debut Button Retourner Pagnet**/

    else if (isset($_POST['btnRetournerPagnet'])) {



        $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));

        

        $sqlP="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";

        $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());

        $pagnet = mysql_fetch_assoc($resP) ;



        $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";

        $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

        //$ligne = mysql_fetch_assoc($resL) ;



        if($pagnet['type']==0 || $pagnet['type']==30){

            while ($ligne=mysql_fetch_assoc($resL)){

                if($ligne['classe']==0){

                    $sqlS="SELECT idDesignation,designation,uniteStock,nbreArticleUniteStock FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                    $designation = mysql_fetch_assoc($resS) ;

                        if(mysql_num_rows($resS)){

                            if ($ligne['unitevente']==$designation['uniteStock']) {

                                $sqlD="SELECT idEntrepotStock,quantiteStockCourant,totalArticleStock FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC LIMIT 10 ";

                                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                                $retour=$ligne['quantite'] * $designation['nbreArticleUniteStock'];

                                while ($stock = mysql_fetch_assoc($resD)) {

                                    if($retour>= 0){

                                        $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                        if($stock['totalArticleStock'] >= $quantiteStockCourant){

                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                        }

                                        else{

                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idEntrepotStock=".$stock['idEntrepotStock'];

                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                        }

                                        $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                                    }

                                }



                            }

                            else if ($ligne['unitevente']=='Demi Gros') {

                                $sqlD="SELECT idEntrepotStock,quantiteStockCourant,totalArticleStock FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC  LIMIT 10 ";

                                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                                $retour=$ligne['quantite']* ($designation['nbreArticleUniteStock']/2);

                                while ($stock = mysql_fetch_assoc($resD)) {

                                    if($retour>= 0){

                                        $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                        if($stock['totalArticleStock'] >= $quantiteStockCourant){

                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                        }

                                        else{

                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idEntrepotStock=".$stock['idEntrepotStock'];

                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                        }

                                        $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                                    }

                                }



                            }

                            else {

                                $sqlD="SELECT idEntrepotStock,quantiteStockCourant,totalArticleStock  FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC  LIMIT 10 ";

                                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                                $retour=$ligne['quantite'];

                                while ($stock = mysql_fetch_assoc($resD)) {

                                    if($retour >= 0){

                                        $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                        if($stock['totalArticleStock'] >= $quantiteStockCourant){

                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                        }

                                        else{

                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idEntrepotStock=".$stock['idEntrepotStock'];

                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                        

                                        }

                                        $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                                        

                                    }

                                    

                                }



                            }

                        }

                }

            }


            // suppression du pagnet aprés su^ppression de ses lignes

            $sqlR="UPDATE `".$nomtablePagnet."` set type=2 where idPagnet=".$idPagnet;

            $resR=@mysql_query($sqlR) or die ("mise à jour client  impossible".mysql_error());
            

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



        }

        
    }

/**Fin Button Retourner Pagnet**/


/**Debut Button Annuler Ligne d'un Pagnet**/
    
    else if (isset($_POST['btnRetourAvant'])) {



        $numligne=$_POST['numligne'];

        // $unitevente=$_POST['unitevente'];



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

        $unitevente=$_POST['unitevente'];

        $prixunitevente=$_POST['prixunitevente'];

        $prixtotal=$_POST['prixtotal'];

        $totalp=$_POST['totalp'];



        $sqlL="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";

        $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

        $ligne = mysql_fetch_assoc($resL) ;



        $sqlP="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";

        $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());

        $pagnet = mysql_fetch_assoc($resP) ;



        $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' && numligne<>".$numligne."";

        $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

        $TotalT = mysql_fetch_array($resT) ;

        

        $idClient=$pagnet['idClient'];



        $difference=$TotalT[0] + ($quantite * $ligne['prixunitevente']) - $pagnet["remise"];



        if($difference >= 0 && $ligne['quantite'] >= $quantite){



            if($pagnet['type']==0 || $pagnet['type']==30){

                if($ligne['classe']==0){

                    $sqlS="SELECT idDesignation,designation,uniteStock,nbreArticleUniteStock FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                    $designation = mysql_fetch_assoc($resS) ;

                        if(mysql_num_rows($resS)){

                            if ($ligne['unitevente']==$designation['uniteStock']) {

                                $sqlD="SELECT idEntrepotStock,quantiteStockCourant,totalArticleStock FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC Limit 10";

                                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                                $retour=($ligne['quantite'] - $quantite) *$designation['nbreArticleUniteStock'];

                                while ($stock = mysql_fetch_assoc($resD)) {

                                    if($retour>= 0){

                                        $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                        if($stock['totalArticleStock'] >= $quantiteStockCourant){

                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                        }

                                        else{

                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idEntrepotStock=".$stock['idEntrepotStock'];

                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                        }

                                        $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                                    }

                                }

                                if($quantite==0){

                                    $sqlD="DELETE FROM `".$nomtableLigne."` where numligne=".$numligne;

                                    $resD=@mysql_query($sqlD) or die ("mise à jour client  impossible".mysql_error());

                                }

                                else{

                                    $prixTotal=$quantite * $ligne['prixunitevente'];

                                    $sql3="UPDATE `".$nomtableLigne."` set quantite=".$quantite.", prixtotal=".$prixTotal."  where numligne=".$ligne['numligne']."  ";

                                    $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

                                }



                            }

                            else if ($ligne['unitevente']=='Demi Gros') {

                                $sqlD="SELECT idEntrepotStock,quantiteStockCourant,totalArticleStock  FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC Limit 10";

                                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                                $retour=($ligne['quantite'] - $quantite ) * ($designation['nbreArticleUniteStock']/2);

                                while ($stock = mysql_fetch_assoc($resD)) {

                                    if($retour>= 0){

                                        $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                        if($stock['totalArticleStock'] >= $quantiteStockCourant){

                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                        }

                                        else{

                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idEntrepotStock=".$stock['idEntrepotStock'];

                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                        }

                                        $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                                    }

                                }

                                if($quantite==0){

                                    $sqlD="DELETE FROM `".$nomtableLigne."` where numligne=".$numligne;

                                    $resD=@mysql_query($sqlD) or die ("mise à jour client  impossible".mysql_error());

                                }

                                else{

                                    $prixTotal=$quantite * $ligne['prixunitevente'];

                                    $sql3="UPDATE `".$nomtableLigne."` set quantite=".$quantite.", prixtotal=".$prixTotal."  where numligne=".$ligne['numligne']."  ";

                                    $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

                                }



                            }

                            else {

                                $sqlD="SELECT idEntrepotStock,quantiteStockCourant,totalArticleStock  FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC Limit 10 ";

                                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                                $retour=$ligne['quantite'] - $quantite;

                                while ($stock = mysql_fetch_assoc($resD)) {

                                    if($retour >= 0){

                                        $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                        if($stock['totalArticleStock'] >= $quantiteStockCourant){

                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                        }

                                        else{

                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idEntrepotStock=".$stock['idEntrepotStock'];

                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                           

                                        }

                                        $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                                        

                                    }

                                    

                                }

                                if($quantite==0){

                                    $sqlD="DELETE FROM `".$nomtableLigne."` where numligne=".$numligne;

                                    $resD=@mysql_query($sqlD) or die ("mise à jour client  impossible".mysql_error());

                                }

                                else{

                                    $prixTotal=$quantite * $ligne['prixunitevente'];

                                    $sql3="UPDATE `".$nomtableLigne."` set quantite=".$quantite.", prixtotal=".$prixTotal."  where numligne=".$ligne['numligne']."  ";

                                    $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

                                }

                            }

                        }

                }

                $sqlTP="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";

                $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());

                $TotalTP = mysql_fetch_array($resTP) ;


                $apayerPagnet=0;

                if($TotalTP[0]!=null){

                    $apayerPagnet=$TotalTP[0];

                    $restourne=0;

                    if($pagnet['remise']!=0){

                        $apayerPagnet=$TotalTP[0] - $pagnet['remise'];

                    }

                    if($pagnet['versement']!=0){

                        $restourne=$apayerPagnet - $pagnet['versement'];

                    }

                

                    $sql16="update `".$nomtablePagnet."` set totalp=".$TotalTP[0].",apayerPagnet=".$apayerPagnet.",

                                                        restourne=".$restourne." where idPagnet='".$idPagnet."'";

                    $res16=mysql_query($sql16) or die ("update Pagnet impossible =>".mysql_error());

                    

                     

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

                            $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where verrouiller='1' && idClient=".$idClient." && (type=0 || type=30) ";

                            $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());

                            $TotalP = mysql_fetch_array($res18) ;



                            $sql19="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." && type=1 ";

                            $res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());

                            $TotalR = mysql_fetch_array($res19) ;



                            $total=$TotalP[0] - $TotalR[0];

                            

                            $sql20="UPDATE `".$nomtableBon."` set montant=".$total.", date='".$dateString."' where idClient=".$idClient;

                            $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());

                            

                            $sql3="UPDATE `".$nomtableClient."` set solde=solde-".$prixtotal." where idClient=".$idClient;

                            $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());



                        }

                    }



                }

                else{

                    $sqlR="UPDATE `".$nomtablePagnet."` set type=2 where idPagnet=".$idPagnet;

                    $resR=@mysql_query($sqlR) or die ("mise à jour client  impossible".mysql_error());

                }



            }



        }

        else {

            echo "<p>IMPOSSIBLE.</br></br> Vous ne pouvez pas retourner cette ligne du pagnet numéro ".$idPagnet." , car c'est la(les) dernière(s) ligne(s). Cela risque de fausser les calculs surtout quand vous avez effectué des remises.</br></br>Il faut retourner tout le pagnet.</p>";

        }

        echo '1';

    }

/**Fin Button Retourner Ligne d'un Pagnet**/

    else if (isset($_POST['modeEditionBtnET'])){



        $idPagnet=htmlspecialchars(trim($_POST['idPanier']));
    
    
    
        $sql1="UPDATE `".$nomtablePagnet."` set  verrouiller=0 where idPagnet=".$idPagnet;
    
        $res1=mysql_query($sql1) or die ("Edition panier impossible =>".mysql_error());
    
            
    
        if($res1){
    
        
    
        $sqlP="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
    
        $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());
    
        $pagnet = mysql_fetch_assoc($resP) ;
    
    
    
        $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
    
        $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
    
        //$ligne = mysql_fetch_assoc($resL) ;
    
    
    
        if($pagnet['type']==0 || $pagnet['type']==30){
    
            while ($ligne=mysql_fetch_assoc($resL)){
    
                if($ligne['classe']==0){
    
                    $sqlS="SELECT idDesignation,designation,uniteStock,nbreArticleUniteStock FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
    
                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    
                    $designation = mysql_fetch_assoc($resS) ;
    
                        if(mysql_num_rows($resS)){
    
                            if ($ligne['unitevente']==$designation['uniteStock']) {
    
                                $sqlD="SELECT idEntrepotStock,quantiteStockCourant,totalArticleStock FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC Limit 10";
    
                                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    
                                $retour=$ligne['quantite']*$designation['nbreArticleUniteStock'];
    
                                while ($stock = mysql_fetch_assoc($resD)) {
    
                                    if($retour>= 0){
    
                                        $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;
    
                                        if($stock['totalArticleStock'] >= $quantiteStockCourant){
    
                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];
    
                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    
                                        }
    
                                        else{
    
                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idEntrepotStock=".$stock['idEntrepotStock'];
    
                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    
                                        }
    
                                        $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;
    
                                    }
    
                                }
    
    
    
                            }
    
                            else if ($ligne['unitevente']=='Demi Gros') {
    
                                $sqlD="SELECT idEntrepotStock,quantiteStockCourant,totalArticleStock FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC Limit 10 ";
    
                                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    
                                $retour=$ligne['quantite']* ($designation['nbreArticleUniteStock']/2);
    
                                while ($stock = mysql_fetch_assoc($resD)) {
    
                                    if($retour>= 0){
    
                                        $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;
    
                                        if($stock['totalArticleStock'] >= $quantiteStockCourant){
    
                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];
    
                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    
                                        }
    
                                        else{
    
                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idEntrepotStock=".$stock['idEntrepotStock'];
    
                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    
                                        }
    
                                        $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;
    
                                    }
    
                                }
    
    
    
                            }
    
                            else {
    
                                $sqlD="SELECT idEntrepotStock,quantiteStockCourant,totalArticleStock FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC Limit 10 ";
    
                                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    
                                $retour=$ligne['quantite'];
    
                                while ($stock = mysql_fetch_assoc($resD)) {
    
                                    if($retour >= 0){
    
                                        $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;
    
                                        if($stock['totalArticleStock'] >= $quantiteStockCourant){
    
                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];
    
                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    
                                        }
    
                                        else{
    
                                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idEntrepotStock=".$stock['idEntrepotStock'];
    
                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    
                                        
    
                                        }
    
                                        $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;
    
                                        
    
                                    }
    
                                    
    
                                }
    
    
    
                            }
    
                        }
    
                }
    
            }
            
    
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
    
    
    
            $result="1";
    
        }
    
    
    
        }
    
        else{
    
        $result="0"; 
    
        }
    
        echo $result;
        // exit($result);
    
    }