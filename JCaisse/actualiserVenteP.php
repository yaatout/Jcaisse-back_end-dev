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

/**********************/
$numligne         =@$_POST["numligne"];
$type             =@htmlentities($_POST["type"]);
$designation      =@htmlentities($_POST["designation"]);
$unitevente		  =@$_POST["unitevente"];
$prix             =@$_POST["prix"];
$quantite         =@$_POST["quantite"];
$remise           =@$_POST["remise"];
$prixtotal        =@$_POST["prixt"];

$aliasTrans          =@$_POST["aliasTrans"];
$typeTrans          =@$_POST["typeTrans"];
$montantTrans        =@$_POST["montantTrans"];
$pagnetTrans        =@$_POST["pagnetTrans"];
$idFact          =@$_POST["idFact"];

$modifier         =@$_POST["modifier"];
$annuler          =@$_POST["annuler"];
/***************/

$numligne2       =@$_GET["numligne"];
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
$dateHeures=$dateString.' '.$heureString;
/**Fin informations sur la date d'Aujourdhui **/

if($mois==1){
    $annee_paie=$annee - 1;
}
else{
    $annee_paie=$annee;
}

if ($_SESSION['compte']==1) {
    /***Debut compte qui reçoit paiement ***/
    $sqlGetComptePay="SELECT * FROM `".$nomtableCompte."` where idCompte<>3 ORDER BY idCompte ASC";
    $resPay = mysql_query($sqlGetComptePay) or die ("persoonel requête 2".mysql_error());

    $cpt_array = [];
    while ($cpt = mysql_fetch_array($resPay)) {
        # code...
        $cpt_array[] = $cpt;  // var_dump($key);
    }
}

/**Debut Button Ajouter Ligne dans un pagnet**/
    if (isset($_POST['btnEnregistrerCodeBarre'])) {

        if (isset($_POST['codeBarre']) && isset($_POST['idPagnet'])) {
            $codeBarre=htmlspecialchars(trim($_POST['codeBarre']));
            $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));
            $codeBrute=explode('-', $codeBarre);
            if(!empty($codeBrute[1]) && is_int($codeBrute[0])){
                $msg_info="<b>LE CODE-BARRE SAISIE NE FIGURE PAS DANS LE STOCK.</b></br></br>
                    VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ...";
            }
            /**Debut de la Vente des Produits sur la Designation */
            else{
                $sql="SELECT * FROM `".$nomtableDesignation."` WHERE (codeBarreDesignation='".$codeBarre."' OR codeBarreuniteStock='".$codeBarre."' or designation='".$codeBarre."') ";
                $res=mysql_query($sql) or die ("select stock impossible =>".mysql_error());
                if(mysql_num_rows($res)){
                    $design = mysql_fetch_assoc($res);
                    if($design['codeBarreDesignation']==$codeBarre){
                        $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$design['idDesignation']."' ";
                        $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
                        $t_stock = mysql_fetch_array($res_t) ;
                        $restant = $t_stock[0];
                        if($restant>0){
                            /***Debut verifier si la ligne du produit existe deja ***/
                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and classe=0 ";
                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                            $ligne = mysql_fetch_assoc($res);
                            /***Debut verifier si la ligne du produit existe deja ***/
                            if($ligne != null){
                                $quantite = $ligne['quantite'] + 1;
                                $reste = $t_stock[0] - $quantite;
                                if ($reste>=0){
                                    $prixTotal=$quantite*$ligne['prixPublic'];
                                    $sql7="UPDATE `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where idPagnet=".$idPagnet." and idDesignation=".$design['idDesignation'];
                                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                }
                                else{
                                    $msg_info="<p>IMPOSSIBLE.</br>
                                    </br> La quantité de ce stock est insuffisant pour la ligne.
                                    </br> Il vous reste  <span>".$t_stock[0]."</span> Unités dans le Stock.
                                    </p>";
                                }                     
                            }
                            else {
                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                                $ligne = mysql_fetch_assoc($res) ;
                                if(mysql_num_rows($res)){
                                    if($ligne['classe']==0){
                                        $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",0)";
                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                    }
                                }
                                else{
                                    $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",0)";
                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                }
                            
                            }
                        }
                        else{
                            $msg_info="<b>LE CODE-BARRE SAISIE CORRESPOND A UN STOCK FINI (TERMINER).</b></br></br>
                            VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ....";
                        }
                    }
                    if($design['codeBarreuniteStock']==$codeBarre){
                        $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$design['idDesignation']."' ";
                        $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
                        $t_stock = mysql_fetch_array($res_t) ;
                        $restant = $t_stock[0];
                        if($restant>0){
                            /***Debut verifier si la ligne du produit existe deja ***/
                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and classe=0 ";
                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                            $ligne = mysql_fetch_assoc($res);
                            /***Debut verifier si la ligne du produit existe deja ***/
                            if($ligne != null){
                                $quantite = $ligne['quantite'] + 1;
                                $reste = $t_stock[0] - $quantite;
                                if ($reste>=0){
                                    $prixTotal=$quantite*$ligne['prixPublic'];
                                    $sql7="UPDATE `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where idPagnet=".$idPagnet." and idDesignation=".$design['idDesignation'];
                                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                }
                                else{
                                    $msg_info="<p>IMPOSSIBLE.</br>
                                    </br> La quantité de ce stock est insuffisant pour la ligne.
                                    </br> Il vous reste  <span>".$t_stock[0]."</span> Unités dans le Stock.
                                    </p>";
                                }                     
                            }
                            else {
                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                                $ligne = mysql_fetch_assoc($res) ;
                                if(mysql_num_rows($res)){
                                    if($ligne['classe']==0){
                                        $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",0)";
                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                    }
                                }
                                else{
                                    $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",0)";
                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                }
                            
                            }
                        }
                        else{
                            $msg_info="<b>LE CODE-BARRE SAISIE CORRESPOND A UN STOCK FINI (TERMINER).</b></br></br>
                            VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ....";
                        }
                    }
                    if($design['designation']==$codeBarre){
                        if($design['classe']==0){
                            $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$design['idDesignation']."' ";
                            $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
                            $t_stock = mysql_fetch_array($res_t) ;
                            $restant = $t_stock[0];
                            if($restant>0){
                                /***Debut verifier si la ligne du produit existe deja ***/
                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and classe=0 ";
                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                                $ligne = mysql_fetch_assoc($res);
                                /***Debut verifier si la ligne du produit existe deja ***/
                                if($ligne != null){
                                    $quantite = $ligne['quantite'] + 1;
                                    $reste = $t_stock[0] - $quantite;
                                    if ($reste>=0){
                                        $prixTotal=$quantite*$ligne['prixPublic'];
                                        $sql7="UPDATE `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where idPagnet=".$idPagnet." and idDesignation=".$design['idDesignation'];
                                        $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                    }
                                    else{
                                        $msg_info="<p>IMPOSSIBLE.</br>
                                        </br> La quantité de ce stock est insuffisant pour la ligne.
                                        </br> Il vous reste  <span>".$t_stock[0]."</span> Unités dans le Stock.
                                        </p>";
                                    }                     
                                }
                                else {
                                    $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
                                    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                                    $ligne = mysql_fetch_assoc($res) ;
                                    if(mysql_num_rows($res)){
                                        if($ligne['classe']==0){
                                            $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",0)";
                                            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                        }
                                    }
                                    else{
                                        $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",0)";
                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                    }
                                
                                }
                            }
                            else{
                                $msg_info="<b>LE CODE-BARRE SAISIE CORRESPOND A UN STOCK FINI (TERMINER).</b></br></br>
                                VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ....";
                            }
                        }
                        if($design['classe']==1){
                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                            $ligne = mysql_fetch_assoc($res) ;
                            if(mysql_num_rows($res)){
                                /***Debut verifier si la ligne du produit existe deja ***/
                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=1  ";
                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                                $ligne1 = mysql_fetch_assoc($res);
                                /***Debut verifier si la ligne du produit existe deja ***/
                                if($ligne1 != null){
                                    $quantite = $ligne1['quantite'] + 1;
                                    $prixTotal=$quantite*$ligne1['prixPublic'];
                                    $sql7="UPDATE `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where idPagnet=".$idPagnet." and idDesignation='".$design['idDesignation']."' and classe=1 ";
                                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                }
                                else {
                                    if($ligne['classe']==1){
                                        if($design['forme']!='Transaction'){
                                            $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",1)";
                                            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                        }
                                        
                                    }
                                    
                                }
                                
                            }
                            else{
                                if($design['forme']=='Transaction'){
                                /*  $reqT="SELECT * from `aaa-transaction` where idTransaction='".$design['description']."'";
                                    $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());
                                    $transaction = mysql_fetch_array($resT);
                                    $image=$transaction['aliasTransaction'];
                                    $trans_alias=$transaction['aliasTransaction'];
                                    $trans_pagnet=$idPagnet;
                                    $msg_transaction="OK";*/
                                }
                                else{
                                    $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",1)";
                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                }
                            }
                        }
                        if($design['classe']==2){
                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                            $ligne = mysql_fetch_assoc($res) ;
                            if(mysql_num_rows($res)){
                                /***Debut verifier si la ligne du produit existe deja ***/
                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idStock='".$design['idDesignation']."' and classe=2  ";
                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                                $ligne1 = mysql_fetch_assoc($res);
                                /***Debut verifier si la ligne du produit existe deja ***/
                                if($ligne1 != null){
                                    $quantite = $ligne1['quantite'] + 1;
                                    $prixTotal=$quantite*$ligne1['prixPublic'];
                                    $sql7="UPDATE `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where idPagnet=".$idPagnet." and idDesignation='".$design['idDesignation']."' and classe=2 ";
                                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                }
                                else {
                                    if($ligne['classe']==2){
                                        $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",2)";
                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                    }
                                }
                            }
                            else{
                                $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",2)";
                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                            }
                        }
                        if($design['classe']==5){
                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                            $ligne = mysql_fetch_assoc($res) ;
                            if(mysql_num_rows($res)){
                                /***Debut verifier si la ligne du produit existe deja ***/
                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=5  ";
                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                                $ligne1 = mysql_fetch_assoc($res);
                                /***Debut verifier si la ligne du produit existe deja ***/
                                if($ligne1 != null){
                                }
                                else {
                                    if($ligne['classe']==5){
                                        $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",5)";
                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                    }
                                }
                            }
                            else{
                                $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",5)";
                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                            }
                        }
                        if($design['classe']==7){
                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                            $ligne = mysql_fetch_assoc($res) ;
                            if(mysql_num_rows($res)){
                                /***Debut verifier si la ligne du produit existe deja ***/
                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=7  ";
                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                                $ligne1 = mysql_fetch_assoc($res);
                                /***Debut verifier si la ligne du produit existe deja ***/
                                if($ligne1 != null){
                                }
                                else {
                                    if($ligne['classe']==7){
                                        $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",7)";
                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                    }
                                }
                            }
                            else{
                                $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",7)";
                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                            }
                        }
                    }
                }
                else{
                    $msg_info="<b>LE CODE-BARRE SAISIE NE FIGURE PAS DANS LE STOCK.</b></br></br>
                    VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ..."; 
                }
            }
            /**Debut de la Vente des Produits sur la Designation */
        }

    }
/**Fin Button Ajouter Ligne dans un pagnet**/

/**Debut Button Terminer Pagnet**/
    if (isset($_POST['btnImprimerFacture'])) {
        if (isset($_POST['remise']) || isset($_POST['versement'])) {
            // code...
            $idPagnet=@$_POST['idPagnet'];

            $sql="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
            $pagnet = mysql_fetch_assoc($res) ;

            $sqlL="SELECT * FROM `".$nomtableLigne."` WHERE idPagnet='".$idPagnet."' ";
            $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
            
            /*****Debut Nombre de Panier ouvert****/
            $query = mysql_query("SELECT * FROM `".$nomtableLigne."` WHERE idPagnet='".$idPagnet."' ");
            $nbre_fois=mysql_num_rows($query);
            /*****Fin Nombre de Panier ouvert****/

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

                        $sql3="UPDATE `".$nomtablePagnet."` SET verrouiller='1', remise='".$remise."',totalp='".$totalp."',apayerPagnet='".$apayerPagnet."',versement='".$versement."',restourne='".$monaie."' WHERE idPagnet='".$idPagnet."' ";
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
                                    $operation='depot';
                                    if ($idCompte == '2') {
                                        $description="Bon";
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

                        if(($i==$j) && $res3){
                            mysql_query("COMMIT;");
                        }
                        else{
                            mysql_query("ROLLBACK;");
                            $msg_info="<p>PROBLEME CONNECTION INTERNET .</br></br> Veuillez terminer le panier numéro ".$idPagnet.".</p>";
                        }
                        
                    }
                    else {
                        $msg_info="<p>IMPOSSIBLE.</br></br> Avant de terminer le panier numéro ".$idPagnet.", il faut verifier la remise.</p>";
                    }
                    
                }
                else {
                    $msg_info="<p>IMPOSSIBLE.</br></br> Avant de terminer le panier numéro ".$idPagnet.", il faut au moins ajouter un produit.</p>";
                }
            }

        }else 
        {}
    }
/**Fin Button /**Debut Button Terminer Pagnet**/

/**Debut Button Terminer Pagnet Imputation**/
    // if (isset($_POST['btnTerminerImputation'])) {

    //     $idMutuellePagnet=@$_POST['idMutuellePagnet'];
    //     $codeBeneficiaire=htmlspecialchars(trim($_POST['codeBeneficiaire']));
    //     $numeroRecu=htmlspecialchars(trim($_POST['numeroRecu']));
    //     $dateRecu=htmlspecialchars(trim($_POST['dateRecu']));
        
    //     $sql="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";
    //     $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    //     $pagnet = mysql_fetch_assoc($res) ;
    //     if($pagnet!=null){
    //         $sql0="SELECT * FROM `".$nomtableMutuelle."` where idMutuelle=".$pagnet['idMutuelle']." ";
    //         $res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());
    //         $mutuelle = mysql_fetch_assoc($res0) ;
    //         if($mutuelle!=null && ($pagnet['codeAdherant']!=null && $pagnet['codeAdherant']!=' ') && ($pagnet['adherant']!=null && $pagnet['adherant']!=' ') && ($codeBeneficiaire!='' && $numeroRecu!='' && $dateRecu!='')){
    //             $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ";
    //             $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
    //             $TotalP = mysql_fetch_array($resT) ;

    //             $totalp=$TotalP[0];
    //             $apayerMutuelle= ($totalp * $pagnet['taux']) / 100;
    //             $apayerPagnet= $totalp - $apayerMutuelle;

    //             //$msg_info="<p>$totalp</p> <p>$remise</p> <p>$versement</p> <p>$apayerPagnet</p>";

    //             $sqlL="SELECT * FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ";
    //             $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
    //             //$ligne = mysql_fetch_assoc($resL) ;

    //             /*****Debut Nombre de Panier ouvert****/
    //             $query = mysql_query("SELECT * FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ");
    //             $nbre_fois=mysql_num_rows($query);
    //             /*****Fin Nombre de Panier ouvert****/

    //             /*****Debut Difference entre Total Panier et Remise****/
    //             $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ";
    //             $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
    //             $TotalT = mysql_fetch_array($resT) ;

    //             $difference=$TotalT[0];
    //             /*****Fin Difference entre Total Panier et Remise****/

    //             if($pagnet['verrouiller']==0){
    //                 if($nbre_fois>0){
    //                     if($difference>=0){
    //                         mysql_query("SET AUTOCOMMIT=0;");
    //                         mysql_query("START TRANSACTION;");
    //                         $i=0;
    //                         $j=0;
    //                         while ($ligne=mysql_fetch_assoc($resL)){
    //                             if($ligne['classe']==0){
    //                                 $sqlS="SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation='".$ligne['idDesignation']."' ";
    //                                 $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    //                                 $designation = mysql_fetch_assoc($resS) ;
    //                                     if(mysql_num_rows($resS)){
    //                                         $restant=$ligne['quantite'];
    //                                         $sqlD="SELECT * FROM `".$nomtableStock."` WHERE idDesignation='".$ligne['idDesignation']."' ORDER BY idStock ASC ";
    //                                         $resD=mysql_query($sqlD) or die ("select designation impossible =>".mysql_error());
    //                                         $stock0 = mysql_fetch_assoc($resD);
    //                                         $quantiteinitial=$stock0['quantiteStockCourant'] - $restant;
    //                                         if($quantiteinitial >= 0){
    //                                             $sqlS0="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$quantiteinitial."' WHERE idStock='".$stock0['idStock']."' ";
    //                                             $resS0=mysql_query($sqlS0) or die ("update stock impossible =>".mysql_error());
    //                                             if($resS0){
    //                                                 $i=$i + 1;;
    //                                             }
    //                                         }
    //                                         else{
    //                                             $sqlE="SELECT * FROM `".$nomtableStock."` WHERE idDesignation='".$ligne['idDesignation']."' ORDER BY idStock ASC ";
    //                                             $resE=mysql_query($sqlE) or die ("select designation impossible =>".mysql_error());
    //                                             $k=0;
    //                                             $l=0;
    //                                             while ($stock = mysql_fetch_assoc($resE)) {
    //                                                 if($restant>= 0){
    //                                                     $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
    //                                                     if($quantiteStockCourant > 0){
    //                                                         $sqlS1="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$quantiteStockCourant."' WHERE idStock='".$stock['idStock']."' ";
    //                                                         $resS1=mysql_query($sqlS1) or die ("update stock impossible =>".mysql_error());
    //                                                         if($resS1){
    //                                                             $k=$k + 1;
    //                                                         }
    //                                                     }
    //                                                     else{
    //                                                         $sqlS2="UPDATE `".$nomtableStock."` SET quantiteStockCourant=0 WHERE idStock='".$stock['idStock']."' ";
    //                                                         $resS2=mysql_query($sqlS2) or die ("update stock impossible =>".mysql_error());
    //                                                         if($resS2){
    //                                                             $k=$k + 1;
    //                                                         }
    //                                                     }
    //                                                     $restant= $restant - $stock['quantiteStockCourant'] ;
    //                                                     $l=$l + 1;
    //                                                 }
    //                                             }
    //                                             if($k==$l){
    //                                                 $i=$i + 1;
    //                                             }
    //                                         }
    //                                     }
    //                             }
    //                             else{
    //                                 $i=$i + 1;  
    //                             }
    //                             $j=$j + 1;
    //                         }
    //                         $sql3="UPDATE `".$nomtableMutuellePagnet."` set codeBeneficiaire='".$codeBeneficiaire."',numeroRecu='".$numeroRecu."',dateRecu='".$dateRecu."',
    //                         verrouiller='1',totalp=".$totalp.",apayerPagnet=".$apayerPagnet.",apayerMutuelle=".$apayerMutuelle." where idMutuellePagnet=".$idMutuellePagnet;
    //                         $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());

    //                         if(($i==$j) && $res3){
    //                             mysql_query("COMMIT;");
    //                         }
    //                         else{
    //                             mysql_query("ROLLBACK;");
    //                             $msg_info="<p>PROBLEME CONNECTION INTERNET .</br></br> Veuillez terminer le bon numéro ".$idMutuellePagnet.".</p>";
    //                         }
    //                     }
    //                     else {
    //                         $msg_info="<p>IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idMutuellePagnet.", il faut verifier la remise.</p>";
    //                     }
                        
    //                 }
    //                 else {
    //                     $msg_info="<p>IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idMutuellePagnet.", il faut au moins ajouter un produit.</p>";
    //                 }
    //             }
    //         }
    //         else{
    //             $msg_info="<p>IMPOSSIBLE.</br></br> Il manque des Informations sur la mutuelle de Sante .</p>";
    //         }
    //     }
    //     else{
    //         $msg_info="<p>ERREUR.</br></br> Veuillez reessayer s'il vous plait.</p>";
    //     }
    // }
/**Fin Button Terminer Pagnet Imputation**/

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
    }
/**Fin Button Annuler Pagnet**/

/**Debut Button Annuler Imputation**/
    if (isset($_POST['btnAnnulerImputation']) ) {

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

    }
/**Fin Button Annuler Imputation**/

/**Debut Button Retourner Pagnet**/
    if (isset($_POST['btnRetournerPagnet'])) {

        $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));

        $sql="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
        $pagnet = mysql_fetch_assoc($res) ;

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

            if(($i==$j) && $resRP){
                mysql_query("COMMIT;");

                if ($_SESSION['compte']==1) {
                    $description="Retour panier";
                    $operation='retrait';
            
                    $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$pagnet['apayerPagnet']." where  idCompte=".$pagnet['idCompte'];
                    $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());
                    
                    if ($pagnet['remise']!='' && $pagnet['remise']!=0) {
                        # code...
                        $sql8="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$pagnet['remise']." where  idCompte=3";
                        $res8=@mysql_query($sql8) or die ("mise à jour 2 pour activer ou pas ".mysql_error());
                    }
                    //Annulation des mouvements relatifs à ce panier
                    $sql="UPDATE `".$nomtableComptemouvement."` set  annuler='1', description='".$description."' where  mouvementLink=".$idPagnet."";
                    $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
                }
            }
            else{
                mysql_query("ROLLBACK;");
                $msg_info="<p>PROBLEME CONNECTION INTERNET .</br></br> Veuillez retourner le panier numéro ".$idPagnet.".</p>";
            }
        }

    }
/**Fin Button Retourner Pagnet**/

/**Debut Button Retourner Imputation**/
    if (isset($_POST['btnRetournerImputation'])) {

        $idMutuellePagnet=htmlspecialchars(trim($_POST['idMutuellePagnet']));

        $sql="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";
        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
        $pagnet = mysql_fetch_assoc($res) ;

        if($pagnet['type']==0){
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

            if(($i==$j) && $resRP){
                mysql_query("COMMIT;");
            }
            else{
                mysql_query("ROLLBACK;");
                $msg_info="<p>PROBLEME CONNECTION INTERNET .</br></br> Veuillez retourner le bon numéro ".$idMutuellePagnet.".</p>";
            }
        }
    }
/**Fin Button Retourner Imputation**/

/**Debut Button Annuler Ligne d'un Pagnet**/
    if (isset($_POST['btnRetourAvant'])) {

        $numligne=$_POST['numligne'];
        $forme=$_POST['forme'];

        //on fait la suppression de cette ligne dans la table ligne
        $sql3="DELETE FROM `".$nomtableLigne."` where numligne=".$numligne;
        $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

    }
/**Fin Button Annuler Ligne d'un Pagnet**/
/**Debut Button Retourner Ligne d'un Pagnet**/
    if (isset($_POST['btnRetourApres'])) {

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
                    
            /********* Début retour ligne compte **********/
            $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$prixtotal." where idCompte=".$pagnet['idCompte'];
            //var_dump($sql7);
            $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());
        
            $sql8="UPDATE `".$nomtableComptemouvement."` set  montant= ".$apayerPagnet." where idCompte<>3 and mouvementLink=".$pagnet['idPagnet'];
            //var_dump($sql7);
            $res8=@mysql_query($sql8) or die ("mise à jour 2 pour activer ou pas ".mysql_error());
        
            /******** Fin retour ligne compte ***********/

            if(($i!=0 && $j!=0 && $k!=0) && ($i==$j)){
                mysql_query("COMMIT;");
            }
            else{
                mysql_query("ROLLBACK;");
                $msg_info="<p>PROBLEME CONNECTION INTERNET .</br></br> Veuillez retourner la ligne ".$numligne.".</p>";
            }
        }
        else {
            $msg_info="<p>IMPOSSIBLE.</br></br> Vous ne pouvez pas retourner cette ligne du pagnet numéro ".$idPagnet." , car c'est la(les) dernière(s) ligne(s). Cela risque de fausser les calculs surtout quand vous avez effectué des remises.</br></br>Il faut retourner tout le pagnet.</p>";
        }

    }
/**Fin Button Retourner Ligne d'un Pagnet**/


echo'<body><header>';

require('header.php');

echo'<div class="container" >';

    if(!@$_GET["jour"]){
        $date = new DateTime();
        $timezone = new DateTimeZone('Africa/Dakar');
        $date->setTimezone($timezone);
        $datehier = date('d-m-Y', strtotime('-1 days'));
        $datehier_Y = date('Y-m-d', strtotime('-1 days'));

        /**Debut Button Ajouter Pagnet**/
            if (isset($_POST['btnSavePagnetVente'])) {

                $paieMois=$annee-$mois;
                $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."' and YEAR(datePs)='".$annee_paie."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";
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
                        $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$datehier."','".$heureString."','".$_SESSION['iduser']."',0,0,0,0,0,0,0,0)";
                        $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());
                    }
                }
                else{
                    $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$datehier."','".$heureString."','".$_SESSION['iduser']."',0,0,0,0,0,0,0,0)";
                    $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());
                }
            }
        /**Fin Button Ajouter Pagnet**/

        /**Debut Button Ajouter Imputation**/
            if (isset($_POST['btnSavePagnetImputation'])) {

                $paieMois=$annee-$mois;
                $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."'  and YEAR(datePs)='".$annee_paie."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";
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

                        $query = mysql_query("SELECT * FROM `".$nomtableMutuellePagnet."` where iduser='".$_SESSION['iduser']."' && idClient=0 && datepagej ='".$datehier."' && verrouiller=0");
                        $nbre_fois=mysql_num_rows($query);
                
                        if($nbre_fois<1){
                            $sql6="insert into `".$nomtableMutuellePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,idMutuelle,taux,remise,verrouiller,idClient) values('".$datehier."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0)";
                            $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());
                        }
                    }
                }
                else{

                    $query = mysql_query("SELECT * FROM `".$nomtableMutuellePagnet."` where iduser='".$_SESSION['iduser']."' && idClient=0 && datepagej ='".$datehier."' && verrouiller=0");
                    $nbre_fois=mysql_num_rows($query);

                    if($nbre_fois<1){
                        $sql6="insert into `".$nomtableMutuellePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,idMutuelle,taux,remise,verrouiller,idClient) values('".$datehier."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0)";
                        $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());
                    }
                }

            }
        /**Fin Button Ajouter Imputation**/

        echo'
        <section> <div class="container">';

            $sqlV="SELECT DISTINCT p.idPagnet
                FROM `".$nomtablePagnet."` p
                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
                WHERE (l.classe=0 || l.classe=1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$datehier."' && p.type=0   ORDER BY p.idPagnet DESC";
            $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());
            $T_ventes = 0 ;
            $S_ventes = 0;
            while ($pagnet = mysql_fetch_assoc($resV)) {
                $sqlS="SELECT SUM(apayerPagnet)
                FROM `".$nomtablePagnet."`
                where idClient=0 &&  verrouiller=1 && datepagej ='".$datehier."' && idPagnet='".$pagnet['idPagnet']."' && type=0  ORDER BY idPagnet DESC";
                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                $S_ventes = mysql_fetch_array($resS);
                $T_ventes = $S_ventes[0] + $T_ventes;
            }

            $sqlM="SELECT DISTINCT m.idMutuellePagnet
                FROM `".$nomtableMutuellePagnet."` m
                INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet
                WHERE l.classe=0  && m.idClient=0  && m.verrouiller=1 && m.datepagej ='".$datehier."' && m.type=0   ORDER BY m.idMutuellePagnet DESC";
            $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());
            $T_mutuelles = 0 ;
            $S_mutuelles = 0;
            while ($mutuelle = mysql_fetch_assoc($resM)) {
                $sqlS="SELECT SUM(apayerPagnet)
                FROM `".$nomtableMutuellePagnet."`
                where idClient=0 &&  verrouiller=1 && datepagej ='".$datehier."' && idMutuellePagnet='".$mutuelle['idMutuellePagnet']."' && type=0  ORDER BY idMutuellePagnet DESC";
                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                $S_mutuelles = mysql_fetch_array($resS);
                $T_mutuelles = $S_mutuelles[0] + $T_mutuelles;
            }

            $sqlD="SELECT DISTINCT p.idPagnet
                FROM `".$nomtablePagnet."` p
                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
                WHERE l.classe=2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$datehier."' && p.type=0  ORDER BY p.idPagnet DESC";
            $resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());
            $T_depenses = 0 ;
            $S_depenses = 0;
            while ($pagnet = mysql_fetch_assoc($resD)) {
                $sqlS="SELECT SUM(apayerPagnet)
                FROM `".$nomtablePagnet."`
                where idClient=0 &&  verrouiller=1 && datepagej ='".$datehier."' && idPagnet='".$pagnet['idPagnet']."'  && type=0  ORDER BY idPagnet DESC";
                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                $S_depenses = mysql_fetch_array($resS);
                $T_depenses = $S_depenses[0] + $T_depenses;
            }

            $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient!=0  &&  dateVersement  ='".$datehier."'  ORDER BY idVersement DESC";
            $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());
            $T_versements = mysql_fetch_array($resP5) ;


            $T_caisse = $T_ventes  + $T_mutuelles - $T_depenses; 
            
        ?>
            <div class="jumbotron">
                <div class="col-sm-2 pull-right" >
                    <input type="date" class="form-control" id="jour_date"  onchange="date_jour_VenteP('jour_date');"  <?php echo '  max="'.date('Y-m-d', strtotime('-1 days')).'" ';?> value="<?php echo date('Y-m-d', strtotime($datehier));?>" name="dateInventaire" required  />
                </div>
                <h2>Journal de caisse du : <?php echo $datehier; ?></h2>
                    <div class="panel-group">
                        <div class="panel" style="background:#cecbcb;">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapse1">Total Caisse : <?php echo number_format((($T_caisse + $T_versements[0]) * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole']; ?>   </a>
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
            </div>
            <!--*******************************Debut Rechercher Produit****************************************-->
            <!--<form  class="pull-right" id="searchProdForm" method="post" name="searchProdForm" >
                <input type="hidden" id="dateProduitVendu"  />
                <input type="text" size="30" class="form-control" name="produit" placeholder="Rechercher ..."  id="produitVenduAvPh" autocomplete="off" />
                    <span id="reponsePV"></span>
            </form>-->
            <!--*******************************Fin Rechercher Produit****************************************-->

                <?php 
 
                    $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."' and YEAR(datePs)='".$annee_paie."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";
                    $res0=mysql_query($sql0);
                    if(mysql_num_rows($res0)){
                        if($jour > 0){
                            if ($jour > 4){
                                if ($_SESSION['mutuelle']==1){
                                    echo ' 
                                        <form name="formulairePagnet" method="post"  >
                                            <button disabled="true" type="submit" style="margin-right:50px"  class="btn btn-success noImpr pull-left" name="btnSavePagnetVente">
                                                            <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                                            </button>
                                        </form>
                                        <form name="formulairePagnet" method="post"   >
                                            <button disabled="true" type="submit" class="btn btn-info noImpr " name="btnSavePagnetImputation">
                                                            <i class="glyphicon glyphicon-plus"></i>Ajouter une Imputation
                                            </button>
                                        </form>
                                    ';
                                }
                                else{
                                    echo ' 
                                        <form name="formulairePagnet" method="post"  >
                                            <button disabled="true" type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente">
                                                            <i class="glyphicon glyphicon-plus"></i>Ajouter un Bon
                                            </button>
                                        </form>
                                    ';
                                }
                            }
                            else{
                                if ($_SESSION['mutuelle']==1){
                                    echo ' 
                                        <form name="formulairePagnet" method="post"  >
                                            <button type="submit" style="margin-right:50px"  class="btn btn-success noImpr pull-left" name="btnSavePagnetVente">
                                                            <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                                            </button>
                                        </form>
                                        <form name="formulairePagnet" method="post"   >
                                            <button type="submit" class="btn btn-info noImpr " name="btnSavePagnetImputation">
                                                            <i class="glyphicon glyphicon-plus"></i>Ajouter une Imputation
                                            </button>
                                        </form>
                                    ';
                                }
                                else{
                                    echo ' 
                                        <form name="formulairePagnet" method="post"  >
                                            <button type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente">
                                                            <i class="glyphicon glyphicon-plus"></i>Ajouter un Bon
                                            </button>
                                        </form>
                                    ';
                                }
                            }
                            echo "<h6><span id='blinker' style='color:red;'>VEUILLEZ EFFECTUER LE PAIEMENT DU SERVICE JCAISSE AVANT LE 5 !</span></h6>";
                            echo '<br>';
                        }
                        else{
                            if ($_SESSION['mutuelle']==1){
                                echo ' 
                                    <form name="formulairePagnet" method="post"  >
                                        <button type="submit" style="margin-right:50px"  class="btn btn-success noImpr pull-left" name="btnSavePagnetVente">
                                                        <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                                        </button>
                                    </form>
                                    <form name="formulairePagnet" method="post"   >
                                        <button type="submit" class="btn btn-info noImpr " name="btnSavePagnetImputation">
                                                        <i class="glyphicon glyphicon-plus"></i>Ajouter une Imputation
                                        </button>
                                    </form>
                                ';
                            }
                            else{
                                echo ' 
                                    <form name="formulairePagnet" method="post"  >
                                        <button type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente">
                                                        <i class="glyphicon glyphicon-plus"></i>Ajouter un Bon
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
                                    <button type="submit" style="margin-right:50px"  class="btn btn-success noImpr pull-left" name="btnSavePagnetVente">
                                                    <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                                    </button>
                                </form>
                                <form name="formulairePagnet" method="post"   >
                                    <button type="submit" class="btn btn-info noImpr " name="btnSavePagnetImputation">
                                                    <i class="glyphicon glyphicon-plus"></i>Ajouter une Imputation
                                    </button>
                                </form>
                            ';
                        }
                        else{
                            echo ' 
                                <form name="formulairePagnet" method="post"  >
                                    <button type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente">
                                                    <i class="glyphicon glyphicon-plus"></i>Ajouter un Bon
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
                    </script>
                <!-- Fin Javascript de l'Accordion pour Tout les Paniers -->

                <!-- Debut de l'Accordion pour Tout les Paniers -->
                    <div class="panel-group" id="accordion">

                        <?php
                            if($_SESSION['proprietaire']==1){
                                // On détermine sur quelle page on se trouve
                                if(isset($_GET['page']) && !empty($_GET['page'])){
                                    $currentPage = (int) strip_tags($_GET['page']);
                                }else{
                                    $currentPage = 1;
                                }
                                // On détermine le nombre d'articles par page
                                $parPage = 10;

                                if (isset($_POST['produit'])) {
                                    $produit=@htmlspecialchars($_POST["produit"]);
                                    /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                                        $sqlC="SELECT
                                            COUNT(*) AS total
                                            FROM
                                            (
                                            SELECT p.idPagnet FROM `".$nomtablePagnet."` p 
                                            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
                                            where p.idClient=0 AND p.datepagej ='".$datehier."' AND p.verrouiller=1 AND p.type=0 AND l.designation='".$produit."'
                                                UNION ALL
                                            SELECT m.idMutuellePagnet FROM `".$nomtableMutuellePagnet."` m 
                                            INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet
                                            where m.idClient=0 AND m.datepagej ='".$datehier."' AND m.verrouiller=1 AND m.type=0 AND l.designation='".$produit."'
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
                                            where p.idClient=0 AND p.datepagej ='".$datehier."' AND p.verrouiller=1 AND p.type=0 AND l.designation='".$produit."' 
                                                UNION ALL
                                            SELECT CONCAT(m.heurePagnet,'+',m.idMutuellePagnet,'+2') AS codePagnetMutuelle FROM `".$nomtableMutuellePagnet."` m 
                                            INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet
                                            where m.idClient=0 AND m.datepagej ='".$datehier."' AND m.verrouiller=1 AND m.type=0 AND l.designation='".$produit."' 
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
                                            SELECT p.idPagnet FROM `".$nomtablePagnet."` p where p.idClient=0 AND  p.datepagej ='".$datehier."' AND p.verrouiller=1 AND p.type=0
                                                UNION ALL
                                            SELECT m.idMutuellePagnet FROM `".$nomtableMutuellePagnet."` m where m.idClient=0 AND  m.datepagej ='".$datehier."' AND m.verrouiller=1 AND m.type=0
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
                                            SELECT  CONCAT(p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p where p.idClient=0 AND p.datepagej ='".$datehier."' AND p.verrouiller=1 AND p.type=0 
                                                UNION ALL
                                            SELECT CONCAT(m.heurePagnet,'+',m.idMutuellePagnet,'+2') AS codePagnetMutuelle FROM `".$nomtableMutuellePagnet."` m where m.idClient=0 AND m.datepagej ='".$datehier."' AND m.verrouiller=1 AND m.type=0
                                            ) AS a ORDER BY codePagnet DESC LIMIT ".$premier.",".$parPage." ";
                                        $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                    /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/
                                }

                                /**Debut requete pour Lister les Paniers en cours d'Aujourd'hui (1 au maximum) **/
                                    $sqlP0="SELECT codePagnet
                                    FROM
                                    (SELECT CONCAT(p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p where p.iduser='".$_SESSION['iduser']."' AND p.idClient=0 AND p.datepagej ='".$datehier."' AND p.verrouiller=0 
                                    UNION ALL
                                    SELECT CONCAT(m.heurePagnet,'+',m.idMutuellePagnet,'+2') AS codePagnetMutuelle FROM `".$nomtableMutuellePagnet."` m where m.iduser='".$_SESSION['iduser']."' AND m.idClient=0 AND m.datepagej ='".$datehier."' AND m.verrouiller=0 
                                    ) AS a ORDER BY codePagnet DESC  ";
                                    $resP0 = mysql_query($sqlP0) or die ("persoonel requête 20".mysql_error());
                                /**Fin requete pour Lister les Paniers en cours d'Aujourd'hui (1 au maximum) **/

                                /**Debut requete pour Rechercher le dernier Panier Ajouter  **/
                                    $reqA="SELECT heurePagnet
                                    FROM
                                    (SELECT p.heurePagnet FROM `".$nomtablePagnet."` p where p.idClient=0 AND p.datepagej ='".$datehier."' AND p.verrouiller=1 AND p.type=0
                                    UNION 
                                    SELECT m.heurePagnet FROM `".$nomtableMutuellePagnet."` m where m.idClient=0 AND m.datepagej ='".$datehier."' AND m.verrouiller=1 AND m.type=0
                                    ) AS a ORDER BY heurePagnet DESC LIMIT 1";
                                    $resA=mysql_query($reqA) or die ("persoonel requête 2".mysql_error());
                                /**Fin requete pour Rechercher le dernier Panier Ajouter  **/
                            }
                        ?>         
                        
                        <!-- Debut Boucle while concernant les Paniers en cours (2 aux maximum) -->  
                            <?php while ($pagnets = mysql_fetch_assoc($resP0)) {   ?>
                                <?php	
                                    $pagnetJour = explode("+", $pagnets['codePagnet']);
                                    if($pagnetJour[2]==1){
                                        $sqlT1="SELECT * FROM `".$nomtablePagnet."` where idPagnet='".$pagnetJour[1]."' ";
                                        $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());
                                        $pagnet = mysql_fetch_assoc($resT1);
                                        if($pagnet!=null){ ?>
                                            <div class="panel panel-primary">
                                                <div class="panel-heading" style="height: 38px;">
                                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
                                                        <div class="right-arrow pull-right">+</div>
                                                        <a href="#">
                                                            <span class="noImpr col-md-2 col-sm-2 col-xs-12"> Panier <?php echo ': En cours ...'; ?> </span>
                                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12"> Date: <?php echo $pagnet['datepagej']." ".$pagnet['heurePagnet']; ?> </span>
                                                            <!-- <span class="spanDate noImpr col-md-2 col-sm-2 col-xs-12">Heure: <?php //echo $pagnet['heurePagnet']; ?></span> -->
                                                            <?php	$sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                    $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
                                                                    $TotalT = mysql_fetch_array($resT) ;
                                                            ?>
                                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total panier: <span <?php echo  "id=somme_Total".$pagnet['idPagnet']; ?> ><?php echo $TotalT[0]; ?>  </span></span>
                                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total à payer: <span <?php echo  "id=somme_Apayer".$pagnet['idPagnet'] ; ?> ><?php echo $TotalT[0]; ?> </span></span>      
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?> class="panel-collapse collapse in">
                                                    <div class="panel-body" >
                                                        <div class="cache_btn_Terminer row">
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
                                                                    <input type="number" <?php echo "id=val_remise".$pagnet['idPagnet'] ; ?> name="remise" onkeyup="modif_Remise(this.value, <?php echo  $pagnet['idPagnet']; ?>)"
                                                                    class="remise form-control col-md-2 col-sm-2 col-xs-3" placeholder="Remise....">
                                                                    
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
                                                                    <?php } ?>

                                                                    <?php if($_SESSION['Pays']=='Canada'){  ?> 
                                                                        <input type="number" name="versement" id="versement" <?= (@$pagnet['idCompte']!=0) ? 'style="display:none;"' : '';?>
                                                                        class="versement form-control col-md-2 col-sm-2 col-xs-3" placeholder="Espèce...">
            
                                                                    <?php }
                                                                    else{   ?>
                                                                        <input type="number" name="versement" id="versement" <?= (@$pagnet['idCompte']!=0) ? 'style="display:none;"' : '';?>
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
                                                                        <button type="submit" style="" name="btnImprimerFacture" <?php echo  "id=btnImprimerFacture".$pagnet['idPagnet']."" ; ?> class="btn btn-success btn_Termine_Panier terminer" data-toggle="modal" onclick='remiseB("<?php echo  $pagnet['idPagnet'] ; ?>");' ><span class="glyphicon glyphicon-ok"></span></button>
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
                                                                                    <button type="submit" name="btnAnnulerPagnet" class="btn btn-success">Confirmer</button>
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
                                                                                                    <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>
                                                                                                onkeyup="modif_Quantite_Ph(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 70%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>
                                                                                                                        />
                                                                                                <?php 
                                                                                            }
                                                                                            else{
                                                                                                $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
                                                                                                $resD=mysql_query($sqlD) or die ("select Designation impossible =>".mysql_error());
                                                                                                $designation = mysql_fetch_assoc($resD);
                                                                                        
                                                                                                ?>  
                                                                                                    <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>
                                                                                                onkeyup="modif_Quantite_PhP(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" style="width: 70%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>
                                                                                                                        />
                                                                                                <?php 

                                                                                            }
                                                                                        }
                                                                                        if($ligne['classe']==1 || $ligne['classe']==2) { ?> 
                                                                                            <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>
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
                                                                                <input class="prixPublic form-control" style="width: 70%"  type="number" <?php echo  "id=prixPublic".$ligne['prixPublic'].""; ?>  <?php echo  "value=".$ligne['prixPublic'].""; ?>
                                                                                            onkeyup="modif_Prix_Ph(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" />
                                                                            </td>
                                                                            <td>
                                                                                <button type="submit" 	 class="btn btn-warning pull-right btn_Retourner_Produit" data-toggle="modal" <?php echo  "data-target=#msg_rtrnAvant_ligne".$ligne['numligne'] ; ?>>
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
                                                                                                    <button type="submit" name="btnRetourAvant" class="btn btn-success">Confirmer</button>
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
                                    }
                                    else if($pagnetJour[2]==2){
                                        $sqlT2="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet='".$pagnetJour[1]."'  ";
                                        $resT2 = mysql_query($sqlT2) or die ("persoonel requête 2".mysql_error());
                                        $mutuelle = mysql_fetch_assoc($resT2);
                                        if($mutuelle!=null){?>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#mutuelle".$mutuelle['idMutuellePagnet']."" ; ?>  class="panel-title expand">
                                                        <div class="right-arrow pull-right">+</div>
                                                        <a class="row" href="#">
                                                            <span class="noImpr col-md-2 col-sm-2 col-xs-12"> Panier <?php echo ': En cours ...'; ?> </span>
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
                                                    <div class="panel-body" >
                                                        <div class="cache_btn_Terminer">
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
                                                                <input type="text" id="codeAdherantMutuelle" class="form-control col-md-3 col-sm-3 col-xs-4 codeAdherantMutuelle" value="<?php echo $mutuelle['codeAdherant'] ; ?>"  placeholder="Code Adherant...."   />
                                                                
                                                                <input type="text" class="form-control col-md-3 col-sm-3 col-xs-4 codeBeneficiaire" id="codeBeneficiaire" name="codeBeneficiaire" placeholder="Code Beneficiaire...."  />
                                                                <input type="text" class="form-control col-md-3 col-sm-3 col-xs-4 numeroRecu" id="numeroRecu" name="numeroRecu" placeholder="Numero Reçu...."  />
                                                                <input type="date" class="form-control col-md-3 col-sm-3 col-xs-4 dateRecu" id="dateRecu" name="dateRecu" placeholder="Date Reçu.."  />
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

                                                                                echo '<option selected="true" disabled="disabled"  value="'.$exMtlle["idMutuelle"].'§'.$mutuelle['idMutuellePagnet'].'" >'.$exMtlle['nomMutuelle'].'</option>';

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
                                                                <?php if ($_SESSION['compte']==1) { ?>
                                                                <select class="form-control compte compteMutuelle col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" data-type="mutuelle" name="compte" style="margin-left:5px;"  <?php echo  "id=compte".$mutuelle['idMutuellePagnet']; ?>>
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
                                                                <?php } ?>
                                                                <?php
                                                                    if ($mutuelle['type']=='30') {
                                                                        
                                                                        $sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$mutuelle['idClient']."";
                                                                        $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
                                                                        $client = mysql_fetch_assoc($res3);
                                                                ?>
                                                                        <input type="text" class="client clientInput clientMutuelle form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" data-type="mutuelle" name="clientInput" id="clientInput<?= $mutuelle['idMutuellePagnet'];?>" autocomplete="off" value="<?= $client['prenom']." ".$client['nom']; ?>"/>

                                                                <?php
                                                                        # code...
                                                                    } else {
                                                                        # code...                                                            
                                                                ?> 
                                                                        <input type="text" class="client clientInput clientMutuelle form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" data-type="mutuelle" name="clientInput" id="clientInput<?= $mutuelle['idMutuellePagnet'];?>" autocomplete="off" placeholder="Client"/>

                                                                <?php   }  ?> 
                                                                

                                                                <input type="hidden" name="idMutuellePagnet"   <?php echo  "value=".$mutuelle['idMutuellePagnet']."" ; ?>>
                                                                <input type="hidden" name="totalp" <?php echo  "id=totalp".$mutuelle['idMutuellePagnet']."" ; ?> value="<?php echo $mutuelle['totalp']; ?>" >
                                                                <button type="button" name="btnTerminerImputation" <?php echo  "id=btnTerminerMutuelle".$mutuelle['idMutuellePagnet']."" ; ?> class="btn btn-success btn_Termine_Panier terminer terminerMutuelle" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" onclick='remiseB("<?php echo  $mutuelle['idMutuellePagnet'] ; ?>");' ><span class="glyphicon glyphicon-ok"></span></button>
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
                                                                                    <button type="submit" name="btnAnnulerImputation" class="btn btn-success">Confirmer</button>
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
                                                                                                <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>
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
                                                                                <input class="prixPublic form-control" style="width: 70%"  type="number" <?php echo  "id=prixPublic".$ligne['prixPublic'].""; ?>  <?php echo  "value=".$ligne['prixPublic'].""; ?>
                                                                                            onkeyup="modif_Prix_Mutuelle(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $mutuelle['idMutuellePagnet']; ?>)" >
                                                                            </td>
                                                                            <td>
                                                                                <button type="submit" 	 class="btn btn-warning pull-right btn_Retourner_Produit" data-toggle="modal" <?php echo  "data-target=#msg_rtrnAvant_ligne".$ligne['numligne'] ; ?>>
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
                                                                                                    <button type="submit" name="btnRetourAvant" class="btn btn-success">Confirmer</button>
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
                                    }
                                ?>
                            <?php   } ?>
                        <!-- Fin Boucle while concernant les Paniers en cours (2 aux maximum) -->

                        <!-- Debut Boucle while concernant les Paniers Vendus -->
                            <?php $n=$nbPaniers - (($currentPage * 10) - 10); while ($ventes = mysql_fetch_assoc($resP1)) {   ?>
                                <?php	$idmax=mysql_result($resA,0); ?>
                                <?php
                                    $vente = explode("+", $ventes['codePagnet']);
                                    if($vente[2]==1){
                                        $sqlT1="SELECT * FROM `".$nomtablePagnet."` where idPagnet='".$vente[1]."'  ";
                                        $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());
                                        $pagnet = mysql_fetch_assoc($resT1);
                                        if($pagnet!=null){
                                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";
                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                                            $ligne = mysql_fetch_assoc($res) ;
                                            if (($ligne['classe']==0 || $ligne['classe']==1) && $pagnet['type']==0){?>
                                                <?php if ($pagnet['totalp']==0) {?>
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
                                                                            $TotalP = mysql_fetch_array($resP);?>
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
                                                                                        <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                <!--*******************************Debut Editer Pagnet****************************************-->
                                                                <?php if ($_SESSION['proprietaire']==1 && $_SESSION['editionPanier']==1){ ?>
                                                                    <button type="button" class="btn btn-primary pull-left modeEditionBtnPh" id="edit-<?= $pagnet['idPagnet'] ; ?>">
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
                                                                                                            <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                            <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                            <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                            <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                            <input type="hidden" name="forme" <?php echo  "value=".$ligne['forme'].""; ?> >
                                                                                                            <input type="hidden" name="prixPublic" 	<?php echo  "value=".$ligne['prixPublic'].""; ?> >
                                                                                                            <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                            <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            <div class="row">
                                                                                                                <div class="col-xs-6">
                                                                                                                    <label for="reference">Ancienne quantite </label>
                                                                                                                    <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                </div>
                                                                                                                <div class="col-xs-6">
                                                                                                                    <label for="reference">Nouvelle quantite</label>
                                                                                                                    <input type="text" class="form-control" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="modal-footer">
                                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                            <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
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
                                                                            <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        <div>
                                                                            <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                        </div>
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
                                                    <div class="panel panel-success">
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
                                                                if($idmax == $pagnet['idPagnet']){
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
                                                                                        <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
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
                                                                
                                                                <?php if ($_SESSION['caissier']==1){ ?>
                                                                
                                                                    <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">
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
                                                                                                            <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                            <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                            <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                            <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                            <input type="hidden" name="forme" <?php echo  "value=".$ligne['forme'].""; ?> >
                                                                                                            <input type="hidden" name="prixPublic" 	<?php echo  "value=".$ligne['prixPublic'].""; ?> >
                                                                                                            <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                            <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                            <div class="row">
                                                                                                                <div class="col-xs-6">
                                                                                                                    <label for="reference">Ancienne quantite </label>
                                                                                                                    <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                </div>
                                                                                                                <div class="col-xs-6">
                                                                                                                    <label for="reference">Nouvelle quantite</label>
                                                                                                                    <input type="text" class="form-control" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="modal-footer">
                                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                            <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
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
                                                                            <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                                <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        <div>
                                                                            <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                        </div>
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
                                                            if($idmax == $pagnet['idPagnet']){
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
                                                                                    <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <!--*******************************Fin Retourner Pagnet****************************************-->
                                                            
                                                            <?php if ($_SESSION['caissier']==1){ ?>
                                                            
                                                                <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">
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
                                                                                                        <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                        <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                        <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                        <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                        <input type="hidden" name="forme" <?php echo  "value=".$ligne['forme'].""; ?> >
                                                                                                        <input type="hidden" name="prixPublic" 	<?php echo  "value=".$ligne['prixPublic'].""; ?> >
                                                                                                        <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                        <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                        <div class="row">
                                                                                                            <div class="col-xs-6">
                                                                                                                <label for="reference">Ancienne quantite </label>
                                                                                                                <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="col-xs-6">
                                                                                                                <label for="reference">Nouvelle quantite</label>
                                                                                                                <input type="text" class="form-control" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="modal-footer">
                                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                        <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
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
                                                            if($idmax == $pagnet['idPagnet']){
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
                                                                                    <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
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
                                                                
                                                                <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">
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
                                                                                                        <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
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
                                                            if($idmax == $pagnet['idPagnet']){
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
                                                                                    <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <!--*******************************Fin Retourner Pagnet****************************************-->
                                                                
                                                            <?php if ($_SESSION['caissier']==1){ ?>
                                                                
                                                                <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">
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
                                                                                                        <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
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
                                                            if($idmax == $pagnet['idPagnet']){
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
                                                                                    <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <!--*******************************Fin Retourner Pagnet****************************************-->
                                                                
                                                            <?php if ($_SESSION['caissier']==1){ ?>
                                                                
                                                                <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">
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
                                                                                                        <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
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
                                                            if($idmax == $mutuelle['idMutuellePagnet']){
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
                                                                                    <button type="submit" name="btnRetournerImputation" class="btn btn-success">Confirmer</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <!--*******************************Fin Retourner Pagnet****************************************-->
                                                                
                                                            <?php if ($_SESSION['caissier']==1){ ?>
                                                                <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_mutuelle".$mutuelle['idMutuellePagnet'] ; ?>>
                                                                    Facture
                                                                </button>
                                                            <?php } ?>

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
                                                                
                                                            <?php if ($_SESSION['caissier']==1){ ?>
                                                                <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$mutuelle['idMutuellePagnet'] ;?>').submit();">
                                                                Ticket de Caisse
                                                                </button>
                                                            <?php } ?>

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
                                                                        <?php if($mutuelle['idCompte']!=0 && $mutuelle['idCompte']>0): 
                                                                                $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$mutuelle['idCompte'];
                                                                                $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());
                                                                                $cpt = mysql_fetch_array($resPay2);
                                                                            ?>
                                                                            <?php  echo 'Compte :'. $cpt['nomCompte'].'<br/>';?>
                                                                        <?php endif; ?>
                                                                    </div>
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
                            <?php if($nbPaniers >= 11){ ?>
                                <ul class="pagination pull-right">
                                    <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                                    <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                                        <a href="actualiserVenteP.php?jour=<?= $datehier; ?>&&page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                                    </li>
                                    <?php for($page = 1; $page <= $pages; $page++): ?>
                                        <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                                        <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                                            <a href="actualiserVenteP.php?jour=<?= $datehier; ?>&&page=<?= $page ?>" class="page-link"><?= $page ?></a>
                                        </li>
                                    <?php endfor ?>
                                        <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                                        <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                                        <a href="actualiserVenteP.php?jour=<?= $datehier; ?>&&page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
                                    </li>
                                </ul>
                            <?php } ?>
                        <!-- Fin Boucle while concernant les Paniers Vendus  -->

                    </div>
                <!-- Fin de l'Accordion pour Tout les Paniers -->
                <?php echo'
            </div>
        </section>';
    }
    else {
        $datejour=@$_GET["jour"];

        /**Debut Button Ajouter Pagnet**/
            if (isset($_POST['btnSavePagnetVente'])) {
                $paieMois=$annee-$mois;
                $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."' and YEAR(datePs)='".$annee_paie."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";
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
                        $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$datejour."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0)";
                        $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());
                    }
                }
                else{
                    $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$datejour."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0)";
                    $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());
                }
            }
        /**Fin Button Ajouter Pagnet**/

        /**Debut Button Ajouter Imputation**/
            if (isset($_POST['btnSavePagnetImputation'])) {

                $paieMois=$annee-$mois;
                $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."'  and YEAR(datePs)='".$annee_paie."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";
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

                        $query = mysql_query("SELECT * FROM `".$nomtableMutuellePagnet."` where iduser='".$_SESSION['iduser']."' && idClient=0 && datepagej ='".$datejour."' && verrouiller=0");
                        $nbre_fois=mysql_num_rows($query);
                
                        if($nbre_fois<1){
                            $sql6="insert into `".$nomtableMutuellePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,idMutuelle,taux,remise,verrouiller,idClient) values('".$datejour."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0)";
                            $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());
                        }
                    }
                }
                else{

                    $query = mysql_query("SELECT * FROM `".$nomtableMutuellePagnet."` where iduser='".$_SESSION['iduser']."' && idClient=0 && datepagej ='".$datejour."' && verrouiller=0");
                    $nbre_fois=mysql_num_rows($query);

                    if($nbre_fois<1){
                        $sql6="insert into `".$nomtableMutuellePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,idMutuelle,taux,remise,verrouiller,idClient) values('".$datejour."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0)";
                        $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());
                    }
                }

            }
        /**Fin Button Ajouter Imputation**/

        echo'
        <section> <div class="container">';

            $sqlV="SELECT DISTINCT p.idPagnet
                FROM `".$nomtablePagnet."` p
                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
                WHERE (l.classe=0 || l.classe=1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$datejour."' && p.type=0   ORDER BY p.idPagnet DESC";
            $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());
            $T_ventes = 0 ;
            $S_ventes = 0;
            while ($pagnet = mysql_fetch_assoc($resV)) {
                $sqlS="SELECT SUM(apayerPagnet)
                FROM `".$nomtablePagnet."`
                where idClient=0 &&  verrouiller=1 && datepagej ='".$datejour."' && idPagnet='".$pagnet['idPagnet']."' && type=0  ORDER BY idPagnet DESC";
                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                $S_ventes = mysql_fetch_array($resS);
                $T_ventes = $S_ventes[0] + $T_ventes;
            }

            $sqlM="SELECT DISTINCT m.idMutuellePagnet
                FROM `".$nomtableMutuellePagnet."` m
                INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet
                WHERE l.classe=0  && m.idClient=0  && m.verrouiller=1 && m.datepagej ='".$datejour."' && m.type=0   ORDER BY m.idMutuellePagnet DESC";
            $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());
            $T_mutuelles = 0 ;
            $S_mutuelles = 0;
            while ($mutuelle = mysql_fetch_assoc($resM)) {
                $sqlS="SELECT SUM(apayerPagnet)
                FROM `".$nomtableMutuellePagnet."`
                where idClient=0 &&  verrouiller=1 && datepagej ='".$datejour."' && idMutuellePagnet='".$mutuelle['idMutuellePagnet']."' && type=0  ORDER BY idMutuellePagnet DESC";
                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                $S_mutuelles = mysql_fetch_array($resS);
                $T_mutuelles = $S_mutuelles[0] + $T_mutuelles;
            }

            $sqlD="SELECT DISTINCT p.idPagnet
                FROM `".$nomtablePagnet."` p
                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
                WHERE l.classe=2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$datejour."' && p.type=0  ORDER BY p.idPagnet DESC";
            $resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());
            $T_depenses = 0 ;
            $S_depenses = 0;
            while ($pagnet = mysql_fetch_assoc($resD)) {
                $sqlS="SELECT SUM(apayerPagnet)
                FROM `".$nomtablePagnet."`
                where idClient=0 &&  verrouiller=1 && datepagej ='".$datejour."' && idPagnet='".$pagnet['idPagnet']."'  && type=0  ORDER BY idPagnet DESC";
                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                $S_depenses = mysql_fetch_array($resS);
                $T_depenses = $S_depenses[0] + $T_depenses;
            }

            $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient!=0  &&  dateVersement  ='".$datejour."'  ORDER BY idVersement DESC";
            $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());
            $T_versements = mysql_fetch_array($resP5) ;

            $T_caisse =$T_ventes + $T_mutuelles - $T_depenses; 
            
        ?>
            <?php $dateDepartTimestamp = strtotime($datejour); ?>
            <div class="jumbotron">
                <div class="col-sm-2 pull-right" >
                    <input type="date" class="form-control" id="jour_date"  onchange="date_jour_VenteP('jour_date');"  <?php echo '  max="'.date('Y-m-d', strtotime('-1 days')).'" ';?> value="<?php echo date('Y-m-d', strtotime($datejour));?>"  required  />
                </div>
                <h2>Journal de caisse du : <?php echo $datejour; ?></h2>
                <div class="panel-group">
                    <div class="panel" style="background:#cecbcb;">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapse2">Total Caisse : <?php echo number_format((($T_caisse + $T_versements[0]) * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole']; ?>   </a>
                            </h4>
                        </div>
                        <div id="collapse2" class="panel-collapse collapse">
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
                                    $sqld="select * from `".$nomtableVersement."` where dateVersement  ='".$dateString2."'  ";
                                    $resd=mysql_query($sqld);
                                    if(mysql_num_rows($resd)){ 
                                        echo' <h6>Versements : '.number_format($T_versements[0], 2, ',', ' ').'   </h6>'; 
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--*******************************Debut Rechercher Produit****************************************-->
            <!--<form  class="pull-right" id="searchProdForm" method="post" name="searchProdForm" >
                <input type="hidden" id="dateProduitVendu" />
                <input type="text" size="30" class="form-control" name="produit" placeholder="Rechercher ..."  id="produitVenduAvPh" autocomplete="off" />
                    <span id="reponsePV"></span>
            </form>-->
            <!--*******************************Fin Rechercher Produit****************************************-->

            <?php  

                $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."' and YEAR(datePs)='".$annee_paie."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";
                $res0=mysql_query($sql0);
                if(mysql_num_rows($res0)){
                    if($jour > 0){
                        if ($jour > 4){
                            if ($_SESSION['mutuelle']==1){
                                echo ' 
                                    <form name="formulairePagnet" method="post"  >
                                        <button disabled="true" type="submit" style="margin-right:50px"  class="btn btn-success noImpr pull-left" name="btnSavePagnetVente">
                                                        <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                                        </button>
                                    </form>
                                    <form name="formulairePagnet" method="post"   >
                                        <button disabled="true" type="submit" class="btn btn-info noImpr " name="btnSavePagnetImputation">
                                                        <i class="glyphicon glyphicon-plus"></i>Ajouter une Imputation
                                        </button>
                                    </form>
                                ';
                            }
                            else{
                                echo ' 
                                    <form name="formulairePagnet" method="post"  >
                                        <button disabled="true" type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente">
                                                        <i class="glyphicon glyphicon-plus"></i>Ajouter un Bon
                                        </button>
                                    </form>
                                ';
                            }
                        }
                        else{
                            if ($_SESSION['mutuelle']==1){
                                echo ' 
                                    <form name="formulairePagnet" method="post"  >
                                        <button type="submit" style="margin-right:50px"  class="btn btn-success noImpr pull-left" name="btnSavePagnetVente">
                                                        <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                                        </button>
                                    </form>
                                    <form name="formulairePagnet" method="post"   >
                                        <button type="submit" class="btn btn-info noImpr " name="btnSavePagnetImputation">
                                                        <i class="glyphicon glyphicon-plus"></i>Ajouter une Imputation
                                        </button>
                                    </form>
                                ';
                            }
                            else{
                                echo ' 
                                    <form name="formulairePagnet" method="post"  >
                                        <button type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente">
                                                        <i class="glyphicon glyphicon-plus"></i>Ajouter un Bon
                                        </button>
                                    </form>
                                ';
                            }
                        }
                        echo "<h6><span id='blinker' style='color:red;'>VEUILLEZ EFFECTUER LE PAIEMENT DU SERVICE JCAISSE AVANT LE 5 !</span></h6>";
                        echo '<br>';
                    }
                    else{
                        if ($_SESSION['mutuelle']==1){
                            echo ' 
                                <form name="formulairePagnet" method="post"  >
                                    <button type="submit" style="margin-right:50px"  class="btn btn-success noImpr pull-left" name="btnSavePagnetVente">
                                                    <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                                    </button>
                                </form>
                                <form name="formulairePagnet" method="post"   >
                                    <button type="submit" class="btn btn-info noImpr " name="btnSavePagnetImputation">
                                                    <i class="glyphicon glyphicon-plus"></i>Ajouter une Imputation
                                    </button>
                                </form>
                            ';
                        }
                        else{
                            echo ' 
                                <form name="formulairePagnet" method="post"  >
                                    <button type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente">
                                                    <i class="glyphicon glyphicon-plus"></i>Ajouter un Bon
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
                                <button type="submit" style="margin-right:50px"  class="btn btn-success noImpr pull-left" name="btnSavePagnetVente">
                                                <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                                </button>
                            </form>
                            <form name="formulairePagnet" method="post"   >
                                <button type="submit" class="btn btn-info noImpr " name="btnSavePagnetImputation">
                                                <i class="glyphicon glyphicon-plus"></i>Ajouter une Imputation
                                </button>
                            </form>
                        ';
                    }
                    else{
                        echo ' 
                            <form name="formulairePagnet" method="post"  >
                                <button type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente">
                                                <i class="glyphicon glyphicon-plus"></i>Ajouter un Bon
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
                </script>
            <!-- Fin Javascript de l'Accordion pour Tout les Paniers -->

            <!-- Debut de l'Accordion pour Tout les Paniers -->
                <div class="panel-group" id="accordion">

                    <?php
                        if($_SESSION['proprietaire']==1){
                            // On détermine sur quelle page on se trouve
                            if(isset($_GET['page']) && !empty($_GET['page'])){
                                $currentPage = (int) strip_tags($_GET['page']);
                            }else{
                                $currentPage = 1;
                            }
                            // On détermine le nombre d'articles par page
                            $parPage = 10;

                            if (isset($_POST['produit'])) {
                                $produit=@htmlspecialchars($_POST["produit"]);
                                /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                                    $sqlC="SELECT
                                        COUNT(*) AS total
                                        FROM
                                        (
                                        SELECT p.idPagnet FROM `".$nomtablePagnet."` p 
                                        INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
                                        where p.idClient=0 AND p.datepagej ='".$datejour."' AND p.verrouiller=1 AND p.type=0 AND l.designation='".$produit."'
                                            UNION ALL
                                        SELECT m.idMutuellePagnet FROM `".$nomtableMutuellePagnet."` m 
                                        INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet
                                        where m.idClient=0 AND m.datepagej ='".$datejour."' AND m.verrouiller=1 AND m.type=0 AND l.designation='".$produit."'
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
                                        where p.idClient=0 AND p.datepagej ='".$datejour."' AND p.verrouiller=1 AND p.type=0 AND l.designation='".$produit."' 
                                            UNION ALL
                                        SELECT CONCAT(m.heurePagnet,'+',m.idMutuellePagnet,'+2') AS codePagnetMutuelle FROM `".$nomtableMutuellePagnet."` m 
                                        INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet
                                        where m.idClient=0 AND m.datepagej ='".$datejour."' AND m.verrouiller=1 AND m.type=0 AND l.designation='".$produit."' 
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
                                        SELECT p.idPagnet FROM `".$nomtablePagnet."` p where p.idClient=0 AND  p.datepagej ='".$datejour."' AND p.verrouiller=1 AND p.type=0
                                            UNION ALL
                                        SELECT m.idMutuellePagnet FROM `".$nomtableMutuellePagnet."` m where m.idClient=0 AND  m.datepagej ='".$datejour."' AND m.verrouiller=1 AND m.type=0
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
                                        SELECT  CONCAT(p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p where p.idClient=0 AND p.datepagej ='".$datejour."' AND p.verrouiller=1 AND p.type=0 
                                            UNION ALL
                                        SELECT CONCAT(m.heurePagnet,'+',m.idMutuellePagnet,'+2') AS codePagnetMutuelle FROM `".$nomtableMutuellePagnet."` m where m.idClient=0 AND m.datepagej ='".$datejour."' AND m.verrouiller=1 AND m.type=0
                                        ) AS a ORDER BY codePagnet DESC LIMIT ".$premier.",".$parPage." ";
                                    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                                /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/
                            }

                            /**Debut requete pour Lister les Paniers en cours d'Aujourd'hui (1 au maximum) **/
                                $sqlP0="SELECT codePagnet
                                FROM
                                (SELECT CONCAT(p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p where p.iduser='".$_SESSION['iduser']."' AND p.idClient=0 AND p.datepagej ='".$datejour."' AND p.verrouiller=0 
                                UNION ALL
                                SELECT CONCAT(m.heurePagnet,'+',m.idMutuellePagnet,'+2') AS codePagnetMutuelle FROM `".$nomtableMutuellePagnet."` m where m.iduser='".$_SESSION['iduser']."' AND m.idClient=0 AND m.datepagej ='".$datejour."' AND m.verrouiller=0 
                                ) AS a ORDER BY codePagnet DESC  ";
                                $resP0 = mysql_query($sqlP0) or die ("persoonel requête 20".mysql_error());
                            /**Fin requete pour Lister les Paniers en cours d'Aujourd'hui (1 au maximum) **/

                            /**Debut requete pour Rechercher le dernier Panier Ajouter  **/
                                $reqA="SELECT heurePagnet
                                FROM
                                (SELECT p.heurePagnet FROM `".$nomtablePagnet."` p where p.idClient=0 AND p.datepagej ='".$datejour."' AND p.verrouiller=1 AND p.type=0
                                UNION 
                                SELECT m.heurePagnet FROM `".$nomtableMutuellePagnet."` m where m.idClient=0 AND m.datepagej ='".$datejour."' AND m.verrouiller=1 AND m.type=0
                                ) AS a ORDER BY heurePagnet DESC LIMIT 1";
                                $resA=mysql_query($reqA) or die ("persoonel requête 2".mysql_error());
                            /**Fin requete pour Rechercher le dernier Panier Ajouter  **/
                        }
                    ?>         
                    
                    <!-- Debut Boucle while concernant les Paniers en cours (2 aux maximum) -->  
                        <?php while ($pagnets = mysql_fetch_assoc($resP0)) {   ?>
                            <?php	
                                $pagnetJour = explode("+", $pagnets['codePagnet']);
                                if($pagnetJour[2]==1){
                                    $sqlT1="SELECT * FROM `".$nomtablePagnet."` where idPagnet='".$pagnetJour[1]."' ";
                                    $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());
                                    $pagnet = mysql_fetch_assoc($resT1);
                                    if($pagnet!=null){ ?>
                                        <div class="panel panel-primary">
                                            <div class="panel-heading" style="height: 38px;">
                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
                                                    <div class="right-arrow pull-right">+</div>
                                                    <a href="#">
                                                        <span class="noImpr col-md-2 col-sm-2 col-xs-12"> Panier <?php echo ': En cours ...'; ?> </span>
                                                        <span class="noImpr col-md-3 col-sm-3 col-xs-12"> Date: <?php echo $pagnet['datepagej']." ".$pagnet['heurePagnet']; ?> </span>
                                                        <!-- <span class="spanDate noImpr col-md-2 col-sm-2 col-xs-12">Heure: <?php //echo $pagnet['heurePagnet']; ?></span> -->
                                                        <?php	$sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";
                                                                $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
                                                                $TotalT = mysql_fetch_array($resT) ;
                                                        ?>
                                                        <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total panier: <span <?php echo  "id=somme_Total".$pagnet['idPagnet']; ?> ><?php echo $TotalT[0]; ?>  </span></span>
                                                        <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total à payer: <span <?php echo  "id=somme_Apayer".$pagnet['idPagnet'] ; ?> ><?php echo $TotalT[0]; ?> </span></span>      
                                                    </a>
                                                </h4>
                                            </div>
                                            <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?> class="panel-collapse collapse in">
                                                <div class="panel-body" >
                                                    <div class="cache_btn_Terminer row">
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
                                                                <input type="number" <?php echo "id=val_remise".$pagnet['idPagnet'] ; ?> name="remise" onkeyup="modif_Remise(this.value, <?php echo  $pagnet['idPagnet']; ?>)"
                                                                class="remise form-control col-md-2 col-sm-2 col-xs-3" placeholder="Remise....">
                                                                
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
                                                                <?php } ?>

                                                                <?php if($_SESSION['Pays']=='Canada'){  ?> 
                                                                    <input type="number" name="versement" id="versement" <?= (@$pagnet['idCompte']!=0) ? 'style="display:none;"' : '';?>
                                                                    class="versement form-control col-md-2 col-sm-2 col-xs-3" placeholder="Espèce...">
        
                                                                <?php }
                                                                else{   ?>
                                                                    <input type="number" name="versement" id="versement" <?= (@$pagnet['idCompte']!=0) ? 'style="display:none;"' : '';?>
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
                                                                    <button type="submit" style="" name="btnImprimerFacture" <?php echo  "id=btnImprimerFacture".$pagnet['idPagnet']."" ; ?> class="btn btn-success btn_Termine_Panier terminer" data-toggle="modal" onclick='remiseB("<?php echo  $pagnet['idPagnet'] ; ?>");' ><span class="glyphicon glyphicon-ok"></span></button>
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
                                                                                <button type="submit" name="btnAnnulerPagnet" class="btn btn-success">Confirmer</button>
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
                                                                                                <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>
                                                                                            onkeyup="modif_Quantite_Ph(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 70%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>
                                                                                                                    />
                                                                                            <?php 
                                                                                        }
                                                                                        else{
                                                                                            $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
                                                                                            $resD=mysql_query($sqlD) or die ("select Designation impossible =>".mysql_error());
                                                                                            $designation = mysql_fetch_assoc($resD);
                                                                                    
                                                                                            ?>  
                                                                                                <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>
                                                                                            onkeyup="modif_Quantite_PhP(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" style="width: 70%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>
                                                                                                                    />
                                                                                            <?php 

                                                                                        }
                                                                                    }
                                                                                    if($ligne['classe']==1 || $ligne['classe']==2) { ?> 
                                                                                        <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>
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
                                                                            <input class="prixPublic form-control" style="width: 70%"  type="number" <?php echo  "id=prixPublic".$ligne['prixPublic'].""; ?>  <?php echo  "value=".$ligne['prixPublic'].""; ?>
                                                                                        onkeyup="modif_Prix_Ph(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" />
                                                                        </td>
                                                                        <td>
                                                                            <button type="submit" 	 class="btn btn-warning pull-right btn_Retourner_Produit" data-toggle="modal" <?php echo  "data-target=#msg_rtrnAvant_ligne".$ligne['numligne'] ; ?>>
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
                                                                                                <button type="submit" name="btnRetourAvant" class="btn btn-success">Confirmer</button>
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
                                }
                                else if($pagnetJour[2]==2){
                                    $sqlT2="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet='".$pagnetJour[1]."'  ";
                                    $resT2 = mysql_query($sqlT2) or die ("persoonel requête 2".mysql_error());
                                    $mutuelle = mysql_fetch_assoc($resT2);
                                    if($mutuelle!=null){?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#mutuelle".$mutuelle['idMutuellePagnet']."" ; ?>  class="panel-title expand">
                                                    <div class="right-arrow pull-right">+</div>
                                                    <a class="row" href="#">
                                                        <span class="noImpr col-md-2 col-sm-2 col-xs-12"> Panier <?php echo ': En cours ...'; ?> </span>
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
                                                    <div class="cache_btn_Terminer">
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
                                                            <input type="text" id="codeAdherantMutuelle" class="form-control col-md-3 col-sm-3 col-xs-4 codeAdherantMutuelle" value="<?php echo $mutuelle['codeAdherant'] ; ?>"  placeholder="Code Adherant...."   />
                                                            
                                                            <input type="text" class="form-control col-md-3 col-sm-3 col-xs-4 codeBeneficiaire" id="codeBeneficiaire" name="codeBeneficiaire" placeholder="Code Beneficiaire...."  />
                                                            <input type="text" class="form-control col-md-3 col-sm-3 col-xs-4 numeroRecu" id="numeroRecu" name="numeroRecu" placeholder="Numero Reçu...."  />
                                                            <input type="date" class="form-control col-md-3 col-sm-3 col-xs-4 dateRecu" id="dateRecu" name="dateRecu" placeholder="Date Reçu.."  />
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

                                                                                echo '<option selected="true" disabled="disabled"  value="'.$exMtlle["idMutuelle"].'§'.$mutuelle['idMutuellePagnet'].'" >'.$exMtlle['nomMutuelle'].'</option>';

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
                                                                <?php if ($_SESSION['compte']==1) { ?>
                                                                <select class="form-control compte compteMutuelle col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" data-type="mutuelle" name="compte" style="margin-left:5px;"  <?php echo  "id=compte".$mutuelle['idMutuellePagnet']; ?>>
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
                                                                <?php } ?>
                                                                <?php
                                                                    if ($mutuelle['type']=='30') {
                                                                        
                                                                        $sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$mutuelle['idClient']."";
                                                                        $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
                                                                        $client = mysql_fetch_assoc($res3);
                                                                ?>
                                                                        <input type="text" class="client clientInput clientMutuelle form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" data-type="mutuelle" name="clientInput" id="clientInput<?= $mutuelle['idMutuellePagnet'];?>" autocomplete="off" value="<?= $client['prenom']." ".$client['nom']; ?>"/>

                                                                <?php
                                                                        # code...
                                                                    } else {
                                                                        # code...                                                            
                                                                ?> 
                                                                        <input type="text" class="client clientInput clientMutuelle form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" data-type="mutuelle" name="clientInput" id="clientInput<?= $mutuelle['idMutuellePagnet'];?>" autocomplete="off" placeholder="Client"/>

                                                                <?php   }  ?> 
                                                                

                                                                <input type="hidden" name="idMutuellePagnet"   <?php echo  "value=".$mutuelle['idMutuellePagnet']."" ; ?>>
                                                                <input type="hidden" name="totalp" <?php echo  "id=totalp".$mutuelle['idMutuellePagnet']."" ; ?> value="<?php echo $mutuelle['totalp']; ?>" >
                                                                <button type="button" name="btnTerminerImputation" <?php echo  "id=btnTerminerMutuelle".$mutuelle['idMutuellePagnet']."" ; ?> class="btn btn-success btn_Termine_Panier terminer terminerMutuelle" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" onclick='remiseB("<?php echo  $mutuelle['idMutuellePagnet'] ; ?>");' ><span class="glyphicon glyphicon-ok"></span></button>
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
                                                                                <button type="submit" name="btnAnnulerImputation" class="btn btn-success">Confirmer</button>
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
                                                                                            <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>
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
                                                                            <input class="prixPublic form-control" style="width: 70%"  type="number" <?php echo  "id=prixPublic".$ligne['prixPublic'].""; ?>  <?php echo  "value=".$ligne['prixPublic'].""; ?>
                                                                                        onkeyup="modif_Prix_Mutuelle(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $mutuelle['idMutuellePagnet']; ?>)" >
                                                                        </td>
                                                                        <td>
                                                                            <button type="submit" 	 class="btn btn-warning pull-right btn_Retourner_Produit" data-toggle="modal" <?php echo  "data-target=#msg_rtrnAvant_ligne".$ligne['numligne'] ; ?>>
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
                                                                                                <button type="submit" name="btnRetourAvant" class="btn btn-success">Confirmer</button>
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
                                }
                            ?>
                        <?php   } ?>
                    <!-- Fin Boucle while concernant les Paniers en cours (2 aux maximum) -->

                    <!-- Debut Boucle while concernant les Paniers Vendus -->
                        <?php $n=$nbPaniers - (($currentPage * 10) - 10); while ($ventes = mysql_fetch_assoc($resP1)) {   ?>
                            <?php	$idmax=mysql_result($resA,0); ?>
                            <?php
                                $vente = explode("+", $ventes['codePagnet']);
                                if($vente[2]==1){
                                    $sqlT1="SELECT * FROM `".$nomtablePagnet."` where idPagnet='".$vente[1]."'  ";
                                    $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());
                                    $pagnet = mysql_fetch_assoc($resT1);
                                    if($pagnet!=null){
                                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";
                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                                        $ligne = mysql_fetch_assoc($res) ;
                                        if (($ligne['classe']==0 || $ligne['classe']==1) && $pagnet['type']==0){?>
                                            <?php if ($pagnet['totalp']==0) {?>
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
                                                                        $TotalP = mysql_fetch_array($resP);?>
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
                                                                                    <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
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
                                                                                                        <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                        <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                        <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                        <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                        <input type="hidden" name="forme" <?php echo  "value=".$ligne['forme'].""; ?> >
                                                                                                        <input type="hidden" name="prixPublic" 	<?php echo  "value=".$ligne['prixPublic'].""; ?> >
                                                                                                        <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                        <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                        <div class="row">
                                                                                                            <div class="col-xs-6">
                                                                                                                <label for="reference">Ancienne quantite </label>
                                                                                                                <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="col-xs-6">
                                                                                                                <label for="reference">Nouvelle quantite</label>
                                                                                                                <input type="text" class="form-control" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="modal-footer">
                                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                        <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
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
                                                                        <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                            <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div>
                                                                        <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                    </div>
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
                                                <div class="panel panel-success">
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
                                                            if($idmax == $pagnet['idPagnet']){
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
                                                                                    <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
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
                                                            
                                                                <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">
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
                                                                                                        <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                        <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                        <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                        <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                        <input type="hidden" name="forme" <?php echo  "value=".$ligne['forme'].""; ?> >
                                                                                                        <input type="hidden" name="prixPublic" 	<?php echo  "value=".$ligne['prixPublic'].""; ?> >
                                                                                                        <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                        <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                        <div class="row">
                                                                                                            <div class="col-xs-6">
                                                                                                                <label for="reference">Ancienne quantite </label>
                                                                                                                <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                            </div>
                                                                                                            <div class="col-xs-6">
                                                                                                                <label for="reference">Nouvelle quantite</label>
                                                                                                                <input type="text" class="form-control" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="modal-footer">
                                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                        <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
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
                                                                        <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>
                                                                            <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div>
                                                                        <?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
                                                                    </div>
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
                                                </div> <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>
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
                                                                                <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <!--*******************************Fin Retourner Pagnet****************************************-->
                                                        
                                                        <?php if ($_SESSION['caissier']==1){ ?>
                                                        
                                                            <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">
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
                                                                                                    <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                                                                    <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >
                                                                                                    <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >
                                                                                                    <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
                                                                                                    <input type="hidden" name="forme" <?php echo  "value=".$ligne['forme'].""; ?> >
                                                                                                    <input type="hidden" name="prixPublic" 	<?php echo  "value=".$ligne['prixPublic'].""; ?> >
                                                                                                    <input type="hidden" name="prixtotal" <?php echo  "value=".$ligne['prixtotal'].""; ?> >
                                                                                                    <input type="hidden" name="totalp"<?php echo  "value=".$pagnet['totalp'].""; ?> >
                                                                                                    <div class="row">
                                                                                                        <div class="col-xs-6">
                                                                                                            <label for="reference">Ancienne quantite </label>
                                                                                                            <input type="text" disabled="true" class="form-control" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                        </div>
                                                                                                        <div class="col-xs-6">
                                                                                                            <label for="reference">Nouvelle quantite</label>
                                                                                                            <input type="text" class="form-control" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                    <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
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
                                                        if($idmax == $pagnet['idPagnet']){
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
                                                                                <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
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
                                                            
                                                        <?php if ($_SESSION['caissier']==1){ ?>
                                                            
                                                            <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">
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
                                                                                                    <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
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
                                                        if($idmax == $pagnet['idPagnet']){
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
                                                                                <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <!--*******************************Fin Retourner Pagnet****************************************-->
                                                            
                                                        <?php if ($_SESSION['caissier']==1){ ?>
                                                            
                                                            <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">
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
                                                                                                    <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
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
                                                        if($idmax == $pagnet['idPagnet']){
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
                                                                                <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <!--*******************************Fin Retourner Pagnet****************************************-->
                                                            
                                                        <?php if ($_SESSION['caissier']==1){ ?>
                                                            
                                                            <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">
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
                                                                                                    <button type="submit" name="btnRetourApres" class="btn btn-success">Confirmer</button>
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
                                                        if($idmax == $mutuelle['idMutuellePagnet']){
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
                                                                                <button type="submit" name="btnRetournerImputation" class="btn btn-success">Confirmer</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <!--*******************************Fin Retourner Pagnet****************************************-->
                                                            
                                                        <?php if ($_SESSION['caissier']==1){ ?>
                                                            <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_mutuelle".$mutuelle['idMutuellePagnet'] ; ?>>
                                                                Facture
                                                            </button>
                                                        <?php } ?>

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
                                                            
                                                        <?php if ($_SESSION['caissier']==1){ ?>
                                                            <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$mutuelle['idMutuellePagnet'] ;?>').submit();">
                                                            Ticket de Caisse
                                                            </button>
                                                        <?php } ?>

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
                                                                    <?php if($mutuelle['idCompte']!=0 && $mutuelle['idCompte']>0): 
                                                                            $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$mutuelle['idCompte'];
                                                                            $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());
                                                                            $cpt = mysql_fetch_array($resPay2);
                                                                        ?>
                                                                        <?php  echo 'Compte :'. $cpt['nomCompte'].'<br/>';?>
                                                                    <?php endif; ?>
                                                                </div>
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
                        <?php if($nbPaniers >= 11){ ?>
                            <ul class="pagination pull-right">
                                <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                                <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                                    <a href="actualiserVenteP.php?jour=<?= $datejour; ?>&&page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                                </li>
                                <?php for($page = 1; $page <= $pages; $page++): ?>
                                    <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                                    <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                                        <a href="actualiserVenteP.php?jour=<?= $datejour; ?>&&page=<?= $page ?>" class="page-link"><?= $page ?></a>
                                    </li>
                                <?php endfor ?>
                                    <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                                    <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                                    <a href="actualiserVenteP.php?jour=<?= $datejour; ?>&&page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
                                </li>
                            </ul>
                        <?php } ?>
                    <!-- Fin Boucle while concernant les Paniers Vendus  -->

                </div>
            <!-- Fin de l'Accordion pour Tout les Paniers -->
                <?php   echo'
            </div>
        </section>';
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

    <!-- Debut PopUp d'Alerte sur le retard de Paiement -->
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
    
<?php
}
?>

<?php
    function calendrierMois($mois) {
        switch($mois) {
            case '01': $mois = 'JANVIER'; break;
            case '02': $mois = 'FEVRIER'; break;
            case '03': $mois = 'MARS'; break;
            case '04': $mois = 'AVRIL'; break;
            case '05': $mois = 'MAI'; break;
            case '06': $mois = 'JUIN'; break;
            case '07': $mois = 'JUILLET'; break;
            case '08': $mois = 'AOUT'; break;
            case '09': $mois = 'SEPTEMBRE'; break;
            case '10': $mois = 'OCTOBRE'; break;
            case '11': $mois = 'NOVEMBRE'; break;
            case '12': $mois = 'DECEMBRE'; break;
            default: $mois =''; break;
        }
        return $mois;
    }
?>
