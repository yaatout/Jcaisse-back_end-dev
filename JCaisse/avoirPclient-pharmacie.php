<?php
session_start();
if(!$_SESSION['iduser']){
	header('Location:../index.php');
	}
require('connection.php');
require('declarationVariables.php');

/**********************/
//$date = new DateTime('25-02-2011');
//$date = new DateTime();
$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateString2=$annee.'-'.$mois.'-'.$jour;

$nomtableClient=$_SESSION['nomB']."-client";
$nomtableVersement=$_SESSION['nomB']."-versement";
$nomtablePagnet=$_SESSION['nomB']."-pagnet";
$nomtableStock=$_SESSION['nomB']."-stock";
$nomtableLigne=$_SESSION['nomB']."-lignepj";
$nomtableDesignation=$_SESSION['nomB']."-designation";
$nomtableBon=$_SESSION['nomB']."-bon";


$idClient=htmlspecialchars(trim($_GET['c']));
$sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient."";
$res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
$client = mysql_fetch_array($res3);

/**Debut Button Ajouter Pagnet**/
    if (isset($_POST['btnSavePagnetVente'])) {

        $date = new DateTime();
        $annee =$date->format('Y');
        $mois =$date->format('m');
        $jour =$date->format('d');
        $heureString=$date->format('H:i:s');
        $dateString=$annee.'-'.$mois.'-'.$jour ;
        $dateString2=$jour.'-'.$mois.'-'.$annee;

        $sql1="select * from `".$nomtableJournal."` where annee=".$annee." and mois=".$mois;
        $res1=mysql_query($sql1);

        $query = mysql_query("SELECT * FROM `".$nomtablePagnet."` where idClient='".$idClient."'  && verrouiller=0");
        $nbre_fois=mysql_num_rows($query);

        if($client['activer'] == 1){
            if(mysql_num_rows($res1)){
                $sql2="select * from `".$nomtablePage."` where datepage='".$dateString."'";
                $res2=mysql_query($sql2);

                if(mysql_num_rows($res2)){
                    if($nbre_fois<1){
                        $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,'".$idClient."')";
                        $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());
                    }
                }
                else{
                    $sql2="insert into `".$nomtablePage."` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0)";
                    $res1=@mysql_query($sql1) or die ("insertion page journal impossible-1".mysql_error());
        
                    if($nbre_fois<1){
                        $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,'".$idClient."')";
                        $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error() );
                    }
        
                }
            }
            else{
                $sql1="insert into `".$nomtableJournal."` (mois,annee,totalVente,totalVersement,totalBon,totalFrais) values(".$mois.",".$annee.",0,0,0,0)";
                $res1=@mysql_query($sql1) or die ("insertion journal impossible-1".mysql_error());

                $sql2="insert into `".$nomtablePage."` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0)";
                $res2=@mysql_query($sql2) or die ("insertion page journal impossible-2".mysql_error());
        
                if($nbre_fois<1){
                        $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,'".$idClient."')";
                        $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error() );
                }
        
            }
        }
        else {
            $msg_info="<b>Ce Client est Desactiver vous ne pouvez pas faire de bon sur lui.</b></br></br>
                        VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STATUT DE CE CLIENT ....";
        }


    }
/**Fin Button Ajouter Pagnet**/

/**Debut Button Ajouter Ligne dans un pagnet**/
    if (isset($_POST['btnEnregistrerCodeBarre'])) {

        if (isset($_POST['codeBarre']) && isset($_POST['idPagnet'])) {
            $codeBarre=htmlspecialchars(trim($_POST['codeBarre']));
            $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));
            $codeBrute=explode('-', $codeBarre);
            if(!empty($codeBrute[1])){
                $classe=$codeBrute[1];
                /**Debut de la Vente des Produits sur le Stock */
                if($classe==1 || $classe==2){
                    $idStock=$codeBrute[0];
                    $sql0="SELECT * FROM `".$nomtableStock."` where idStock=".$idStock;
                    $res0=mysql_query($sql0) or die ("select stock impossible =>".mysql_error());
                    if(mysql_num_rows($res0)){
                        $stock = mysql_fetch_array($res0) or die ("select stock impossible =>".mysql_error());
                        $idDesignation=$stock["idDesignation"];

                        $sql6="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$idDesignation."' and classe=0 ";
                        $res6=mysql_query($sql6) or die ("select stock impossible =>".mysql_error());
                        if(mysql_num_rows($res6)){
                            $totalp=0;
                            $tailleTableau=count($codeBrute);
                            $quantiteStockCourant=$stock['quantiteStockCourant'];
                            $forme=$stock['forme'];
                                if ($quantiteStockCourant>0) {
                                // insertion dans l'historique
                                    $sql6="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$idDesignation;
                                    $res6=mysql_query($sql6) or die ("select stock impossible =>".mysql_error());
                                    $design = mysql_fetch_array($res6);
                                    if ($tailleTableau==2) {
                                        $numero=$codeBrute[1];
                                        if ($numero==1){
                                            /***Debut verifier si la ligne du produit existe deja ***/
                                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." and idStock=".$idStock;
                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                                            $ligne = mysql_fetch_assoc($res);
                                            /***Debut verifier si la ligne du produit existe deja ***/
                                            if($ligne != null){
                                                $quantite = $ligne['quantite'] + 1;
                                                $restant = $quantiteStockCourant - $quantite;
                                                if ($restant>=0){
                                                    $prixTotal=$quantite*$ligne['prixPublic'];
                                                    $sql7="UPDATE `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where idPagnet=".$idPagnet." and idStock=".$idStock;
                                                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                                }
                                                else{
                                                    $msg_info="<p>IMPOSSIBLE.</br>
                                                    </br> La quantité de ce stock est insuffisant pour la ligne.
                                                    </br> Il vous reste  <span>".$quantiteStockCourant."</span> Unités dans le Stock.
                                                    </p>";
                                                }
                                            }
                                            else {
                                                if($ligne['classe']==0 || $ligne['classe']==1){
                                                    $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',".$idStock.",'".$design['forme']."',".$stock['prixPublic'].",1,".$stock['prixPublic'].",".$idPagnet.",0)";
                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                                }
                                                else{
                                                    $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',".$idStock.",'".$design['forme']."',".$stock['prixPublic'].",1,".$stock['prixPublic'].",".$idPagnet.",0)";
                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                                }
                                            }
                                        }
                                        else if ($numero==2){
                                            /***Debut verifier si la ligne du produit existe deja ***/
                                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." and idStock=".$idStock;
                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                                            $ligne = mysql_fetch_assoc($res);
                                            /***Debut verifier si la ligne du produit existe deja ***/
                                            if($ligne != null){
                                                $quantite = $ligne['quantite'] + 1;
                                                $restant = $quantiteStockCourant - $quantite;
                                                if ($restant>=0){
                                                    $prixTotal=$quantite*$ligne['prixPublic'];
                                                    $sql7="UPDATE `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where idPagnet=".$idPagnet." and idStock=".$idStock;
                                                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                                }
                                                else{
                                                    $msg_info="<p>IMPOSSIBLE.</br>
                                                    </br> La quantité de ce stock est insuffisant pour la ligne.
                                                    </br> Il vous reste  <span>".$quantiteStockCourant."</span> Unités dans le Stock.
                                                    </p>";
                                                }
                                            }
                                            else {
                                                if($ligne['classe']==0 || $ligne['classe']==1){
                                                    $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',".$idStock.",'".$design['forme']."',".$stock['prixPublic'].",1,".$stock['prixPublic'].",".$idPagnet.",0)";
                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                                }
                                                else{
                                                    $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',".$idStock.",'".$design['forme']."',".$stock['prixPublic'].",1,".$stock['prixPublic'].",".$idPagnet.",0)";
                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                                }
                                            }
                                        }
                                    }
                            
                                }else{
                                    $msg_info="<b>LE CODE-BARRE SAISIE CORRESPOND A UN STOCK FINI (TERMINER).</b></br></br>
                                    VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ....";
                                //echo '<script type="text/javascript"> alert("ALERT : LE CODE-BARRE SAISIE CORRESPOND A UN STOCK FINI (TERMINER). VEUILLEZ CONTACTER LE GERANT POUR VERIFIER SON STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ...");</script>';
                                }
                        }else{
                            $msg_info="<b>LE CODE-BARRE SAISIE NE FIGURE PAS DANS LE STOCK.</b></br></br>
                                VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ...";
                        }

                    }else{
                        $msg_info="<b>LE CODE-BARRE SAISIE NE FIGURE PAS DANS LE STOCK.</b></br></br>
                                VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ...";
                    }
                }
                /**Fin de la Vente des Produits sur le Stock*/
                /**Debut de la Vente des Services, Depenses  */
                if($classe==3){
                    $idDesignation=$codeBrute[0];
                    $sql0="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$idDesignation."' and  classe=1 ";
                    $res0=mysql_query($sql0) or die ("select stock impossible =>".mysql_error());
                    if(mysql_num_rows($res0)){
                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                        $ligne = mysql_fetch_assoc($res) ;
                        if(mysql_num_rows($res)){
                            /***Debut verifier si la ligne du produit existe deja ***/
                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$idDesignation."' and classe=1  ";
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
                                if($ligne['classe']==1 || $ligne['classe']==0){
                                    $design = mysql_fetch_array($res0);
                                    if($design['forme']!='Transaction'){
                                        $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",1)";
                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                    }
                                    
                                }
                                
                            }
                            
                        }
                        else{
                            $design = mysql_fetch_array($res0);
                            if($design['forme']=='Transaction'){
                            /* $reqT="SELECT * from `aaa-transaction` where idTransaction='".$design['description']."'";
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
                    else{
                        $msg_info="Erreur";
                    }
                }
                /**Fin de la Vente des Services, Depenses  */
            }
            /**Debut de la Vente des Produits sur la Designation */
            else{
                $sql="SELECT * FROM `".$nomtableDesignation."` where (codeBarreDesignation='".$codeBarre."' or codeBarreuniteStock='".$codeBarre."' or designation='".$codeBarre."') ";
                $res=mysql_query($sql) or die ("select stock impossible =>".mysql_error());
                if(mysql_num_rows($res)){
                    $design = mysql_fetch_array($res);
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
                                    if($ligne['classe']==0 || $ligne['classe']==1){
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
                                    if($ligne['classe']==0 || $ligne['classe']==1){
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
                                        if($ligne['classe']==0 || $ligne['classe']==1){
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
                                    if($ligne['classe']==1 || $ligne['classe']==0){
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
                        if($design['classe']==6){
                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                            $ligne = mysql_fetch_assoc($res) ;
                            if(mysql_num_rows($res)){
                                /***Debut verifier si la ligne du produit existe deja ***/
                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=6  ";
                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                                $ligne1 = mysql_fetch_assoc($res);
                                /***Debut verifier si la ligne du produit existe deja ***/
                                if($ligne1 != null){
                                }
                                else {
                                    if($ligne['classe']==6){
                                        $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",6)";
                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                                    }
                                }
                            }
                            else{
                                $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",6)";
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
            $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
            $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
            $TotalP = mysql_fetch_array($resT) ;

            /*$sqlTD="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." && forme='Transaction' && quantite=0 ";
            $resTD = mysql_query($sqlTD) or die ("persoonel requête 2".mysql_error());
            $TotalTD = mysql_fetch_array($resTD) ;*/

            //$Total[ = $TotalP[0] - $TotalTD[0];

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

            //$msg_info="<p>$totalp</p> <p>$remise</p> <p>$versement</p> <p>$apayerPagnet</p>";

            $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
            $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
            //$ligne = mysql_fetch_assoc($resL) ;

            /*****Debut Nombre de Panier ouvert****/
            $query = mysql_query("SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ");
            $nbre_fois=mysql_num_rows($query);
            /*****Fin Nombre de Panier ouvert****/

            /*****Debut Difference entre Total Panier et Remise****/
            $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
            $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
            $TotalT = mysql_fetch_array($resT) ;

            /*$sqlTD="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." && unitevente='Transaction' && quantite=0 ";
            $resTD = mysql_query($sqlTD) or die ("persoonel requête 2".mysql_error());
            $TotalTD = mysql_fetch_array($resTD) ;*/

            $difference=$TotalT[0] - $remise;
            /*****Fin Difference entre Total Panier et Remise****/

            if($nbre_fois>0){
                if($difference>=0){
                    while ($ligne=mysql_fetch_array($resL)){
                        if($ligne['idStock']!=0 && $ligne['classe']==0){
                            $sqlS="SELECT * FROM `".$nomtableStock."` where idStock=".$ligne['idStock'];
                            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                                if(mysql_num_rows($resS)){

                                    $stock = mysql_fetch_array($resS) or die ("select stock impossible =>".mysql_error());
                                    $idDesignation=$stock['idDesignation'];
                        
                                    $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$idDesignation;
                                    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
                                    $designation = mysql_fetch_array($resD);

                                    if(mysql_num_rows($resD)){
                    
                                        $quantiteStockCourant=$stock['quantiteStockCourant'];
                                        $forme=$stock['forme'];

                                        $quantiteCourant=$quantiteStockCourant - $ligne['quantite'];
                                        if($quantiteCourant >= 0){
                                            $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteCourant." where idStock=".$ligne['idStock'];
                                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                        }

                                    }

                                }
                        }
                        else{
                            if($ligne['classe']==0){
                                $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
                                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                                $designation = mysql_fetch_array($resS) ;
                                    if(mysql_num_rows($resS)){
                                        $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock ASC ";
                                        $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
                                        $restant=$ligne['quantite'];
                                        while ($stock = mysql_fetch_array($resD)) {
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
                                    }
                            }
                        }
                    }
                    $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1', remise=".$remise.",totalp=".$totalp.",apayerPagnet=".$apayerPagnet.",versement=".$versement.",restourne=".$monaie." where idPagnet=".$idPagnet;
                    $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());

                    /************************************** UPDATE BON Et DU REMISE******************************************/
                    $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." ";
                    $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());
                    $Total = mysql_fetch_array($res18) ;
                    $sql20="UPDATE `".$nomtableBon."` set montant=".$Total[0].", date='".$dateString."' where idClient=".$idClient;
                    $res17=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());
                }
                else {
                    $msg_info="<p>IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idPagnet.", il faut verifier la remise.</p>";
                }
                
            }
            else {
                $msg_info="<p>IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idPagnet.", il faut au moins ajouter un produit.</p>";
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
        
        while ($ligne=mysql_fetch_array($resL)){

            //on fait la suppression de cette ligne dans la table ligne
            $sql3="DELETE FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
            $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

        }

        // suppression du pagnet aprés su^ppression de ses lignes
        $sqlR="DELETE FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet;
        $resR=@mysql_query($sqlR) or die ("mise à jour client  impossible".mysql_error());

        /************************************** UPDATE BON Et DU REMISE******************************************/
        $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." ";
        $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());
        $Total = mysql_fetch_array($res18) ;

        if($Total[0] != null){
            $sql20="UPDATE `".$nomtableBon."` set montant=".$Total[0].", date='".$dateString."' where idClient=".$idClient;
            $res17=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());
        }
        else{
        $sql20="UPDATE `".$nomtableBon."` set montant='0', date=".$dateString." where idClient=".$idClient;
        $res17=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());
        }
    }
/**Fin Button Annuler Pagnet**/

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

            if($ligne['idStock']!=0 && $ligne['classe']==0){
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
                        $forme=$stock['forme'];

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
            else{
                if($ligne['classe']==0){
                    $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                    $designation = mysql_fetch_array($resS) ;
                        if(mysql_num_rows($resS)){
                            $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";
                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
                            $retour=$ligne['quantite'];
                            while ($stock = mysql_fetch_array($resD)) {
                                if($retour >= 0){
                                    $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;
                                    if($stock['quantiteStockinitial'] >= $quantiteStockCourant){
                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                    }
                                    else{
                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$stock['quantiteStockinitial']." where idStock=".$stock['idStock'];
                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                    
                                    }
                                    $retour=($retour + $stock['quantiteStockCourant']) - $stock['quantiteStockinitial'] ;
                                    
                                }
                                
                            }
                        }
                }
            }

        }

        // suppression des lignes
        $sqlR="DELETE FROM `".$nomtableLigne."` where idPagnet=".$idPagnet;
        $resR=@mysql_query($sqlR) or die ("mise à jour client  impossible".mysql_error());

        // suppression du pagnet aprés su^ppression de ses lignes
        $sqlR="DELETE FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet;
        $resR=@mysql_query($sqlR) or die ("mise à jour client  impossible".mysql_error());

        /************************************** UPDATE BON Et DU REMISE******************************************/
        $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." ";
        $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());
        $Total = mysql_fetch_array($res18) ;

        if($Total[0] != null){
            $sql20="UPDATE `".$nomtableBon."` set montant=".$Total[0].", date='".$dateString."' where idClient=".$idClient;
            $res17=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());
        }
        else{
        $sql20="UPDATE `".$nomtableBon."` set montant='0', date=".$dateString." where idClient=".$idClient;
        $res17=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());

        }
    }
/**Fin Button Retourner Pagnet**/

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

                /*  if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {

                    $quantiteCourant=$quantiteStockCourant + ($designation['nbreArticleUniteStock'] * $ligne['quantite']);

                    if($quantiteStockCourant >= 0){

                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteCourant." where idStock=".$ligne['idStock'];
                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                        $sqlD="DELETE FROM `".$nomtableLigne."` where numligne=".$numligne;
                        $resD=@mysql_query($sqlD) or die ("mise à jour client  impossible".mysql_error());

                    }

                    }
                    else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){ */

                        $quantiteCourant=$quantiteStockCourant + $ligne['quantite'];

                    if($quantiteStockCourant >= 0){

                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteCourant." where idStock=".$ligne['idStock'];
                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                        $sqlD="DELETE FROM `".$nomtableLigne."` where numligne=".$numligne;
                        $resD=@mysql_query($sqlD) or die ("mise à jour client  impossible".mysql_error());

                    }

                // }

                }

            }

            $newPrix=$totalp-($quantite*$prixPublic);

            $sql19="SELECT remise,versement FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
            $res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());
            $pagnet = mysql_fetch_array($res19) ;

            $apayerPagnet=$newPrix-$pagnet['remise'];
            $restourne=$pagnet['versement']-$apayerPagnet;

            $sql16="update `".$nomtablePagnet."` set totalp=".$newPrix.",apayerPagnet=".$apayerPagnet.",
                                                restourne=".$restourne." where idPagnet='".$idPagnet."'";
            $res16=mysql_query($sql16) or die ("update Pagnet impossible =>".mysql_error());

            /************************************** UPDATE BON Et DU REMISE******************************************/
            $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." ";
            $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());
            $Total = mysql_fetch_array($res18) ;
            $sql20="UPDATE `".$nomtableBon."` set montant=".$Total[0].", date='".$dateString."' where idClient=".$idClient;
            $res17=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());
        }
        else {
            $msg_info="<p>IMPOSSIBLE.</br></br> Vous ne pouvez pas retourner cette ligne du pagnet numéro ".$idPagnet." , car c'est la(les) dernière(s) ligne(s). Cela risque de fausser les calculs surtout quand vous avez effectué des remises.</br></br>Il faut retourner tout le pagnet.</p>";
        }

    }
/**Fin Button Retourner Ligne d'un Pagnet**/

require('entetehtml.php');
?>
<!-- Debut Code HTML -->
<body onLoad="">
<header>
    <?php require('header.php'); ?>
    <!-- Debut Container HTML -->
    <div class="container" >
        <?php
        $sql2="SELECT * FROM `".$nomtablePagnet."` where idClient='".$idClient."'  ORDER BY idPagnet DESC";
        $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
        $total=0;
        while ($pagnet0 = mysql_fetch_array($res2))
        $total+=$pagnet0['apayerPagnet'];

        $T_solde=0;
        $sql12="SELECT solde FROM `".$nomtableClient."` where idClient=".$idClient." ";
        $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
        $versement = mysql_fetch_array($res12) ;
        $T_solde=$versement[0] - $total ;
                ?>

        <div class="jumbotron noImpr">
            <div class="col-sm-2 pull-right" >
                <form name="formulaireInfo" id="formulaireInfo" method="post" action="ajax/designationInfo.php">
                        <div class="form-group" >
                            <input type="text" class="form-control" placeholder="Recherche Prix..." id="designationInfo" name="designation" size="100"/>
                            <div id="reponseS"></div>
                            <div id="resultatS"></div>

                        </div>
                </form>
            </div>
            <h2>La liste des Avoir du client : <?php echo $client['prenom']." ".strtoupper($client['nom']); ?> </h2>
		    

        </div>
        <form name="formulairePagnet" method="post" >
            <button type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente">
                            <i class="glyphicon glyphicon-plus"></i>Ajouter un Avoir
            </button><br><br>
        </form>

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
            </script>
        <!-- Fin Javascript de l'Accordion pour Tout les Paniers -->

        <!-- Debut de l'Accordion pour Tout les Paniers -->
            <div class="panel-group" id="accordion">

                <?php
                /**Debut informations sur la date d'Aujourdhui **/
                    $date = new DateTime();
                    $annee =$date->format('Y');
                    $mois =$date->format('m');
                    $jour =$date->format('d');
                    $heureString=$date->format('H:i:s');
                    $dateString2=$jour.'-'.$mois.'-'.$annee;
                /**Fin informations sur la date d'Aujourdhui **/

                /**Debut requete pour Lister les Paniers en cours d'Aujourd'hui (1 au maximum) **/
                    $sqlP0="SELECT * FROM `".$nomtablePagnet."` where idClient='".$idClient."'  && verrouiller=0 ORDER BY idPagnet DESC";
                    $resP0 = mysql_query($sqlP0) or die ("persoonel requête 2".mysql_error());
                /**Fin requete pour Lister les Paniers en cours d'Aujourd'hui (1 au maximum) **/

                /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                    $sqlP1="SELECT * FROM `".$nomtablePagnet."` where idClient='".$idClient."'  && verrouiller=1  ORDER BY idPagnet DESC";
                    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                    $sqlC="SELECT COUNT(*) FROM `".$nomtablePagnet."` where idClient='".$idClient."'  && verrouiller=1  ORDER BY idPagnet DESC";
                    $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                    $nbre = mysql_fetch_array($resC) ;
                /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/

                /**Debut requete pour Lister tout les Paniers d'Aujourd'hui  **/
                    $sql2="SELECT * FROM `".$nomtablePagnet."` where idClient='".$idClient."'  ORDER BY idPagnet DESC";
                    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
                /**Fin requete pour Lister tout les Paniers d'Aujourd'hui  **/

                /**Debut requete pour Rechercher le dernier Panier Ajouter  **/
                    $reqA="SELECT idPagnet from `".$nomtablePagnet."` where idClient='".$idClient."' order by idPagnet desc limit 1";
                    $resA=mysql_query($reqA) or die ("persoonel requête 2".mysql_error());
                /**Fin requete pour Rechercher le dernier Panier Ajouter  **/ ?>
                
                <!-- Debut Boucle while concernant les Paniers en cours (1 aux maximum) -->
                    <?php while ($pagnet = mysql_fetch_array($resP0)) {   ?>

                                <?php	$idmax=mysql_result($resA,0); ?>

                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
                                        <div class="right-arrow pull-right">+</div>
                                        <a href="#"> Panier <?php echo ': En cours ...'; ?>

                                        <span class="spanDate noImpr"> </span>
                                        <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                                                <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                                <?php if ($pagnet['verrouiller']==0): ?>
                                                    <?php	$sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";
                                                            $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
                                                            $TotalT = mysql_fetch_array($resT) ;
                                                            ?>
                                                    <span class="spanTotal noImpr" >Total panier: <span <?php echo  "id=somme_Total".$pagnet['idPagnet']; ?> ><?php echo $TotalT[0]; ?>  </span></span>
                                                    <span class="spanTotal noImpr" >Total à payer: <span <?php echo  "id=somme_Apayer".$pagnet['idPagnet'] ; ?> ><?php echo $TotalT[0]; ?> </span></span>
                                                <?php endif; ?>

                                        </a>
                                        </h4>
                                    </div>
                                    <div
                                    <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>
                                    <?php if ($pagnet['verrouiller']==0)  { ?> class="panel-collapse collapse in" <?php }
                                        else {
                                            if($idmax == $pagnet['idPagnet']){
                                                ?> class="panel-collapse collapse in" <?php
                                                }
                                                else  {
                                                ?> class="panel-collapse collapse " <?php
                                                }

                                        } ?>  >
                                        <div class="panel-body" >
                                                    <?php
                                                    if ($pagnet['verrouiller']==0) {  ?>
                                                        <!--*******************************Debut Ajouter Ligne****************************************-->
                                                        <form  class="form-inline pull-left noImpr" id="ajouterProdForm" method="post" >
                                                        <input type="text" class="inputbasic form-control" name="codeBarre" id="codeBarreLignePhC" autofocus="" autocomplete="off" required />
                                                        <input type="hidden" name="idPagnet" id="idPagnet"
                                                            <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                            <span id="reponseV"></span>
                                                        <button type="submit" name="btnEnregistrerCodeBarre"
                                                        id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs " ><span class="glyphicon glyphicon-plus" ></span>
                                                        Ajouter</button><div id="reponseS"></div>
                                                        </form>
                                                        <!--*******************************Fin Ajouter Ligne****************************************-->
                                                        <?php
                                                    }
                                                    ?>
                                                <?php if ($pagnet['verrouiller']==0): ?>

                                                <!--*******************************Debut Annuler Pagnet****************************************-->
                                                <button type="submit" 	 class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_ann_pagnet".$pagnet['idPagnet'] ; ?>>
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
                                                                    <button type="submit" name="btnAnnulerPagnet" class="btn btn-success">Confirmer</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--*******************************Fin Annuler Pagnet****************************************-->

                                                <!--*******************************Debut Terminer Pagnet****************************************-->
                                                <form class="form-inline pull-right noImpr" style="margin-right:20px;" id="factForm" method="post">
                                                    <input type="number" <?php echo  "id=val_remise".$pagnet['idPagnet'] ; ?>  name="remise" onkeyup="modif_Remise(this.value, <?php echo  $pagnet['idPagnet']; ?>)"
                                                    class="form-control" placeholder="Remise...." >
                                                    <input type="number" name="versement"
                                                    class="form-control" placeholder="Espece..." style="margin-left:15px;">
                                                    <input type="hidden" name="idPagnet"   <?php echo  "value=".$pagnet['idPagnet']."" ; ?>>
                                                    <input type="hidden" name="totalp" <?php echo  "id=totalp".$pagnet['idPagnet']."" ; ?> value="<?php echo $pagnet['totalp']; ?>" >
                                                    <button disabled="true" type="submit" style="margin-left:15px;" name="btnImprimerFacture" <?php echo  "id=btnImprimerFacture".$pagnet['idPagnet']."" ; ?> class="btn btn-success pull-right" data-toggle="modal" onclick='remiseB("<?php echo  $pagnet['idPagnet'] ; ?>");' ><span class="glyphicon glyphicon-lock"></span>Terminer</button>
                                                </form>
                                                <!--*******************************Fin Terminer Pagnet****************************************-->

                                                <?php endif; ?>

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
                                                    while ($ligne = mysql_fetch_array($res8)) {?>
                                                    <tr>
                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                        </td>
                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                        <td> <?php if ($pagnet['verrouiller']==0):  ?>
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
                                                                                $designation = mysql_fetch_array($resD);
                                                                                ?>  
                                                                                    <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>
                                                                                onkeyup="modif_Quantite_Ph(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>
                                                                                                        >
                                                                                <?php 
                                                                            }
                                                                            else{
                                                                                $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
                                                                                $resD=mysql_query($sqlD) or die ("select Designation impossible =>".mysql_error());
                                                                                $designation = mysql_fetch_array($resD);
                                                                        
                                                                                ?>  
                                                                                    <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>
                                                                                onkeyup="modif_Quantite_PhP(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>
                                                                                                        >
                                                                                <?php 

                                                                            }
                                                                        }
                                                                        if($ligne['classe']==1 || $ligne['classe']==2) { ?> 
                                                                            <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?>
                                                                            onkeyup="modif_QuantiteSDP(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>
                                                                                                    >
                                                                        <?php  
                                                                        }
                                                                        if($ligne['classe']==5 || $ligne['classe']==7 ) {?>
                                                                            <?php echo 'Montant'; ?>
                                                                        <?php }
                                                                    } ?>                                     
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="forme "><?php echo $ligne['forme']; ?> </td>

                                                        <td> <?php if ($pagnet['verrouiller']==0): ?>
                                                            <input class="prixPublic form-control" style="width: 70%"  type="number" <?php echo  "id=prixPublic".$ligne['prixPublic'].""; ?>  <?php echo  "value=".$ligne['prixPublic'].""; ?>
                                                                        onkeyup="modif_Prix_Ph(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" >
                                                            <?php endif; ?>
                                                        </td>

                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                        </td>
                                                        <td><?php if ($pagnet['verrouiller']==0): ?>
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
                                                            <?php endif; ?>
                                                        </td>
                                                        </tr>
                                                        <?php  }  ?>

                                                    </tbody>
                                                    </table>
                                        </div>
                                    </div>
                                </div>

                    <?php   } ?>
                <!-- Fin Boucle while concernant les Paniers en cours (1 aux maximum) -->

                <!-- Debut Boucle while concernant les Paniers Vendus -->
                    <?php $n=$nbre[0]; while ($pagnet = mysql_fetch_array($resP1)) {   ?>

                        <?php	$idmax=mysql_result($resA,0); ?>

                        <?php
                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";
                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                            $ligne = mysql_fetch_assoc($res) ;
                            if($ligne['classe']==0 || $ligne['classe']==1){?>
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
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
                                    <div
                                    <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>
                                    <?php if ($pagnet['verrouiller']==0)  { ?> class="panel-collapse collapse in" <?php }
                                        else {
                                            if($idmax == $pagnet['idPagnet']){
                                                ?> class="panel-collapse collapse in" <?php
                                                }
                                                else  {
                                                ?> class="panel-collapse collapse " <?php
                                                }

                                        } ?>  >
                                        <div class="panel-body" >
                                                
                                                <?php if ($pagnet['verrouiller']==1):  ?>

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
                                                                    <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--*******************************Fin Retourner Pagnet****************************************-->

                                                <button disabled="true" class="btn btn-warning  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                Facture
                                                </button>

                                                <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"
                                                    method="post" action="barcodeFacture.php" >
                                                    <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                </form>

                                                <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">
                                                Ticket de Caisse
                                                </button>

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
                                                    <th>Forme</th>
                                                    <th>Prix Public</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";
                                                    $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                    while ($ligne = mysql_fetch_array($res8)) {?>
                                                    <tr>
                                                        <td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?><?php echo  "value=".$ligne['numligne']."" ; ?> >
                                                        </td>
                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>
                                                        <td><?php
                                                            if ($pagnet['verrouiller']==1): ?>
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
                                                                
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="forme ">
                                                            <?php echo $ligne['forme']; ?>
                                                        </td>

                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                            <?php echo  $ligne['prixPublic']; ?>  <span class="factureFois" ></span>
                                                            <?php endif; ?>
                                                        </td>

                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                        </td>
                                                        <td>
                                                            <?php if ($pagnet['verrouiller']==1): ?>
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
                                                            <?php endif; ?>

                                                        </td>
                                                        </tr>
                                                        <?php  }  ?>

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
                                <?php
                            }
                            else if ($ligne['classe']==6) {?>
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">
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
                                    <div
                                    <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?>
                                    <?php if ($pagnet['verrouiller']==0)  { ?> class="panel-collapse collapse in" <?php }
                                        else {
                                            if($idmax == $pagnet['idPagnet']){
                                                ?> class="panel-collapse collapse in" <?php
                                                }
                                                else  {
                                                ?> class="panel-collapse collapse " <?php
                                                }

                                        } ?>  >
                                        <div class="panel-body" >
                                                
                                                <?php if ($pagnet['verrouiller']==1):  ?>

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
                                                                    <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--*******************************Fin Retourner Pagnet****************************************-->

                                                <button disabled="true" class="btn btn-warning  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">
                                                Facture
                                                </button>

                                                <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"
                                                    method="post" action="barcodeFacture.php" >
                                                    <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                                                </form>

                                                <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">
                                                Ticket de Caisse
                                                </button>

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
                                                    <th>Forme</th>
                                                    <th>Prix Public</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";
                                                    $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                                    while ($ligne = mysql_fetch_array($res8)) {?>
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

                                                        <td> <?php if ($pagnet['verrouiller']==1):?>
                                                            <?php echo  $ligne['prixPublic']; ?>  <span class="factureFois" ></span>
                                                            <?php endif; ?>
                                                        </td>

                                                        <td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
                                                                                        <?php echo  "value=".$ligne['quantite']."" ; ?> >
                                                        </td>
                                                        <td>
                                                            <?php if ($pagnet['verrouiller']==1): ?>
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
                                                            <?php endif; ?>

                                                        </td>
                                                        </tr>
                                                        <?php  }  ?>

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
                                <?php
                            }
                        ?>	

                    <?php $n=$n-1;   } ?>
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

