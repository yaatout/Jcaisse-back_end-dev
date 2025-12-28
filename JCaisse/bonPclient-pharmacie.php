<?php



/**Debut Button Ajouter Pagnet**/

    if (isset($_POST['btnSavePagnetVente'])) {



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

                                $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,taux,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,'".$client['taux']."','".$idClient."')";

                                $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());

                            }

                        }

                        else{

                            $sql3="insert into `".$nomtablePage."` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('".$dateString."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0)";

                            $res3=@mysql_query($sql3) or die ("insertion page journal impossible-1".mysql_error());

                

                            if($nbre_fois<1){

                                $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,taux,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,'".$client['taux']."','".$idClient."')";

                                $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error() );

                            }

                

                        }

                    }

                    else{

                        $sql0="insert into `".$nomtableJournal."` (mois,annee,totalVente,totalVersement,totalBon,totalFrais) values(".$mois.",".$annee.",0,0,0,0)";

                        $res0=@mysql_query($sql0) or die ("insertion journal impossible-1".mysql_error());

        

                        $sql2="insert into `".$nomtablePage."` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('".$dateString."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0)";

                        $res2=@mysql_query($sql2) or die ("insertion page journal impossible-2".mysql_error());

                

                        if($nbre_fois<1){

                                $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,taux,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,'".$client['taux']."','".$idClient."')";

                                $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error() );

                        }

                

                    }

                }

                else {

                    $msg_info="<b>Ce Client est Desactiver vous ne pouvez pas faire de bon sur lui.</b></br></br>

                                VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STATUT DE CE CLIENT ....";

                }

            }

        }

        else{

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

                            $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,taux,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,'".$client['taux']."','".$idClient."')";

                            $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());

                        }

                    }

                    else{

                        $sql3="insert into `".$nomtablePage."` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('".$dateString."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0)";

                        $res3=@mysql_query($sql3) or die ("insertion page journal impossible-1".mysql_error());

            

                        if($nbre_fois<1){

                            $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,taux,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,'".$client['taux']."','".$idClient."')";

                            $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error() );

                        }

            

                    }

                }

                else{

                    $sql0="insert into `".$nomtableJournal."` (mois,annee,totalVente,totalVersement,totalBon,totalFrais) values(".$mois.",".$annee.",0,0,0,0)";

                    $res0=@mysql_query($sql0) or die ("insertion journal impossible-1".mysql_error());

    

                    $sql2="insert into `".$nomtablePage."` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('".$dateString."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0)";

                    $res2=@mysql_query($sql2) or die ("insertion page journal impossible-2".mysql_error());

            

                    if($nbre_fois<1){

                            $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,taux,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,'".$client['taux']."','".$idClient."')";

                            $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error() );

                    }

            

                }

            }

            else {

                $msg_info="<b>Ce Client est Desactiver vous ne pouvez pas faire de bon sur lui.</b></br></br>

                            VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STATUT DE CE CLIENT ....";

            }

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

        

                $query = mysql_query("SELECT * FROM `".$nomtableMutuellePagnet."` where idClient='".$idClient."'  && verrouiller=0");

                $nbre_fois=mysql_num_rows($query);

        

                if($client['activer'] == 1){

                    if($nbre_fois<1){

                        $sql6="insert into `".$nomtableMutuellePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,idMutuelle,taux,remise,verrouiller,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,'".$idClient."')";

                        $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());

                    }

                }

                else {

                    $msg_info="<b>Ce Client est Desactiver vous ne pouvez pas faire de bon sur lui.</b></br></br>

                                VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STATUT DE CE CLIENT ....";

                }

            }

        }

        else{

            $query = mysql_query("SELECT * FROM `".$nomtableMutuellePagnet."` where idClient='".$idClient."'  && verrouiller=0");

            $nbre_fois=mysql_num_rows($query);



            if($client['activer'] == 1){

                if($nbre_fois<1){

                    $sql6="insert into `".$nomtableMutuellePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,idMutuelle,taux,remise,verrouiller,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,'".$idClient."')";

                    $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());

                }

            }

            else {

                $msg_info="<b>Ce Client est Desactiver vous ne pouvez pas faire de bon sur lui.</b></br></br>

                            VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STATUT DE CE CLIENT ....";

            }

        }



    }

/**Fin Button Ajouter Imputation**/



/**Debut Button Ajouter Ligne dans un pagnet**/

    if (isset($_POST['btnEnregistrerCodeBarre'])) {



        if (isset($_POST['codeBarre']) && isset($_POST['idPagnet'])) {

            $codeBarre=htmlspecialchars(trim($_POST['codeBarre']));

            $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));

            $codeBrute=explode('-', $codeBarre);

            if(!empty($codeBrute[1])  && is_int($codeBrute[0])){

                $classe=$codeBrute[1];

                /**Debut de la Vente des Produits sur le Stock */

                if($classe==1 || $classe==2){

                    $idStock=$codeBrute[0];

                    $sql0="SELECT * FROM `".$nomtableStock."` where idStock=".$idStock;

                    $res0=mysql_query($sql0) or die ("select stock impossible =>".mysql_error());

                    if(mysql_num_rows($res0)){

                        $stock = mysql_fetch_assoc($res0) or die ("select stock impossible =>".mysql_error());

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

                                    $design = mysql_fetch_assoc($res6);

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

                                    $design = mysql_fetch_assoc($res0);

                                    if($design['forme']!='Transaction'){

                                        $sql7="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",1)";

                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                                    }

                                    

                                }

                                

                            }

                            

                        }

                        else{

                            $design = mysql_fetch_assoc($res0);

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

                        $sqlL0="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";

                        $resL0 = mysql_query($sqlL0) or die ("persoonel requête 2".mysql_error());
            
                        $ligne0 = mysql_fetch_assoc($resL0) ;
            
                        if($ligne0['classe']==0 || $ligne0['classe']==1){

                            $apayerPagnet=$totalp-$remise- (($totalp * $pagnet['taux'])/100);
                            $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1', remise=".$remise.",totalp=".$totalp.",apayerPagnet=".$apayerPagnet." where idPagnet=".$idPagnet;

                            $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
            
                        }
                        else{
                            $apayerPagnet=$totalp-$remise;
                            $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1', remise=".$remise.",totalp=".$totalp.",apayerPagnet=".$apayerPagnet.",taux=0 where idPagnet=".$idPagnet;

                            $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
                        }



                        /************************************** UPDATE BON Et DU REMISE******************************************/

                        $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." ";

                        $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());

                        $Total = mysql_fetch_array($res18) ;



                        $sql20="UPDATE `".$nomtableBon."` set montant=".$Total[0].", date='".$dateString."' where idClient=".$idClient;

                        $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());

                        

                        if ($_SESSION['compte']==1) {

                            $operation='depot';

                            $idCompte='2';

                            $description="Bon client";



                            $sql8="UPDATE `".$nomtablePagnet."`  set  idCompte=".$idCompte." where  idPagnet=".$idPagnet;

                            $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());

            

                            $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte + ".$apayerPagnet." where  idCompte=".$idCompte;

                            $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

            

                            $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser) values(".$apayerPagnet.",'".$operation."',".$idCompte.",'".$description."','".$dateHeures."','".$dateHeures."',".$idPagnet.",".$_SESSION['iduser'].")";

                            $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error());

                                                                    

                            if ($remise != 0) {

                                $operation='depot';

                                $idCompteRemise = '3';

                                $description="Remise vente";

                

                                $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte + ".$remise." where  idCompte=".$idCompteRemise;

                                $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

                

                                $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser) values(".$remise.",'".$operation."',".$idCompteRemise.",'".$description."','".$dateHeures."','".$dateHeures."',".$idPagnet.",".$_SESSION['iduser'].")";

                                $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error());

                

                            }



                            if(isset($_POST['avanceInput']) && $_POST['avanceInput'] >0){

                                // $avanceInput=$_POST['avanceInput'];

                                $montant=htmlspecialchars(trim($_POST['avanceInput']));

                                $compteAvance=htmlspecialchars(trim($_POST['compteAvance']));

                                // $idClient=$pagnet['idClient'];

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



                                $sql7="UPDATE `".$nomtableCompte."` set montantCompte= montantCompte - ".$montant." where  idCompte=".$idCompteBon;

                                $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());



                                $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,mouvementLink,idUser) values(".$montant.",'".$operation2."',".$idCompteBon.",'".$description2."','".$dateHeures."','".$dateHeures."',".$idVersement.",".$idPagnet.",".$_SESSION['iduser'].")";

                                $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

                                    

                            }

                        }



                        if(($i==$j) && $res3 && $res18 && $res20){

                            mysql_query("COMMIT;");



                        }

                        else{

                            mysql_query("ROLLBACK;");

                            $msg_info="<p>PROBLEME CONNECTION INTERNET .</br></br> Veuillez terminer le bon numéro ".$idPagnet.".</p>";

                        }

                    }

                    else {

                        $msg_info="<p>IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idPagnet.", il faut verifier la remise.</p>";

                    }

                    

                }

                else {

                    $msg_info="<p>IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idPagnet.", il faut au moins ajouter un produit.</p>";

                }

            }



        }else 

        {}

    }

/**Fin Button /**Debut Button Terminer Pagnet**/



/**Debut Button Terminer Pagnet Imputation**/

    // if (isset($_POST['btnTerminerImputation'])) {



    //     $idMutuellePagnet=@$_POST['idMutuellePagnet'];

    //     $nomClient=$client['prenom']." ".$client['nom'];

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

    //         if($mutuelle!=null && ($pagnet['codeAdherant']!=null && $pagnet['codeAdherant']!=' ') && ($codeBeneficiaire!='' && $numeroRecu!='' && $dateRecu!='')){

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

    //                         $sql3="UPDATE `".$nomtableMutuellePagnet."` set adherant='".$nomClient."',codeBeneficiaire='".$codeBeneficiaire."',numeroRecu='".$numeroRecu."',dateRecu='".$dateRecu."',

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

            $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";

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

            

            $sqlRP="UPDATE `".$nomtablePagnet."` set type=2 where idPagnet=".$idPagnet;

            $resRP=@mysql_query($sqlRP) or die ("mise à jour client  impossible".mysql_error());



            /************************************** UPDATE BON Et DU REMISE******************************************/

            $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." AND type=0 ";

            $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());

            $Total = mysql_fetch_array($res18) ;

            if($Total[0] != null){

                $sql20="UPDATE `".$nomtableBon."` set montant=".$Total[0].", date='".$dateString."' where idClient=".$idClient;

                $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());

            }

            else{

            $sql20="UPDATE `".$nomtableBon."` set montant='0', date=".$dateString." where idClient=".$idClient;

            $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());

            }



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

                $sql="DELETE FROM `".$nomtableComptemouvement."` where  mouvementLink=".$idPagnet."";

                $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());

                

                if ($pagnet['avance'] != 0) {

                    

                    $sql0="DELETE FROM `".$nomtableVersement."` where  idPagnet=".$idPagnet."";

                    $res0=@mysql_query($sql0) or die ("mise à jour idClient ".mysql_error());

                }

                

                $sql3="UPDATE `".$nomtableClient."` set solde=solde-".$pagnet['apayerPagnet']." where idClient=".$idClient;

                $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());

            }



            if(($i==$j) && $resRP && $res18 && $res20){

                mysql_query("COMMIT;");



            }

            else{

                mysql_query("ROLLBACK;");

                $msg_info="<p>PROBLEME CONNECTION INTERNET .</br></br> Veuillez retourner le bon numéro ".$idPagnet.".</p>";

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



            if ($client['avoir']==1) {

                # code...

                $sql8="UPDATE `".$nomtableClient."` set montantAvoir=montantAvoir+".$pagnet['apayerMutuelle']." where idClient=".$client['idClient'];

                $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());

            } else {

                # code...

                if ($_SESSION['compte']==1) {

                    $description="Retour panier imputation";

                    $operation='retrait';

            

                    $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$pagnet['apayerPagnet']." where  idCompte=".$pagnet['idCompte'];

                    $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

                    

                    if ($pagnet['remise']!='' && $pagnet['remise']!=0) {

                        # code...

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

                    

                    $sql3="UPDATE `".$nomtableClient."` set solde=solde-".$pagnet['apayerPagnet']." where idClient=".$idClient;

                    $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());

                }

            }

            



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



        $difference=$TotalT[0] + ($quantite * $ligne['prixPublic']) - $pagnet["remise"];



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

            else{

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



            $sqlTP="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` WHERE idPagnet=".$idPagnet." ";

            $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());

            $TotalTP = mysql_fetch_array($resTP) ;

            $k=0;

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

            /************************************** UPDATE BON Et DU REMISE******************************************/

            $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." ";

            $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());

            $Total = mysql_fetch_array($res18) ;

            $sql20="UPDATE `".$nomtableBon."` set montant=".$Total[0].", date='".$dateString."' where idClient=".$idClient;

            $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());

                    

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

            

            if(($i!=0 && $j!=0 && $k!=0) && ($i==$j) && $res18 && $res20){

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



/**Debut Button Terminer avoir**/

    if (isset($_POST['btnEnregistrerAvoir'])) {

        $montant=htmlspecialchars(trim($_POST['montant']));

        $sql2="insert into `".$nomtableVersement."` (idClient,paiement,montantAvoir,dateVersement,heureVersement,iduser) values(".$idClient.",'Depot avoir mensuel',".$montant.",'".$dateString2."','".$heureString."',".$_SESSION['iduser'].")";

        //echo $sql2;

        $res2=mysql_query($sql2) or die ("insertion Versement impossible =>".mysql_error());

        // $solde=$montant+$client['solde'];



        $sql3="UPDATE `".$nomtableClient."` set montantAvoir=montantAvoir+".$montant." where idClient=".$idClient;

        $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());

        // var_dump($sql3):;

    }

/**Fin Button Terminer avoir**/



/**Debut Button Terminer Versement**/

    if (isset($_POST['btnEnregistrerVersement'])) {

        $montant=htmlspecialchars(trim($_POST['montant']));

       // $paiement=htmlspecialchars($_POST['typeVersement']);

        if($_SESSION['proprietaire']==1){

            $dateActualiser=htmlspecialchars(trim($_POST['dateActualiser']));

            if($dateActualiser!=null){

                $dateVersement=$dateActualiser;

            }

            else{

                $dateVersement=$dateString2;

            }

        }

        else{

            $dateVersement=$dateString2;

        }


        $sql2="insert into `".$nomtableVersement."` (idClient,montant,dateVersement,heureVersement,iduser) values(".$idClient.",".$montant.",'".$dateString2."','".$heureString."',".$_SESSION['iduser'].")";

        //echo $sql2;

        $res2=mysql_query($sql2) or die ("insertion Versement impossible =>".mysql_error());

        

        $solde=$client['solde']-$montant;



        $sql3="UPDATE `".$nomtableClient."` set solde=".$solde." where idClient=".$idClient;

        $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());

        if ($_SESSION['compte']==1) {

            if(isset($_POST['compte'])) {

                $operation='depot';

                $idCompte=$_POST['compte'];

                $description="Versement bon";

                

                $sql100="select * from `".$nomtableVersement."` ORDER BY idVersement DESC LIMIT 1";

                $res100=mysql_query($sql100);

                $idVFetch = mysql_fetch_array($res100) ;

                $idVersement = $idVFetch['idVersement'];



                $sql8="UPDATE `".$nomtableVersement."` set idCompte=".$idCompte." where  idVersement=".$idVersement;

                $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());



                $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte + ".$montant." where  idCompte=".$idCompte;

                $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());



                $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,idUser) values(".$montant.",'".$operation."',".$idCompte.",'".$description."','".$dateHeures."','".$dateHeures."',".$idVersement.",".$_SESSION['iduser'].")";

                $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

                

                $operation2='retrait';

                $idCompteBon='2';

                $description2="Bon encaissé";



                $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$montant." where  idCompte=".$idCompteBon;

                $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());



                $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idUser) values(".$montant.",'".$operation2."',".$idCompteBon.",'".$description2."','".$dateHeures."','".$dateHeures."',".$_SESSION['iduser'].")";

                $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

            }

        }

    }

/**Fin Button Terminer Versement**/



/**Debut Button Annuler Versement**/

    if (isset($_POST['btnAnnulerVersement'])) {

        $idVersement=htmlspecialchars(trim($_POST['idVersement']));

        $montant=htmlspecialchars(trim($_POST['montant']));

        $montantAvoir=htmlspecialchars(trim($_POST['montantAvoirName']));

        $idVersementAvoir=htmlspecialchars(trim($_POST['idVersementAvoir']));



        if ($idVersementAvoir==1) {

            # code...

            $sql4="UPDATE `".$nomtableClient."` set montantAvoir=montantAvoir-".$montantAvoir." where idClient=".$idClient;

            $res4=mysql_query($sql4) or die ("update solde client impossible =>".mysql_error());

        } else {

                # code...



            //Update du solde du client

            $solde=$client['solde'] + $montant ;

            $sql4="UPDATE `".$nomtableClient."` set solde=".$solde." where idClient=".$idClient;

            $res4=mysql_query($sql4) or die ("update solde client impossible =>".mysql_error());



            if ($_SESSION['compte']==1) {            



                $sqlGetComptePay2="SELECT * FROM `".$nomtableVersement."` where idVersement = ".$idVersement;

                $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());

                $v = mysql_fetch_array($resPay2);

                $idCompte = $v['idCompte'];



                $sql70="UPDATE `".$nomtableCompte."` set  montantCompte=montantCompte-".$montant." where  idCompte='".$idCompte."'";

                $res70=@mysql_query($sql70) or die ("test 1 pour activer ou pas ".mysql_error());

                

                $idCompteBon='2';

                $description2="Versement annuler";



                $sql7="UPDATE `".$nomtableCompte."` set  montantCompte=montantCompte+".$montant." where  idCompte=".$idCompteBon;

                //var_dump($sql7);

                $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

                

                //on fait la suppression des mouvements

                $sql8="DELETE FROM `".$nomtableComptemouvement."` where idVersement=".$idVersement;

                $res8=@mysql_query($sql8) or die ("mise à jour client  impossible".mysql_error());

            }

        }

        



        //on fait la suppression de cette Versement

        $sql3="DELETE FROM `".$nomtableVersement."` where idVersement=".$idVersement;

        $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

    }

/**Fin Button Annuler Versement**/



/**Debut Button Modifier Versement**/

    if (isset($_POST['btnModifierVersement'])) {

        $idVersement=htmlspecialchars(trim($_POST['idVersement']));

        $description=htmlspecialchars($_POST['description']);

        $dateVersement=htmlspecialchars(trim($_POST['dateVersement']));

        if($_SESSION['depotAvoir']==1 && $client['avoir']==1){

            $montantAvoir=htmlspecialchars(trim($_POST['montantAvoir']));

            $sql2="UPDATE `".$nomtableVersement."` set montantAvoir='".$montantAvoir."',paiement='".$description."',dateVersement='".$dateVersement."' where idVersement='".$idVersement."' ";

            $res2=mysql_query($sql2) or die ("insertion Versement impossible =>".mysql_error()); 

        }

        else{

            $montant=htmlspecialchars(trim($_POST['montant']));

            $sql2="UPDATE `".$nomtableVersement."` set montant='".$montant."',paiement='".$description."',dateVersement='".$dateVersement."' where idVersement='".$idVersement."' ";

            $res2=mysql_query($sql2) or die ("insertion Versement impossible =>".mysql_error()); 

        }

    }

/**Fin Button Modifier Versement**/

/**Debut Button upload Image Versement**/
    if (isset($_POST['btnUploadImgVersement'])) {
        $idVersement=htmlspecialchars(trim($_POST['idVersement']));
        if(isset($_FILES['file'])){
            $tmpName = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];
            $size = $_FILES['file']['size'];
            $error = $_FILES['file']['error'];

            $tabExtension = explode('.', $name);
            $extension = strtolower(end($tabExtension));

            $extensions = ['jpg', 'png', 'jpeg', 'gif','pdf'];
            $maxSize = 400000;

            if(in_array($extension, $extensions) && $error == 0){

                $uniqueName = uniqid('', true);
                //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
                $file = $uniqueName.".".$extension;
                //$file = 5f586bf96dcd38.73540086.jpg

                move_uploaded_file($tmpName, './PiecesJointes/'.$file);

                $sql2="UPDATE `".$nomtableVersement."` set image='".$file."' where idVersement='".$idVersement."' ";
                $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
            }
            else{
                echo "Une erreur est survenue";
            }
        }
    }
/**Fin Button upload Image Versement**/

/**Debut Button upload Image Mutuelle**/
    if (isset($_POST['btnUploadImgMutuelle'])) {
        $idMutuellePagnet=htmlspecialchars(trim($_POST['idMutuelle']));
        if(isset($_FILES['file'])){
            $tmpName = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];
            $size = $_FILES['file']['size'];
            $error = $_FILES['file']['error'];

            $tabExtension = explode('.', $name);
            $extension = strtolower(end($tabExtension));

            $extensions = ['jpg', 'png', 'jpeg', 'gif','pdf'];
            $maxSize = 400000;

            if(in_array($extension, $extensions) && $error == 0){

                $uniqueName = uniqid('', true);
                //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
                $file = $uniqueName.".".$extension;
                //$file = 5f586bf96dcd38.73540086.jpg

                move_uploaded_file($tmpName, './PiecesJointes/'.$file);

                $sql2="UPDATE `".$nomtableMutuellePagnet."` set image='".$file."' where idMutuellePagnet='".$idMutuellePagnet."' ";
                $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
            }
            else{
                echo "Une erreur est survenue";
            }
        }
    }
/**Fin Button upload Image Mutuelle**/



require('entetehtml.php');

?>

<!-- Debut Code HTML -->

<body onLoad="">

<header>

    <?php require('header.php'); ?>

    <!-- Debut Container HTML -->

    <div class="container" >

        <?php

            // On détermine sur quelle page on se trouve

            if(isset($_GET['page']) && !empty($_GET['page'])){

                $currentPage = (int) strip_tags($_GET['page']);

            }else{

                $currentPage = 1;

            }

            // On détermine le nombre d'articles par page

            $parPage = 10;

            $T_solde=0;



            if(isset($_GET['debut']) && isset($_GET['fin'])){

                $dateDebut=@htmlspecialchars($_GET["debut"]);

                $dateFin=@htmlspecialchars($_GET["fin"]);

                /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/

                    $sqlC="SELECT

                        COUNT(*) AS total

                    FROM

                    (

                    SELECT p.idClient FROM `".$nomtablePagnet."` p where p.idClient='".$idClient."' AND p.verrouiller=1 AND (p.type=0 || p.type=10) AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')  or (p.datepagej BETWEEN '".$dateDebut."' AND '".$dateFin."')

                        UNION ALL

                    SELECT m.idClient FROM `".$nomtableMutuellePagnet."` m where m.idClient='".$idClient."' AND m.verrouiller=1 AND m.type=0 AND (CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')  or (m.datepagej BETWEEN '".$dateDebut."' AND '".$dateFin."')

                        UNION ALL

                    SELECT v.idClient FROM `".$nomtableVersement."` v where v.idClient='".$idClient."' AND (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."')

                    ) AS a ";

                    $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());

                    $nbre = mysql_fetch_array($resC) ;

                    $nbPaniers = (int) $nbre['total'];

                /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/



                // On calcule le nombre de pages total

                $pages = ceil($nbPaniers / $parPage);

                                

                $premier = ($currentPage * $parPage) - $parPage;



                /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/

                    $sqlP1="SELECT codePagnet

                    FROM

                    (

                    SELECT CONCAT(SUBSTR(p.datepagej,7, 4),'',SUBSTR(p.datepagej,4, 2),'',SUBSTR(p.datepagej,1, 2),'',p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p where p.idClient='".$idClient."' AND p.verrouiller=1 AND (p.type=0 || p.type=30)  AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')  or (p.datepagej BETWEEN '".$dateDebut."' AND '".$dateFin."')

                        UNION ALL

                    SELECT CONCAT(SUBSTR(m.datepagej,7, 4),'',SUBSTR(m.datepagej,4, 2),'',SUBSTR(m.datepagej,1, 2),'',m.heurePagnet,'+',m.idMutuellePagnet,'+2') AS codePagnetMutuelle FROM `".$nomtableMutuellePagnet."` m where m.idClient='".$idClient."' AND m.verrouiller=1 AND (m.type=0 || m.type=30) AND (CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')  or (m.datepagej BETWEEN '".$dateDebut."' AND '".$dateFin."')

                        UNION ALL

                    SELECT CONCAT(SUBSTR(v.dateVersement,7, 4),'',SUBSTR(v.dateVersement,4, 2),'',SUBSTR(v.dateVersement,1, 2),'',v.heureVersement,'+',v.idVersement,'+3') AS codeVersement FROM `".$nomtableVersement."` v where v.idClient='".$idClient."' AND  (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."')

                    ) AS a ORDER BY codePagnet DESC LIMIT ".$premier.",".$parPage." ";

                    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 20".mysql_error());

                /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/



                $sql11="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." AND verrouiller=1 AND type=0 AND (CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (datepagej BETWEEN '".$dateDebut."' AND '".$dateFin."') ";

                $res11 = mysql_query($sql11) or die ("persoonel requête 2".mysql_error());

                $TotalB = mysql_fetch_array($res11) ;



                $sql12="SELECT SUM(apayerPagnet) FROM `".$nomtableMutuellePagnet."` where idClient=".$idClient." AND verrouiller=1 AND type=0 AND (CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (datepagej BETWEEN '".$dateDebut."' AND '".$dateFin."') ";

                $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());

                $TotalM = mysql_fetch_array($res12) ;

    

                $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient=".$idClient." AND  (CONCAT(CONCAT(SUBSTR(dateVersement,7, 10),'',SUBSTR(dateVersement,3, 4)),'',SUBSTR(dateVersement,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."') ";

                $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());

                $TotalV = mysql_fetch_array($res13) ;

            }else{

                $dateDebut=$_SESSION['dateCB'];

                $dateFin=$dateString;

                /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/

                    $sqlC="SELECT

                        COUNT(*) AS total

                        FROM

                        (

                        SELECT p.idClient FROM `".$nomtablePagnet."` p where p.idClient='".$idClient."' AND p.verrouiller=1 AND (p.type=0 || p.type=10)

                            UNION ALL

                        SELECT m.idClient FROM `".$nomtableMutuellePagnet."` m where m.idClient='".$idClient."' AND m.verrouiller=1 AND m.type=0

                            UNION ALL

                        SELECT v.idClient FROM `".$nomtableVersement."` v where v.idClient='".$idClient."'

                        ) AS a ";

                    $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());

                    $nbre = mysql_fetch_array($resC) ;

                    $nbPaniers = (int) $nbre['total'];

                /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/



                // On calcule le nombre de pages total

                $pages = ceil($nbPaniers / $parPage);

                                

                $premier = ($currentPage * $parPage) - $parPage;



                /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/

                    $sqlP1="SELECT codePagnet

                    FROM

                    (

                    SELECT CONCAT(SUBSTR(p.datepagej,7, 4),'',SUBSTR(p.datepagej,4, 2),'',SUBSTR(p.datepagej,1, 2),'',p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p where p.idClient='".$idClient."' AND p.verrouiller=1 AND (p.type=0 || p.type=30) 

                        UNION ALL

                    SELECT CONCAT(SUBSTR(m.datepagej,7, 4),'',SUBSTR(m.datepagej,4, 2),'',SUBSTR(m.datepagej,1, 2),'',m.heurePagnet,'+',m.idMutuellePagnet,'+2') AS codePagnetMutuelle FROM `".$nomtableMutuellePagnet."` m where m.idClient='".$idClient."' AND m.verrouiller=1 AND (m.type=0 || m.type=30)

                        UNION ALL

                    SELECT CONCAT(SUBSTR(v.dateVersement,7, 4),'',SUBSTR(v.dateVersement,4, 2),'',SUBSTR(v.dateVersement,1, 2),'',v.heureVersement,'+',v.idVersement,'+3') AS codeVersement FROM `".$nomtableVersement."` v where v.idClient='".$idClient."'

                    ) AS a ORDER BY codePagnet DESC LIMIT ".$premier.",".$parPage." ";

                    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 20".mysql_error());

                /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/    


                $sql11="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." AND verrouiller=1 AND (type=0 || type=30) ";

                $res11 = mysql_query($sql11) or die ("persoonel requête 2".mysql_error());

                $TotalB = mysql_fetch_array($res11) ;



                $sql12="SELECT SUM(apayerPagnet) FROM `".$nomtableMutuellePagnet."` where idClient=".$idClient." AND verrouiller=1 AND (type=0 || type=30)  ";

                $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());

                $TotalM = mysql_fetch_array($res12) ;

    

                $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient=".$idClient."  ";

                $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());

                $TotalV = mysql_fetch_array($res13) ;

            }



            $T_solde=$TotalB[0] + $TotalM[0] - $TotalV[0];

            //Somme des Pagnets Bons du Client
            $stmtBons = $bdd->prepare("UPDATE  `".$nomtableClient."` SET solde=:solde WHERE idClient=:idClient ");
            $stmtBons->bindValue(':idClient', $client['idClient'], PDO::PARAM_INT);
            $stmtBons->bindValue(':solde', $T_solde);
            $stmtBons->execute();


            $sqlA1="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient." ";

            $resA1=mysql_query($sqlA1) or die ("insertion client impossible =>".mysql_error() );

            $clientA = mysql_fetch_array($resA1) ;

            $T_avoir = $clientA['montantAvoir'];

        ?>



        <script type="text/javascript">

            $(function() {

                //var start = '21/10/2020';

                    client=<?php echo json_encode($idClient); ?>;

                    dateDebut = <?php echo json_encode($dateDebut); ?>;

                    dateFin = <?php echo json_encode($dateFin); ?>;

                    tabDebut=dateDebut.split('-');

                    tabFin=dateFin.split('-');

                var start = tabDebut[2]+'/'+tabDebut[1]+'/'+tabDebut[0];

                var end = tabFin[2]+'/'+tabFin[1]+'/'+tabFin[0];

                function cb(start, end) {

                    var debut=start.format('YYYY-MM-DD');

                    var fin=end.format('YYYY-MM-DD');

                    window.location.href = "bonPclient.php?c="+client+"&&debut="+debut+"&&fin="+fin;

                    //alert(start);

                }

                $('#reportrange').daterangepicker({

                    startDate: start,

                    endDate: end,

                    locale: {

                        format: 'DD/MM/YYYY',

                        separator: ' - ',

                        applyLabel: 'Valider',

                        cancelLabel: 'Annuler',

                        fromLabel: 'De',

                        toLabel: 'à',

                        customRangeLabel: 'Choisir',

                        daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve','Sa'],

                        monthNames: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'November', 'Decembre'],

                        firstDay: 1

                    },

                    ranges: {

                        'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],

                        'Une Semaine': [moment().subtract(6, 'days'), moment()],

                        'Un Mois': [moment().subtract(30, 'days'), moment()],

                        'Ce mois ci': [moment().startOf('month'), moment()],

                        'Dernier Mois': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]

                    },

                }, cb);

                cb(start, end);



            });
            $(document).ready(function(){
                $('#btnRafraichirTaux').click(function(){
                    document.getElementById('btnValiderTaux').style.display = "block";
                    document.getElementById('btnRafraichirTaux').style.display = "none";
                    document.getElementById('inputTauxNouveau').style.display = "block";
                    document.getElementById('inputTauxAncien').style.display = "none";
                });
                $('#btnValiderTaux').click(function(){
                    var taux=$("#inputTauxNouveau").val();
                    var idClient=<?php echo $idClient; ?>;
                    $.ajax({
                        url: "ajax/modifierLigneAjax.php",
                        method: "POST",
                        data: {
                            operation: 32,
                            idClient : idClient,
                            taux: taux
                        },
                        success: function(data) {

                            tab = data.split('<>');
                            if(tab[0]=='1'){
                                $("#inputTauxAncien").val(tab[1]+' %');
                                document.getElementById('btnValiderTaux').style.display = "none";
                                document.getElementById('btnRafraichirTaux').style.display = "block";
                                document.getElementById('inputTauxNouveau').style.display = "none";
                                document.getElementById('inputTauxAncien').style.display = "block";
                            }
                        },
                        error: function() {

                        },
                        dataType: "text"
                    });

                });
            });

        </script>



        <div class="jumbotron noImpr">

            <div class="col-sm-4 pull-right" >

                <div aria-label="navigation">

                    <ul class="pager">

                        <li>

                        <input type="text" id="reportrange" />

                        </li>

                    </ul>

                </div>   

            </div>

            <h2>Les Operations du client : <?php echo $client['prenom']." ".strtoupper($client['nom']); ?> </h2>

            <h5>Du : 

                <?php

                        $debutDetails=explode("-", $dateDebut);

                        $dateDebutA=$debutDetails[2].'-'.$debutDetails[1].'-'.$debutDetails[0];

                        $finDetails=explode("-", $dateFin);

                        $dateFinA=$finDetails[2].'-'.$finDetails[1].'-'.$finDetails[0];

                        echo $dateDebutA." au ".$dateFinA; 

                ?>

            </h5>

            <div class="panel-group">

                <div class="panel" style="background:#cecbcb;">

                    <?php if ($client['avoir']==0) { ?>

                        <div class="panel-heading" >

                            <h4 class="panel-title">

                            <a data-toggle="collapse" href="#collapse1">Montant à verser : <?php echo number_format($T_solde, 2, ',', ' '); ?>   </a>

                            </h4>

                        </div>

                        <div id="collapse1" class="panel-collapse collapse">

                            <div class="panel-heading" style="margin-left:2%;">

                                <?php ?>

                                <h4>Total des Bons : <?php echo $TotalB[0]; ?></h4>

                                <h4>Total des Versements : <?php echo $TotalV[0]; ?></h4>

                            </div>

                        </div>                    

                        <?php } else {?>

                        <div class="panel-heading" >

                            <h4 class="panel-title">

                            <a data-toggle="collapse" href="#collapse1">Montant avoir : <?php echo number_format($T_avoir, 2, ',', ' '); ?>   </a>

                            </h4>

                        </div>

                    <?php } ?>

                </div>

            </div>

            <form class="form-inline pull-right noImpr"  style="margin-right:20px;"
                method="post">
                <?php if ($_SESSION['proprietaire']==1){ ?>
                    <button type="button" id="btnRafraichirTaux" class="btn btn-info pull-right" >
                        <span class="glyphicon glyphicon-pencil"></span>
                    </button> 
                    <button style="display:none" type="button" id="btnValiderTaux" class="btn btn-success pull-right" >
                        <span class="glyphicon glyphicon-pencil"></span>
                    </button> 
                <?php } ?> 
                <div class="col-xs-5 pull-right">
                    <input  type="text" disabled="true" id="inputTauxAncien"  class="form-control"  <?php echo "value='".$client['taux']." %'"  ; ?> >
                    <input  type="text" style="display:none" id="inputTauxNouveau" class="form-control"  <?php echo "value='".$client['taux']."'"  ; ?> >
                </div>
            </form>

            <form class="form-inline pull-left noImpr"  target="_blank" style="margin-left:20px;"

                method="post" action="pdfOperationsClient.php" >

                <input type="hidden" name="dateDebut" id="dateDebutOP"  <?php echo  "value=".$dateDebut."" ; ?> >

                <input type="hidden" name="dateFin" id="dateFinOP"  <?php echo  "value=".$dateFin."" ; ?> >

                <input type="hidden" name="idClient" id="idClientOP"  <?php echo  "value=".$idClient."" ; ?> >

                <button  class="btn btn-primary  pull-left" style="margin-left:20px;" >

                    <span class="glyphicon glyphicon-print"></span> Impression Relevé

                </button>

            </form>

        </div>



        <?php if ($_SESSION['caisse']==1 || $_SESSION['proprietaire']==1){ ?>

            <!--*******************************Debut Rechercher Produit****************************************-->

            <!--<form  class="pull-right" id="searchProdForm" method="post" name="searchProdForm" >

                <input type="hidden" id="clientBon"  />

                <input type="text" size="30" class="form-control" name="produit" placeholder="Rechercher ..."  id="produitBon" autocomplete="off" />

                    <span id="reponsePV"></span>

            </form>-->

            <!--*******************************Fin Rechercher Produit****************************************-->

            <!--*******************************Debut Ajouter Versement****************************************-->

            <?php
                $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."' and YEAR(datePs)='".$annee_paie."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";

                $res0=mysql_query($sql0);

                if(mysql_num_rows($res0)){

                    if($jour > 0){

                        if($jour > 4){
                            if ($client['avoir']==0) { 
                                echo '
                                <button disabled="true" type="button" class="btn btn-success noImpr pull-right" data-toggle="modal" data-target="#addVer">

                                    <i class="glyphicon glyphicon-plus"></i>Versement

                                </button> ';
                            } else { 
                                echo '
                                <button disabled="true" type="button" class="btn btn-success noImpr pull-right" data-toggle="modal" data-target="#addAvoir">

                                <i class="glyphicon glyphicon-plus"></i>Enregistrer avoir

                                </button> ';
                            } 
                            if ($_SESSION['mutuelle']==1){
                                if ($client['avoir']==0) { 
                                    ?>
                                    <form name="formulairePagnet" method="post">
                
                                        <button disabled="true" type="submit" style="margin-right:50px"  class="btn btn-success noImpr pull-left" name="btnSavePagnetVente" <?= ($client['plafond']!=null && $client['plafond']!=0 && $client['plafond']<=($T_solde * $_SESSION['devise'])) ? 'disabled' : '' ;?>>
                
                                            <i class="glyphicon glyphicon-plus"></i>Ajouter un Bon
                
                                        </button>
                
                                    </form>
                                    <?php
                
                                } 
                                ?>
                                    <form name="formulairePagnet" method="post"   >
                    
                                        <button disabled="true" type="submit" class="btn btn-info noImpr " name="btnSavePagnetImputation" <?= ($client['plafond']!=null && $client['plafond']!=0 && $client['plafond']<=($T_solde * $_SESSION['devise'])) ? 'disabled' : '' ;?>>
                    
                                                        <i class="glyphicon glyphicon-plus"></i>Ajouter une Imputation
                    
                                        </button>
                    
                                    </form> 
                                <?php 
                            }
                            else {
                                ?>
                                <form name="formulairePagnet" method="post"  >
                
                                    <button disabled="true" type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente" <?= ($client['plafond']!=null && $client['plafond']!=0 && $client['plafond']<=($T_solde * $_SESSION['devise'])) ? '' : '' ;?>>
                
                                                    <i class="glyphicon glyphicon-plus"></i>Ajouter un Bon
                
                                    </button>
                
                                </form>
                                <?php 
                            }
                        }
                        else{
                            if ($client['avoir']==0) { 
                                echo '
                                <button type="button" class="btn btn-success noImpr pull-right" data-toggle="modal" data-target="#addVer">

                                    <i class="glyphicon glyphicon-plus"></i>Versement

                                </button> ';
                            } else { 
                                echo '
                                <button type="button" class="btn btn-success noImpr pull-right" data-toggle="modal" data-target="#addAvoir">

                                <i class="glyphicon glyphicon-plus"></i>Enregistrer avoir

                                </button> ';
                            } 
                            if ($_SESSION['mutuelle']==1){
                                if ($client['avoir']==0) { 
                                    ?>
                                        <form name="formulairePagnet" method="post">
                    
                                            <button type="submit" style="margin-right:50px"  class="btn btn-success noImpr pull-left" name="btnSavePagnetVente" <?= ($client['plafond']!=null && $client['plafond']!=0 && $client['plafond']<=($T_solde * $_SESSION['devise'])) ? 'disabled' : '' ;?>>
                    
                                                <i class="glyphicon glyphicon-plus"></i>Ajouter un Bon
                    
                                            </button>
                    
                                        </form>
                                    <?php
                
                                } 
                                ?>
                                    <form name="formulairePagnet" method="post"   >
                    
                                        <button type="submit" class="btn btn-info noImpr " name="btnSavePagnetImputation" <?= ($client['plafond']!=null && $client['plafond']!=0 && $client['plafond']<=($T_solde * $_SESSION['devise'])) ? 'disabled' : '' ;?>>
                    
                                                        <i class="glyphicon glyphicon-plus"></i>Ajouter une Imputation
                    
                                        </button>
                    
                                    </form> 
                                <?php 
                            }
                            else {
                                ?>
                                <form name="formulairePagnet" method="post"  >
                
                                    <button type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente" <?= ($client['plafond']!=null && $client['plafond']!=0 && $client['plafond']<=($T_solde * $_SESSION['devise'])) ? '' : '' ;?>>
                
                                                    <i class="glyphicon glyphicon-plus"></i>Ajouter un Bon
                
                                    </button>
                
                                </form>
                                <?php 
                            }
                        }
                    }
                    else{
                        if ($client['avoir']==0) { 
                            echo '
                            <button type="button" class="btn btn-success noImpr pull-right" data-toggle="modal" data-target="#addVer">

                                <i class="glyphicon glyphicon-plus"></i>Versement

                            </button> ';
                        } else { 
                            echo '
                            <button type="button" class="btn btn-success noImpr pull-right" data-toggle="modal" data-target="#addAvoir">

                            <i class="glyphicon glyphicon-plus"></i>Enregistrer avoir

                            </button> ';
                        } 
                        if ($_SESSION['mutuelle']==1){
                            if ($client['avoir']==0) { 
                                ?>
                                    <form name="formulairePagnet" method="post">
                
                                        <button type="submit" style="margin-right:50px"  class="btn btn-success noImpr pull-left" name="btnSavePagnetVente" <?= ($client['plafond']!=null && $client['plafond']!=0 && $client['plafond']<=($T_solde * $_SESSION['devise'])) ? 'disabled' : '' ;?>>
                
                                            <i class="glyphicon glyphicon-plus"></i>Ajouter un Bon
                
                                        </button>
                
                                    </form>
                                <?php
            
                            } 
                            ?>
                                <form name="formulairePagnet" method="post"   >
                
                                    <button type="submit" class="btn btn-info noImpr " name="btnSavePagnetImputation" <?= ($client['plafond']!=null && $client['plafond']!=0 && $client['plafond']<=($T_solde * $_SESSION['devise'])) ? 'disabled' : '' ;?>>
                
                                                    <i class="glyphicon glyphicon-plus"></i>Ajouter une Imputation
                
                                    </button>
                
                                </form> 
                            <?php 
                        }
                        else {
                            ?>
                            <form name="formulairePagnet" method="post"  >
            
                                <button type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente" <?= ($client['plafond']!=null && $client['plafond']!=0 && $client['plafond']<=($T_solde * $_SESSION['devise'])) ? '' : '' ;?>>
            
                                                <i class="glyphicon glyphicon-plus"></i>Ajouter un Bon
            
                                </button>
            
                            </form>
                            <?php 
                        }
                        
                    }

                }
                else{
                    if ($client['avoir']==0) { 
                        echo '
                        <button type="button" class="btn btn-success noImpr pull-right" data-toggle="modal" data-target="#addVer">

                            <i class="glyphicon glyphicon-plus"></i>Versement

                        </button> ';
                    } else { 
                        echo '
                        <button type="button" class="btn btn-success noImpr pull-right" data-toggle="modal" data-target="#addAvoir">

                        <i class="glyphicon glyphicon-plus"></i>Enregistrer avoir

                        </button> ';
                    } 
                    if ($_SESSION['mutuelle']==1){
                        if ($client['avoir']==0) { 
                            ?>
                                <form name="formulairePagnet" method="post">
            
                                    <button type="submit" style="margin-right:50px"  class="btn btn-success noImpr pull-left" name="btnSavePagnetVente" <?= ($client['plafond']!=null && $client['plafond']!=0 && $client['plafond']<=($T_solde * $_SESSION['devise'])) ? 'disabled' : '' ;?>>
            
                                        <i class="glyphicon glyphicon-plus"></i>Ajouter un Bon
            
                                    </button>
            
                                </form>
                            <?php
        
                        } 
                        ?>
                            <form name="formulairePagnet" method="post"   >
            
                                <button type="submit" class="btn btn-info noImpr " name="btnSavePagnetImputation" <?= ($client['plafond']!=null && $client['plafond']!=0 && $client['plafond']<=($T_solde * $_SESSION['devise'])) ? 'disabled' : '' ;?>>
            
                                                <i class="glyphicon glyphicon-plus"></i>Ajouter une Imputation
            
                                </button>
            
                            </form> 
                        <?php 
                    }
                    else {
                        ?>
                        <form name="formulairePagnet" method="post"  >
        
                            <button type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente" <?= ($client['plafond']!=null && $client['plafond']!=0 && $client['plafond']<=($T_solde * $_SESSION['devise'])) ? '' : '' ;?>>
        
                                            <i class="glyphicon glyphicon-plus"></i>Ajouter un Bon
        
                            </button>
        
                        </form>
                        <?php 
                    }
                }
            ?>

            <div class="modal fade" id="addVer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

                <div class="modal-dialog" role="document">

                    <div class="modal-content">

                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                            <h4 class="modal-title" id="myModalLabel">Nouveau versement</h4>

                        </div>

                        <div class="modal-body">

                            <form name="formulaireVersement" method="post" <?php echo  "action=bonPclient.php?c=". $idClient."" ; ?>>

                                <div class="form-group">

                                    <label for="inputEmail3" class="control-label">Montant</label>

                                    <input type="number" class="form-control" id="montant" name="montant" placeholder="Montant" autofocus required="">

                                    <span class="text-danger" ></span>

                                </div>

                                <?php if ($_SESSION['proprietaire']==1): ?>

                                    <div class="form-group">

                                        <label for="inputEmail3" class="control-label">Date (jj-mm-aaaa) </label>

                                        <input type="text" size='11' maxLength='10' value="" class="form-control dateInputControl"  name="dateActualiser" placeholder="jj-mm-aaaa" required="">

                                        <span class="text-danger" ></span>

                                    </div>

                                <?php endif; ?>                                                                                  

                                <?php if ($_SESSION['compte']==1) { ?>          

                                <select class="form-control compte" name="compte"  <?php echo  "id=compte".$pagnet['idPagnet'] ; ?>>

                                    <!-- <option value="caisse">Caisse</option> -->

                                    <?php

                                        foreach ($cpt_array as $key) { ?>

                                            <option value="<?= $key['idCompte'];?>"><?= $key['nomCompte'];?></option>

                                        <?php } ?>

                                </select>                        

                                <?php } ?>              

                                <div class="modal-footer">

                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                                    <button type="submit" name="btnEnregistrerVersement" class="btn btn-primary">Enregistrer</button>

                                </div>

                            </form>

                        </div>



                    </div>

                </div>

            </div>

            <div class="modal fade" id="addAvoir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

                <div class="modal-dialog" role="document">

                    <div class="modal-content">

                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                            <h4 class="modal-title" id="myModalLabel">Nouveau avoir</h4>

                        </div>

                        <div class="modal-body">

                            <form name="formulaireVersement" method="post" <?php echo "action=bonPclient.php?c=". $idClient."" ; ?>>

                                <div class="form-group">

                                    <label for="inputEmail3" class="control-label">Montant</label>

                                    <input type="number" class="form-control" id="montant" name="montant" placeholder="Montant" required="">

                                    <span class="text-danger" ></span>

                                </div>            

                                <div class="modal-footer">

                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                                    <button type="submit" name="btnEnregistrerAvoir" class="btn btn-primary">Enregistrer</button>

                                </div>

                            </form>

                        </div>



                    </div>

                </div>

            </div>

            <!--*******************************Fin Ajouter Versement****************************************-->

            <?php  

                $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."'  and YEAR(datePs)='".$annee_paie."' and MONTH(datePs)!='".$mois."'  and aPayementBoutique=0 ";

                $res0=mysql_query($sql0);

                if(mysql_num_rows($res0)){

                    if($jour > 0){

                        echo "<h6><span id='blinker' style='color:red;'>VEUILLEZ EFFECTUER LE PAIEMENT DU SERVICE JCAISSE AVANT LE 5 !</span></h6>";

                    }

                    else if ($client['plafond']!=null && $client['plafond']!=0 && $client['plafond']<=($T_solde * $_SESSION['devise'])){
        
                        echo "<h6><span id='blinker' style='color:red;'>CE CLIENT A ATTEINT LE PLAFOND DE SON COMPTE.</span></h6>";
        
                    }

                    else{

                        echo '<br>';

                    }

                }

                else{

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



                    function modifierVersement(idVersement) {

                        $("#ancienV"+idVersement).hide();

                        $("#nouveauV"+idVersement).show();

                    }
                    function showPreviewVersement(event,idVersement) {
                        var file = document.getElementById('input_file_Versement'+idVersement).value;
                        var reader = new FileReader();
                        reader.onload = function()
                        {
                            var format = file.substr(file.length - 3);
                            var pdf = document.getElementById('output_pdf_Versement'+idVersement);
                            var image = document.getElementById('output_image_Versement'+idVersement);
                            if(format=='pdf'){
                                document.getElementById('output_pdf_Versement'+idVersement).style.display = "block";
                                document.getElementById('output_image_Versement'+idVersement).style.display = "none";
                                pdf.src = reader.result;
                            }
                            else{
                                document.getElementById('output_image_Versement'+idVersement).style.display = "block";
                                document.getElementById('output_pdf_Versement'+idVersement).style.display = "none";
                                image.src = reader.result;
                            }
                        }
                        reader.readAsDataURL(event.target.files[0]);
                        document.getElementById('btn_upload_Versement'+idVersement).style.display = "block";
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



                    /**Debut requete pour Lister les Paniers en cours d'Aujourd'hui (1 au maximum) **/

                        $sqlP0="SELECT codePagnet

                        FROM

                        (SELECT CONCAT(SUBSTR(p.datepagej,7, 4),'',SUBSTR(p.datepagej,4, 2),'',SUBSTR(p.datepagej,1, 2),'',p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p where p.idClient='".$idClient."' AND p.verrouiller=0 AND (p.type=0 || p.type=30)

                        UNION ALL

                        SELECT CONCAT(SUBSTR(m.datepagej,7, 4),'',SUBSTR(m.datepagej,4, 2),'',SUBSTR(m.datepagej,1, 2),'',m.heurePagnet,'+',m.idMutuellePagnet,'+2') AS codePagnetMutuelle FROM `".$nomtableMutuellePagnet."` m where m.idClient='".$idClient."' AND m.verrouiller=0 AND (m.type=0 || m.type=30)

                        ) AS a ORDER BY codePagnet DESC LIMIT ".$premier.",".$parPage." ";

                        $resP0 = mysql_query($sqlP0) or die ("persoonel requête 20".mysql_error());

                    /**Fin requete pour Lister les Paniers en cours d'Aujourd'hui (1 au maximum) **/



                    /**Debut requete pour Rechercher le dernier Panier Ajouter  **/

                        $reqA="SELECT codePagnet

                            FROM

                            (

                            SELECT CONCAT(SUBSTR(p.datepagej,7, 4),'',SUBSTR(p.datepagej,4, 2),'',SUBSTR(p.datepagej,1, 2),'-',p.heurePagnet) AS codePagnet FROM `".$nomtablePagnet."` p where p.idClient='".$idClient."' AND p.verrouiller=1 AND (p.type=0 || p.type=30) 

                                UNION ALL

                            SELECT CONCAT(SUBSTR(m.datepagej,7, 4),'',SUBSTR(m.datepagej,4, 2),'',SUBSTR(m.datepagej,1, 2),'-',m.heurePagnet) AS codePagnetMutuelle FROM `".$nomtableMutuellePagnet."` m where m.idClient='".$idClient."' AND m.verrouiller=1 AND (m.type=0 || m.type=30)

                                UNION ALL

                            SELECT CONCAT(SUBSTR(v.dateVersement,7, 4),'',SUBSTR(v.dateVersement,4, 2),'',SUBSTR(v.dateVersement,1, 2),'-',v.heureVersement) AS codeVersement FROM `".$nomtableVersement."` v where v.idClient='".$idClient."'

                            ) AS a ORDER BY codePagnet DESC LIMIT 1 ";

                        $resA = mysql_query($reqA) or die ("persoonel requête 20".mysql_error());

                    /**Fin requete pour Rechercher le dernier Panier Ajouter  **/ ?>

                    

                    <!-- Debut Boucle while concernant les Paniers en cours (1 aux maximum) -->

                        <?php while ($pagnets = mysql_fetch_assoc($resP0)) {   ?>

                            <?php

                                $pagnetClient = explode("+", $pagnets['codePagnet']);

                                if($pagnetClient[2]==1){

                                    $sqlT1="SELECT * FROM `".$nomtablePagnet."` where idPagnet='".$pagnetClient[1]."' AND idClient='".$idClient."' ";

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

                                                        <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total à payer: <span <?php echo  "id=somme_Apayer".$pagnet['idPagnet'] ; ?> ><?php echo ($TotalT[0] - (($TotalT[0] * $pagnet['taux']) / 100));  ?> </span></span>      

                                                    </a>

                                                </h4>

                                            </div>

                                            <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?> class="panel-collapse collapse in"  >

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

                                                                <input type="hidden" <?php echo "id=val_remise".$pagnet['idPagnet'] ; ?> name="remise" value="0">

                                                                <select class="form-control col-md-2 col-sm-2 col-xs-3 tauxPanier filedReadonly<?= $pagnet['idPagnet'] ; ?>" name="taux" <?php echo  "id=taux".$pagnet['idPagnet'].""; ?>  onchange="modif_TauxPanier(this.value,<?php echo  $pagnet['idPagnet']; ?>)" >
                                                                    <option selected="true" disabled="disabled" value="<?= $pagnet['taux']; ?>"><?= $pagnet['taux'].' %' ; ?></option>
                                                                    <option value="0">0 %</option>
                                                                    <option value="3">3 %</option>
                                                                    <option value="5">5 %</option>
                                                                    <option value="10">10 %</option>
                                                                    <option value="15">15 %</option>
                                                                </select>

                                                                <?php if($_SESSION['compte']==1):?>

                                                                    <input type="number" class="avanceInput form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $pagnet['idPagnet'];?>" name="avanceInput" id="avanceInput<?= $pagnet['idPagnet'];?>" autocomplete="off" placeholder="Avance"/>                                                    

                                                                    <select class="compteAvance <?= $pagnet['idPagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $pagnet['idPagnet'];?>" name="compteAvance"  <?php echo  "id=compteAvance".$pagnet['idPagnet'] ; ?>>

                                                                        <!-- <option value="caisse">Caisse</option> -->

                                                                    <?php foreach ($cpt_array as $key) { 

                                                                        if($key['idCompte'] != 2){

                                                                        ?>

                                                                            <option value="<?= $key['idCompte'];?>"><?= $key['nomCompte'];?></option>

                                                                        <?php }

                                                                        } ?>

                                                                    </select>

                                                                <?php endif; ?>                                                      

                                                                

                                                                <input type="hidden" name="idPagnet"   <?php echo  "value=".$pagnet['idPagnet']."" ; ?>>

                                                                <input type="hidden" name="totalp" <?php echo  "id=totalp".$pagnet['idPagnet']."" ; ?> value="<?php echo $pagnet['totalp']; ?>" >

                                                                <!-- <div class="btnAction col-md-1 col-xs-12"> -->

                                                                    <button type="submit" style="" name="btnImprimerFacture" <?php echo  "id=btnImprimerFacture".$pagnet['idPagnet']."" ; ?> class="btn btn-success btn_Termine_Panier terminer" data-toggle="modal"><span class="glyphicon glyphicon-ok"></span></button>

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

                                else if($pagnetClient[2]==2){

                                    $sqlT2="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet='".$pagnetClient[1]."' AND idClient='".$idClient."' ";

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

                                                            <?php if ($client['avoir']==1) { ?>

                                                                <input type="hidden" id="idClientAvoir<?= $mutuelle['idMutuellePagnet'];?>" <?php echo  "value=".$client['idClient']."" ; ?> >

                                                                <span class="reponseClient">

                                                                    <input type="text" id="clientMutuelle<?= $mutuelle['idMutuellePagnet'];?>" disabled class="form-control clientMutuelleInput col-md-3 col-sm-3 col-xs-4 clientMutuelle" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" value="<?php echo $client['prenom']." ".strtoupper($client['nom']); ?>" autocomplete="off"  />

                                                                </span> 

                                                                <input type="text" id="codeAdherantMutuelle" class="form-control col-md-3 col-sm-3 col-xs-4 codeAdherantMutuelle" disabled="true" value="<?php echo $client['matriculePension'] ; ?>"  placeholder="Code Adherant...."   />

                                                                

                                                                <input type="text" class="form-control col-md-3 col-sm-3 col-xs-4 codeBeneficiaire" id="codeBeneficiaire" disabled="true" name="codeBeneficiaire" value="<?php echo $client['numCarnet'] ; ?>" placeholder="Code Beneficiaire...."  />

                                                                <input type="text" class="form-control col-md-3 col-sm-3 col-xs-4 numeroRecu" id="numeroRecu" disabled="true" name="numeroRecu" value="0000" placeholder="Numero Reçu...."  />

                                                                <input type="text" class="form-control col-md-3 col-sm-3 col-xs-4 dateRecu" id="dateRecu" disabled="true" name="dateRecu" value="<?php echo $dateString2 ; ?>" placeholder="Date Reçu.."  />

                                                            <?php } else { ?>

                                                                <span class="reponseClient">

                                                                    <input type="text" id="clientMutuelle<?= $mutuelle['idMutuellePagnet'];?>" disabled class="form-control clientMutuelleInput col-md-3 col-sm-3 col-xs-4 clientMutuelle" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" value="<?php echo $client['prenom']." ".strtoupper($client['nom']); ?>" autocomplete="off"  />

                                                                </span> 

                                                                <input type="text" id="codeAdherantMutuelle" class="form-control col-md-3 col-sm-3 col-xs-4 codeAdherantMutuelle" value="<?php echo $mutuelle['codeAdherant'] ; ?>"  placeholder="Code Adherant...."   />

                                                                

                                                                <input type="text" class="form-control col-md-3 col-sm-3 col-xs-4 codeBeneficiaire" id="codeBeneficiaire" name="codeBeneficiaire" value="<?php echo $mutuelle['codeBeneficiaire'] ; ?>" placeholder="Code Beneficiaire...."  />

                                                                <input type="text" class="form-control col-md-3 col-sm-3 col-xs-4 numeroRecu" id="numeroRecu" name="numeroRecu" value="<?php echo $mutuelle['numeroRecu'] ; ?>" placeholder="Numero Reçu...."  />

                                                                <input type="date" class="form-control col-md-3 col-sm-3 col-xs-4 dateRecu" id="dateRecu" name="dateRecu" value="<?php echo $mutuelle['dateRecu'] ; ?>" placeholder="Date Reçu.."  />

                                                            <?php } ?>

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

                                                                <?php if ($client['avoir']==1) { ?>

                                                                    <select class="form-control col-md-2 col-sm-2 col-xs-3 idMutuelle" placeholder="Mutuelle" disabled="true" name="idMutuelle" <?php echo  "id=mutuellePagnet".$mutuelle['idMutuellePagnet'].""; ?> onchange="modif_MutuellePagnet(this.value)" >  >

                                                                        <option value="MUTUELLE DES INVALIDES">MUTUELLE DES INVALIDES</option>                                                                          

                                                                    </select>

                                                                    <input type="text" class="form-control col-md-2 col-sm-2 col-xs-3 tauxMutuelle" disabled="true" value="100%" <?php echo  "id=tauxMutuelle".$mutuelle['idMutuellePagnet'].""; ?> placeholder="0%">

                                                                <?php } else { ?>

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

                                                                            $sqlM="SELECT * from `".$nomtableMutuelle."` order by nomMutuelle ASC";

                                                                            $resM=mysql_query($sqlM);

                                                                            while($mtlle=mysql_fetch_array($resM)){

                                                                            echo '<option value="'.$mtlle["idMutuelle"].'§'.$mutuelle['idMutuellePagnet'].'" >'.$mtlle['nomMutuelle'].'</option>';

                                                                            }

                                                                        ?>

                                                                    </select>

                                                                    <input  type="text" class="form-control col-md-2 col-sm-2 col-xs-3 tauxMutuelle"  disabled="true" <?php echo  "value=".$mutuelle['taux']."%"; ?> <?php echo  "id=tauxMutuelle".$mutuelle['idMutuellePagnet'].""; ?> placeholder="0%">

                                                                <?php } ?>

                                                                <?php if($_SESSION['compte']==1):?>

                                                                    <input type="number" class="avanceInput avanceInputM form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" name="avanceInput" id="avanceInput<?= $mutuelle['idMutuellePagnet'];?>" autocomplete="off"  placeholder="Avance"/>

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

                                                                <?php endif; ?>

                                            

                                                                <input type="hidden" name="idMutuellePagnet"   <?php echo  "value=".$mutuelle['idMutuellePagnet']."" ; ?>>

                                                                <input type="hidden" name="totalp" <?php echo  "id=totalp".$mutuelle['idMutuellePagnet']."" ; ?> value="<?php echo $mutuelle['totalp']; ?>" >

                                                                <button type="button" name="btnTerminerImputation" <?php echo  "id=btnTerminerMutuelle".$mutuelle['idMutuellePagnet']."" ; ?> class="btn btn-success btn_Termine_Panier terminer terminerMutuelleBon" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>"><span class="glyphicon glyphicon-ok"></span></button>

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

                                                                            ?>

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

                    <!-- Fin Boucle while concernant les Paniers en cours (1 aux maximum) -->



                    <!-- Debut Boucle while concernant les Paniers Vendus -->

                        <?php $n=$nbPaniers - (($currentPage * 10) - 10); 

                            $idmax=@mysql_fetch_array($resA); 

                            $idmax=$idmax[0];

                            while ($bons = mysql_fetch_assoc($resP1)) {   ?>

                                <?php

                                    $bon = explode("+", $bons['codePagnet']);

                                    if($bon[2]==1){

                                        $sqlT1="SELECT * FROM `".$nomtablePagnet."` where idPagnet='".$bon[1]."' AND idClient='".$idClient."' ";

                                        $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());

                                        $pagnet = mysql_fetch_assoc($resT1);

                                        if($pagnet!=null){

                                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";

                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                                            $ligne = mysql_fetch_assoc($res) ;

                                            $tab=explode("-",$pagnet['datepagej']);

                                            $datePagnet=$tab[2].''.$tab[1].''.$tab[0];

                                            if (($ligne['classe']==0 || $ligne['classe']==1) && ($pagnet['type']==0 || $pagnet['type']==30)){?>

                                                    <div class="panel panel-success">

                                                        <div class="panel-heading">

                                                            <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                                                <div class="right-arrow pull-right">+</div>

                                                                <a href="#"> Bon <?php echo $n; ?>

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

                                                                if($idmax === $datePagnet."-".$pagnet['heurePagnet']){

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




                                                                <button class="btn btn-warning  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">

                                                                Facture

                                                                </button>




                                                                <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                    method="post" action="pdfFacturePharmacie.php" >

                                                                    <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                </form>

                                                                <!--*******************************Fin Facture****************************************-->

                                                                


                                                                

                                                                    <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                                    Ticket de Caisse

                                                                    </button>



                                                            

                                                                <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                    method="post" action="barcodeFacture.php" >

                                                                    <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                </form>

                                                                

                                                                <!--*******************************Debut Editer Pagnet****************************************-->

                                                                <?php if ($_SESSION['proprietaire']==1 && $_SESSION['editionPanier']==1){ ?>

                                                                    <button type="button" style="margin-right:20px;" class="btn btn-primary pull-right modeEditionBtnPh" id="edit-<?= $pagnet['idPagnet'] ; ?>">

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


                                                                <div id="factForm">
                                                                    <input type="text" placeholder="Changer le client" class="client clientInput form-control col-md-2 col-sm-2 col-xs-3 alert alert-info" data-type="simple" data-idPanier="<?= $pagnet['idPagnet'];?>" name="clientInput" id="clientInput<?= $pagnet['idPagnet'];?>" autocomplete="off" />&ensp;<span class="btn btn-success btn-sm glyphicon glyphicon-ok" id="changedOk<?= $pagnet['idPagnet'];?>" style="display:none"></span>
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

                                            else if ($ligne['classe']==6 || $ligne['classe']==9) {?>

                                                <div class="panel panel-info">

                                                    <div class="panel-heading">

                                                        <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                                            <div class="right-arrow pull-right">+</div>

                                                            <a href="#"> Bon

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

                                                            if($idmax == $pagnet['datepagej']."-".$pagnet['heurePagnet']){

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

                                                                




                                                                <button disabled="true" class="btn btn-warning  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">

                                                                Facture

                                                                </button>

                                                                



                                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                method="post" action="pdfFacturePharmacie.php" >

                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                            </form>

                                                                




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

                                                                                        <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal">

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

                                        }

                                    }

                                    else if($bon[2]==2){

                                        // var_dump($bon);

                                        $sqlT1="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet='".$bon[1]."' AND idClient='".$idClient."' ";

                                        $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());

                                        $mutuelle = mysql_fetch_assoc($resT1);

                                        if($mutuelle!=null){

                                            $sql="SELECT * FROM `".$nomtableLigne."` where idMutuellePagnet=".$mutuelle['idMutuellePagnet']." ";

                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                                            $ligne = mysql_fetch_assoc($res) ;

                                            $tab=explode("-",$mutuelle['datepagej']);

                                            $datePagnet=$tab[2].''.$tab[1].''.$tab[0];

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

                                                            if($idmax == $datePagnet."-".$mutuelle['heurePagnet']){

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

                                                            <!--*******************************Debut Editer Pagnet****************************************-->

                                                                <?php if ($_SESSION['proprietaire']==1 && $_SESSION['editionPanier']==1){ ?>

                                                                    <button type="button" class="btn btn-primary pull-left modeEditionBtnMt btn_disabled_after_click" id="edit-<?= $mutuelle['idMutuellePagnet'] ; ?>">

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


                                                                <button class="btn btn-warning  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'mutuelle_facture'.$mutuelle['idMutuellePagnet'] ;?>').submit();">

                                                                Facture

                                                                </button>


    

                                                            <form class="form-inline pull-right noImpr" <?php echo  "id=mutuelle_facture".$mutuelle['idMutuellePagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                method="post" action="pdfFacturePharmacie.php" >

                                                                <input type="hidden" name="idMutuellePagnet" id="idMutuellePagnet"  <?php echo  "value=".$mutuelle['idMutuellePagnet']."" ; ?> >

                                                            </form>

                                                                


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

                                                            <!--*******************************Fin Total Facture****************************************-->

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

                                    else if($bon[2]==3){

                                        $sqlT2="SELECT * FROM `".$nomtableVersement."` where idVersement='".$bon[1]."' AND idClient='".$idClient."' ";

                                        $resT2 = mysql_query($sqlT2) or die ("persoonel requête 2".mysql_error());

                                        $versement = mysql_fetch_assoc($resT2);

                                        if($versement!=null){

                                            $tab=explode("-",$versement['dateVersement']);

                                            $datePagnet=$tab[2].''.$tab[1].''.$tab[0];

                                            ?>

                                            <div style="padding-top : 2px;" class="panel panel-warning">

                                                <div class="panel-heading">

                                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#versement".$versement['idVersement']."" ; ?>  class="panel-title expand">

                                                    <div class="right-arrow pull-right">+</div>

                                                    <a href="#"> Versement 

                                                        <span class="spanDate noImpr"> </span>

                                                        <span class="spanDate noImpr"> Date: <?php echo $versement['dateVersement']; ?> </span>

                                                        <span class="spanDate noImpr">Heure: <?php echo $versement['heureVersement']; ?></span>

                                                        <?php if($versement['montantAvoir']==0){?>

                                                            <span class="spanDate noImpr">Montant: <?php echo ($versement['montant'] * $_SESSION['devise']).' '.$_SESSION['symbole']; ?></span>

                                                        <?php } else{?>

                                                            <span class="spanDate noImpr">Montant: <?php echo ($versement['montantAvoir'] * $_SESSION['devise']).' '.$_SESSION['symbole']; ?></span>

                                                        <?php } ?>

                                                        <span class="spanDate noImpr"> Facture : #<?php echo $versement['idVersement']; ?></span>

                                                    </a>

                                                    </h4>

                                                </div>

                                                <div <?php echo  "id=versement".$versement['idVersement']."" ; ?> 

                                                    <?php 

                                                        if($idmax == $datePagnet."-".$versement['heureVersement']){

                                                            ?> class="panel-collapse collapse in" <?php

                                                        }

                                                        else  {

                                                            ?> class="panel-collapse collapse " <?php

                                                        }

                                                    ?> >

                                                    <div class="panel-body" >

                                                        <?php if ($versement['iduser']==$_SESSION['iduser'] || $_SESSION['proprietaire']==1){ ?>

                                                            <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_anl_versement".$versement['idVersement'] ; ?>	 >

                                                                <span class="glyphicon glyphicon-remove"></span>Annuler

                                                            </button>

                                                        <?php }

                                                        else {  ?>

                                                            <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >

                                                                <span class="glyphicon glyphicon-remove"></span>Annuler

                                                            </button>

                                                        <?php }?>

                                                        

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

                                                                    <input type="hidden" name="idVersementAvoir" id="idVersementAvoir"  <?php echo  "value=".$client['avoir']."" ; ?>>

                                                                    <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?>>

                                                                    <input type="hidden" name="montant" id="montant"  <?php echo  "value=".$versement['montant']."" ; ?>>

                                                                    <input type="hidden" name="montantAvoirName" id="montantAvoirName"  <?php echo  "value=".$versement['montantAvoir']."" ; ?>>

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

                                                            method="post"  >

                                                            <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?> >

                                                            

                                                            

                                                            <button disabled="true" type="submit" class="btn btn-warning pull-right" data-toggle="modal" name="barcodeFactureV">

                                                            <span class="glyphicon glyphicon-lock"></span>Facture

                                                            </button>

                                                            

                                                            

                                                        </form>



                                                        <form class="form-inline pull-right noImpr" style="margin-right:20px;"

                                                            method="post" action="barcodeFacture.php" target="_blank"  >

                                                            <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?> >

                                                            


                                                            

                                                            <button type="submit" class="btn btn-info pull-right" data-toggle="modal" name="barcodeFactureV">

                                                            <span class="glyphicon glyphicon-lock"></span>Ticket de Caisse

                                                            </button>

                                                            

                                                            

                                                        </form>

                                                            

                                                        <div <?php echo  "id=divImpVer".$versement['idVersement']."" ; ?>  >

                                                            <table class="table table-bordered" style="margin-top:40px;">

                                                                <thead>

                                                                    <tr>

                                                                        <th>Description</th>

                                                                        <th>Montant</th>

                                                                        <th>Date</th>
                                                                        <th>Piece Jointe</th>

                                                                    </tr>

                                                                </thead>

                                                                <tbody>

                                                                    <tr id="ancienV<?php echo  $versement['idVersement'] ; ?>">

                                                                        <td> 

                                                                            <?php

                                                                                

                                                                                if($_SESSION['depotAvoir']==1 && $client['avoir']==1){

                                                                                    echo $versement['paiement'];

                                                                                    echo '<br /> Type paiement : Espèces';

                                                                                }else {

                                                                                    echo $versement['paiement'];

                                                                                    if($_SESSION['compte']==1){

                                                                                        echo  "<br /> Type paiement : " ;

                                                                                        $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$versement['idCompte'];

                                                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());

                                                                                        $cpt = mysql_fetch_array($resPay2);

                                                                                        $retVal = ($cpt['nomCompte']) ? $cpt['nomCompte'] : '----' ;

                                                                                        echo $retVal;

                                                                                    }

                                                                                    else{

                                                                                        echo '<br /> Type paiement : Espèces';

                                                                                    }

                                                                                }

                                                                            ?>

                                                                        </td>

                                                                        <td>

                                                                            <?php

                                                                                if($_SESSION['depotAvoir']==1 && $client['avoir']==1){

                                                                                    echo  ($versement['montantAvoir'] * $_SESSION['devise']).' '.$_SESSION['symbole'];

                                                                                }

                                                                                else{

                                                                                    echo  ($versement['montant'] * $_SESSION['devise']).' '.$_SESSION['symbole'];

                                                                                } 

                                                                             ?>

                                                                        </td>

                                                                        <td>

                                                                            <?php echo $versement['dateVersement'];    ?>

                                                                        </td>
                                                                        <td>
                                                                            <?php if($versement['image']!=null && $versement['image']!=' '){
                                                                                $format=substr($versement['image'], -3); ?>
                                                                                <?php if($format=='pdf'){ ?>
                                                                                    <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvVersement".$versement['idVersement'] ; ?> onclick="imageUpVersement(<?php echo $versement['idVersement']; ?>,<?php echo $versement['image']; ?>)" 	 />
                                                                                <?php }
                                                                                    else { 
                                                                                ?>
                                                                                    <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvVersement".$versement['idVersement'] ; ?> onclick="imageUpVersement(<?php echo $versement['idVersement']; ?>,<?php echo $versement['image']; ?>)" 	 />
                                                                                <?php } ?>
                                                                            <?php }
                                                                                else { 
                                                                            ?>
                                                                                <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" <?php echo "data-target=#imageNvVersement".$versement['idVersement'] ; ?> onclick="imageUpVersement(<?php echo $versement['idVersement']; ?>,<?php echo $versement['image']; ?>)" 	 />
                                                                            <?php } ?>
                                                                        </td>

                                                                        <td>

                                                                            <button type="button" class="btn btn-warning pull-right"  onclick="modifierVersement(<?php echo $versement['idVersement']; ?>)" >

                                                                                    <span class="glyphicon glyphicon-pencil"></span>

                                                                            </button>

                                                                        </td>

                                                                    </tr>

                                                                    <tr style="display:none" id="nouveauV<?php echo  $versement['idVersement'] ; ?>">

                                                                        <form class="form-inline noImpr" method="post" >

                                                                            <td><textarea  type="textarea" class="form-control" name="description" placeholder="Description" ><?php echo $versement['paiement'];?></textarea></td> 

                                                                            <td>

                                                                                <?php

                                                                                    if($_SESSION['depotAvoir']==1 && $client['avoir']==1){?>

                                                                                            <input type="number" class="form-control" value="<?php echo $versement['montantAvoir'];?>" name="montantAvoir" placeholder="Montant" required="">

                                                                                        <?php

                                                                                    }

                                                                                    else{?>

                                                                                        <input type="number" class="form-control" value="<?php echo $versement['montant'];?>" name="montant" placeholder="Montant" required="">

                                                                                        <?php

                                                                                    } 

                                                                                ?>

                                                                            </td>

                                                                            <td>

                                                                                <input type="text" size='11' maxLength='10' value="<?php echo $versement['dateVersement'];?>" onKeyUp="masqueSaisieDate(this.form.dateVersement)" class="form-control"  name="dateVersement" placeholder="jj-mm-aaaa" required="">

                                                                            </td>

                                                                            <td>

                                                                                <input type="hidden" name="idVersement" <?php echo  "value=".$versement['idVersement']."" ; ?> >

                                                                                <button type="submit" class="btn btn-success pull-right" name="btnModifierVersement">

                                                                                        <span class="glyphicon glyphicon-ok"></span>

                                                                                </button>

                                                                            </td>

                                                                        </form>

                                                                    </tr>

                                                                </tbody>

                                                            </table>

                                                          </div> 

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="modal fade" <?php echo  "id=imageNvVersement".$versement['idVersement'] ; ?> role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header" style="padding:35px 50px;">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Versement : <b>#<?php echo  $versement['idVersement'] ; ?></b></h4>
                                                        </div>
                                                        <form   method="post" enctype="multipart/form-data">
                                                            <div class="modal-body" style="padding:40px 50px;">
                                                                <input  type="text" style="display:none" name="idVersement" id="idVersement_Upd_Nv" <?php echo  "value=".$versement['idVersement']."" ; ?> />
                                                                <div class="form-group" style="text-align:center;" >
                                                                    <?php 
                                                                        if($versement['image']!=null && $versement['image']!=' '){ 
                                                                            $format=substr($versement['image'], -3);
                                                                            ?>
                                                                                <input type="file" name="file" value="<?php echo  $versement['image']; ?>" accept="image/*" id="input_file_Versement<?php echo  $versement['idVersement']; ?>" onchange="showPreviewVersement(event,<?php echo  $versement['idVersement']; ?>);"/><br />
                                                                                <?php if($format=='pdf'){ ?>
                                                                                    <iframe id="output_pdf_Versement<?php echo  $versement['idVersement']; ?>" src="./PiecesJointes/<?php echo  $versement['image']; ?>" width="100%" height="500px"></iframe>
                                                                                    <img style="display:none;" width="500px" height="500px" id="output_image_Versement<?php echo  $versement['idVersement'];  ?>"/>
                                                                                <?php }
                                                                                else { ?>
                                                                                    <img  src="./PiecesJointes/<?php echo  $versement['image']; ?>" width="500px" height="500px" id="output_image_Versement<?php echo  $versement['idVersement']; ?>"/>
                                                                                    <iframe id="output_pdf_Versement<?php echo  $versement['idVersement'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                                <?php } ?>
                                                                            <?php 
                                                                        }
                                                                        else{ ?>
                                                                            <input type="file" name="file" accept="image/*" id="input_file_Versement<?php echo  $versement['idVersement']; ?>" id="cover_image" onchange="showPreviewVersement(event,<?php echo  $versement['idVersement']; ?>);"/><br />
                                                                            <img  style="display:none;" width="500px" height="500px" id="output_image_Versement<?php echo  $versement['idVersement']; ?>"/>
                                                                            <iframe id="output_pdf_Versement<?php echo  $versement['idVersement'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                        <?php
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                    <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgVersement" id="btn_upload_Versement<?php echo  $versement['idVersement']; ?>" >
                                                                        <span class="glyphicon glyphicon-upload"></span> Enregistrer
                                                                    </button>
                                                                    <?php if($versement['image']!=null && $versement['image']!=' '){ ?>
                                                                        <!-- <button type="submit" class="btn btn-primary pull-right" name="btnDownloadImg" >
                                                                        <span class="glyphicon glyphicon-download"></span> Telecharger
                                                                    </button> -->
                                                                    <?php } ?>
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

                        <?php if($nbPaniers >= 11){

                            if(isset($_GET['debut']) && isset($_GET['fin'])){ ?>

                            <ul class="pagination pull-right">

                                <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->

                                <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">

                                    <a href="bonPclient.php?c=<?= $idClient; ?>&&debut=<?= $dateDebut?>&&fin=<?= $dateFin?>&&page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>

                                </li>

                                <?php for($page = 1; $page <= $pages; $page++): ?>

                                    <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->

                                    <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">

                                        <a href="bonPclient.php?c=<?= $idClient; ?>&&debut=<?= $dateDebut?>&&fin=<?= $dateFin?>&&page=<?= $page ?>" class="page-link"><?= $page ?></a>

                                    </li>

                                <?php endfor ?>

                                    <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->

                                    <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">

                                    <a href="bonPclient.php?c=<?= $idClient; ?>&&debut=<?= $dateDebut?>&&fin=<?= $dateFin?>&&page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>

                                </li>

                            </ul>

                            <?php 

                                }

                                else{ ?>

                                    <ul class="pagination pull-right">

                                        <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->

                                        <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">

                                            <a href="bonPclient.php?c=<?= $idClient; ?>&&page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>

                                        </li>

                                        <?php for($page = 1; $page <= $pages; $page++): ?>

                                            <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->

                                            <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">

                                                <a href="bonPclient.php?c=<?= $idClient; ?>&&page=<?= $page ?>" class="page-link"><?= $page ?></a>

                                            </li>

                                        <?php endfor ?>

                                            <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->

                                            <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">

                                            <a href="bonPclient.php?c=<?= $idClient; ?>&&page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>

                                        </li>

                                    </ul>

                            <?php }

                            } 

                        ?>

                    <!-- Fin Boucle while concernant les Paniers Vendus  -->



                </div>

            <!-- Fin de l'Accordion pour Tout les Paniers -->

        <?php } ?>



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

            

            <div id="msg_info_TerminerImputation" class="modal fade " role="dialog">

                <div class="modal-dialog">

                    <!-- Modal content-->

                    <div class="modal-content">

                        <div class="modal-header panel-primary">

                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                            <h4 class="modal-title">Alerte</h4>

                        </div>

                        <div class="modal-body">

                            <p id="p_msg_info_TerminerImputation">

                            </p>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

                        </div>

                    </div>

                </div>

            </div>



        <!-- Debut PopUp d'Alerte sur le retard de Paiement -->

        <!-- Debut Message d'Alerte concernant avance > apayerpagnet -->

        <div id="msg_info_avance" class="modal fade " role="dialog">

                    <div class="modal-dialog">

                        <!-- Modal content-->

                        <div class="modal-content">

                            <div class="modal-header panel-primary">

                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                <h4 class="modal-title">Alerte</h4>

                            </div>

                            <div class="modal-body">

                                <p> ATTENTION!</br>

                                Le montant de l'avance est suppérieur au montant du panier. <br>

                                Cette avance ne sera pas enregistrée si vous terminez le panier.

                                </p>

                            </div>

                            <div class="modal-footer">

                                <button type="button" id="closeAvance" class="btn btn-primary" data-dismiss="modal">Close</button>

                            </div>

                        </div>

                    </div>

            </div>

        <!-- Fin Message d'Alerte concernant avance > apayerpagnet -->

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



    </div>

    <!-- Fin Container HTML -->

</header>

</body>

<!-- Fin Code HTML -->



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



