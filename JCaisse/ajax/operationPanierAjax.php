<?php

session_start();
if(!$_SESSION['iduser']){ 
  header('Location:../index.php');
  }

require('../connection.php');

require('../connectionPDO.php');

require('../declarationVariables.php'); 

if ($_SESSION['tampon'] == 1 && $_SESSION['page']=='vente') {
    // var_dump('tampon');
    // die();
    require('operationPanierAjax-tampon.php');
} else {
    

function getPanierInCptChoice($idP, $tab) {
    $i=0;
    foreach ($tab as $key) {
        # code...        
        $items = explode('-', $key);
        $idPanier = $items[0];
        // $idCompte = $items[1];
        // $montant = $items[2];

        if ($idP == $idPanier) {
            # code...
            
            // var_dump('ggggggggggg');
            return $i;
        } 
        $i++;
    }

    return -1;  

}

$beforeTime = '00:00:00';
$afterTime = '06:00:00';

    // var_dump(date('d-m-Y',strtotime("-1 days")));

if($_SESSION['Pays']=='Canada'){  
	$date = new DateTime();
	$timezone = new DateTimeZone('Canada/Eastern');
}
else{
	$date = new DateTime();
	$timezone = new DateTimeZone('Africa/Dakar');
}
$date->setTimezone($timezone);
$heureString=$date->format('H:i:s');

if ($heureString >= $beforeTime && $heureString < $afterTime) {
   	// var_dump ('is between');
	$date = new DateTime (date('d-m-Y',strtotime("-1 days")));
}

// $date->setTimezone($timezone);
$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateString2=$jour.'-'.$mois.'-'.$annee;
$dateHeures=$dateString.' '.$heureString;

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
    
            $query = mysql_query("SELECT idPagnet FROM `".$nomtablePagnet."` where  iduser='".$_SESSION['iduser']."' && type=0 && idClient=0 && datepagej ='".$dateString2."' && verrouiller=0");
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

        $query = mysql_query("SELECT idPagnet FROM `".$nomtablePagnet."` where  iduser='".$_SESSION['iduser']."' && type=0 && idClient=0 && datepagej ='".$dateString2."' && verrouiller=0");
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
    if (isset($_POST['remise']) || isset($_POST['versement']) || isset($_POST['compte'])) {

        $idPagnet=@$_POST['idPagnet'];
        $qtyVendu=0;

        $sql="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
        $pagnet = mysql_fetch_assoc($res) ;

        $dejaTerminer=$pagnet['dejaTerminer'];

        $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
        $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
        $TotalP = mysql_fetch_array($resT) ;

        if ($_SESSION['Pays']=='Canada') { 
            $sqlTP="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
            $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());
            $TotalTP = mysql_fetch_array($resTP) ;
            $totalTvaP=$TotalTP[0];

            $sqlTR="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
            $resTR = mysql_query($sqlTR) or die ("persoonel requête 2".mysql_error());
            $TotalTR = mysql_fetch_array($resTR) ;
            $totalTvaR=$TotalTR[0];
        }

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

        $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
        $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

        /*****Debut Nombre de Panier ouvert****/
        $query = mysql_query("SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ");
        $nbre_fois=mysql_num_rows($query);
        /*****Fin Nombre de Panier ouvert****/

        /*****Debut Difference entre Total Panier et Remise****/
        $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
        $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
        $TotalT = mysql_fetch_array($resT) ;

        $difference=$TotalT[0] - $remise;
        /*****Fin Difference entre Total Panier et Remise****/

        if($pagnet['verrouiller']==0){
            if($nbre_fois>0){
                if($difference>=0){
                    if($pagnet['type']==0 || $pagnet['type']==30){
                        while ($ligne=mysql_fetch_assoc($resL)){
                            if($ligne['classe']==0){
                                $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
                                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                                $designation = mysql_fetch_assoc($resS) ;
                                    if(mysql_num_rows($resS)){
                                        $sql_E="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' AND quantiteStockCourant!=0 ";
                                        $res_E=mysql_query($sql_E) or die ("select stock impossible =>".mysql_error());
                                        $t_stock = mysql_fetch_array($res_E) ;
                                        if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {
                                            $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' AND quantiteStockCourant!=0 ORDER BY idStock ASC ";
                                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
                                            $restant=$ligne['quantite']*$designation['nbreArticleUniteStock'];
                                            $qtyVendu=$ligne['quantite']*$designation['nbreArticleUniteStock'];
                                            while ($stock = mysql_fetch_assoc($resD)) {
                                                if($restant>= 0){
                                                    $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
                                                    if($quantiteStockCourant > 0){
                                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
                                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                                    }
                                                    else{
                                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];
                                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                                    }
                                                    $restant= $restant - $stock['quantiteStockCourant'] ;
                                                }
                                            }
                                            $stock_V=$ligne['quantite']*$designation['nbreArticleUniteStock'];
                                            if($t_stock[0]<$stock_V){
                                                if(is_numeric($t_stock[0])){
                                                    $stock_T=$t_stock[0];
                                                }
                                                else{
                                                    $stock_T=0;
                                                }
                                                $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
                                                VALUES(0,'.$ligne['idDesignation'].','.$stock_V.','.$designation['nbreArticleUniteStock'].','.$stock_T.',"'.$dateString.'",5)';
                                                $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
                                            }
    
                                        }
                                        else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){
                                            $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' AND quantiteStockCourant!=0 ORDER BY idStock ASC ";
                                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
                                            $restant=$ligne['quantite'];
                                            $qtyVendu=$ligne['quantite'];
                                            while ($stock = mysql_fetch_assoc($resD)) {
                                                if($restant>= 0){
                                                    $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
                                                    if($quantiteStockCourant > 0){
                                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
                                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                                    }
                                                    else{
                                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];
                                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                                    }
                                                    $restant= $restant - $stock['quantiteStockCourant'] ;
                                                }
                                            }
                                            $stock_V=$ligne['quantite'];
                                            if($t_stock[0]<$stock_V){
                                                if(is_numeric($t_stock[0])){
                                                    $stock_T=$t_stock[0];
                                                }
                                                else{
                                                    $stock_T=0;
                                                }
                                                $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
                                                VALUES(0,'.$ligne['idDesignation'].','.$stock_V.',1,'.$stock_T.',"'.$dateString.'",5)';
                                                $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
                                            }
    
                                        }
                                        /************************/
                                        
                                        $sqlSV="UPDATE `".$nomtableStock."` set quantiteStockTemp=quantiteStockTemp-".$qtyVendu." where idDesignation=".$ligne['idDesignation'];
                                        $resSV=mysql_query($sqlSV) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                        /************************/
                                    }
                            }
                        }

                        if($_SESSION['Pays']=='Canada'){ 
                            if ($dejaTerminer == 0) {
                                # code...
                                $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1', remise=".$remise.",totalp=".$totalp.",apayerPagnet=".$apayerPagnet.",apayerPagnetTvaP=".$totalTvaP.",apayerPagnetTvaR=".$totalTvaR.",versement=".$versement.",restourne=".$monaie.", datepagej='".$dateString2."', heurePagnet='".$heureString."', dejaTerminer=1 where idPagnet=".$idPagnet;
                                $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
                            } else {
                                # code...
                                $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1', remise=".$remise.",totalp=".$totalp.",apayerPagnet=".$apayerPagnet.",apayerPagnetTvaP=".$totalTvaP.",apayerPagnetTvaR=".$totalTvaR.",versement=".$versement.",restourne=".$monaie." where idPagnet=".$idPagnet;
                                $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
                            }
                            
                        }
                        else{
                            if ($dejaTerminer == 0) {
                                # code...
                                $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1', remise=".$remise.",totalp=".$totalp.",apayerPagnet=".$apayerPagnet.",versement=".$versement.",restourne=".$monaie.", datepagej='".$dateString2."', heurePagnet='".$heureString."', dejaTerminer=1 where idPagnet=".$idPagnet;
                                $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
                            } else {
                                # code...
                                $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1', remise=".$remise.",totalp=".$totalp.",apayerPagnet=".$apayerPagnet.",versement=".$versement.",restourne=".$monaie." where idPagnet=".$idPagnet;
                                $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
                            }
                            
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
                                    
                                } else {
                                    
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

                                    }  
                                    // else if ($idCompte == '10') {
                                    //     $description="Encaissement vente";
                                    // }
                                    else {
                                        $description="Encaissement vente";
                                    }
            
                                    if ($idCompte == '1000') {
                                        # code...
                                        $i = 0;
                                        $idComptes = [];
                                        $choiceArray = [];
                                        foreach ($_SESSION['cptChoiceArray'] as $choice) {
                                            # code... 
                                            foreach ($choice as $key) {      
                                                $items = explode('-', $key);
                                                $idPanier = $items[0];
                                                // $idCompte = $items[1];
                                                // $montant = $items[2];

                                                if ($idPagnet == $idPanier) {
                                                    # code.
                                                    $choiceArray = $_SESSION['cptChoiceArray'][$i];
                                                    break 2;
                                                }
                                                //  else {
                                                //     var_dump(4);
                                                //     // $_SESSION['cptChoiceArray'][] = $choiceArray;
                                                //     $j++;
                                                // }
                                            }
                                            $i++;
                                        }
                                        // var_dump($choiceArray);
                                        $k=0;
                                        foreach ($choiceArray as $key) {

                                            $_items = explode('-', $key);
                                            $idPagnet = $_items[0];
                                            $_idCompte = $_items[1];
                                            $montant = $_items[2];
                                            $idComptes[$k] = $_idCompte;

                                            $sql7="UPDATE `".$nomtableCompte."` set montantCompte= montantCompte + ".$montant." where  idCompte=".$_idCompte;
                                            $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());
                            
                                            $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser) values(".$montant.",'".$operation."',".$_idCompte.",'".$description."','".$dateHeures."','".$dateHeures."',".$idPagnet.",".$_SESSION['iduser'].")";
                                            $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error());
                                            $k++;
                                        }
                                        
                                        $idsCpteMultiple = implode('_',$idComptes);
                                        // var_dump($idComptes);
                                        // var_dump($idsCpteMultiple);

                                        $sql8="UPDATE `".$nomtablePagnet."`  set  idCompte=".$idCompte.", idsCpteMultiple='".$idsCpteMultiple."'  where  idPagnet=".$idPagnet;
                                        $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());


                                    } else {
                                        # code...
                                        $sql8="UPDATE `".$nomtablePagnet."`  set  idCompte=".$idCompte." where  idPagnet=".$idPagnet;
                                        $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());

                                        $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte + ".$apayerPagnet." where  idCompte=".$idCompte;
                                        $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());
                        
                                        $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser) values(".$apayerPagnet.",'".$operation."',".$idCompte.",'".$description."','".$dateHeures."','".$dateHeures."',".$idPagnet.",".$_SESSION['iduser'].")";
                                        $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error());
                                    }
                                    
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

                        $resultat='1<>'.$apayerPagnet;
                    }

                }
                else {
                    $resultat='0';
                    echo "<p>IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idPagnet.", il faut verifier la remise.</p>";
                }
                
            }
            else {
                $resultat='0';
                echo "<p>IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idPagnet.", il faut au moins ajouter un produit.</p>";
            }
        }
       // echo $resultat;

    }else  {}
    // echo $msg_info;
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

    echo "1";
}
/**Fin Button Annuler Pagnet**/

/**Debut Button Retourner Pagnet**/
else if (isset($_POST['btnRetournerPagnet'])) {

    $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));
    $qtyVendu=0;
    
    $sqlP="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
    $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());
    $pagnet = mysql_fetch_assoc($resP) ;

    $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
    $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
    //$ligne = mysql_fetch_assoc($resL) ;
    $idClient=$pagnet['idClient'];

    if($pagnet['type']==0 || $pagnet['type']==30){
        while ($ligne=mysql_fetch_assoc($resL)){
            if($ligne['classe']==0){
                $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                $designation = mysql_fetch_assoc($resS) ;
                    if(mysql_num_rows($resS)){
                        if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {
                            $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";
                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
                            $retour=$ligne['quantite']*$designation['nbreArticleUniteStock'];
                            $qtyVendu=$ligne['quantite']*$designation['nbreArticleUniteStock'];
                            while ($stock = mysql_fetch_assoc($resD)) {
                                if($retour>= 0){
                                    $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;
                                    if($stock['totalArticleStock'] >= $quantiteStockCourant){
                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                    }
                                    else{
                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idStock=".$stock['idStock'];
                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                    }
                                    $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;
                                }
                            }

                        }
                        else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){
                            $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";
                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
                            $retour=$ligne['quantite'];
                            $qtyVendu=$ligne['quantite'];
                            while ($stock = mysql_fetch_assoc($resD)) {
                                if($retour >= 0){
                                    $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;
                                    if($stock['totalArticleStock'] >= $quantiteStockCourant){
                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                    }
                                    else{
                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idStock=".$stock['idStock'];
                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                    
                                    }
                                    $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;
                                    
                                }
                                
                            }

                        }
                        /************************/
                        
                        $sqlSV="UPDATE `".$nomtableStock."` set quantiteStockTemp=quantiteStockTemp+".$qtyVendu." where idDesignation=".$ligne['idDesignation'];
                        $resSV=mysql_query($sqlSV) or die ("update quantiteStockCourant impossible =>".mysql_error());
                        /************************/
                        
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
                $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where verrouiller='1' && idClient=".$idClient." && (type=0 || type=30) ";
                $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());
                $TotalP = mysql_fetch_array($res18) ;

                $sql19="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." && type=1 ";
                $res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());
                $TotalR = mysql_fetch_array($res19) ;

                $total=$TotalP[0] - $TotalR[0];
                
                $sql20="UPDATE `".$nomtableBon."` set montant=".$total.", date='".$dateString."' where idClient=".$idClient;
                $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());
                
                $sql3="UPDATE `".$nomtableClient."` set solde=solde-".$pagnet['apayerPagnet']." where idClient=".$idClient;
                $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());

            }
        }

        echo '1';
    }
        
    
}
/**Fin Button Retourner Pagnet**/

/**Debut Button Annuler Ligne d'un Pagnet**/
else if (isset($_POST['btnRetourAvant'])) {

    $numligne=$_POST['numligne'];
    // $unitevente=@$_POST['unitevente'];

    //on fait la suppression de cette ligne dans la table ligne
    $sql3="DELETE FROM `".$nomtableLigne."` where numligne=".$numligne;
    $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

    echo "1";

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
    $qtyVendu=0;

    $sqlL="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($resL) ;

    $sqlP="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
    $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());
    $pagnet = mysql_fetch_assoc($resP) ;
    
    $idClient=$pagnet['idClient'];

    $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' && numligne<>".$numligne."";
    $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
    $TotalT = mysql_fetch_array($resT) ;

    $difference=$TotalT[0] + ($quantite * $ligne['prixunitevente']) - $pagnet["remise"];

    if($difference >= 0 && $ligne['quantite'] >= $quantite){
        if($pagnet['type']==0 || $pagnet['type']==30){
            if($ligne['classe']==0){
                $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                $designation = mysql_fetch_assoc($resS) ;
                    if(mysql_num_rows($resS)){
                        if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {
                            $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";
                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
                            $retour=($ligne['quantite'] - $quantite) * $designation['nbreArticleUniteStock'];
                            $qtyVendu=($ligne['quantite'] - $quantite) * $designation['nbreArticleUniteStock'];
                            while ($stock = mysql_fetch_assoc($resD)) {
                                if($retour>= 0){
                                    $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;
                                    if($stock['totalArticleStock'] >= $quantiteStockCourant){
                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                        
                                    }
                                    else{
                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idStock=".$stock['idStock'];
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
                        else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){
                            $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";
                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
                            $retour=$ligne['quantite'] - $quantite;
                            $qtyVendu=$ligne['quantite'] - $quantite;
                            while ($stock = mysql_fetch_assoc($resD)) {
                                if($retour >= 0){
                                    $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;
                                    if($stock['totalArticleStock'] >= $quantiteStockCourant){
                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                    }
                                    else{
                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idStock=".$stock['idStock'];
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
                        
                        /************************/
                        
                        $sqlSV="UPDATE `".$nomtableStock."` set quantiteStockTemp=quantiteStockTemp+".$qtyVendu." where idDesignation=".$ligne['idDesignation'];
                        $resSV=mysql_query($sqlSV) or die ("update quantiteStockCourant impossible =>".mysql_error());
                        /************************/
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
    
            }
            else{
                $sqlR="UPDATE `".$nomtablePagnet."` set type=2 where idPagnet=".$idPagnet;
                $resR=@mysql_query($sqlR) or die ("mise à jour client  impossible".mysql_error());
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

        $msg_info="1";
    }
    else {
        $msg_info="<p>IMPOSSIBLE.</br></br> Vous ne pouvez pas retourner cette ligne du pagnet numéro ".$idPagnet." , car c'est la(les) dernière(s) ligne(s). Cela risque de fausser les calculs surtout quand vous avez effectué des remises.</br></br>Il faut retourner tout le pagnet.</p>";
    }

    echo $msg_info;
}
/**Fin Button Retourner Ligne d'un Pagnet**/
/**Debut Button ajout info supp**/
else if (isset($_POST['addInfoSup'])) {

    $numLigne=htmlspecialchars($_POST['numLigne'], ENT_QUOTES, 'UTF-8');
    $numeroChassis=htmlspecialchars($_POST['numeroChassis'], ENT_QUOTES, 'UTF-8');
    $numeroMoteur=htmlspecialchars($_POST['numeroMoteur'], ENT_QUOTES, 'UTF-8');
    $couleur=htmlspecialchars($_POST['couleur'], ENT_QUOTES, 'UTF-8');

    $sqlS="UPDATE `".$nomtableLigne."` set numeroChassis='".$numeroChassis."', numeroMoteur='".$numeroMoteur."', couleur='".$couleur."'  where numligne=".$numLigne;
    $resS=mysql_query($sqlS) or die ("info supp impossible =>".mysql_error());

    echo 1;
}
/**Fin Button ajout info supp**/

/**Debut Button somme quantite d'un produit par periode**/
else if (isset($_POST['sumQtyByPeriod'])) {

    $idDesignation=htmlspecialchars($_POST['idDesignation'], ENT_QUOTES, 'UTF-8');
    $date1=htmlspecialchars($_POST['date1'], ENT_QUOTES, 'UTF-8');
    $date2=htmlspecialchars($_POST['date2'], ENT_QUOTES, 'UTF-8');


    $sql="SELECT SUM(l.quantite) as sumQtyByPeriod FROM  `".$nomtableLigne."` l
    
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    
    where p.verrouiller=1 && (p.type=0 || p.type=1 || p.type=30) && l.idDesignation='".$idDesignation."' && 

    (CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$date1."' AND '".$date2."') or (datepagej BETWEEN '".$date1."' AND '".$date2."')";
    
    $query = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    
    $res=mysql_fetch_array($query);
    
    $sumQtyByPeriod=$res["sumQtyByPeriod"];

    echo $sumQtyByPeriod;
}
/**Fin Button somme quantite d'un produit par periode**/

/**Debut gestion compte multiple**/
else if (isset($_POST['cptManage'])) {
    
    $choiceArray=$_POST['data'];
    $idPPosted=$_POST['idPanier'];
    $content = '';

    if (sizeof($_SESSION['cptChoiceArray']) == 0) {
        // var_dump(1);
        # code...        
        $_SESSION['cptChoiceArray'][] = $choiceArray;
    } else {
        // var_dump(2);
        $i=0;
        $j=0;
        foreach ($_SESSION['cptChoiceArray'] as $choice) {
            # code... 
            foreach ($choice as $key) {      
                $items = explode('-', $key);
                $idPanier = $items[0];
                // $idCompte = $items[1];
                // $montant = $items[2];

                if ($idPPosted == $idPanier) {
                    # code...
                    $j = 1;
                    // var_dump(3);
                    $_SESSION['cptChoiceArray'][$i] = $choiceArray;
                    break 2;
                }
                //  else {
                //     var_dump(4);
                //     // $_SESSION['cptChoiceArray'][] = $choiceArray;
                //     $j++;
                // }
            }
            $i++;
        }

        if ($j == 0) {
            # code...
            // var_dump(4);
            $_SESSION['cptChoiceArray'][] = $choiceArray;
        }
        # code...
    }
    // var_dump($_SESSION['cptChoiceArray']);
    // $content = '<table class="table table-striped">';
    $content = '<hr><div>';
        foreach ($_SESSION['cptChoiceArray'] as $choice) { 
            foreach ($choice as $key) {
                # code...

                $items = explode('-', $key);
                $idPanier = $items[0];
                $idCompte = $items[1];
                $montant = $items[2];
                
                if ($idPanier == $idPPosted) {
                    # code...
                    $sqlGetCompte3="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$idCompte;

                    $resPay3 = mysql_query($sqlGetCompte3) or die ("persoonel requête 2".mysql_error());

                    $cpt = mysql_fetch_array($resPay3);

                    // $content = $content.'<tr>';

                    $content = $content.'<br>'.$cpt['nomCompte'].' : ';
                    // $content = $content.'<td>'.$cpt['nomCompte'].'</td>';

                    // $content = $content.'<td>'.$montant.'</td>';
                    $content = $content.''.$montant.'';
                }

            // $content = $content.'</tr>';
            }
        }
            // <td>'..'</td>
            // <td>'..'</td>
      $content = $content.'</div>';

      echo $content;
}
/**Fin gestion compte multiple**/

// else if (isset($_POST['changeNumContainer'])) {
    
//     $idPagnetContainer = $_POST['idPagnet'];
//     $numContainerChanged = $_POST['numContainerChanged'];
    
//     $sql3="UPDATE `".$nomtablePagnet."` set numContainer='".$numContainerChanged."' where idPagnet=".$idPagnetContainer;

//     $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
//     // var_dump($res3);

//     if ($res3) {
//         # code...

//         echo '1';
//     } else {
//         # code...

//         echo '0';
//     }
    
// }


else if (isset($_POST['changeNumContainer'])) {
    
    $idPagnetContainer = $_POST['idPagnet'];
    $numContainerChanged = $_POST['numContainerChanged'];
    $idContainer = $_POST['idContainer'];
    
    $sql3="UPDATE `".$nomtablePagnet."` set numContainer='".$idContainer."' where idPagnet=".$idPagnetContainer;

    $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
    // var_dump($res3);

    if ($res3) {
        # code...

        echo '1';
    } else {
        # code...

        echo '0';
    }
    
}

else if (isset($_POST['cbmManager'])) {
    
    $idClient = htmlspecialchars($_POST['idClient'], ENT_QUOTES);
    $numContainer = htmlspecialchars($_POST['numContainer'], ENT_QUOTES);
    $qty_cbm = htmlspecialchars($_POST['qty_cbm'], ENT_QUOTES);
    $qty_bal = htmlspecialchars($_POST['qty_bal'], ENT_QUOTES);
    $nbPcsInContainer = htmlspecialchars($_POST['nbPcsInContainer'], ENT_QUOTES);

    $totalp=$qty_cbm*127000+$qty_bal;

    $bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    try {
        // From this point and until the transaction is being committed every change to the database can be reverted
        $bdd->beginTransaction();   

        if ($_SESSION['compte']==1) {
            $compte='2';
            
            $req4 = $bdd->prepare("INSERT INTO `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,verrouiller,idCompte,idClient,numContainer,nbPcsInContainer)
            values (:d,:h,:u,:t,:tp,:ap,:v,:cpt,:c,:nc,:nbP)");
            $req4->execute(array(
                'd' => $dateString2,
                'h' => $heureString,
                'u' => $_SESSION['iduser'],
                't' => 0,
                'tp' => $totalp,
                'ap' => $totalp,
                'v' => 1,
                'cpt' => $compte,
                'c' => $idClient,
                'nc' => $numContainer,
                'nbP' => $nbPcsInContainer
            ))  or die(print_r("Insert pagnet 1 ".$req4->errorInfo()));
            $req4->closeCursor();

        } else {
            
            $req4 = $bdd->prepare("INSERT INTO `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,verrouiller,idClient,numContainer,nbPcsInContainer)
            values (:d,:h,:u,:t,:tp,:ap,:v,:c,:nc,:nbP)");
            $req4->execute(array(
                'd' => $dateString2,
                'h' => $heureString,
                'u' => $_SESSION['iduser'],
                't' => 0,
                'tp' => $totalp,
                'ap' => $totalp,
                'v' => 1,
                'c' => $idClient,
                'nc' => $numContainer,
                'nbP' => $nbPcsInContainer
            ))  or die(print_r("Insert pagnet 2 ".$req4->errorInfo()));
            $req4->closeCursor();
        }
        $idPagnet = $bdd->lastInsertId();

        if ($qty_cbm!=0) {
            # code...

            $sql="SELECT * FROM `".$nomtableDesignation."` where designation='cbm'";
            
	        $statement = $bdd->prepare($sql);
            $statement->execute();
            
            $design = $statement->fetch(PDO::FETCH_ASSOC); 
            
            $preparedStatementCbm = $bdd->prepare(
                "insert into `".$nomtableLigne."` (designation, idDesignation, unitevente, prixunitevente, quantite, prixtotal, idPagnet, classe) values (:d,:idd,:uv,:pu,:qty,:p,:idp,:c)"
            );
            
            $preparedStatementCbm->execute([
                'd' => $design['designation'],
                'idd' => $design['idDesignation'],
                'uv' => $design['uniteStock'],
                'pu' => $design['prix'],
                'qty' => $qty_cbm,
                'p' => $design['prix']*$qty_cbm,
                'idp' => $idPagnet,
                'c' => 1
            ]);
        }

        if ($qty_bal!=0) {
            # code...
            $sql="SELECT * FROM `".$nomtableDesignation."` where designation='bal'";
            
	        $statement = $bdd->prepare($sql);
            $statement->execute();
            
            $design = $statement->fetch(PDO::FETCH_ASSOC); 
            
            $preparedStatementBal = $bdd->prepare(
                "insert into `".$nomtableLigne."` (designation, idDesignation, unitevente, prixunitevente, quantite, prixtotal, idPagnet, classe) values (:d,:idd,:uv,:pu,:qty,:p,:idp,:c)"
            );
            
            $preparedStatementBal->execute([
                'd' => $design['designation'],
                'idd' => $design['idDesignation'],
                'uv' => $design['uniteStock'],
                'pu' => $qty_bal,
                'qty' => 1,
                'p' => $qty_bal,
                'idp' => $idPagnet,
                'c' => 1
            ]);
        }

        /**** generate barcode****/
        
        $x= strlen($idPagnet);

        if($x==1){

            $code=$idPagnet.'34522345'.$idPagnet;

        }

       else if($x==2){

            $code=$idPagnet.'342234'.$idPagnet;

        }

       else if($x==3){

            $code=$idPagnet.'3223'.$idPagnet;

        }

        else if($x==4){

            $code=$idPagnet.'22'.$idPagnet;

        } else {
            $rand = rand(100,999);
            $code=$idPagnet.''.$rand;
        }

        $sqlU="UPDATE `".$nomtablePagnet."` SET codeBarrePcsInContainer='".$code."' where idPagnet=".$idPagnet;
        
        $statementU = $bdd->prepare($sqlU);
        $statementU->execute();

        /**** generate barcode****/
        // Make the changes to the database permanent
        $bdd->commit();
        
        echo '1';
    }
    catch ( PDOException $e ) { 
        // Failed to insert the order into the database so we rollback any changes
        $bdd->rollback();
        throw $e;

        // echo '0';
    }
    
}

else if(isset($_POST['numContainerChange'])){

    $containers = [];
    $nomtableContainer=$_SESSION['nomB']."-container";

    $query=htmlspecialchars(trim($_POST['query']));   
  
    $sql3="SELECT * FROM `".$nomtableContainer."` where (numContainer LIKE '%$query%') ORDER BY idContainer DESC";
  
    $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
  
  
    while($container = mysql_fetch_assoc($res3)){
  
        $containers[] = $container['idContainer']." . ".$container['numContainer'];
  
    }
  
    echo json_encode($containers);
  
  }
  

  else if(isset($_POST['getContainer'])){

    $idContainer=htmlspecialchars(trim($_POST['idContainer']));   
  
    $sql3="SELECT * FROM `".$nomtableContainer."` where idContainer=".$idContainer;
  
    $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
  
    $container = mysql_fetch_assoc($res3);
  
    echo json_encode($container);
  
}

else if(isset($_POST['confimrEditContainer'])){

    $idContainer=htmlspecialchars(trim($_POST['idContainer']));   
    $numContainer=htmlspecialchars(trim($_POST['numContainer']));   
    $dateDepart=htmlspecialchars(trim($_POST['dateDepart']));   
    $dateArrivee=htmlspecialchars(trim($_POST['dateArrivee']));   
  
    $sql3="UPDATE `".$nomtableContainer."` SET numContainer='".$numContainer."', dateDepart='".$dateDepart."', dateArrivee='".$dateArrivee."' where idContainer=".$idContainer;
  
    $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
    
    if ($res3) {
        echo '1';
    } else {
        echo '0';
    }
    
}

else if(isset($_POST['confimrDeleteContainer'])){

    $idContainer=htmlspecialchars(trim($_POST['idContainer']));    
  
    $sql3="UPDATE `".$nomtableContainer."` SET retirer=1 where idContainer=".$idContainer;
  
    $res3 = mysql_query($sql3) or die ("retirer container ".mysql_error());
    
    if ($res3) {
        echo '1';
    } else {
        echo '0';
    }
    
}

}