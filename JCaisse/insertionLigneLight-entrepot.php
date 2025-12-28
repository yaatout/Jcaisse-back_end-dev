<?php



function find_p_with_position($pns, $des)
{
    foreach ($pns as $index => $p) {
        if (($p['idDesignation'] == $des)) {
            return $index;
        }
    }
    return FALSE;
}


$sql = "SELECT * from `aaa-utilisateur` where idutilisateur='" . $_SESSION['iduser'] . "' ";

$resU = mysql_query($sql);

$userAcces = mysql_fetch_array($resU);


if ($_SESSION['compte'] == 1) {

    $sqlGetComptePay = "SELECT * FROM `" . $nomtableCompte . "` where idCompte<>3 && typeCompte<>'Caisse Transfert' ORDER BY idCompte";

    $resPay = mysql_query($sqlGetComptePay) or die("persoonel requête 2" . mysql_error());

    $sqlGetComptePay2 = "SELECT * FROM `" . $nomtableCompte . "` where idCompte<>2 && idCompte<>3 && idCompte<>1000 && typeCompte<>'Caisse Transfert' ORDER BY idCompte";

    $resPay2 = mysql_query($sqlGetComptePay2) or die("persoonel requête 2" . mysql_error());
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

/**Debut Button Ajouter Pagnet**/

if (isset($_POST['btnSavePagnetVente'])) {



    $paieMois = $annee - $mois;

    $sql0 = "SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='" . $_SESSION['idBoutique'] . "' and YEAR(datePs)='" . $annee_paie . "' and MONTH(datePs)!='" . $mois . "' and aPayementBoutique=0 ";

    $res0 = mysql_query($sql0);

    if (mysql_num_rows($res0)) {

        if ($jour > 4) {

            $paie = mysql_fetch_array($res0);

            $periode_paie = explode("-", $paie['datePS']);

            $mois_periode = $periode_paie[1];
            $annee_periode = $periode_paie[0];

            $periode = calendrierMois($mois_periode);

            $msg_paiement = "<b><span style='color:red;'>VOUS NE POUVEZ PAS CREER UN PANIER.</span></b></br></br>

                <span style='color:green;'>VEUILLEZ CONTACTER LE PROPRIETAIRE POUR VERIFIER SI LE PAIEMENT DU MOIS DE </span><span style='color:red;'>" . $periode . " " . $annee_periode . "</span><span style='color:green;'> DE LA PLATEFORME JCAISSE EST EFFECTUE.</span>";
        } else {

            $sql1 = "select * from `" . $nomtableJournal . "` where annee=" . $annee . " and mois=" . $mois;

            $res1 = mysql_query($sql1);



            $query = mysql_query("SELECT * FROM `" . $nomtablePagnet . "` where iduser='" . $_SESSION['iduser'] . "' && idClient=0 && datepagej ='" . $dateString2 . "' && verrouiller=0");

            $nbre_fois = mysql_num_rows($query);



            if (mysql_num_rows($res1)) {

                $sql2 = "select * from `" . $nomtablePage . "` where datepage='" . $dateString . "'";

                $res2 = mysql_query($sql2);

                if (mysql_num_rows($res2)) {

                    if ($nbre_fois < 2) {

                        $sql6 = "insert into `" . $nomtablePagnet . "` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('" . $dateString2 . "','" . $heureString . "'," . $_SESSION['iduser'] . ",0,0,0,0,0,0,0,0)";

                        $res6 = mysql_query($sql6) or die("insertion pagnier impossible =>" . mysql_error());
                    }
                } else {

                    $sql0 = "insert into `" . $nomtablePage . "` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('" . $dateString . "','" . $heureString . "'," . $_SESSION['iduser'] . ",0,0,0,0,0,0)";

                    $res0 = @mysql_query($sql0) or die("insertion page journal impossible-1" . mysql_error());



                    if ($nbre_fois < 2) {

                        $sql6 = "insert into `" . $nomtablePagnet . "` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('" . $dateString2 . "','" . $heureString . "'," . $_SESSION['iduser'] . ",0,0,0,0,0,0,0,0)";

                        $res6 = mysql_query($sql6) or die("insertion pagnier impossible =>" . mysql_error());
                    }
                }
            } else {

                $sql0 = "insert into `" . $nomtableJournal . "` (mois,annee,totalVente,totalVersement,totalBon,totalFrais) values(" . $mois . "," . $annee . ",0,0,0,0)";

                $res0 = @mysql_query($sql0) or die("insertion journal impossible-1" . mysql_error());



                $sql2 = "insert into `" . $nomtablePage . "` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('" . $dateString . "','" . $heureString . "'," . $_SESSION['iduser'] . ",0,0,0,0,0,0)";

                $res2 = @mysql_query($sql2) or die("insertion page journal impossible-2" . mysql_error());



                if ($nbre_fois < 2) {

                    $sql6 = "insert into `" . $nomtablePagnet . "` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('" . $dateString2 . "','" . $heureString . "'," . $_SESSION['iduser'] . ",0,0,0,0,0,0,0,0)";

                    $res6 = mysql_query($sql6) or die("insertion pagnier impossible =>" . mysql_error());
                }
            }
        }
    } else {

        $sql1 = "select * from `" . $nomtableJournal . "` where annee=" . $annee . " and mois=" . $mois;

        $res1 = mysql_query($sql1);



        $query = mysql_query("SELECT * FROM `" . $nomtablePagnet . "` where iduser='" . $_SESSION['iduser'] . "' && idClient=0 && datepagej ='" . $dateString2 . "' && verrouiller=0");

        $nbre_fois = mysql_num_rows($query);



        if (mysql_num_rows($res1)) {

            $sql2 = "select * from `" . $nomtablePage . "` where datepage='" . $dateString . "'";

            $res2 = mysql_query($sql2);

            if (mysql_num_rows($res2)) {

                if ($nbre_fois < 2) {

                    $sql6 = "insert into `" . $nomtablePagnet . "` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('" . $dateString2 . "','" . $heureString . "'," . $_SESSION['iduser'] . ",0,0,0,0,0,0,0,0)";

                    $res6 = mysql_query($sql6) or die("insertion pagnier impossible =>" . mysql_error());
                }
            } else {

                $sql0 = "insert into `" . $nomtablePage . "` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('" . $dateString . "','" . $heureString . "'," . $_SESSION['iduser'] . ",0,0,0,0,0,0)";

                $res0 = @mysql_query($sql0) or die("insertion page journal impossible-1" . mysql_error());



                if ($nbre_fois < 2) {

                    $sql6 = "insert into `" . $nomtablePagnet . "` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('" . $dateString2 . "','" . $heureString . "'," . $_SESSION['iduser'] . ",0,0,0,0,0,0,0,0)";

                    $res6 = mysql_query($sql6) or die("insertion pagnier impossible =>" . mysql_error());
                }
            }
        } else {

            $sql0 = "insert into `" . $nomtableJournal . "` (mois,annee,totalVente,totalVersement,totalBon,totalFrais) values(" . $mois . "," . $annee . ",0,0,0,0)";

            $res0 = @mysql_query($sql0) or die("insertion journal impossible-1" . mysql_error());



            $sql2 = "insert into `" . $nomtablePage . "` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('" . $dateString . "','" . $heureString . "'," . $_SESSION['iduser'] . ",0,0,0,0,0,0)";

            $res2 = @mysql_query($sql2) or die("insertion page journal impossible-2" . mysql_error());



            if ($nbre_fois < 2) {

                $sql6 = "insert into `" . $nomtablePagnet . "` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('" . $dateString2 . "','" . $heureString . "'," . $_SESSION['iduser'] . ",0,0,0,0,0,0,0,0)";

                $res6 = mysql_query($sql6) or die("insertion pagnier impossible =>" . mysql_error());
            }
        }
    }
}

/**Fin Button Ajouter Pagnet**/


/*******Debut Imprimer proforma******/

if (isset($_POST['btnImprimerProforma'])) {

    $idPagnet = @$_POST['idPagnet'];
    $nomClient = @$_POST['clientProforma'];

    $sql = "SELECT * FROM `" . $nomtablePagnet . "` where idPagnet=" . $idPagnet . " ";

    $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

    $pagnet = mysql_fetch_assoc($res);

    $sqlT = "SELECT SUM(prixtotal) FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

    $resT = mysql_query($sqlT) or die("persoonel requête 2" . mysql_error());

    $TotalP = mysql_fetch_array($resT);

    $sqlTD = "SELECT SUM(prixtotal) FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " && unitevente='Transaction' && quantite=0 ";

    $resTD = mysql_query($sqlTD) or die("persoonel requête 2" . mysql_error());

    $TotalTD = mysql_fetch_array($resTD);

    //$Total[ = $TotalP[0] - $TotalTD[0];

    $totalp = $TotalP[0] - ($TotalTD[0] * 2);

    // var_dump($totalp);

    if (@$_POST['remise'] == '') {

        $remise = 0;
    } else {

        $remise = @$_POST['remise'];
    }

    $apayerPagnet = $totalp - $remise;

    if (@$_POST['versement'] == '') {

        $versement = 0;

        $monaie = 0;
    } else {
        $versement = @$_POST['versement'];

        $monaie = $versement - $apayerPagnet;
    }

    $sql3 = "UPDATE `" . $nomtablePagnet . "` set verrouiller='1', remise=" . $remise . ",totalp=" . $totalp . ",apayerPagnet=" . $apayerPagnet . ",versement=" . $versement . ",restourne=" . $monaie . ",nomClient='" . $nomClient . "' where idPagnet=" . $idPagnet;

    $res3 = @mysql_query($sql3) or die("mise à jour verouillage  impossible" . mysql_error());
}

/*******Fin Imprimer proforma******/


/**Debut Button Ajouter Ligne dans un pagnet**/

if (isset($_POST['btnEnregistrerCodeBarre'])) {



    $resultat = " =ko" + htmlspecialchars($_POST['codeBarre']);



    if (isset($_POST['codeBarre']) && isset($_POST['idPagnet'])) {

        $codeBarre = htmlspecialchars(trim($_POST['codeBarre']));

        $idPagnet = htmlspecialchars(trim($_POST['idPagnet']));

        $codeBrute = explode('-', $codeBarre);



        $sql = "SELECT * FROM `" . $nomtablePagnet . "` where idPagnet=" . $idPagnet . " ";

        $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

        $pagnet = mysql_fetch_assoc($res);



        if (!empty($codeBrute[1]) && is_int($codeBrute[0])) {
        }

        /**Debut de la Vente des Produits sur la Designation */

        else {

            $sql = "SELECT * FROM `" . $nomtableDesignation . "` where (codeBarreDesignation='" . $codeBarre . "' or codeBarreuniteStock='" . $codeBarre . "' or designation='" . $codeBarre . "') ";

            $res = mysql_query($sql) or die("select stock impossible =>" . mysql_error());

            if (mysql_num_rows($res)) {

                $design = mysql_fetch_assoc($res);

                if ($design['codeBarreDesignation'] == $codeBarre) {

                    if ($pagnet['type'] == 0 || $pagnet['type'] == 30) {

                        $sql_t = "SELECT SUM(quantiteStockCourant) FROM `" . $nomtableStock . "` where idDesignation='" . $design['idDesignation'] . "' ";

                        $res_t = mysql_query($sql_t) or die("select stock impossible =>" . mysql_error());

                        $t_stock = mysql_fetch_array($res_t);

                        $restant = $t_stock[0];

                        if ($restant > 0) {

                            /***Debut verifier si la ligne du produit existe deja ***/

                            $sql = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet='" . $idPagnet . "' and idDesignation='" . $design['idDesignation'] . "' and classe=0 ";

                            $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

                            $ligne = mysql_fetch_assoc($res);

                            /***Debut verifier si la ligne du produit existe deja ***/

                            if ($ligne != null) {

                                $reqP = "SELECT * from  `" . $nomtableEntrepotStock . "` 

                                        where idDesignation='" . $design['idDesignation'] . "' AND quantiteStockCourant>0 AND idEntrepot!='" . $ligne['idEntrepot'] . "' ";

                                $resP = mysql_query($reqP) or die("select stock impossible =>" . mysql_error());

                                $entrepot = mysql_fetch_assoc($resP);

                                if (mysql_num_rows($resP)) {

                                    /***Debut verifier si la ligne du produit existe deja ***/

                                    $sql1 = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet='" . $idPagnet . "' and idDesignation='" . $design['idDesignation'] . "' AND idEntrepot='" . $entrepot['idEntrepot'] . "'  and classe=0 ";

                                    $res1 = mysql_query($sql1) or die("persoonel requête 2" . mysql_error());

                                    $ligne1 = mysql_fetch_assoc($res1);

                                    /***Debut verifier si la ligne du produit existe deja ***/

                                    if ($ligne1 != null) {
                                    } else {

                                        $reqP1 = "SELECT * from  `" . $nomtableEntrepotStock . "` 

                                                where idDesignation='" . $design['idDesignation'] . "' AND quantiteStockCourant>0 AND idEntrepot!='" . $ligne['idEntrepot'] . "' AND idEntrepot!='" . $ligne1['idEntrepot'] . "'  ";

                                        $resP1 = mysql_query($reqP1) or die("select stock impossible =>" . mysql_error());

                                        $produit = mysql_fetch_assoc($resP1);

                                        if (mysql_num_rows($resP1)) {

                                            if ($ligne['classe'] == 0) {

                                                $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "',0,'" . $design['idDesignation'] . "','" . $produit['idEntrepot'] . "','" . $design['uniteStock'] . "'," . $design['prixuniteStock'] . ",1," . $design['prixuniteStock'] . "," . $idPagnet . ",0)";

                                                $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                            }
                                        } else {
                                        }
                                    }
                                } else {
                                }
                            } else {

                                $sqlN = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

                                $resN = mysql_query($sqlN) or die("persoonel requête 2" . mysql_error());

                                $ligneN = mysql_fetch_assoc($resN);

                                if ($_SESSION['entrepot'] != 0 || $_SESSION['entrepot'] != null) {

                                    $reqP = "SELECT * from  `" . $nomtableEntrepotStock . "` 

                                            where idDesignation='" . $design['idDesignation'] . "' AND quantiteStockCourant>0  AND idEntrepot='" . $_SESSION['entrepot'] . "' ";

                                    $resP = mysql_query($reqP) or die("select stock impossible =>" . mysql_error());

                                    if (mysql_num_rows($resP)) {

                                        $entrepot = mysql_fetch_assoc($resP);

                                        if ($ligneN['classe'] == 0 || $ligneN['classe'] == 1) {

                                            $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "',0,'" . $design['idDesignation'] . "','" . $entrepot['idEntrepot'] . "','" . $design['uniteStock'] . "'," . $design['prixuniteStock'] . ",1," . $design['prixuniteStock'] . "," . $idPagnet . ",0)";

                                            $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                        }
                                    } else {

                                        $reqP1 = "SELECT * from  `" . $nomtableEntrepotStock . "` 

                                                where idDesignation='" . $design['idDesignation'] . "' AND quantiteStockCourant>0 ORDER BY quantiteStockCourant DESC LIMIT 0,1 ";

                                        $resP1 = mysql_query($reqP1) or die("select stock impossible =>" . mysql_error());

                                        $depot = mysql_fetch_assoc($resP1);

                                        $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "',0,'" . $design['idDesignation'] . "','" . $depot['idEntrepot'] . "','" . $design['uniteStock'] . "'," . $design['prixuniteStock'] . ",1," . $design['prixuniteStock'] . "," . $idPagnet . ",0)";

                                        $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                    }
                                } else {

                                    $reqP = "SELECT * from  `" . $nomtableEntrepotStock . "` 

                                            where idDesignation='" . $design['idDesignation'] . "' AND quantiteStockCourant>0 ORDER BY quantiteStockCourant DESC LIMIT 0,1 ";

                                    $resP = mysql_query($reqP) or die("select stock impossible =>" . mysql_error());

                                    $entrepot = mysql_fetch_assoc($resP);

                                    if (mysql_num_rows($res)) {

                                        if ($ligneN['classe'] == 0 || $ligneN['classe'] == 1) {

                                            $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "',0,'" . $design['idDesignation'] . "','" . $entrepot['idEntrepot'] . "','" . $design['uniteStock'] . "'," . $design['prixuniteStock'] . ",1," . $design['prixuniteStock'] . "," . $idPagnet . ",0)";

                                            $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                        }
                                    } else {

                                        $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "',0,'" . $design['idDesignation'] . "','" . $entrepot['idEntrepot'] . "','" . $design['uniteStock'] . "'," . $design['prixuniteStock'] . ",1," . $design['prixuniteStock'] . "," . $idPagnet . ",0)";

                                        $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                    }
                                }
                            }
                        } else {

                            $msg_info = "<b>LE CODE-BARRE SAISIE CORRESPOND A UN STOCK FINI (TERMINER).</b></br></br>

                                    VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ....";
                        }
                    } else {

                        /***Debut verifier si la ligne du produit existe deja ***/

                        $sql = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet='" . $idPagnet . "' and designation='" . $design['designation'] . "' and (unitevente='Article' or  unitevente='article') and classe=0 ";

                        $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

                        $ligne1 = mysql_fetch_assoc($res);

                        /***Debut verifier si la ligne du produit existe deja ***/

                        if ($ligne1 != null) {

                            $quantite = $ligne1['quantite'] + 1;

                            $prixTotal = $quantite * $ligne1['prixunitevente'];

                            $sql7 = "UPDATE `" . $nomtableLigne . "` set quantite='" . $quantite . "',prixtotal=" . $prixTotal . " where idPagnet='" . $idPagnet . "' and idDesignation='" . $design['idDesignation'] . "' and (unitevente='Article' or  unitevente='article') and classe=0 ";

                            $res7 = mysql_query($sql7) or die("update quantiteStockCourant impossible =>" . mysql_error());
                        } else {

                            $sql = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

                            $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

                            $ligne = mysql_fetch_assoc($res);

                            if (mysql_num_rows($res)) {

                                if ($ligne['classe'] == 0) {

                                    $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "',0,'" . $design['idDesignation'] . "','article'," . $design['prix'] . ",1," . $design['prix'] . "," . $idPagnet . ",0)";

                                    $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                }
                            } else {

                                $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "',0,'" . $design['idDesignation'] . "','article'," . $design['prix'] . ",1," . $design['prix'] . "," . $idPagnet . ",0)";

                                $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                            }
                        }
                    }
                }

                if ($design['codeBarreuniteStock'] == $codeBarre) {
                }

                if ($design['designation'] == $codeBarre) {

                    if ($design['classe'] == 0) {

                        if ($pagnet['type'] == 0) {

                            $sql_t = "SELECT SUM(quantiteStockCourant) FROM `" . $nomtableStock . "` where idDesignation='" . $design['idDesignation'] . "' ";

                            $res_t = mysql_query($sql_t) or die("select stock impossible =>" . mysql_error());

                            $t_stock = mysql_fetch_array($res_t);

                            $restant = $t_stock[0];

                            if ($restant > 0) {

                                /***Debut verifier si la ligne du produit existe deja ***/

                                $sql = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet='" . $idPagnet . "' and idDesignation='" . $design['idDesignation'] . "' and classe=0 ";

                                $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

                                $ligne = mysql_fetch_assoc($res);

                                /***Debut verifier si la ligne du produit existe deja ***/

                                if ($ligne != null) {

                                    $reqP = "SELECT * from  `" . $nomtableEntrepotStock . "` 

                                            where idDesignation='" . $design['idDesignation'] . "' AND quantiteStockCourant>0 AND idEntrepot!='" . $ligne['idEntrepot'] . "' ";

                                    $resP = mysql_query($reqP) or die("select stock impossible =>" . mysql_error());

                                    $entrepot = mysql_fetch_assoc($resP);

                                    if (mysql_num_rows($resP)) {

                                        /***Debut verifier si la ligne du produit existe deja ***/

                                        $sql1 = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet='" . $idPagnet . "' and idDesignation='" . $design['idDesignation'] . "' AND idEntrepot='" . $entrepot['idEntrepot'] . "'  and classe=0 ";

                                        $res1 = mysql_query($sql1) or die("persoonel requête 2" . mysql_error());

                                        $ligne1 = mysql_fetch_assoc($res1);

                                        /***Debut verifier si la ligne du produit existe deja ***/

                                        if ($ligne1 != null) {
                                        } else {

                                            $reqP1 = "SELECT * from  `" . $nomtableEntrepotStock . "` 

                                                    where idDesignation='" . $design['idDesignation'] . "' AND quantiteStockCourant>0 AND idEntrepot!='" . $ligne['idEntrepot'] . "' AND idEntrepot!='" . $ligne1['idEntrepot'] . "'  ";

                                            $resP1 = mysql_query($reqP1) or die("select stock impossible =>" . mysql_error());

                                            $produit = mysql_fetch_assoc($resP1);

                                            if (mysql_num_rows($resP1)) {

                                                if ($ligne['classe'] == 0) {

                                                    $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "',0,'" . $design['idDesignation'] . "','" . $produit['idEntrepot'] . "','" . $design['uniteStock'] . "'," . $design['prixuniteStock'] . ",1," . $design['prixuniteStock'] . "," . $idPagnet . ",0)";

                                                    $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                                }
                                            } else {
                                            }
                                        }
                                    } else {
                                    }
                                } else {

                                    $sqlN = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

                                    $resN = mysql_query($sqlN) or die("persoonel requête 2" . mysql_error());

                                    $ligneN = mysql_fetch_assoc($resN);

                                    if ($_SESSION['entrepot'] != 0 || $_SESSION['entrepot'] != null) {

                                        $reqP = "SELECT * from  `" . $nomtableEntrepotStock . "` 

                                                where idDesignation='" . $design['idDesignation'] . "' AND quantiteStockCourant>0  AND idEntrepot='" . $_SESSION['entrepot'] . "' ";

                                        $resP = mysql_query($reqP) or die("select stock impossible =>" . mysql_error());

                                        if (mysql_num_rows($resP)) {

                                            $entrepot = mysql_fetch_assoc($resP);

                                            if ($ligneN['classe'] == 0 || $ligneN['classe'] == 1) {

                                                $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "',0,'" . $design['idDesignation'] . "','" . $entrepot['idEntrepot'] . "','" . $design['uniteStock'] . "'," . $design['prixuniteStock'] . ",1," . $design['prixuniteStock'] . "," . $idPagnet . ",0)";

                                                $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                            }
                                        } else {

                                            $reqP1 = "SELECT * from  `" . $nomtableEntrepotStock . "` 

                                                    where idDesignation='" . $design['idDesignation'] . "' AND quantiteStockCourant>0 ORDER BY quantiteStockCourant DESC LIMIT 0,1 ";

                                            $resP1 = mysql_query($reqP1) or die("select stock impossible =>" . mysql_error());

                                            $depot = mysql_fetch_assoc($resP1);

                                            $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "',0,'" . $design['idDesignation'] . "','" . $depot['idEntrepot'] . "','" . $design['uniteStock'] . "'," . $design['prixuniteStock'] . ",1," . $design['prixuniteStock'] . "," . $idPagnet . ",0)";

                                            $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                        }
                                    } else {

                                        $reqP = "SELECT * from  `" . $nomtableEntrepotStock . "` 

                                                where idDesignation='" . $design['idDesignation'] . "' AND quantiteStockCourant>0 ORDER BY quantiteStockCourant DESC LIMIT 0,1 ";

                                        $resP = mysql_query($reqP) or die("select stock impossible =>" . mysql_error());

                                        $entrepot = mysql_fetch_assoc($resP);

                                        if (mysql_num_rows($res)) {

                                            if ($ligneN['classe'] == 0 || $ligneN['classe'] == 1) {

                                                $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "',0,'" . $design['idDesignation'] . "','" . $entrepot['idEntrepot'] . "','" . $design['uniteStock'] . "'," . $design['prixuniteStock'] . ",1," . $design['prixuniteStock'] . "," . $idPagnet . ",0)";

                                                $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                            }
                                        } else {

                                            $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "',0,'" . $design['idDesignation'] . "','" . $entrepot['idEntrepot'] . "','" . $design['uniteStock'] . "'," . $design['prixuniteStock'] . ",1," . $design['prixuniteStock'] . "," . $idPagnet . ",0)";

                                            $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                        }
                                    }
                                }
                            } else {

                                $msg_info = "<b>LE CODE-BARRE SAISIE CORRESPOND A UN STOCK FINI (TERMINER).</b></br></br>

                                        VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ....";
                            }
                        } else {

                            /***Debut verifier si la ligne du produit existe deja ***/

                            $sql = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet='" . $idPagnet . "' and designation='" . $design['designation'] . "' and (unitevente='Article' or  unitevente='article') and classe=0 ";

                            $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

                            $ligne1 = mysql_fetch_assoc($res);

                            /***Debut verifier si la ligne du produit existe deja ***/

                            if ($ligne1 != null) {

                                $quantite = $ligne1['quantite'] + 1;

                                $prixTotal = $quantite * $ligne1['prixunitevente'];

                                $sql7 = "UPDATE `" . $nomtableLigne . "` set quantite='" . $quantite . "',prixtotal=" . $prixTotal . " where idPagnet='" . $idPagnet . "' and idDesignation='" . $design['idDesignation'] . "' and (unitevente='Article' or  unitevente='article') and classe=0 ";

                                $res7 = mysql_query($sql7) or die("update quantiteStockCourant impossible =>" . mysql_error());
                            } else {

                                $sql = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

                                $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

                                $ligne = mysql_fetch_assoc($res);

                                if (mysql_num_rows($res)) {

                                    if ($ligne['classe'] == 0) {

                                        $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "',0,'" . $design['idDesignation'] . "',0,'" . $design['uniteStock'] . "'," . $design['prixuniteStock'] . ",1," . $design['prixuniteStock'] . "," . $idPagnet . ",0)";

                                        $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                    }
                                } else {

                                    $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "',0,'" . $design['idDesignation'] . "',0,'" . $design['uniteStock'] . "'," . $design['prixuniteStock'] . ",1," . $design['prixuniteStock'] . "," . $idPagnet . ",0)";

                                    $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                }
                            }
                        }
                    }

                    if ($design['classe'] == 1 && $pagnet['type'] == 0) {

                        $sql = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

                        $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

                        $ligne = mysql_fetch_assoc($res);

                        if (mysql_num_rows($res)) {

                            /***Debut verifier si la ligne du produit existe deja ***/

                            $sql = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet='" . $idPagnet . "' and idDesignation='" . $design['idDesignation'] . "' and classe=1  ";

                            $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

                            $ligne1 = mysql_fetch_assoc($res);

                            /***Debut verifier si la ligne du produit existe deja ***/

                            if ($ligne1 != null) {

                                $quantite = $ligne1['quantite'] + 1;

                                $prixTotal = $quantite * $ligne1['prixunitevente'];

                                $sql7 = "UPDATE `" . $nomtableLigne . "` set quantite='" . $quantite . "',prixtotal=" . $prixTotal . " where idPagnet='" . $idPagnet . "' and idDesignation='" . $design['idDesignation'] . "' and classe=1  ";

                                $res7 = mysql_query($sql7) or die("update quantiteStockCourant impossible =>" . mysql_error());
                            } else {

                                if ($ligne['classe'] == 1) {

                                    if ($design['uniteStock'] != 'Transaction') {

                                        $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "','" . $design['idDesignation'] . "','" . $design['idDesignation'] . "','" . $design['uniteStock'] . "'," . $design['prix'] . ",1," . $design['prix'] . "," . $idPagnet . ",1)";

                                        $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                    }
                                }
                            }
                        } else {

                            if ($design['uniteStock'] == 'Transaction') {

                                $reqT = "SELECT * from `aaa-transaction` where idTransaction='" . $design['description'] . "'";

                                $resT = mysql_query($reqT) or die("select stock impossible =>" . mysql_error());

                                $transaction = mysql_fetch_assoc($resT);

                                $image = $transaction['aliasTransaction'];

                                $trans_alias = $transaction['aliasTransaction'];

                                $trans_pagnet = $idPagnet;

                                $msg_transaction = "OK";
                            } else if ($design['uniteStock'] == 'Credit') {

                                $reqT = "SELECT * from `aaa-transaction` where idTransaction='" . $design['description'] . "'";

                                $resT = mysql_query($reqT) or die("select stock impossible =>" . mysql_error());

                                $transaction = mysql_fetch_assoc($resT);

                                $image = $transaction['aliasTransaction'];

                                $trans_alias = $transaction['aliasTransaction'];

                                $trans_pagnet = $idPagnet;

                                $msg_credit = "OK";
                            } else {



                                $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "'," . $design['idDesignation'] . "," . $design['idDesignation'] . ",'" . $design['uniteStock'] . "'," . $design['prix'] . ",1," . $design['prix'] . "," . $idPagnet . ",1)";

                                $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                            }
                        }
                    }

                    if ($design['classe'] == 2 && $pagnet['type'] == 0) {

                        $sql = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

                        $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

                        $ligne = mysql_fetch_assoc($res);

                        if (mysql_num_rows($res)) {

                            /***Debut verifier si la ligne du produit existe deja ***/

                            $sql = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet='" . $idPagnet . "' and idStock='" . $design['idDesignation'] . "' and classe=2  ";

                            $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

                            $ligne1 = mysql_fetch_assoc($res);

                            /***Debut verifier si la ligne du produit existe deja ***/

                            if ($ligne1 != null) {

                                $quantite = $ligne1['quantite'] + 1;

                                $prixTotal = $quantite * $ligne1['prixunitevente'];

                                $sql7 = "UPDATE `" . $nomtableLigne . "` set quantite='" . $quantite . "',prixtotal=" . $prixTotal . " where idPagnet='" . $idPagnet . "' and idStock='" . $design['idDesignation'] . "' and classe=2  ";

                                $res7 = mysql_query($sql7) or die("update quantiteStockCourant impossible =>" . mysql_error());
                            } else {

                                if ($ligne['classe'] == 2) {

                                    $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "'," . $design['idDesignation'] . "," . $design['idDesignation'] . ",'" . $design['uniteStock'] . "'," . $design['prix'] . ",1," . $design['prix'] . "," . $idPagnet . ",2)";

                                    $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                }
                            }
                        } else {

                            $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "'," . $design['idDesignation'] . "," . $design['idDesignation'] . ",'" . $design['uniteStock'] . "'," . $design['prix'] . ",1," . $design['prix'] . "," . $idPagnet . ",2)";

                            $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                        }
                    }

                    if ($design['classe'] == 5 && $pagnet['type'] == 0) {

                        $sql = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

                        $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

                        $ligne = mysql_fetch_assoc($res);

                        if (mysql_num_rows($res)) {

                            /***Debut verifier si la ligne du produit existe deja ***/

                            $sql = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet='" . $idPagnet . "' and idDesignation='" . $design['idDesignation'] . "' and classe=5  ";

                            $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

                            $ligne1 = mysql_fetch_assoc($res);

                            /***Debut verifier si la ligne du produit existe deja ***/

                            if ($ligne1 != null) {
                            } else {

                                if ($ligne['classe'] == 5) {

                                    $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "'," . $design['idDesignation'] . "," . $design['idDesignation'] . ",'" . $design['uniteStock'] . "'," . $design['prix'] . ",1," . $design['prix'] . "," . $idPagnet . ",5)";

                                    $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                }
                            }
                        } else {

                            $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "'," . $design['idDesignation'] . "," . $design['idDesignation'] . ",'" . $design['uniteStock'] . "'," . $design['prix'] . ",1," . $design['prix'] . "," . $idPagnet . ",5)";

                            $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                        }
                    }

                    if ($design['classe'] == 7 && $pagnet['type'] == 0) {

                        $sql = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

                        $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

                        $ligne = mysql_fetch_assoc($res);

                        if (mysql_num_rows($res)) {

                            /***Debut verifier si la ligne du produit existe deja ***/

                            $sql = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet='" . $idPagnet . "' and idDesignation='" . $design['idDesignation'] . "' and classe=7  ";

                            $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

                            $ligne1 = mysql_fetch_assoc($res);

                            /***Debut verifier si la ligne du produit existe deja ***/

                            if ($ligne1 != null) {
                            } else {

                                if ($ligne['classe'] == 7) {

                                    $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "'," . $design['idDesignation'] . "," . $design['idDesignation'] . ",'" . $design['uniteStock'] . "'," . $design['prix'] . ",1," . $design['prix'] . "," . $idPagnet . ",7)";

                                    $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                                }
                            }
                        } else {

                            $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $design['designation'] . "'," . $design['idDesignation'] . "," . $design['idDesignation'] . ",'" . $design['uniteStock'] . "'," . $design['prix'] . ",1," . $design['prix'] . "," . $idPagnet . ",7)";

                            $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());
                        }
                    }

                    if ($design['classe'] == 8) {

                        $sql3 = "UPDATE `" . $nomtablePagnet . "` set type='1' where idPagnet=" . $idPagnet;

                        $res3 = @mysql_query($sql3) or die("mise à jour verouillage  impossible" . mysql_error());
                    }

                    if ($design['classe'] == 10) {

                        $sql3 = "UPDATE `" . $nomtablePagnet . "` set type='10' where idPagnet=" . $idPagnet;

                        $res3 = @mysql_query($sql3) or die("mise à jour verouillage  impossible" . mysql_error());
                    }
                }
            } else {

                $msg_info = "<b>LE CODE-BARRE SAISIE NE FIGURE PAS DANS LE STOCK.</b></br></br>

                        VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ...";
            }
        }

        /**Debut de la Vente des Produits sur la Designation */
    }
}

/**Fin Button Ajouter Ligne dans un pagnet**/



/**Debut Button Ajouter Transaction dans un pagnet**/

if (isset($_POST['btnEnregistrerTransaction'])) {

    $reqT = "SELECT * from `aaa-transaction` where aliasTransaction='" . $aliasTrans . "'";

    $resT = mysql_query($reqT) or die("select stock impossible =>" . mysql_error());

    $transaction = mysql_fetch_assoc($resT);



    if (($idFact != null) && ($typeTrans == 2)) {

        $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $transaction['nomTransaction'] . "',0,0,'Facture'," . $montantTrans . "," . $idFact . "," . $montantTrans . "," . $pagnetTrans . ",3)";

        $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());



        $sql3 = "UPDATE `" . $nomtablePagnet . "` set verrouiller='1', totalp=" . $montantTrans . ",apayerPagnet=" . $montantTrans . " where idPagnet=" . $pagnetTrans;

        $res3 = @mysql_query($sql3) or die("mise à jour verouillage  impossible" . mysql_error());
    }

    if (($typeTrans == 0) || ($typeTrans == 1)) {

        $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $transaction['nomTransaction'] . "',0,0,'Transaction'," . $montantTrans . "," . $typeTrans . "," . $montantTrans . "," . $pagnetTrans . ",3)";

        $res7 = mysql_query($sql7) or die("insertion pagnier impossible 7  =>" . mysql_error());



        $sql3 = "UPDATE `" . $nomtablePagnet . "` set verrouiller='1', totalp=" . $montantTrans . ",apayerPagnet=" . $montantTrans . " where idPagnet=" . $pagnetTrans;

        $res3 = @mysql_query($sql3) or die("mise à jour verouillage  impossible" . mysql_error());
    }

    if ($typeTrans == 3) {

        $sql7 = "insert into `" . $nomtableLigne . "` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('" . $transaction['nomTransaction'] . "',0,0,'Credit'," . $montantTrans . "," . $idFact . "," . $montantTrans . "," . $pagnetTrans . ",4)";

        $res7 = mysql_query($sql7) or die("insertion pagnier impossible 8  =>" . mysql_error());



        $sql3 = "UPDATE `" . $nomtablePagnet . "` set verrouiller='1', totalp=" . $montantTrans . ",apayerPagnet=" . $montantTrans . " where idPagnet=" . $pagnetTrans;

        $res3 = @mysql_query($sql3) or die("mise à jour verouillage  impossible" . mysql_error());
    }
}

/**Fin Button Ajouter Transaction dans un pagnet**/



/**Debut Button Terminer Pagnet**/

if (isset($_POST['btnImprimerFacture'])) {

    $idPagnet = @$_POST['idPagnet'];

    $sql = "SELECT * FROM `" . $nomtablePagnet . "` where idPagnet=" . $idPagnet . " ";

    $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

    $pagnet = mysql_fetch_assoc($res);

    $reference = array();
    $ligneIns = array();

    $sqlL = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

    $resL = mysql_query($sqlL) or die("persoonel requête 2" . mysql_error());
    $resL2 = mysql_query($sqlL) or die("persoonel requête 2" . mysql_error());

    if ($pagnet['type'] == 0 || $pagnet['type'] == 30) {

        while ($ligne = mysql_fetch_assoc($resL2)) {
            if ($ligne['classe'] == 0) {

                $sqlGetStock = "SELECT idDesignation,designation,nbreArticleUniteStock,SUM(`quantiteStockCourant`) as stockTotal FROM `" . $nomtableEntrepotStock . "` where idEntrepot=" . $ligne['idEntrepot'] . " and idDesignation=" . $ligne['idDesignation'] . " GROUP BY `idDesignation`";
                $refJcaisse = mysql_query($sqlGetStock) or die("persoonel requête 1" . mysql_error());
                $stock = mysql_fetch_array($refJcaisse);

                $qtLigne = 0;

                // while ($key = mysql_fetch_array($refJcaisse)) {
                //   $reference[] = $key;
                // }
                // var_dump($reference);
                // if (find_p_with_position($reference, $ligne['idDesignation']) !==FALSE) {
                //     $c = 0;
                //     $i=find_p_with_position($reference, $ligne['idDesignation']);

                $sqlS = "SELECT * FROM `" . $nomtableDesignation . "` where idDesignation=" . $ligne['idDesignation'];

                $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                $designation = mysql_fetch_assoc($resS);

                if (mysql_num_rows($resS)) {

                    if ($ligne['unitevente'] == $designation['uniteStock']) {

                        $qtLigne = $ligne['quantite'] * $designation['nbreArticleUniteStock'];
                    } elseif ($ligne['unitevente'] == 'Demi Gros') {

                        $qtLigne = $ligne['quantite'] * ($designation['nbreArticleUniteStock'] / 2);
                    } else {

                        $qtLigne = $ligne['quantite'];
                    }
                }

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
    }
    if (count($ligneIns) > 0) {
        # code...

    } else {
        if (isset($_POST['remise']) || isset($_POST['versement'])) {

            // code...

            $idPagnet = @$_POST['idPagnet'];



            $sql = "SELECT * FROM `" . $nomtablePagnet . "` where idPagnet=" . $idPagnet . " ";

            $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

            $pagnet = mysql_fetch_assoc($res);



            $sqlT = "SELECT SUM(prixtotal) FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

            $resT = mysql_query($sqlT) or die("persoonel requête 2" . mysql_error());

            $TotalP = mysql_fetch_array($resT);



            $sqlTD = "SELECT SUM(prixtotal) FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " && unitevente='Transaction' && quantite=0 ";

            $resTD = mysql_query($sqlTD) or die("persoonel requête 2" . mysql_error());

            $TotalTD = mysql_fetch_array($resTD);



            //$Total[ = $TotalP[0] - $TotalTD[0];



            $totalp = $TotalP[0] - ($TotalTD[0] * 2);

            if (@$_POST['remise'] == '') {

                $remise = 0;
            } else {

                $remise = @$_POST['remise'];
            }

            $apayerPagnet = $totalp - $remise;

            if (@$_POST['versement'] == '') {

                $versement = 0;

                $monaie = 0;
            } else {

                $versement = @$_POST['versement'];

                $monaie = $versement - $apayerPagnet;
            }



            if (@$_POST['livreur'] == '') {

                $livreur = '';
            } else {

                $livreur = @$_POST['livreur'];
            }



            //$msg_info="<p>$totalp</p> <p>$remise</p> <p>$versement</p> <p>$apayerPagnet</p>";



            $sqlL = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

            $resL = mysql_query($sqlL) or die("persoonel requête 2" . mysql_error());

            //$ligne = mysql_fetch_assoc($resL) ;



            /*****Debut Nombre de Panier ouvert****/

            $query = mysql_query("SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ");

            $nbre_fois = mysql_num_rows($query);

            /*****Fin Nombre de Panier ouvert****/



            /*****Debut Difference entre Total Panier et Remise****/

            $sqlT = "SELECT SUM(prixtotal) FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

            $resT = mysql_query($sqlT) or die("persoonel requête 2" . mysql_error());

            $TotalT = mysql_fetch_array($resT);



            $sqlTD = "SELECT SUM(prixtotal) FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " && unitevente='Transaction' && quantite=0 ";

            $resTD = mysql_query($sqlTD) or die("persoonel requête 2" . mysql_error());

            $TotalTD = mysql_fetch_array($resTD);



            $difference = $TotalT[0] - $TotalTD[0] - $remise;

            /*****Fin Difference entre Total Panier et Remise****/



            if ($pagnet['verrouiller'] == 0) {

                if ($nbre_fois > 0) {

                    if ($difference >= 0) {

                        if ($pagnet['type'] == 0 || $pagnet['type'] == 30) {

                            while ($ligne = mysql_fetch_assoc($resL)) {

                                if ($ligne['classe'] == 0) {

                                    $sqlS = "SELECT * FROM `" . $nomtableDesignation . "` where idDesignation=" . $ligne['idDesignation'];

                                    $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                                    $designation = mysql_fetch_assoc($resS);

                                    if (mysql_num_rows($resS)) {

                                        if ($ligne['unitevente'] == $designation['uniteStock']) {

                                            $sqlD = "SELECT * FROM `" . $nomtableEntrepotStock . "` where idDesignation='" . $ligne['idDesignation'] . "' AND idEntrepot='" . $ligne['idEntrepot'] . "' ORDER BY idEntrepotStock ASC ";

                                            $resD = mysql_query($sqlD) or die("select stock impossible =>" . mysql_error());

                                            $restant = $ligne['quantite'] * $designation['nbreArticleUniteStock'];

                                            while ($stock = mysql_fetch_assoc($resD)) {

                                                if ($restant >= 0) {

                                                    $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                                                    if ($quantiteStockCourant > 0) {

                                                        $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=" . $quantiteStockCourant . " where idEntrepotStock=" . $stock['idEntrepotStock'];

                                                        $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                                    } else {

                                                        $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=0 where idEntrepotStock=" . $stock['idEntrepotStock'];

                                                        $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                                    }

                                                    $restant = $restant - $stock['quantiteStockCourant'];
                                                }
                                            }
                                        } else if ($ligne['unitevente'] == 'Demi Gros') {

                                            $sqlD = "SELECT * FROM `" . $nomtableEntrepotStock . "` where idDesignation='" . $ligne['idDesignation'] . "' AND idEntrepot='" . $ligne['idEntrepot'] . "' ORDER BY idEntrepotStock ASC ";

                                            $resD = mysql_query($sqlD) or die("select stock impossible =>" . mysql_error());

                                            $restant = $ligne['quantite'] * ($designation['nbreArticleUniteStock'] / 2);

                                            while ($stock = mysql_fetch_assoc($resD)) {

                                                if ($restant >= 0) {

                                                    $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                                                    if ($quantiteStockCourant > 0) {

                                                        $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=" . $quantiteStockCourant . " where idEntrepotStock=" . $stock['idEntrepotStock'];

                                                        $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                                    } else {

                                                        $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=0 where idEntrepotStock=" . $stock['idEntrepotStock'];

                                                        $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                                    }

                                                    $restant = $restant - $stock['quantiteStockCourant'];
                                                }
                                            }
                                        } else {

                                            $sqlD = "SELECT * FROM `" . $nomtableEntrepotStock . "` where idDesignation='" . $ligne['idDesignation'] . "' AND idEntrepot='" . $ligne['idEntrepot'] . "' ORDER BY idEntrepotStock ASC ";

                                            $resD = mysql_query($sqlD) or die("select stock impossible =>" . mysql_error());

                                            $restant = $ligne['quantite'];

                                            while ($stock = mysql_fetch_assoc($resD)) {

                                                if ($restant >= 0) {

                                                    $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                                                    if ($quantiteStockCourant > 0) {

                                                        $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=" . $quantiteStockCourant . " where idEntrepotStock=" . $stock['idEntrepotStock'];

                                                        $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                                    } else {

                                                        $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=0 where idEntrepotStock=" . $stock['idEntrepotStock'];

                                                        $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                                    }

                                                    $restant = $restant - $stock['quantiteStockCourant'];
                                                }
                                            }
                                        }
                                    }
                                }
                            }



                            $sql3 = "UPDATE `" . $nomtablePagnet . "` set verrouiller='1', remise=" . $remise . ",totalp=" . $totalp . ",apayerPagnet=" . $apayerPagnet . ",versement=" . $versement . ",restourne=" . $monaie . ",livreur='" . $livreur . "' where idPagnet=" . $idPagnet;

                            $res3 = @mysql_query($sql3) or die("mise à jour verouillage  impossible" . mysql_error());



                            if ($_SESSION['compte'] == 1) {

                                if (isset($_POST['compte'])) {

                                    $idCompte = $_POST['compte'];



                                    $sqlL = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ORDER BY numLigne LIMIT 1 ";

                                    $resL = mysql_query($sqlL) or die("persoonel requête 2" . mysql_error());

                                    $lignes = mysql_fetch_array($resL);

                                    if ($lignes['classe'] == '2') {



                                        $description = "Dépenses";

                                        $operation = 'retrait';



                                        $sql8 = "UPDATE `" . $nomtablePagnet . "` set idCompte=" . $idCompte . " where  idPagnet=" . $idPagnet;

                                        $res8 = @mysql_query($sql8) or die("mise à jour pour activer ou pas " . mysql_error());



                                        $sql7 = "UPDATE `" . $nomtableCompte . "` set  montantCompte= montantCompte - " . $apayerPagnet . " where  idCompte=" . $idCompte;

                                        $res7 = @mysql_query($sql7) or die("mise à jour 2 pour activer ou pas " . mysql_error());



                                        $sql6 = "insert into `" . $nomtableComptemouvement . "` (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser) values(" . $apayerPagnet . ",'" . $operation . "'," . $idCompte . ",'" . $description . "','" . $dateHeures . "','" . $dateHeures . "'," . $idPagnet . "," . $_SESSION['iduser'] . ")";

                                        $res6 = mysql_query($sql6) or die("insertion Cmpte impossible =>" . mysql_error());
                                    } else {



                                        if (isset($_POST['avanceInput']) && $_POST['avanceInput'] > 0 && $idCompte == '2') {

                                            // $avanceInput=$_POST['avanceInput'];

                                            $montant = htmlspecialchars(trim($_POST['avanceInput']));

                                            $compteAvance = htmlspecialchars(trim($_POST['compteAvance']));

                                            $idClient = $pagnet['idClient'];

                                            $dateVersement = $dateString2;



                                            $sql30 = "UPDATE `" . $nomtablePagnet . "` set avance=" . $montant . " where idPagnet=" . $idPagnet;

                                            $res30 = mysql_query($sql30) or die("update avance pagnet impossible =>" . mysql_error());



                                            $sql2 = "insert into `" . $nomtableVersement . "` (idClient,paiement,montant,dateVersement,heureVersement,idCompte,idPagnet,iduser) values(" . $idClient . ",'avance client'," . $montant . ",'" . $dateVersement . "','" . $heureString . "'," . $compteAvance . "," . $idPagnet . "," . $_SESSION['iduser'] . ")";

                                            $res2 = mysql_query($sql2) or die("insertion Versement impossible =>" . mysql_error());

                                            // $solde=$montant+$client['solde'];

                                            $reqv = "SELECT idVersement from `" . $nomtableVersement . "` order by idVersement desc limit 1";

                                            $resv = mysql_query($reqv) or die("persoonel requête 2" . mysql_error());

                                            $v = mysql_fetch_array($resv);

                                            $idVersement = $v['idVersement'];



                                            $sql3 = "UPDATE `" . $nomtableClient . "` set solde=solde-" . $montant . " where idClient=" . $idClient;

                                            $res3 = mysql_query($sql3) or die("update solde client impossible =>" . mysql_error());



                                            $operation = 'depot';

                                            $description = "Avance bon";



                                            $sql7 = "UPDATE `" . $nomtableCompte . "` set montantCompte=montantCompte + " . $montant . " where idCompte=" . $compteAvance;

                                            $res7 = @mysql_query($sql7) or die("mise à jour 2 pour activer ou pas " . mysql_error());



                                            $sql6 = "insert into `" . $nomtableComptemouvement . "` (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,mouvementLink,idUser) values(" . $montant . ",'" . $operation . "'," . $compteAvance . ",'" . $description . "','" . $dateHeures . "','" . $dateHeures . "'," . $idVersement . "," . $idPagnet . "," . $_SESSION['iduser'] . ")";

                                            $res6 = mysql_query($sql6) or die("insertion Cmpte impossible =>" . mysql_error());



                                            $operation2 = 'retrait';

                                            $idCompteBon = '2';

                                            $description2 = "Bon encaissé";



                                            $sql7 = "UPDATE `" . $nomtableCompte . "` set  montantCompte= montantCompte - " . $montant . " where  idCompte=" . $idCompteBon;

                                            $res7 = @mysql_query($sql7) or die("mise à jour 2 pour activer ou pas " . mysql_error());



                                            $sql6 = "insert into `" . $nomtableComptemouvement . "` (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,mouvementLink,idUser) values(" . $montant . ",'" . $operation2 . "'," . $idCompteBon . ",'" . $description2 . "','" . $dateHeures . "','" . $dateHeures . "'," . $idVersement . "," . $idPagnet . "," . $_SESSION['iduser'] . ")";

                                            $res6 = mysql_query($sql6) or die("insertion Cmpte impossible =>" . mysql_error());
                                        }

                                        $operation = 'depot';

                                        if ($idCompte == '2') {

                                            $description = "Bon";

                                            $idClient = $pagnet['idClient'];



                                            $sql3 = "UPDATE `" . $nomtableClient . "` set solde=solde+" . $apayerPagnet . " where idClient=" . $idClient;

                                            $res3 = mysql_query($sql3) or die("update solde client impossible =>" . mysql_error());



                                            $sql18 = "SELECT SUM(apayerPagnet-avance) FROM `" . $nomtablePagnet . "` where verrouiller='1' && idClient=" . $idClient . " && (type=0 || type=30) ";

                                            $res18 = mysql_query($sql18) or die("persoonel requête 2" . mysql_error());

                                            $TotalP = mysql_fetch_array($res18);



                                            $sql19 = "SELECT SUM(apayerPagnet) FROM `" . $nomtablePagnet . "` where idClient=" . $idClient . " && type=1 ";

                                            $res19 = mysql_query($sql19) or die("persoonel requête 2" . mysql_error());

                                            $TotalR = mysql_fetch_array($res19);



                                            $total = $TotalP[0] - $TotalR[0];



                                            $sql20 = "UPDATE `" . $nomtableBon . "` set montant=" . $total . ", date='" . $dateString . "' where idClient=" . $idClient;

                                            $res20 = mysql_query($sql20) or die("update Pagnet impossible =>" . mysql_error());
                                        } else {

                                            $description = "Encaissement vente";
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
                                            $k = 0;
                                            foreach ($choiceArray as $key) {

                                                $_items = explode('-', $key);
                                                $idPagnet = $_items[0];
                                                $_idCompte = $_items[1];
                                                $montant = $_items[2];
                                                $idComptes[$k] = $_idCompte;

                                                $sql7 = "UPDATE `" . $nomtableCompte . "` set montantCompte= montantCompte + " . $montant . " where  idCompte=" . $_idCompte;
                                                $res7 = @mysql_query($sql7) or die("mise à jour 2 pour activer ou pas " . mysql_error());

                                                $sql6 = "insert into `" . $nomtableComptemouvement . "` (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser) values(" . $montant . ",'" . $operation . "'," . $_idCompte . ",'" . $description . "','" . $dateHeures . "','" . $dateHeures . "'," . $idPagnet . "," . $_SESSION['iduser'] . ")";
                                                $res6 = mysql_query($sql6) or die("insertion Cmpte impossible =>" . mysql_error());
                                                $k++;
                                            }

                                            $idsCpteMultiple = implode('_', $idComptes);
                                            // var_dump($idComptes);
                                            // var_dump($idsCpteMultiple);

                                            $sql8 = "UPDATE `" . $nomtablePagnet . "`  set  idCompte=" . $idCompte . ", idsCpteMultiple='" . $idsCpteMultiple . "'  where  idPagnet=" . $idPagnet;
                                            $res8 = @mysql_query($sql8) or die("mise à jour pour activer ou pas " . mysql_error());
                                        } else {
                                            $sql8 = "UPDATE `" . $nomtablePagnet . "`  set  idCompte=" . $idCompte . " where  idPagnet=" . $idPagnet;

                                            $res8 = @mysql_query($sql8) or die("mise à jour pour activer ou pas " . mysql_error());



                                            $sql7 = "UPDATE `" . $nomtableCompte . "` set  montantCompte= montantCompte + " . $apayerPagnet . " where  idCompte=" . $idCompte;

                                            $res7 = @mysql_query($sql7) or die("mise à jour 2 pour activer ou pas " . mysql_error());



                                            $sql6 = "insert into `" . $nomtableComptemouvement . "` (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser) values(" . $apayerPagnet . ",'" . $operation . "'," . $idCompte . ",'" . $description . "','" . $dateHeures . "','" . $dateHeures . "'," . $idPagnet . "," . $_SESSION['iduser'] . ")";

                                            $res6 = mysql_query($sql6) or die("insertion Cmpte impossible =>" . mysql_error());
                                        }
                                    }
                                }

                                if ($remise != 0) {

                                    $operation = 'depot';

                                    $idCompteRemise = '3';

                                    $description = "Remise vente";



                                    $sql7 = "UPDATE `" . $nomtableCompte . "` set  montantCompte= montantCompte + " . $remise . " where  idCompte=" . $idCompteRemise;

                                    $res7 = @mysql_query($sql7) or die("mise à jour 2 pour activer ou pas " . mysql_error());



                                    $sql6 = "insert into `" . $nomtableComptemouvement . "` (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser) values(" . $remise . ",'" . $operation . "'," . $idCompteRemise . ",'" . $description . "','" . $dateHeures . "','" . $dateHeures . "'," . $idPagnet . "," . $_SESSION['iduser'] . ")";

                                    $res6 = mysql_query($sql6) or die("insertion Cmpte impossible =>" . mysql_error());
                                }
                            }
                        }
                    } else {

                        $msg_info = "<p>IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro " . $idPagnet . ", il faut verifier la remise.</p>";
                    }
                } else {

                    $msg_info = "<p>IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro " . $idPagnet . ", il faut au moins ajouter un produit.</p>";
                }
            }
        }
    }
}

/**Fin Button Terminer Pagnet**/



/**Debut Button Annuler Pagnet**/

if (isset($_POST['btnAnnulerPagnet'])) {



    $idPagnet = htmlspecialchars(trim($_POST['idPagnet']));



    $sqlL = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

    $resL = mysql_query($sqlL) or die("persoonel requête 2" . mysql_error());



    while ($ligne = mysql_fetch_assoc($resL)) {



        //on fait la suppression de cette ligne dans la table ligne

        $sql3 = "DELETE FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

        $res3 = @mysql_query($sql3) or die("mise à jour client  impossible" . mysql_error());
    }



    // suppression du pagnet aprés su^ppression de ses lignes

    $sqlR = "DELETE FROM `" . $nomtablePagnet . "` where idPagnet=" . $idPagnet;

    $resR = @mysql_query($sqlR) or die("mise à jour client  impossible" . mysql_error());
}

/**Fin Button Annuler Pagnet**/



/**Debut Button Retourner Pagnet**/

if (isset($_POST['btnRetournerPagnet'])) {



    $idPagnet = htmlspecialchars(trim($_POST['idPagnet']));



    $sqlP = "SELECT * FROM `" . $nomtablePagnet . "` where idPagnet=" . $idPagnet . " ";

    $resP = mysql_query($sqlP) or die("persoonel requête 2" . mysql_error());

    $pagnet = mysql_fetch_assoc($resP);



    $sqlL = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

    $resL = mysql_query($sqlL) or die("persoonel requête 2" . mysql_error());

    //$ligne = mysql_fetch_assoc($resL) ;



    if ($pagnet['type'] == 0 || $pagnet['type'] == 30) {

        while ($ligne = mysql_fetch_assoc($resL)) {

            if ($ligne['classe'] == 0) {

                $sqlS = "SELECT * FROM `" . $nomtableDesignation . "` where idDesignation=" . $ligne['idDesignation'];

                $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                $designation = mysql_fetch_assoc($resS);

                if (mysql_num_rows($resS)) {

                    if ($ligne['unitevente'] == $designation['uniteStock']) {

                        $sqlD = "SELECT * FROM `" . $nomtableEntrepotStock . "` where idDesignation='" . $ligne['idDesignation'] . "'  AND idEntrepot='" . $ligne['idEntrepot'] . "' ORDER BY idEntrepotStock DESC ";

                        $resD = mysql_query($sqlD) or die("select stock impossible =>" . mysql_error());

                        $retour = $ligne['quantite'] * $designation['nbreArticleUniteStock'];

                        while ($stock = mysql_fetch_assoc($resD)) {

                            if ($retour >= 0) {

                                $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                if ($stock['totalArticleStock'] >= $quantiteStockCourant) {

                                    $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=" . $quantiteStockCourant . " where idEntrepotStock=" . $stock['idEntrepotStock'];

                                    $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                } else {

                                    $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=" . $stock['totalArticleStock'] . " where idEntrepotStock=" . $stock['idEntrepotStock'];

                                    $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                }

                                $retour = ($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'];
                            }
                        }
                    } else if ($ligne['unitevente'] == 'Demi Gros') {

                        $sqlD = "SELECT * FROM `" . $nomtableEntrepotStock . "` where idDesignation='" . $ligne['idDesignation'] . "'  AND idEntrepot='" . $ligne['idEntrepot'] . "' ORDER BY idEntrepotStock DESC ";

                        $resD = mysql_query($sqlD) or die("select stock impossible =>" . mysql_error());

                        $retour = $ligne['quantite'] * ($designation['nbreArticleUniteStock'] / 2);

                        while ($stock = mysql_fetch_assoc($resD)) {

                            if ($retour >= 0) {

                                $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                if ($stock['totalArticleStock'] >= $quantiteStockCourant) {

                                    $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=" . $quantiteStockCourant . " where idEntrepotStock=" . $stock['idEntrepotStock'];

                                    $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                } else {

                                    $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=" . $stock['totalArticleStock'] . " where idEntrepotStock=" . $stock['idEntrepotStock'];

                                    $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                }

                                $retour = ($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'];
                            }
                        }
                    } else {

                        $sqlD = "SELECT * FROM `" . $nomtableEntrepotStock . "` where idDesignation='" . $ligne['idDesignation'] . "'  AND idEntrepot='" . $ligne['idEntrepot'] . "' ORDER BY idEntrepotStock DESC ";

                        $resD = mysql_query($sqlD) or die("select stock impossible =>" . mysql_error());

                        $retour = $ligne['quantite'];

                        while ($stock = mysql_fetch_assoc($resD)) {

                            if ($retour >= 0) {

                                $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                if ($stock['totalArticleStock'] >= $quantiteStockCourant) {

                                    $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=" . $quantiteStockCourant . " where idEntrepotStock=" . $stock['idEntrepotStock'];

                                    $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                } else {

                                    $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=" . $stock['totalArticleStock'] . " where idEntrepotStock=" . $stock['idEntrepotStock'];

                                    $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                }

                                $retour = ($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'];
                            }
                        }
                    }
                }
            }
        }



        // suppression du pagnet aprés su^ppression de ses lignes

        $sqlR = "UPDATE `" . $nomtablePagnet . "` set type=2 where idPagnet=" . $idPagnet;

        $resR = @mysql_query($sqlR) or die("mise à jour client  impossible" . mysql_error());





        if ($_SESSION['compte'] == 1) {

            $description = "Retour panier";

            $operation = 'retrait';



            $sql7 = "UPDATE `" . $nomtableCompte . "` set  montantCompte= montantCompte - " . $pagnet['apayerPagnet'] . " where  idCompte=" . $pagnet['idCompte'];

            $res7 = @mysql_query($sql7) or die("mise à jour 2 pour activer ou pas " . mysql_error());



            if ($pagnet['remise'] != '' && $pagnet['remise'] != 0) {

                $sql8 = "UPDATE `" . $nomtableCompte . "` set  montantCompte= montantCompte - " . $pagnet['remise'] . " where  idCompte=3";

                $res8 = @mysql_query($sql8) or die("mise à jour 2 pour activer ou pas " . mysql_error());
            }

            //Annulation des mouvements relatifs à ce panier

            $sql = "DELETE FROM `" . $nomtableComptemouvement . "` where  mouvementLink=" . $idPagnet . "";

            $res = @mysql_query($sql) or die("mise à jour idClient " . mysql_error());



            if ($pagnet['avance'] != 0) {



                $sql0 = "DELETE FROM `" . $nomtableVersement . "` where  idPagnet=" . $idPagnet . "";

                $res0 = @mysql_query($sql0) or die("mise à jour idClient " . mysql_error());
            }



            if ($pagnet['idCompte'] == 2) {

                # code...

                /************************************** UPDATE BON Et DU REMISE******************************************/

                $sql18 = "SELECT SUM(apayerPagnet) FROM `" . $nomtablePagnet . "` where verrouiller='1' && idClient=" . $pagnet['idClient'] . " && (type=0 || type=30) ";

                $res18 = mysql_query($sql18) or die("persoonel requête 2" . mysql_error());

                $TotalP = mysql_fetch_array($res18);



                $sql19 = "SELECT SUM(apayerPagnet) FROM `" . $nomtablePagnet . "` where idClient=" . $pagnet['idClient'] . " && type=1 ";

                $res19 = mysql_query($sql19) or die("persoonel requête 2" . mysql_error());

                $TotalR = mysql_fetch_array($res19);



                $total = $TotalP[0] - $TotalR[0];



                $sql20 = "UPDATE `" . $nomtableBon . "` set montant=" . $total . ", date='" . $dateString . "' where idClient=" . $pagnet['idClient'];

                $res20 = mysql_query($sql20) or die("update Pagnet impossible =>" . mysql_error());



                $sql3 = "UPDATE `" . $nomtableClient . "` set solde=solde-" . $pagnet['apayerPagnet'] . " where idClient=" . $pagnet['idClient'];

                $res3 = mysql_query($sql3) or die("update solde client impossible =>" . mysql_error());
            }
        }
    }
}

/**Fin Button Retourner Pagnet**/



/**Debut Button Annuler Ligne d'un Pagnet**/

if (isset($_POST['btnRetourAvant'])) {



    $numligne = $_POST['numligne'];

    $unitevente = $_POST['unitevente'];



    //on fait la suppression de cette ligne dans la table ligne

    $sql3 = "DELETE FROM `" . $nomtableLigne . "` where numligne=" . $numligne;

    $res3 = @mysql_query($sql3) or die("mise à jour client  impossible" . mysql_error());
}

/**Fin Button Annuler Ligne d'un Pagnet**/



/**Debut Button Retourner Ligne d'un Pagnet**/

if (isset($_POST['btnRetourApres'])) {



    $numligne = $_POST['numligne'];

    $idStock = $_POST['idStock'];

    $designation = $_POST['designation'];

    $idPagnet = $_POST['idPagnet'];

    $quantite = $_POST['quantite'];

    $unitevente = $_POST['unitevente'];

    $prixunitevente = $_POST['prixunitevente'];

    $prixtotal = $_POST['prixtotal'];

    $totalp = $_POST['totalp'];



    $sqlL = "SELECT * FROM `" . $nomtableLigne . "` where numligne=" . $numligne . "";

    $resL = mysql_query($sqlL) or die("persoonel requête 2" . mysql_error());

    $ligne = mysql_fetch_assoc($resL);



    $sqlP = "SELECT * FROM `" . $nomtablePagnet . "` where idPagnet=" . $idPagnet . " ";

    $resP = mysql_query($sqlP) or die("persoonel requête 2" . mysql_error());

    $pagnet = mysql_fetch_assoc($resP);



    $sqlT = "SELECT SUM(prixtotal) FROM `" . $nomtableLigne . "` where idPagnet='" . $idPagnet . "' && numligne<>" . $numligne . "";

    $resT = mysql_query($sqlT) or die("persoonel requête 2" . mysql_error());

    $TotalT = mysql_fetch_array($resT);



    $idClient = $pagnet['idClient'];



    $difference = $TotalT[0] + ($quantite * $ligne['prixunitevente']) - $pagnet["remise"];



    if ($difference >= 0 && $ligne['quantite'] >= $quantite) {



        if ($pagnet['type'] == 0 || $pagnet['type'] == 30) {

            if ($ligne['classe'] == 0) {

                $sqlS = "SELECT * FROM `" . $nomtableDesignation . "` where idDesignation=" . $ligne['idDesignation'];

                $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                $designation = mysql_fetch_assoc($resS);

                if (mysql_num_rows($resS)) {

                    if ($ligne['unitevente'] == $designation['uniteStock']) {

                        $sqlD = "SELECT * FROM `" . $nomtableEntrepotStock . "` where idDesignation='" . $ligne['idDesignation'] . "'  AND idEntrepot='" . $ligne['idEntrepot'] . "' ORDER BY idEntrepotStock DESC ";

                        $resD = mysql_query($sqlD) or die("select stock impossible =>" . mysql_error());

                        $retour = ($ligne['quantite'] - $quantite) * $designation['nbreArticleUniteStock'];

                        while ($stock = mysql_fetch_assoc($resD)) {

                            if ($retour >= 0) {

                                $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                if ($stock['totalArticleStock'] >= $quantiteStockCourant) {

                                    $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=" . $quantiteStockCourant . " where idEntrepotStock=" . $stock['idEntrepotStock'];

                                    $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                } else {

                                    $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=" . $stock['totalArticleStock'] . " where idEntrepotStock=" . $stock['idEntrepotStock'];

                                    $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                }

                                $retour = ($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'];
                            }
                        }

                        if ($quantite == 0) {

                            $sqlD = "DELETE FROM `" . $nomtableLigne . "` where numligne=" . $numligne;

                            $resD = @mysql_query($sqlD) or die("mise à jour client  impossible" . mysql_error());
                        } else {

                            $prixTotal = $quantite * $ligne['prixunitevente'];

                            $sql3 = "UPDATE `" . $nomtableLigne . "` set quantite=" . $quantite . ", prixtotal=" . $prixTotal . "  where numligne=" . $ligne['numligne'] . "  ";

                            $res3 = @mysql_query($sql3) or die("mise à jour client  impossible" . mysql_error());
                        }
                    } else if ($ligne['unitevente'] == 'Demi Gros') {

                        $sqlD = "SELECT * FROM `" . $nomtableEntrepotStock . "` where idDesignation='" . $ligne['idDesignation'] . "'  AND idEntrepot='" . $ligne['idEntrepot'] . "' ORDER BY idEntrepotStock DESC ";

                        $resD = mysql_query($sqlD) or die("select stock impossible =>" . mysql_error());

                        $retour = ($ligne['quantite'] - $quantite) * ($designation['nbreArticleUniteStock'] / 2);

                        while ($stock = mysql_fetch_assoc($resD)) {

                            if ($retour >= 0) {

                                $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                if ($stock['totalArticleStock'] >= $quantiteStockCourant) {

                                    $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=" . $quantiteStockCourant . " where idEntrepotStock=" . $stock['idEntrepotStock'];

                                    $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                } else {

                                    $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=" . $stock['totalArticleStock'] . " where idEntrepotStock=" . $stock['idEntrepotStock'];

                                    $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                }

                                $retour = ($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'];
                            }
                        }

                        if ($quantite == 0) {

                            $sqlD = "DELETE FROM `" . $nomtableLigne . "` where numligne=" . $numligne;

                            $resD = @mysql_query($sqlD) or die("mise à jour client  impossible" . mysql_error());
                        } else {

                            $prixTotal = $quantite * $ligne['prixunitevente'];

                            $sql3 = "UPDATE `" . $nomtableLigne . "` set quantite=" . $quantite . ", prixtotal=" . $prixTotal . "  where numligne=" . $ligne['numligne'] . "  ";

                            $res3 = @mysql_query($sql3) or die("mise à jour client  impossible" . mysql_error());
                        }
                    } else {

                        $sqlD = "SELECT * FROM `" . $nomtableEntrepotStock . "` where idDesignation='" . $ligne['idDesignation'] . "'  AND idEntrepot='" . $ligne['idEntrepot'] . "' ORDER BY idEntrepotStock DESC ";

                        $resD = mysql_query($sqlD) or die("select stock impossible =>" . mysql_error());

                        $retour = $ligne['quantite'] - $quantite;

                        while ($stock = mysql_fetch_assoc($resD)) {

                            if ($retour >= 0) {

                                $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                if ($stock['totalArticleStock'] >= $quantiteStockCourant) {

                                    $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=" . $quantiteStockCourant . " where idEntrepotStock=" . $stock['idEntrepotStock'];

                                    $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                } else {

                                    $sqlS = "UPDATE `" . $nomtableEntrepotStock . "` set quantiteStockCourant=" . $stock['totalArticleStock'] . " where idEntrepotStock=" . $stock['idEntrepotStock'];

                                    $resS = mysql_query($sqlS) or die("update quantiteStockCourant impossible =>" . mysql_error());
                                }

                                $retour = ($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'];
                            }
                        }

                        if ($quantite == 0) {

                            $sqlD = "DELETE FROM `" . $nomtableLigne . "` where numligne=" . $numligne;

                            $resD = @mysql_query($sqlD) or die("mise à jour client  impossible" . mysql_error());
                        } else {

                            $prixTotal = $quantite * $ligne['prixunitevente'];

                            $sql3 = "UPDATE `" . $nomtableLigne . "` set quantite=" . $quantite . ", prixtotal=" . $prixTotal . "  where numligne=" . $ligne['numligne'] . "  ";

                            $res3 = @mysql_query($sql3) or die("mise à jour client  impossible" . mysql_error());
                        }
                    }
                }
            }

            $sqlTP = "SELECT SUM(prixtotal) FROM `" . $nomtableLigne . "` where idPagnet=" . $idPagnet . " ";

            $resTP = mysql_query($sqlTP) or die("persoonel requête 2" . mysql_error());

            $TotalTP = mysql_fetch_array($resTP);



            $apayerPagnet = 0;

            if ($TotalTP[0] != null) {

                $apayerPagnet = $TotalTP[0];

                $restourne = 0;

                if ($pagnet['remise'] != 0) {

                    $apayerPagnet = $TotalTP[0] - $pagnet['remise'];
                }

                if ($pagnet['versement'] != 0) {

                    $restourne = $apayerPagnet - $pagnet['versement'];
                }



                $sql16 = "update `" . $nomtablePagnet . "` set totalp=" . $TotalTP[0] . ",apayerPagnet=" . $apayerPagnet . ",

                                                        restourne=" . $restourne . " where idPagnet='" . $idPagnet . "'";

                $res16 = mysql_query($sql16) or die("update Pagnet impossible =>" . mysql_error());





                if ($_SESSION['compte'] == 1) {

                    /********* Début retour ligne compte **********/

                    $sql7 = "UPDATE `" . $nomtableCompte . "` set  montantCompte= montantCompte - " . $prixtotal . " where idCompte=" . $pagnet['idCompte'];

                    //var_dump($sql7);

                    $res7 = @mysql_query($sql7) or die("mise à jour 2 pour activer ou pas " . mysql_error());



                    $sql8 = "UPDATE `" . $nomtableComptemouvement . "` set  montant= " . $apayerPagnet . " where idCompte<>3 and mouvementLink=" . $pagnet['idPagnet'];

                    //var_dump($sql7);

                    $res8 = @mysql_query($sql8) or die("mise à jour 2 pour activer ou pas " . mysql_error());



                    /******** Fin retour ligne compte ***********/

                    # code...

                    if ($pagnet['idCompte'] == 2) {

                        # code...

                        /************************************** UPDATE BON Et DU REMISE******************************************/

                        $sql18 = "SELECT SUM(apayerPagnet) FROM `" . $nomtablePagnet . "` where verrouiller='1' && idClient=" . $idClient . " && (type=0 || type=30) ";

                        $res18 = mysql_query($sql18) or die("persoonel requête 2" . mysql_error());

                        $TotalP = mysql_fetch_array($res18);



                        $sql19 = "SELECT SUM(apayerPagnet) FROM `" . $nomtablePagnet . "` where idClient=" . $idClient . " && type=1 ";

                        $res19 = mysql_query($sql19) or die("persoonel requête 2" . mysql_error());

                        $TotalR = mysql_fetch_array($res19);



                        $total = $TotalP[0] - $TotalR[0];



                        $sql20 = "UPDATE `" . $nomtableBon . "` set montant=" . $total . ", date='" . $dateString . "' where idClient=" . $idClient;

                        $res20 = mysql_query($sql20) or die("update Pagnet impossible =>" . mysql_error());



                        $sql3 = "UPDATE `" . $nomtableClient . "` set solde=solde-" . $prixtotal . " where idClient=" . $idClient;

                        $res3 = mysql_query($sql3) or die("update solde client impossible =>" . mysql_error());
                    }
                }
            } else {

                $sqlR = "UPDATE `" . $nomtablePagnet . "` set type=2 where idPagnet=" . $idPagnet;

                $resR = @mysql_query($sqlR) or die("mise à jour client  impossible" . mysql_error());
            }
        }
    } else {

        $msg_info = "<p>IMPOSSIBLE.</br></br> Vous ne pouvez pas retourner cette ligne du pagnet numéro " . $idPagnet . " , car c'est la(les) dernière(s) ligne(s). Cela risque de fausser les calculs surtout quand vous avez effectué des remises.</br></br>Il faut retourner tout le pagnet.</p>";
    }
}

/**Fin Button Retourner Ligne d'un Pagnet**/

/**Debut Button upload Image Panier**/
if (isset($_POST['btnUploadImgPanier'])) {
    $idPagnet = htmlspecialchars(trim($_POST['idPanier']));
    if (isset($_FILES['file'])) {
        $tmpName = $_FILES['file']['tmp_name'];
        $name = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $error = $_FILES['file']['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));

        $extensions = ['jpg', 'png', 'jpeg', 'gif', 'pdf'];
        $maxSize = 400000;

        if (in_array($extension, $extensions) && $error == 0) {

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName . "." . $extension;
            //$file = 5f586bf96dcd38.73540086.jpg

            move_uploaded_file($tmpName, './PiecesJointes/' . $file);

            $sql2 = "UPDATE `" . $nomtablePagnet . "` set image='" . $file . "' where idPagnet='" . $idPagnet . "' ";
            $res2 = mysql_query($sql2) or die("modification 1 impossible =>" . mysql_error());
        } else {
            echo "Une erreur est survenue";
        }
    }
}
/**Fin Button upload Image Panier**/



require('entetehtml.php');

?>

<!-- Debut Code HTML -->

<body onLoad="">

    <input type="hidden" id="typeVente" value="2" />
    <input type="hidden" id="venteRapideTest" value="<?= $_SESSION['venterapideEt']; ?>">
    <input type="hidden" id="sessioninfosup" value="<?= $_SESSION['infoSup']; ?>">


    <header>

        <?php require('header.php'); ?>

        <?php

        if ($_SESSION['venterapideEt'] == 0) {
            # code...
        ?>

            <!-- Debut Container HTML -->

            <div class="container">

                <?php

                if ($_SESSION['proprietaire'] == 1) {



                    $sqlApp = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE l.classe=5 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "'  ORDER BY p.idPagnet DESC";

                    $resApp = mysql_query($sqlApp) or die("persoonel requête 2" . mysql_error());

                    $T_App = 0;

                    $S_App = 0;

                    while ($pagnet = mysql_fetch_assoc($resApp)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && idPagnet='" . $pagnet['idPagnet'] . "'  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_App = mysql_fetch_array($resS);

                        $T_App = $S_App[0] + $T_App;
                    }



                    $sqlRC = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE l.classe=7 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "'  ORDER BY p.idPagnet DESC";

                    $resRC = mysql_query($sqlRC) or die("persoonel requête 2" . mysql_error());

                    $T_Rcaisse = 0;

                    $S_Rcaisse = 0;

                    while ($pagnet = mysql_fetch_assoc($resRC)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && idPagnet='" . $pagnet['idPagnet'] . "'  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_Rcaisse = mysql_fetch_array($resS);

                        $T_Rcaisse = $S_Rcaisse[0] + $T_Rcaisse;
                    }



                    $sqlV = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE (l.classe=0 || l.classe=1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "' && p.type=0  ORDER BY p.idPagnet DESC";

                    $resV = mysql_query($sqlV) or die("persoonel requête 2" . mysql_error());

                    $T_ventes = 0;

                    $S_ventes = 0;

                    while ($pagnet = mysql_fetch_assoc($resV)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && type=0 && idPagnet='" . $pagnet['idPagnet'] . "'  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_ventes = mysql_fetch_array($resS);

                        $T_ventes = $S_ventes[0] + $T_ventes;
                    }



                    $sqlBon = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE (l.classe=0 || l.classe=1) && p.idClient!=0 && p.verrouiller=1 && (p.type=0 || p.type=30) && p.datepagej ='" . $dateString2 . "' ORDER BY p.idPagnet DESC";

                    $resBon = mysql_query($sqlBon) or die("persoonel requête 2" . mysql_error());

                    $T_bons = 0;

                    $S_bons = 0;

                    while ($pagnet = mysql_fetch_assoc($resBon)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where idClient!=0 &&  verrouiller=1 && (type=0 || type=30) && datepagej ='" . $dateString2 . "' &&  idPagnet='" . $pagnet['idPagnet'] . "' ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_bons = mysql_fetch_array($resS);

                        $T_bons = $S_bons[0] + $T_bons;
                    }



                    $sqlR = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE l.classe=0 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "' && p.type=1  ORDER BY p.idPagnet DESC";

                    $resR = mysql_query($sqlR) or die("persoonel requête 2" . mysql_error());

                    $T_Rpagnet = 0;

                    $S_Rpagnet = 0;

                    while ($pagnet = mysql_fetch_assoc($resR)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && type=1 && idPagnet='" . $pagnet['idPagnet'] . "'  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_Rpagnet = mysql_fetch_array($resS);

                        $T_Rpagnet = $S_Rpagnet[0] + $T_Rpagnet;
                    }



                    $sqlTD = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE l.classe=3 && l.quantite=1 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "'  ORDER BY p.idPagnet DESC";

                    $resTD = mysql_query($sqlTD) or die("persoonel requête 2" . mysql_error());

                    $T_depot = 0;

                    $S_depot = 0;

                    while ($pagnet = mysql_fetch_assoc($resTD)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && idPagnet='" . $pagnet['idPagnet'] . "'  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_depot = mysql_fetch_array($resS);

                        $T_depot = $S_depot[0] + $T_depot;
                    }



                    $sqlTR = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE l.classe=3 && l.quantite=0 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "'  ORDER BY p.idPagnet DESC";

                    $resTR = mysql_query($sqlTR) or die("persoonel requête 2" . mysql_error());

                    $T_retrait = 0;

                    $S_retrait = 0;

                    while ($pagnet = mysql_fetch_assoc($resTR)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && idPagnet='" . $pagnet['idPagnet'] . "'  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_retrait = mysql_fetch_array($resS);

                        $T_retrait = $S_retrait[0] + $T_retrait;
                    }



                    $sqlTF = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE l.classe=3 && (l.quantite!=0 && l.quantite!=1)  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "'  ORDER BY p.idPagnet DESC";

                    $resTF = mysql_query($sqlTF) or die("persoonel requête 2" . mysql_error());

                    $T_facture = 0;

                    $S_facture = 0;

                    while ($pagnet = mysql_fetch_assoc($resTF)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && idPagnet='" . $pagnet['idPagnet'] . "'  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_facture = mysql_fetch_array($resS);

                        $T_facture = $S_facture[0] + $T_facture;
                    }



                    $sqlTC = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE l.classe=4  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "'  ORDER BY p.idPagnet DESC";

                    $resTC = mysql_query($sqlTC) or die("persoonel requête 2" . mysql_error());

                    $T_credit = 0;

                    $S_credit = 0;

                    while ($pagnet = mysql_fetch_assoc($resTC)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && idPagnet='" . $pagnet['idPagnet'] . "'  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_credit = mysql_fetch_array($resS);

                        $T_credit = $S_credit[0] + $T_credit;
                    }



                    $sqlD = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE l.classe=2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "'  && p.type=0  ORDER BY p.idPagnet DESC";

                    $resD = mysql_query($sqlD) or die("persoonel requête 2" . mysql_error());

                    $T_depenses = 0;

                    $S_depenses = 0;

                    while ($pagnet = mysql_fetch_assoc($resD)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && idPagnet='" . $pagnet['idPagnet'] . "'  && type=0  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_depenses = mysql_fetch_array($resS);

                        $T_depenses = $S_depenses[0] + $T_depenses;
                    }



                    $sqlP5 = "SELECT SUM(montant) FROM `" . $nomtableVersement . "` where idClient!=0  &&  (dateVersement  ='" . $dateString2 . "' || dateVersement  ='" . $dateString . "')  ORDER BY idVersement DESC";

                    $resP5 = mysql_query($sqlP5) or die("persoonel requête 2" . mysql_error());

                    $T_versements = mysql_fetch_array($resP5);
                } else {



                    $sqlApp = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE p.iduser='" . $_SESSION['iduser'] . "' && l.classe=5 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "'  ORDER BY p.idPagnet DESC";

                    $resApp = mysql_query($sqlApp) or die("persoonel requête 2" . mysql_error());

                    $T_App = 0;

                    $S_App = 0;

                    while ($pagnet = mysql_fetch_assoc($resApp)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where iduser='" . $_SESSION['iduser'] . "' && idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && idPagnet='" . $pagnet['idPagnet'] . "'  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_App = mysql_fetch_array($resS);

                        $T_App = $S_App[0] + $T_App;
                    }



                    $sqlRC = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE p.iduser='" . $_SESSION['iduser'] . "' && l.classe=7 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "'  ORDER BY p.idPagnet DESC";

                    $resRC = mysql_query($sqlRC) or die("persoonel requête 2" . mysql_error());

                    $T_Rcaisse = 0;

                    $S_Rcaisse = 0;

                    while ($pagnet = mysql_fetch_assoc($resRC)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where iduser='" . $_SESSION['iduser'] . "' && idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && idPagnet='" . $pagnet['idPagnet'] . "'  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_Rcaisse = mysql_fetch_array($resS);

                        $T_Rcaisse = $S_Rcaisse[0] + $T_Rcaisse;
                    }



                    $sqlV = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE p.iduser='" . $_SESSION['iduser'] . "' && (l.classe=0 || l.classe=1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "' && p.type=0  ORDER BY p.idPagnet DESC";

                    $resV = mysql_query($sqlV) or die("persoonel requête 2" . mysql_error());

                    $T_ventes = 0;

                    $S_ventes = 0;

                    while ($pagnet = mysql_fetch_assoc($resV)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where iduser='" . $_SESSION['iduser'] . "' && idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && type=0 && idPagnet='" . $pagnet['idPagnet'] . "'  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_ventes = mysql_fetch_array($resS);

                        $T_ventes = $S_ventes[0] + $T_ventes;
                    }



                    $sqlR = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE p.iduser='" . $_SESSION['iduser'] . "' && l.classe=0 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "' && p.type=1  ORDER BY p.idPagnet DESC";

                    $resR = mysql_query($sqlR) or die("persoonel requête 2" . mysql_error());

                    $T_Rpagnet = 0;

                    $S_Rpagnet = 0;

                    while ($pagnet = mysql_fetch_assoc($resR)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where iduser='" . $_SESSION['iduser'] . "' && idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && type=1 && idPagnet='" . $pagnet['idPagnet'] . "'  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_Rpagnet = mysql_fetch_array($resS);

                        $T_Rpagnet = $S_Rpagnet[0] + $T_Rpagnet;
                    }



                    $sqlTD = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE p.iduser='" . $_SESSION['iduser'] . "' && l.classe=3 && l.quantite=1 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "'  ORDER BY p.idPagnet DESC";

                    $resTD = mysql_query($sqlTD) or die("persoonel requête 2" . mysql_error());

                    $T_depot = 0;

                    $S_depot = 0;

                    while ($pagnet = mysql_fetch_assoc($resTD)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where iduser='" . $_SESSION['iduser'] . "' && idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && idPagnet='" . $pagnet['idPagnet'] . "'  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_depot = mysql_fetch_array($resS);

                        $T_depot = $S_depot[0] + $T_depot;
                    }



                    $sqlTR = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE p.iduser='" . $_SESSION['iduser'] . "' && l.classe=3 && l.quantite=0 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "'  ORDER BY p.idPagnet DESC";

                    $resTR = mysql_query($sqlTR) or die("persoonel requête 2" . mysql_error());

                    $T_retrait = 0;

                    $S_retrait = 0;

                    while ($pagnet = mysql_fetch_assoc($resTR)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where iduser='" . $_SESSION['iduser'] . "' && idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && idPagnet='" . $pagnet['idPagnet'] . "'  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_retrait = mysql_fetch_array($resS);

                        $T_retrait = $S_retrait[0] + $T_retrait;
                    }



                    $sqlTF = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE p.iduser='" . $_SESSION['iduser'] . "' && l.classe=3 && (l.quantite!=0 && l.quantite!=1)  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "'  ORDER BY p.idPagnet DESC";

                    $resTF = mysql_query($sqlTF) or die("persoonel requête 2" . mysql_error());

                    $T_facture = 0;

                    $S_facture = 0;

                    while ($pagnet = mysql_fetch_assoc($resTF)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where iduser='" . $_SESSION['iduser'] . "' && idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && idPagnet='" . $pagnet['idPagnet'] . "'  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_facture = mysql_fetch_array($resS);

                        $T_facture = $S_facture[0] + $T_facture;
                    }



                    $sqlTC = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE p.iduser='" . $_SESSION['iduser'] . "' && l.classe=4  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "'  ORDER BY p.idPagnet DESC";

                    $resTC = mysql_query($sqlTC) or die("persoonel requête 2" . mysql_error());

                    $T_credit = 0;

                    $S_credit = 0;

                    while ($pagnet = mysql_fetch_assoc($resTC)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where iduser='" . $_SESSION['iduser'] . "' && idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && idPagnet='" . $pagnet['idPagnet'] . "'  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_credit = mysql_fetch_array($resS);

                        $T_credit = $S_credit[0] + $T_credit;
                    }



                    $sqlD = "SELECT DISTINCT p.idPagnet

                        FROM `" . $nomtablePagnet . "` p

                        INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                        WHERE p.iduser='" . $_SESSION['iduser'] . "' && l.classe=2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='" . $dateString2 . "'  && p.type=0  ORDER BY p.idPagnet DESC";

                    $resD = mysql_query($sqlD) or die("persoonel requête 2" . mysql_error());

                    $T_depenses = 0;

                    $S_depenses = 0;

                    while ($pagnet = mysql_fetch_assoc($resD)) {

                        $sqlS = "SELECT SUM(apayerPagnet)

                        FROM `" . $nomtablePagnet . "`

                        where iduser='" . $_SESSION['iduser'] . "' && idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && idPagnet='" . $pagnet['idPagnet'] . "'  && type=0  ORDER BY idPagnet DESC";

                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                        $S_depenses = mysql_fetch_array($resS);

                        $T_depenses = $S_depenses[0] + $T_depenses;
                    }



                    $sqlP5 = "SELECT SUM(montant) FROM `" . $nomtableVersement . "` where idClient!=0  && iduser='" . $_SESSION['iduser'] . "' && (dateVersement  ='" . $dateString2 . "' || dateVersement  ='" . $dateString . "')  ORDER BY idVersement DESC";

                    $resP5 = mysql_query($sqlP5) or die("persoonel requête 2" . mysql_error());

                    $T_versements = mysql_fetch_array($resP5);
                }



                if ($_SESSION['Pays'] == 'Canada') {

                    $T_caisse = $T_App + ($T_ventes + (($T_ventes * 5) / 100) + (($T_ventes * 9.975) / 100)) + $T_depot + $T_facture + $T_credit - $T_Rpagnet - $T_depenses - $T_retrait - $T_Rcaisse;
                } else {

                    $T_caisse = $T_App + $T_ventes + $T_depot + $T_facture + $T_credit - $T_Rpagnet - $T_depenses - $T_retrait - $T_Rcaisse;
                }



                ?>



                <div class="jumbotron">

                    <div class="col-sm-4 pull-right">

                        <form name="searchDesignationForm" id="searchDesignationForm" method="post">

                            <div class="form-group">

                                <input type="text" class="form-control" placeholder="Recherche Prix..." id="designationInfo" name="designation" autocomplete="off" size="100" />


                            </div>

                        </form>

                    </div>

                    <h2>Journal de caisse du : <?php echo $dateString2; ?></h2>

                    <?php if ($_SESSION['proprietaire'] == 1 || $_SESSION['gerant'] == 1 || $_SESSION['caissierAccess'] == 1 || $userAcces['accesTotalVente'] == 1) { ?>

                        <div class="panel-group">

                            <div class="panel" style="background:#cecbcb;">

                                <div class="panel-heading">

                                    <h4 class="panel-title">

                                        <a data-toggle="collapse" href="#collapse1">Total opérations </a>

                                    </h4>

                                </div>

                                <div id="collapse1" class="panel-collapse collapse">

                                    <div class="panel-heading" style="margin-left:2%;">
                                        <?php echo ' <h6>Total : ' . number_format((($T_caisse + $T_versements[0]) * $_SESSION['devise']), 2, ',', ' ') . ' ' . $_SESSION['symbole'] . '   </h6> '; ?>
                                        <?php

                                        $sqlv = "select * from `" . $nomtableDesignation . "` where classe=5 ";

                                        $resv = mysql_query($sqlv);

                                        if (mysql_num_rows($resv)) {

                                            echo ' <h6>Approvisionnement Caisse : ' . number_format(($T_App * $_SESSION['devise']), 2, ',', ' ') . ' ' . $_SESSION['symbole'] . '   </h6> ';
                                        }

                                        ?>

                                        <?php

                                        $sqlv = "select * from `" . $nomtableDesignation . "` where classe=7 ";

                                        $resv = mysql_query($sqlv);

                                        if (mysql_num_rows($resv)) {

                                            echo ' <h6>Retirement Caisse : ' . number_format(($T_Rcaisse * $_SESSION['devise']), 2, ',', ' ') . ' ' . $_SESSION['symbole'] . '    </h6> ';
                                        }

                                        ?>

                                        <?php

                                        $sqlv = "select * from `" . $nomtableDesignation . "` where (classe=1 || classe=2)";

                                        $resv = mysql_query($sqlv);

                                        if (mysql_num_rows($resv)) {

                                            if ($_SESSION['Pays'] == 'Canada') {

                                                /**echo' <h6>Sous-total Ventes : '. ($T_ventes * $_SESSION['devise']).' '.$_SESSION['symbole'].'   </h6> ';

                                                echo' <h6>TPS à 5% Ventes : '. ((($T_ventes * 5)/100) * $_SESSION['devise']).' '.$_SESSION['symbole'].'   </h6> ';

                                                echo' <h6>TVQ à 9.975 Ventes : '. ((($T_ventes * 9.975)/100) * $_SESSION['devise']).' '.$_SESSION['symbole'].'   </h6> ';**/

                                                echo ' <h6>Total Ventes : ' . number_format((($T_ventes + (($T_ventes * 5) / 100) + (($T_ventes * 9.975) / 100)) * $_SESSION['devise']), 2, ',', ' ') . ' ' . $_SESSION['symbole'] . '   </h6> ';
                                            } else {

                                                echo ' <h6>Ventes : ' . number_format(($T_ventes * $_SESSION['devise']), 2, ',', ' ') . ' ' . $_SESSION['symbole'] . '    </h6> ';
                                            }
                                        }

                                        ?>

                                        <?php

                                        $sqlb = "select * from `" . $nomtableDesignation . "` where (classe=1 || classe=2)";

                                        $resb = mysql_query($sqlb);

                                        if (mysql_num_rows($resb)) {

                                            if ($_SESSION['Pays'] == 'Canada') {

                                                /**echo' <h6>Sous-total Ventes : '. ($T_ventes * $_SESSION['devise']).' '.$_SESSION['symbole'].'   </h6> ';

                                                echo' <h6>TPS à 5% Ventes : '. ((($T_ventes * 5)/100) * $_SESSION['devise']).' '.$_SESSION['symbole'].'   </h6> ';

                                                echo' <h6>TVQ à 9.975 Ventes : '. ((($T_ventes * 9.975)/100) * $_SESSION['devise']).' '.$_SESSION['symbole'].'   </h6> ';**/

                                                echo ' <h6>Total Bons : ' . number_format((($T_bons + (($T_bons * 5) / 100) + (($T_bons * 9.975) / 100)) * $_SESSION['devise']), 2, ',', ' ') . ' ' . $_SESSION['symbole'] . '   </h6> ';
                                            } else {

                                                echo ' <h6>Bons : ' . number_format(($T_bons * $_SESSION['devise']), 2, ',', ' ') . ' ' . $_SESSION['symbole'] . '    </h6> ';
                                            }
                                        }

                                        ?>

                                        <?php

                                        $sqlv = "select * from `" . $nomtablePagnet . "` where idClient=0 &&  verrouiller=1 && datepagej ='" . $dateString2 . "' && type=1";

                                        $resv = mysql_query($sqlv);

                                        if (mysql_num_rows($resv)) {

                                            echo ' <h6>Retour pagnet : ' . number_format(($T_Rpagnet * $_SESSION['devise']), 2, ',', ' ') . ' ' . $_SESSION['symbole'] . '   </h6> ';
                                        }

                                        ?>

                                        <?php

                                        $sqlt = "select * from `" . $nomtableDesignation . "` where classe=1 and uniteStock='Transaction' ";

                                        $rest = mysql_query($sqlt);

                                        if (mysql_num_rows($rest)) {

                                            echo ' <h6>Transaction : ' . number_format((($T_depot + $T_facture - $T_retrait) * $_SESSION['devise']), 2, ',', ' ') . ' ' . $_SESSION['symbole'] . '    </h6>';
                                        }

                                        ?>

                                        <?php

                                        $sqlt = "select * from `" . $nomtableDesignation . "` where classe=1 and uniteStock='Credit' ";

                                        $rest = mysql_query($sqlt);

                                        if (mysql_num_rows($rest)) {

                                            echo ' <h6>Credit : ' . number_format(($T_credit * $_SESSION['devise']), 2, ',', ' ') . ' ' . $_SESSION['symbole'] . '   </h6>';
                                        }

                                        ?>

                                        <?php

                                        $sqld = "select * from `" . $nomtableDesignation . "` where classe=2";

                                        $resd = mysql_query($sqld);

                                        if (mysql_num_rows($resd)) {

                                            echo ' <h6>Depenses : ' . number_format(($T_depenses * $_SESSION['devise']), 2, ',', ' ') . ' ' . $_SESSION['symbole'] . '    </h6>';
                                        }

                                        ?>

                                        <?php

                                        $sqld = "select * from `" . $nomtableVersement . "` where (dateVersement  ='" . $dateString2 . "' || dateVersement  ='" . $dateString . "')";

                                        $resd = mysql_query($sqld);

                                        if (mysql_num_rows($resd)) {

                                            echo ' <h6>Versements : ' . number_format(($T_versements[0] * $_SESSION['devise']), 2, ',', ' ') . ' ' . $_SESSION['symbole'] . '    </h6>';
                                        }

                                        ?>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php } ?>

                </div>



                <!--*******************************Debut Rechercher Produit****************************************-->

                <!--<form  class="pull-right" id="searchProdForm" method="post" name="searchProdForm" >

                    <input type="text" size="30" class="form-control" name="produit" placeholder="Rechercher ..."  id="produitVendu" autocomplete="off" />

                        <span id="reponsePV"></span>

            </form>-->

                <!--*******************************Fin Rechercher Produit****************************************-->

                <?php

                $sql0 = "SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='" . $_SESSION['idBoutique'] . "' and YEAR(datePs)='" . $annee_paie . "' and MONTH(datePs)!='" . $mois . "' and aPayementBoutique=0 ";

                $res0 = mysql_query($sql0);
                $ps = mysql_fetch_array($res0);
                $idPS = @$ps['idPS'];
                $montantFixePayement = @$ps['montantFixePayement'];

                if (mysql_num_rows($res0)) {

                    if ($jour > 0) {

                        if ($jour > 4) {
                            echo ' 
                                <form name="formulairePagnet" method="post" >

                                    <button disabled="true" type="submit" class="btn btn-success noImpr" id="addPanierEt" name="btnSavePagnetVente">
                    
                                        <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                    
                                    </button>
                    
                                </form>
                            ';
                        } else {
                            echo ' 
                                <form name="formulairePagnet" method="post" >

                                    <button type="submit" class="btn btn-success noImpr" id="addPanierEt" name="btnSavePagnetVente">
                    
                                        <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                    
                                    </button>
                    
                                </form>
                            ';
                        }

                        // echo "<h6><span id='blinker' style='color:red;'>VEUILLEZ EFFECTUER LE PAIEMENT DU SERVICE JCAISSE AVANT LE 5 !</span></h6>";
                        echo "<h6><span id='blinker' style='color:red;'>VEUILLEZ EFFECTUER LE PAIEMENT DU SERVICE JCAISSE AVANT LE 5 !</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onclick='selectNbMoisPaiement(" . $idPS . "," . $montantFixePayement . ")' style='text-decoration:underline;'>Payer <img src='images/Wave.png' width='25' height='25'></a>&nbsp;&nbsp;&nbsp;&nbsp;<a hidden href='#' onclick='effectue_paiement(" . $idPS . ")' style='text-decoration:underline;'>Cliquez ici pour payer par OMoney</a></h6>";

                        echo '<br>';
                    } else {

                        echo ' 
                            <form name="formulairePagnet" method="post" >

                                <button type="submit" class="btn btn-success noImpr" id="addPanierEt" name="btnSavePagnetVente">
                
                                    <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                
                                </button>
                
                            </form>
                        ';
                        echo '<br>';
                    }
                } else {

                    echo ' 
                        <form name="formulairePagnet" method="post" >

                            <button type="submit" class="btn btn-success noImpr" id="addPanierEt" name="btnSavePagnetVente">
            
                                <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
            
                            </button>
            
                        </form>
                    ';

                    echo '<br>';
                }

                ?>

                <!-- Debut Style CSS pour eliminer les ascenseurs sur les Input de type Numeric -->

                <style>
                    /* Firefox */

                    input[type=number] {

                        -moz-appearance: textfield;

                    }

                    /* Chrome */

                    input::-webkit-inner-spin-button,

                    input::-webkit-outer-spin-button {

                        -webkit-appearance: none;

                        margin: 0;

                    }

                    /* Opéra*/

                    input::-o-inner-spin-button,

                    input::-o-outer-spin-button {

                        -o-appearance: none;

                        margin: 0
                    }
                </style>

                <!-- Fin Style CSS pour eliminer les ascenseurs sur les Input de type Numeric -->



                <!-- Debut Javascript de l'Accordion pour Tout les Paniers -->

                <script>
                    $(function() {

                        $(".expand").on("click", function() {

                            // $(this).next().slideToggle(200);

                            $expand = $(this).find(">:first-child");



                            if ($expand.text() == "+") {

                                $expand.text("-");

                            } else {

                                $expand.text("+");

                            }

                        });

                    });

                    var blink_speed = 500;

                    var t = setInterval(function() {

                        var ele = document.getElementById('blinker');

                        ele.style.visibility = (ele.style.visibility == 'hidden' ? '' : 'hidden');

                    }, blink_speed);

                    function showPreviewPanier(event, idPagnet) {
                        var file = document.getElementById('input_file_Panier' + idPagnet).value;
                        var reader = new FileReader();
                        reader.onload = function() {
                            var format = file.substr(file.length - 3);
                            var pdf = document.getElementById('output_pdf_Panier' + idPagnet);
                            var image = document.getElementById('output_image_Panier' + idPagnet);
                            if (format == 'pdf') {
                                document.getElementById('output_pdf_Panier' + idPagnet).style.display = "block";
                                document.getElementById('output_image_Panier' + idPagnet).style.display = "none";
                                pdf.src = reader.result;
                            } else {
                                document.getElementById('output_image_Panier' + idPagnet).style.display = "block";
                                document.getElementById('output_pdf_Panier' + idPagnet).style.display = "none";
                                image.src = reader.result;
                            }
                        }
                        reader.readAsDataURL(event.target.files[0]);
                        document.getElementById('btn_upload_Panier' + idPagnet).style.display = "block";
                    }
                </script>

                <!-- Fin Javascript de l'Accordion pour Tout les Paniers -->



                <!-- Debut de l'Accordion pour Tout les Paniers -->

                <div class="panel-group" id="accordion">



                    <?php



                    // On détermine sur quelle page on se trouve

                    if (isset($_GET['page']) && !empty($_GET['page'])) {

                        $currentPage = (int) strip_tags($_GET['page']);
                    } else {

                        $currentPage = 1;
                    }

                    // On détermine le nombre d'articles par page

                    $parPage = 10;



                    if ($_SESSION['proprietaire'] == 1) {

                        if (isset($_POST['produit'])) {

                            $produit = @htmlspecialchars($_POST["produit"]);

                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/

                            $sqlC = "SELECT COUNT(*) FROM `" . $nomtablePagnet . "` p

                                INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                                where p.datepagej ='" . $dateString2 . "' && (p.type=0 || p.type=30 || p.type=2 || p.type=10) && p.verrouiller=1 && l.designation='" . $produit . "' ORDER BY p.idPagnet DESC";

                            $resC = mysql_query($sqlC) or die("persoonel requête 2" . mysql_error());

                            $nbre = mysql_fetch_array($resC);

                            $nbPaniers = (int) $nbre[0];

                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/



                            echo '<input type="hidden" id="produitAfter"  value="' . $produit . '"  >';



                            // On calcule le nombre de pages total

                            $pages = ceil($nbPaniers / $parPage);



                            $premier = ($currentPage * $parPage) - $parPage;



                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/

                            $sqlP1 = "SELECT * FROM `" . $nomtablePagnet . "` p

                                INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                                where p.datepagej ='" . $dateString2 . "' && (p.type=0 || p.type=30 || p.type=2 || p.type=10) && p.verrouiller=1 && l.designation='" . $produit . "' ORDER BY p.idPagnet DESC LIMIT " . $premier . "," . $parPage . " ";

                            $resP1 = mysql_query($sqlP1) or die("persoonel requête 2" . mysql_error());

                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/
                        } else {

                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/

                            $sqlC = "SELECT COUNT(*) FROM `" . $nomtablePagnet . "` where datepagej ='" . $dateString2 . "' && (type=0 || type=30 || type=2 || type=10) && verrouiller=1  ORDER BY idPagnet DESC";

                            $resC = mysql_query($sqlC) or die("persoonel requête 2" . mysql_error());

                            $nbre = mysql_fetch_array($resC);

                            $nbPaniers = (int) $nbre[0];

                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/

                            // var_dump($nbPaniers);

                            // On calcule le nombre de pages total

                            $pages = ceil($nbPaniers / $parPage);



                            $premier = ($currentPage * $parPage) - $parPage;



                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/

                            $sqlP1 = "SELECT * FROM `" . $nomtablePagnet . "` where datepagej ='" . $dateString2 . "' && (type=0 || type=30 || type=2 || type=10) && verrouiller=1  ORDER BY idPagnet DESC LIMIT " . $premier . "," . $parPage . " ";

                            $resP1 = mysql_query($sqlP1) or die("persoonel requête 2" . mysql_error());

                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/
                        }



                        /**Debut requete pour Lister les Paniers en cours d'Aujourd'hui (2 aux maximum) **/

                        $sqlP0 = "SELECT * FROM `" . $nomtablePagnet . "` where iduser='" . $_SESSION['iduser'] . "' && datepagej ='" . $dateString2 . "' && verrouiller=0 ORDER BY idPagnet DESC";

                        $resP0 = mysql_query($sqlP0) or die("persoonel requête 2" . mysql_error());

                        /**Fin requete pour Lister les Paniers en cours d'Aujourd'hui (2 aux maximum) **/



                        /**Debut requete pour Lister tout les Paniers d'Aujourd'hui  **/

                        $sql2 = "SELECT * FROM `" . $nomtablePagnet . "` where datepagej ='" . $dateString2 . "' ORDER BY idPagnet DESC";

                        $res2 = mysql_query($sql2) or die("persoonel requête 2" . mysql_error());

                        /**Fin requete pour Lister tout les Paniers d'Aujourd'hui  **/



                        /**Debut requete pour Rechercher le dernier Panier Ajouter  **/

                        $reqA = "SELECT idPagnet from `" . $nomtablePagnet . "` order by idPagnet desc limit 1";

                        $resA = mysql_query($reqA) or die("persoonel requête 2" . mysql_error());

                        /**Fin requete pour Rechercher le dernier Panier Ajouter  **/
                    } else {

                        if (isset($_POST['produit'])) {

                            $produit = @htmlspecialchars($_POST["produit"]);

                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/

                            $sqlC = "SELECT COUNT(*) FROM `" . $nomtablePagnet . "` p

                                INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                                where p.iduser='" . $_SESSION['iduser'] . "' && (p.type=0 || p.type=30 || p.type=10 || p.type=2) && p.datepagej ='" . $dateString2 . "' && p.verrouiller=1 && l.designation='" . $produit . "' ORDER BY p.idPagnet DESC";

                            $resC = mysql_query($sqlC) or die("persoonel requête 2" . mysql_error());

                            $nbre = mysql_fetch_array($resC);

                            $nbPaniers = (int) $nbre[0];

                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/



                            echo '<input type="hidden" id="produitAfter"  value="' . $produit . '"  >';



                            // On calcule le nombre de pages total

                            $pages = ceil($nbPaniers / $parPage);



                            $premier = ($currentPage * $parPage) - $parPage;



                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/

                            $sqlP1 = "SELECT * FROM `" . $nomtablePagnet . "` p

                                INNER JOIN `" . $nomtableLigne . "` l ON l.idPagnet = p.idPagnet

                                where p.iduser='" . $_SESSION['iduser'] . "' && (p.type=0 || p.type=30 || p.type=10 || p.type=2) && p.datepagej ='" . $dateString2 . "' && p.verrouiller=1 && l.designation='" . $produit . "' ORDER BY p.idPagnet DESC LIMIT " . $premier . "," . $parPage . " ";

                            $resP1 = mysql_query($sqlP1) or die("persoonel requête 2" . mysql_error());

                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/
                        } else {

                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/

                            $sqlC = "SELECT COUNT(*) FROM `" . $nomtablePagnet . "` where iduser='" . $_SESSION['iduser'] . "' && (type=0 || type=30 || type=10 || type=2) && datepagej ='" . $dateString2 . "' && verrouiller=1  ORDER BY idPagnet DESC";

                            $resC = mysql_query($sqlC) or die("persoonel requête 2" . mysql_error());

                            $nbre = mysql_fetch_array($resC);

                            $nbPaniers = (int) $nbre[0];

                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/



                            // On calcule le nombre de pages total

                            $pages = ceil($nbPaniers / $parPage);



                            $premier = ($currentPage * $parPage) - $parPage;



                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/

                            $sqlP1 = "SELECT * FROM `" . $nomtablePagnet . "` where iduser='" . $_SESSION['iduser'] . "' && (type=0 || type=30 || type=10 || type=2) && datepagej ='" . $dateString2 . "' && verrouiller=1  ORDER BY idPagnet DESC LIMIT " . $premier . "," . $parPage . " ";

                            $resP1 = mysql_query($sqlP1) or die("persoonel requête 2" . mysql_error());

                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/
                        }



                        /**Debut requete pour Lister les Paniers en cours d'Aujourd'hui (2 aux maximum) **/

                        $sqlP0 = "SELECT * FROM `" . $nomtablePagnet . "` where iduser='" . $_SESSION['iduser'] . "' && datepagej ='" . $dateString2 . "' && verrouiller=0 ORDER BY idPagnet DESC";

                        $resP0 = mysql_query($sqlP0) or die("persoonel requête 2" . mysql_error());

                        /**Fin requete pour Lister les Paniers en cours d'Aujourd'hui (2 aux maximum) **/



                        /**Debut requete pour Lister tout les Paniers d'Aujourd'hui  **/

                        $sql2 = "SELECT * FROM `" . $nomtablePagnet . "` where iduser='" . $_SESSION['iduser'] . "' && datepagej ='" . $dateString2 . "' ORDER BY idPagnet DESC";

                        $res2 = mysql_query($sql2) or die("persoonel requête 2" . mysql_error());

                        /**Fin requete pour Lister tout les Paniers d'Aujourd'hui  **/



                        /**Debut requete pour Rechercher le dernier Panier Ajouter  **/

                        $reqA = "SELECT idPagnet from `" . $nomtablePagnet . "` where iduser='" . $_SESSION['iduser'] . "' order by idPagnet desc limit 1";

                        $resA = mysql_query($reqA) or die("persoonel requête 2" . mysql_error());

                        /**Fin requete pour Rechercher le dernier Panier Ajouter  **/
                    }



                    ?>


                    <input type="hidden" value="<?= $_SESSION['venterapideEt']; ?>">
                    <!-- Debut Boucle while concernant les Paniers en cours (2 aux maximum) -->

                    <?php while ($pagnet = mysql_fetch_assoc($resP0)) { ?>

                        <?php

                        if (($pagnet['type'] == 0) || ($pagnet['type'] == 30)) {   ?>

                            <div class="panel <?= ($pagnet['idClient'] == 0) ? 'panel-primary' : 'panel-info' ?>">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet" . $pagnet['idPagnet'] . ""; ?> class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a class="row" href="#">

                                            <span class="noImpr col-md-2 col-sm-2 col-xs-12"> Panier
                                                <?php echo ': En cours ...'; ?></span>

                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12"> Date:
                                                <?php echo $pagnet['datepagej'] . " " . $pagnet['heurePagnet']; ?> </span>

                                            <!-- <span class="spanDate noImpr col-md-2 col-sm-2 col-xs-12">Heure: <?php // $pagnet['heurePagnet']; 
                                                                                                                    ?></span> -->

                                            <?php $sqlT = "SELECT SUM(prixtotal) FROM `" . $nomtableLigne . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resT = mysql_query($sqlT) or die("persoonel requête 2" . mysql_error());

                                            $TotalT = mysql_fetch_array($resT);

                                            ?>

                                            <?php if ($_SESSION['Pays'] == 'Canada') {   ?>

                                                <span class="noImpr col-md-3 col-sm-3 col-xs-12">Sous-Total : <span <?php echo  "id=somme_Apayer" . $pagnet['idPagnet']; ?>><?php echo $TotalT[0]; ?>
                                                    </span></span>

                                                <span class="noImpr col-md-3 col-sm-3 col-xs-12"> Total : <span <?php echo  "id=somme_ApayerT" . $pagnet['idPagnet']; ?>><?php echo ($TotalT[0] + (($TotalT[0] * 5) / 100) + (($TotalT[0] * 9.975) / 100)); ?>
                                                    </span></span>

                                            <?php } else {   ?>

                                                <span class="noImpr col-md-3 col-sm-3 col-xs-12">Total panier: <span <?php echo  "id=somme_Total" . $pagnet['idPagnet']; ?>><?php echo $TotalT[0]; ?>
                                                    </span></span>

                                                <span class="noImpr col-md-3 col-sm-3 col-xs-12">Total à payer: <span <?php echo  "id=somme_Apayer" . $pagnet['idPagnet']; ?>><?php echo $TotalT[0]; ?>
                                                    </span></span>

                                            <?php } ?>

                                        </a>

                                    </h4>

                                </div>

                                <div <?php echo  "id=pagnet" . $pagnet['idPagnet'] . ""; ?> class="panel-collapse collapse in">

                                    <div class="panel-body">

                                        <div class="cache_btn_Terminer row">

                                            <!--*******************************Debut Ajouter Ligne****************************************-->

                                            <form class="form-inline noImpr ajouterProdForm col-md-4 col-sm-4 col-xs-12" id="ajouterProdFormEt<?= $pagnet['idPagnet']; ?>" onsubmit="return false">

                                                <input type="text" class="inputbasic form-control codeBarreLigneEt" name="codeBarre" id="panier_<?= $pagnet['idPagnet']; ?>" style="width:100%;" autofocus="" autocomplete="off" required />

                                                <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                <!-- <span id="reponseV"></span> -->

                                                <!-- <button type="submit" name="btnEnregistrerCodeBarre"

                                                    id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs " ><span class="glyphicon glyphicon-plus" ></span>

                                                    </button> -->

                                            </form>

                                            <!--*******************************Fin Ajouter Ligne****************************************-->

                                            <div class="col-md-8 col-sm-8 col-xs-12 content2">

                                                <!--*******************************Debut Terminer Pagnet****************************************-->

                                                <form class="form-inline noImpr" id="factForm" method="post">

                                                    <input type="number" <?php echo "id=val_remise" . $pagnet['idPagnet']; ?> name="remise" onkeyup="modif_Remise(this.value, <?php echo  $pagnet['idPagnet']; ?>)" class="remise form-control col-md-2 col-sm-2 col-xs-3" placeholder="Remise....">



                                                    <?php if ($_SESSION['compte'] == 1) { ?>

                                                        <select class="compte <?= $pagnet['idPagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $pagnet['idPagnet']; ?>" name="compte" onchange="showImageCompte(this.value,<?= $pagnet['idPagnet']; ?>)" <?php echo  "id=compte" . $pagnet['idPagnet']; ?>>

                                                            <!-- <option value="caisse">Caisse</option> -->

                                                            <?php

                                                            if ($pagnet['idCompte'] != 0) {



                                                                $sqlGetCompte3 = "SELECT * FROM `" . $nomtableCompte . "` where idCompte = " . $pagnet['idCompte'];

                                                                $resPay3 = mysql_query($sqlGetCompte3) or die("persoonel requête 2" . mysql_error());

                                                                $cpt = mysql_fetch_array($resPay3);

                                                            ?>

                                                                <option value="<?= $pagnet['idCompte']; ?>"><?= $cpt['nomCompte']; ?>
                                                                </option>

                                                            <?php }

                                                            foreach ($cpt_array as $key) { ?>

                                                                <option id="idImageCompte<?= $key['idCompte']; ?>" data-image="<?= $key['image']; ?>" value="<?= $key['idCompte']; ?>">
                                                                    <?= $key['nomCompte']; ?></option>

                                                            <?php } ?>

                                                        </select>





                                                        <?php if ($pagnet['type'] == '30') {



                                                            $sql3 = "SELECT * FROM `" . $nomtableClient . "` where idClient=" . $pagnet['idClient'] . "";

                                                            $res3 = mysql_query($sql3) or die("persoonel requête 3" . mysql_error());

                                                            $client = mysql_fetch_assoc($res3);

                                                        ?>

                                                            <input type="text" class="client clientInput form-control col-md-2 col-sm-2 col-xs-3" data-type="simple" data-idPanier="<?= $pagnet['idPagnet']; ?>" name="clientInput" id="clientInput<?= $pagnet['idPagnet']; ?>" autocomplete="off" value="<?= $client['prenom'] . " " . $client['nom']; ?>" />

                                                            <input type="number" class="avanceInput form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $pagnet['idPagnet']; ?>" name="avanceInput" id="avanceInput<?= $pagnet['idPagnet']; ?>" autocomplete="off" value="<?= $pagnet['avance']; ?>" />

                                                            <select class="compteAvance <?= $pagnet['idPagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $pagnet['idPagnet']; ?>" name="compteAvance" onchange="showImageCompte(this.value,<?= $pagnet['idPagnet']; ?>)" <?php echo  "id=compteAvance" . $pagnet['idPagnet']; ?>>

                                                                <!-- <option value="caisse">Caisse</option> -->

                                                                <?php

                                                                if ($pagnet['idCompte'] != 0) {



                                                                    $sqlGetCompte3 = "SELECT * FROM `" . $nomtableCompte . "` where idCompte = " . $pagnet['idCompte'];

                                                                    $resPay3 = mysql_query($sqlGetCompte3) or die("persoonel requête 2" . mysql_error());

                                                                    $cpt = mysql_fetch_array($resPay3);

                                                                ?>

                                                                    <option value="<?= $pagnet['idCompte']; ?>"><?= $cpt['nomCompte']; ?>
                                                                    </option>

                                                                    <?php }

                                                                foreach ($cpt_array as $key) {

                                                                    if ($key['idCompte'] != 2) {

                                                                    ?>

                                                                        <option id="idImageCompte<?= $key['idCompte']; ?>" data-image="<?= $key['image']; ?>" value="<?= $key['idCompte']; ?>">
                                                                            <?= $key['nomCompte']; ?></option>

                                                                <?php }
                                                                } ?>

                                                            </select>

                                                        <?php

                                                            # code...

                                                        } else {

                                                            # code...                                                            

                                                        ?>

                                                            <input type="text" class="client clientInput form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-type="simple" data-idPanier="<?= $pagnet['idPagnet']; ?>" name="clientInput" id="clientInput<?= $pagnet['idPagnet']; ?>" autocomplete="off" placeholder="Client" />

                                                            <input type="number" class="avanceInput form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-idPanier="<?= $pagnet['idPagnet']; ?>" name="avanceInput" id="avanceInput<?= $pagnet['idPagnet']; ?>" autocomplete="off" placeholder="Avance" />

                                                            <select class="compteAvance <?= $pagnet['idPagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-idPanier="<?= $pagnet['idPagnet']; ?>" name="compteAvance" onchange="showImageCompte(this.value,<?= $pagnet['idPagnet']; ?>)" <?php echo  "id=compteAvance" . $pagnet['idPagnet']; ?>>

                                                                <!-- <option value="caisse">Caisse</option> -->

                                                                <?php foreach ($cpt_array as $key) {

                                                                    if ($key['idCompte'] != 2) {

                                                                ?>

                                                                        <option id="idImageCompte<?= $key['idCompte']; ?>" data-image="<?= $key['image']; ?>" value="<?= $key['idCompte']; ?>">
                                                                            <?= $key['nomCompte']; ?></option>

                                                                <?php }
                                                                } ?>

                                                            </select>

                                                        <?php } ?>



                                                    <?php } ?>



                                                    <?php if ($_SESSION['Pays'] == 'Canada') {  ?>

                                                        <input type="number" name="versement" id="versement<?= $pagnet['idPagnet']; ?>" <?= (@$pagnet['idCompte'] != 0) ? 'style="display:none;"' : ''; ?> class="versement form-control col-md-2 col-sm-2 col-xs-3" placeholder="Espèce...">



                                                    <?php } else {   ?>

                                                        <input type="number" name="versement" id="versement<?= $pagnet['idPagnet']; ?>" <?= (@$pagnet['idCompte'] != 0) ? 'style="display:none;"' : ''; ?> class="versement form-control col-md-2 col-sm-2 col-xs-3" placeholder="Espèce...">



                                                    <?php } ?>



                                                    <input type="hidden" name="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                    <input type="hidden" name="totalp" <?php echo  "id=totalp" . $pagnet['idPagnet'] . ""; ?> value="<?php echo $pagnet['totalp']; ?>">

                                                    <!-- <div class="btnAction col-md-1 col-xs-12"> -->

                                                    <button type="submit" style="" name="btnImprimerFacture" <?php echo  "id=btnImprimerFacture" . $pagnet['idPagnet'] . ""; ?> class="btn btn-success terminer" data-toggle="modal"><span class="glyphicon glyphicon-ok"></span></button>

                                                    <button tabindex="1" type="button" class="btn btn-danger annuler" data-toggle="modal" <?php echo  "data-target=#msg_ann_pagnet" . $pagnet['idPagnet']; ?>>

                                                        <span class="glyphicon glyphicon-remove"></span>

                                                    </button>

                                                    <!-- </div> -->

                                                </form>

                                                <!--*******************************Fin Terminer Pagnet****************************************-->

                                                <!--*******************************Debut Annuler Pagnet****************************************-->

                                                <!-- <div class="col-md-1 col-sm-1 col-xs-1">   -->

                                                <!-- </div>   -->



                                                <div class="modal fade" <?php echo  "id=msg_ann_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

                                                    <div class="modal-dialog">

                                                        <!-- Modal content-->

                                                        <div class="modal-content">

                                                            <div class="modal-header panel-primary">

                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                <h4 class="modal-title">Confirmation</h4>

                                                            </div>

                                                            <form class="form-inline noImpr" id="factForm" method="post">

                                                                <div class="modal-body">

                                                                    <p><?php echo "Voulez-vous annuler le panier numéro <b>" . $pagnet['idPagnet'] . "</b>"; ?>
                                                                    </p>

                                                                    <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value='" . $pagnet['idPagnet'] . "'"; ?>>

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


                                            <!-- Modal paiement multiple-->
                                            <div class="modal fade" id="paiementMultipleModal-<?= $pagnet['idPagnet']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" align="">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" style="float:left" id="exampleModalLabel">Modal
                                                                title</h5>
                                                            <button type="button" style="float:right" class="btn-close btn-danger" data-dismiss="modal"><b>&times;</b></button>
                                                        </div>
                                                        <div class="modal-body" style="text-align:left">
                                                            <?php foreach ($cpt_array2 as $key) { ?>
                                                                <div class="form-check row" style="margin:5px">
                                                                    <!-- <div class="col-lg-1"></div> -->
                                                                    <div class="col-lg-1">
                                                                        <input class="form-check-input checkCompte" name="checkCompte" onChange="checkedCompte(<?= $key['idCompte']; ?>,<?= $pagnet['idPagnet']; ?>)" type="checkbox" data-idCompte="<?= $key['idCompte']; ?>" id="compte_<?= $key['idCompte'] . "_" . $pagnet['idPagnet']; ?>">
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <label class="form-check-label" for="compte_<?= $key['idCompte'] . "_" . $pagnet['idPagnet']; ?>">
                                                                            <?= strtoupper($key['nomCompte']); ?>
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <input type="number" class="form-control" style="display:none" name="montantCompte_<?= $key['idCompte'] . "_" . $pagnet['idPagnet']; ?>" id="montantCompte_<?= $key['idCompte'] . "_" . $pagnet['idPagnet']; ?>" placeholder="Montant <?= $key['nomCompte']; ?>">
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <code id="textAlert-<?= $pagnet['idPagnet']; ?>" style="display:none" align="center">Attention!!! <br> Le montant à payer et les montants entre les comptes ne sont pas conformes. <br></code>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                            <button type="button" onClick="validerCptMultiple(<?= $pagnet['idPagnet']; ?>)" class="btn btn-primary btnValidCptMult" data-idCompte="<?= $key['idCompte']; ?>">Valider</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <table <?php echo  "id=tablePanier" . $pagnet['idPagnet'] . ""; ?> class="tabPanier table" width="100%">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th>Référence</th>

                                                    <th>Quantité</th>

                                                    <th>Unité vente</th>

                                                    <th>Prix </th>

                                                    <th>Depot</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php

                                                $sql8 = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $pagnet['idPagnet'] . " ORDER BY numligne DESC";

                                                $res8 = mysql_query($sql8) or die("persoonel requête 2" . mysql_error());

                                                while ($ligne = mysql_fetch_assoc($res8)) { ?>

                                                    <tr>

                                                        <td class="designation">

                                                            <?php

                                                            if ($ligne['classe'] == 2) {

                                                                echo '<input class="form-control" style="width: 100%"  type="text" value="' . $ligne['designation'] . '"

                                                                                onkeyup="modif_DesignationBon(this.value,' . $ligne['numligne'] . ',' . $pagnet['idPagnet'] . ')" />

                                                                            ';
                                                            } else { ?>

                                                            <?php echo $ligne['designation'];
                                                            }

                                                            ?>

                                                        </td>

                                                        <td>

                                                            <?php if (($ligne['unitevente'] == 'Transaction') || ($ligne['unitevente'] == 'Facture') || ($ligne['unitevente'] == 'Credit')) { ?>



                                                            <?php } else { ?>

                                                                <?php

                                                                if ($ligne['classe'] == 0) {

                                                                    if ($ligne['idStock'] != 0) {

                                                                        $sqlS = "SELECT * FROM `" . $nomtableStock . "` where idStock=" . $ligne['idStock'];

                                                                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                                                                        $stock = mysql_fetch_assoc($resS);



                                                                        $sqlD = "SELECT * FROM `" . $nomtableDesignation . "` where idDesignation=" . $stock['idDesignation'];

                                                                        $resD = mysql_query($sqlD) or die("select stock impossible =>" . mysql_error());

                                                                        $designation = mysql_fetch_assoc($resD);



                                                                        if ($ligne['unitevente'] == $designation['uniteDetails']) {

                                                                ?>

                                                                            <input class="quantite form-control" <?php echo  "id=ligne" . $ligne['numligne'] . ""; ?> <?= (@in_array($ligne['numligne'], $ligneIns)) ? 'style="background-color:#FC8080;' : ''; ?> onkeyup="modif_Quantite(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  $designation['nbreArticleUniteStock']; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 25%" size="3" type="number" <?php echo  "id=quantite" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                        <?php

                                                                        } else if ($ligne['unitevente'] == $designation['uniteStock']) {

                                                                        ?>

                                                                            <input class="quantite form-control" <?php echo  "id=ligne" . $ligne['numligne'] . ""; ?> <?= (@in_array($ligne['numligne'], $ligneIns)) ? 'style="background-color:#FC8080;' : ''; ?> onkeyup="modif_Quantite(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  '1'; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 25%" size="3" type="number" <?php echo  "id=quantite" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                        <?php }
                                                                    } else {

                                                                        $sqlD = "SELECT * FROM `" . $nomtableDesignation . "` where idDesignation=" . $ligne['idDesignation'];

                                                                        $resD = mysql_query($sqlD) or die("select Designation impossible =>" . mysql_error());

                                                                        $designation = mysql_fetch_assoc($resD);

                                                                        ?>

                                                                        <input class="quantite form-control" <?php echo  "id=ligne" . $ligne['numligne'] . ""; ?> <?= (@in_array($ligne['numligne'], $ligneIns)) ? 'style="background-color:#FC8080;' : ''; ?> onkeyup="modif_QuantiteET(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" size="4" type="number" <?php echo  "id=quantite" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                    <?php

                                                                    }
                                                                }

                                                                if ($ligne['classe'] == 1) { ?>

                                                                    <input class="quantite form-control" <?php echo  "id=ligne" . $ligne['numligne'] . ""; ?> onkeyup="modif_QuantiteSD(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                <?php

                                                                }

                                                                if ($ligne['classe'] == 5 || $ligne['classe'] == 7) { ?>

                                                                    <?php echo 'Montant'; ?>

                                                            <?php }
                                                            } ?>

                                                        </td>

                                                        <td class="unitevente ">

                                                            <?php

                                                            if ($ligne['classe'] == 0) {

                                                                if ($ligne['idStock'] != 0) { ?>

                                                                    <?php echo $ligne['unitevente']; ?>

                                                                <?php

                                                                } else {

                                                                ?>

                                                                    <?php

                                                                    if ($ligne['unitevente'] == $designation['uniteStock']) {

                                                                    ?>

                                                                        <select name="uniteVente" class="form-control" onchange="modif_UniteStockET(this.value)">

                                                                            <?php

                                                                            $reqP = 'SELECT * from  `' . $nomtableDesignation . '` where designation="' . $ligne['designation'] . '"';

                                                                            $resP = mysql_query($reqP) or die("select stock impossible =>" . mysql_error());

                                                                            if (mysql_num_rows($resP)) {

                                                                                $produit = mysql_fetch_assoc($resP);

                                                                                echo '<option value="' . $produit["uniteStock"] . '§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§1">' . $produit["uniteStock"] . '</option>';

                                                                                echo '<option value="Demi Gros§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§2">Demi Gros</option>';

                                                                                echo '<option value="Piece§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§1">Piece</option>';
                                                                            }

                                                                            ?>

                                                                        </select>

                                                                    <?php

                                                                    } else {

                                                                    ?>

                                                                        <select name="uniteVente" class="form-control" onchange="modif_UniteStockET(this.value)">

                                                                            <?php

                                                                            $reqP = 'SELECT * from  `' . $nomtableDesignation . '` where designation="' . $ligne['designation'] . '"';

                                                                            $resP = mysql_query($reqP) or die("select stock impossible =>" . mysql_error());

                                                                            if (mysql_num_rows($resP)) {

                                                                                $produit = mysql_fetch_assoc($resP);

                                                                                $demi = $ligne['quantite'] * 2;

                                                                                if ($produit['nbreArticleUniteStock'] == $demi) {

                                                                                    echo '<option value="Demi Gros§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§2">Demi Gros</option>';

                                                                                    echo '<option value="Piece§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§1">Piece</option>';
                                                                                } else {

                                                                                    echo '<option value="Piece§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§1">Piece</option>';

                                                                                    echo '<option value="Demi Gros§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§2">Demi Gros</option>';
                                                                                }

                                                                                if (($produit["uniteStock"] != '') && ($produit["uniteStock"] != null)) {

                                                                                    echo '<option value="' . $produit["uniteStock"] . '§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§1">' . $produit["uniteStock"] . '</option>';
                                                                                }
                                                                            }

                                                                            ?>

                                                                        </select>

                                                                    <?php

                                                                    }

                                                                    ?>

                                                                <?php

                                                                }
                                                            } else if ($ligne['classe'] == 6) { ?>

                                                                <input class="form-control" style="width: 70%" type="text" <?php echo  "id=especes" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['unitevente'] . ""; ?> onkeyup="modif_Espece(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)">

                                                            <?php

                                                            } else  if ($ligne['classe'] != 2 && $ligne['classe'] != 0 && $ligne['classe'] != 6) { ?>

                                                                <?php echo $ligne['unitevente']; ?>

                                                            <?php

                                                            }

                                                            ?>

                                                        </td>

                                                        <td>

                                                            <input <?php echo  "id=prixUniteStock" . $ligne['numligne'] . ""; ?> class="prixunitevente form-control" size="6" type="number" <?php echo  "id=prixunitevente" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['prixunitevente'] . ""; ?> onkeyup="modif_Prix(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)">

                                                        </td>

                                                        <td>

                                                            <?php if ($ligne['classe'] == 0) : ?>

                                                                <select class="form-control" onchange="modif_Depot(this.value)">

                                                                    <?php

                                                                    $reqEp = "SELECT * from  `" . $nomtableEntrepot . "` e

                                                                                    where idEntrepot='" . $ligne['idEntrepot'] . "' ";

                                                                    $resEp = mysql_query($reqEp) or die("select stock impossible =>" . mysql_error());

                                                                    $entrepot = mysql_fetch_assoc($resEp);

                                                                    echo '<option selected value="' . $entrepot["idEntrepot"] . '§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '">' . $entrepot["nomEntrepot"] . '</option>';

                                                                    /***Debut verifier si la ligne du produit existe deja ***/

                                                                    $sqlEl = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet='" . $pagnet['idPagnet'] . "' and idDesignation='" . $ligne['idDesignation'] . "' and idEntrepot!='" . $ligne['idEntrepot'] . "'";

                                                                    $resEl = mysql_query($sqlEl) or die("persoonel requête 2" . mysql_error());

                                                                    /***Debut verifier si la ligne du produit existe deja ***/

                                                                    if (!mysql_num_rows($resEl)) {

                                                                        $reqDp = "SELECT * from  `" . $nomtableEntrepot . "` e

                                                                                        INNER JOIN `" . $nomtableEntrepotStock . "` s ON s.idEntrepot = e.idEntrepot

                                                                                        where s.designation='" . $ligne['designation'] . "' AND s.quantiteStockCourant>0 AND e.idEntrepot<>'" . $entrepot["idEntrepot"] . "' ORDER BY quantiteStockCourant DESC ";

                                                                        $resDp = mysql_query($reqDp) or die("select stock impossible =>" . mysql_error());

                                                                        while ($depot = mysql_fetch_assoc($resDp)) {

                                                                            echo '<option value="' . $depot["idEntrepot"] . '§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '">' . $depot["nomEntrepot"] . '</option>';
                                                                        }
                                                                    }

                                                                    ?>

                                                                </select>

                                                            <?php endif; ?>

                                                        </td>

                                                        <td>

                                                            <button type="submit" class="btn btn-warning pull-right btn_Retourner_Produit" data-toggle="modal" <?php echo  "data-target=#msg_rtrnAvant_ligne" . $ligne['numligne']; ?>>

                                                                <span class="glyphicon glyphicon-remove"></span>Retour

                                                            </button>



                                                            <div class="modal fade" <?php echo  "id=msg_rtrnAvant_ligne" . $ligne['numligne']; ?> role="dialog">

                                                                <div class="modal-dialog">

                                                                    <!-- Modal content-->

                                                                    <div class="modal-content">

                                                                        <div class="modal-header panel-primary">

                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                        </div>

                                                                        <form class="form-inline noImpr" id="factForm" method="post">

                                                                            <div class="modal-body">

                                                                                <p><?php echo  "Voulez-vous annuler cette ligne du panier numéro <b>" . $pagnet['idPagnet'] . "</b>"; ?>
                                                                                </p>

                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                                                <input type="hidden" name="designation" <?php echo  "value=" . $ligne['designation'] . ""; ?>>

                                                                                <input type="hidden" name="numligne" <?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                                                <input type="hidden" name="idStock" <?php echo  "value=" . $ligne['idStock'] . ""; ?>>

                                                                                <input type="hidden" name="unitevente" <?php echo  "value=" . $ligne['unitevente'] . ""; ?>>

                                                                                <input type="hidden" name="quantite" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                                <input type="hidden" name="prixunitevente" <?php echo  "value=" . $ligne['prixunitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=" . $ligne['prixtotal'] . ""; ?>>

                                                                                <input type="hidden" name="totalp" <?php echo  "value=" . $pagnet['totalp'] . ""; ?>>

                                                                            </div>

                                                                            <div class="modal-footer">

                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                                <button type="submit" name="btnRetourAvant" class="btn btn-success">Confirmer</button>

                                                                            </div>

                                                                        </form>

                                                                    </div>

                                                                </div>

                                                            </div>


                                                            <?php
                                                            if ($_SESSION["infoSup"] == 1) {
                                                            ?>
                                                                <button type="button" class="btn btn-info pull-right btn_add_infoSup" data-toggle="modal" data-target="#add_infoSup_modal<?= $ligne['numligne']; ?>">

                                                                    <span class="glyphicon glyphicon-plus"></span>info. sup

                                                                </button>
                                                            <?php
                                                            }
                                                            ?>

                                                            <div id="add_infoSup_modal<?= $ligne['numligne']; ?>" class="modal fade" role="dialog">
                                                                <div class="modal-dialog">
                                                                    <!-- Modal content-->
                                                                    <div class="modal-content">
                                                                        <div class="modal-header panel-primary">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            <h4 class="modal-title">Informations supplémentaires</h4>
                                                                        </div>
                                                                        <div class="modal-body" id="add_infoSup_body<?= $ligne['numligne']; ?>">
                                                                            <div class="form-group col-md-6">
                                                                                <label class="col-form-label" for="numeroChassis<?= $ligne['numligne']; ?>">Numéro
                                                                                    châsis</label>
                                                                                <div class="">
                                                                                    <input type="text" name="numeroChassis" id="numeroChassis<?= $ligne['numligne']; ?>" class="form-control" placeholder="Numéro châsis">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label class="col-form-label" for="numeroMoteur<?= $ligne['numligne']; ?>">Numéro
                                                                                    moteur</label>
                                                                                <div class="">
                                                                                    <input type="text" name="numeroMoteur" id="numeroMoteur<?= $ligne['numligne']; ?>" class="form-control" placeholder="Numéro moteur">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label class="col-form-label" for="couleur<?= $ligne['numligne']; ?>">Couleur</label>
                                                                                <div class="">
                                                                                    <input type="text" name="couleur" id="couleur<?= $ligne['numligne']; ?>" class="form-control" placeholder="Couleur">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                            </div>
                                                                            <br>
                                                                            <br>
                                                                            <br>
                                                                            <br>
                                                                            <!-- </div>
                                                                                    <div class="modal-footer"> -->
                                                                            <button type="button" id="closeInfo" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            <button type="button" onclick="add_infoSup(<?= $ligne['numligne']; ?>)" name="btnAddInfoSup" class="btn btn-success">Ajouter</button>
                                                                        </div>
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

                        } else if ($pagnet['type'] == 10) {   ?>

                            <div class="panel panel-warning">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet" . $pagnet['idPagnet'] . ""; ?> class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a class="row" href="#">

                                            <span class="noImpr col-md-2 col-sm-2 col-xs-12"> Panier
                                                <?php echo ': En cours ...'; ?></span>

                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12"> Date:
                                                <?php echo $pagnet['datepagej'] . " " . $pagnet['heurePagnet']; ?> </span>

                                            <!-- <span class="spanDate noImpr col-md-2 col-sm-2 col-xs-12">Heure: <?php // $pagnet['heurePagnet']; 
                                                                                                                    ?></span> -->

                                            <?php $sqlT = "SELECT SUM(prixtotal) FROM `" . $nomtableLigne . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resT = mysql_query($sqlT) or die("persoonel requête 2" . mysql_error());

                                            $TotalT = mysql_fetch_array($resT);

                                            ?>

                                            <?php if ($_SESSION['Pays'] == 'Canada') {   ?>

                                                <span class="noImpr col-md-3 col-sm-3 col-xs-12">Sous-Total : <span <?php echo  "id=somme_Apayer" . $pagnet['idPagnet']; ?>><?php echo $TotalT[0]; ?>
                                                    </span></span>

                                                <span class="noImpr col-md-3 col-sm-3 col-xs-12"> Total : <span <?php echo  "id=somme_ApayerT" . $pagnet['idPagnet']; ?>><?php echo ($TotalT[0] + (($TotalT[0] * 5) / 100) + (($TotalT[0] * 9.975) / 100)); ?>
                                                    </span></span>

                                            <?php } else {   ?>

                                                <span class="noImpr col-md-3 col-sm-3 col-xs-12">Total panier: <span <?php echo  "id=somme_Total" . $pagnet['idPagnet']; ?>><?php echo $TotalT[0]; ?>
                                                    </span></span>

                                                <span class="noImpr col-md-3 col-sm-3 col-xs-12">Total à payer: <span <?php echo  "id=somme_Apayer" . $pagnet['idPagnet']; ?>><?php echo $TotalT[0]; ?>
                                                    </span></span>

                                            <?php } ?>

                                        </a>

                                    </h4>

                                </div>

                                <div <?php echo  "id=pagnet" . $pagnet['idPagnet'] . ""; ?> class="panel-collapse collapse in">

                                    <div class="panel-body">

                                        <div class="cache_btn_Terminer row">

                                            <!--*******************************Debut Ajouter Ligne****************************************-->

                                            <form class="form-inline noImpr ajouterProdForm col-md-4 col-sm-4 col-xs-12" id="ajouterProdFormEt<?= $pagnet['idPagnet']; ?>" onsubmit="return false">

                                                <input type="text" class="inputbasic form-control codeBarreLigneEt" name="codeBarre" id="panier_<?= $pagnet['idPagnet']; ?>" style="width:100%;" autofocus="" autocomplete="off" required />

                                                <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                <!-- <span id="reponseV"></span> -->

                                                <!-- <button type="submit" name="btnEnregistrerCodeBarre"

                                                    id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs " ><span class="glyphicon glyphicon-plus" ></span>

                                                    </button> -->

                                            </form>

                                            <!--*******************************Fin Ajouter Ligne****************************************-->

                                            <div class="col-md-8 col-sm-8 col-xs-12 content2">

                                                <!--*******************************Debut Terminer Pagnet****************************************-->

                                                <form class="form-inline noImpr" id="factForm" method="post">

                                                    <input type="number" <?php echo "id=val_remise" . $pagnet['idPagnet']; ?> name="remise" onkeyup="modif_Remise(this.value, <?php echo  $pagnet['idPagnet']; ?>)" class="remise form-control col-md-2 col-sm-2 col-xs-3" placeholder="Remise....">

                                                    <!-- <?php //if ($_SESSION['compte']==1) { 
                                                            ?> -->
                                                    <div id="clientProformaDiv">

                                                        <input type="text" name="clientProforma" id="clientProforma<?= $pagnet['idPagnet']; ?>" class="client clientProforma form-control col-md-3 col-sm-3 col-xs-3" onkeyup="addClientProforma(e)" data-idPanier="<?= $pagnet['idPagnet']; ?>" autocomplete="off" placeholder="Nom du client ..." required>

                                                    </div>
                                                    <!-- <?php //}
                                                            ?> -->

                                                    <?php //if ($_SESSION['compte']==1) { 
                                                    ?>

                                                    <!-- <select class="compte <?= $pagnet['idPagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3"  data-idPanier="<?= $pagnet['idPagnet']; ?>" name="compte"  <?php echo  "id=compte" . $pagnet['idPagnet']; ?>> -->

                                                    <!-- <option value="caisse">Caisse</option> -->

                                                    <?php

                                                    // if ($pagnet['idCompte']!=0) {



                                                    //     $sqlGetCompte3="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$pagnet['idCompte'];

                                                    //     $resPay3 = mysql_query($sqlGetCompte3) or die ("persoonel requête 2".mysql_error());

                                                    //     $cpt = mysql_fetch_array($resPay3);

                                                    ?>

                                                    <!-- <option value="<?= $pagnet['idCompte']; ?>"><?= $cpt['nomCompte']; ?></option> -->

                                                    <?php //} 

                                                    // foreach ($cpt_array as $key) { 
                                                    ?>

                                                    <!-- <option value="<?= $key['idCompte']; ?>"><?= $key['nomCompte']; ?></option> -->

                                                    <?php //} 
                                                    ?>

                                                    <!-- </select> -->

                                                    <?php // } 
                                                    ?>



                                                    <?php //if($_SESSION['Pays']=='Canada'){  
                                                    ?>

                                                    <!-- <input type="number" name="versement" id="versement<?= $pagnet['idPagnet']; ?>" <?= (@$pagnet['idCompte'] != 0) ? 'style="display:none;"' : ''; ?> -->

                                                    <!-- class="versement form-control col-md-2 col-sm-2 col-xs-3" placeholder="Espèce..."> -->



                                                    <?php //}

                                                    //else{   
                                                    ?>

                                                    <!-- <input type="number" name="versement" id="versement<?= $pagnet['idPagnet']; ?>" <?= (@$pagnet['idCompte'] != 0) ? 'style="display:none;"' : ''; ?> -->

                                                    <!-- class="versement form-control col-md-2 col-sm-2 col-xs-3" placeholder="Espèce..."> -->



                                                    <?php //} 



                                                    // if ($pagnet['type']=='30') {



                                                    //     $sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$pagnet['idClient']."";

                                                    //     $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());

                                                    //     $client = mysql_fetch_assoc($res3);

                                                    ?>

                                                    <!-- <input type="text" class="client clientInput form-control col-md-2 col-sm-2 col-xs-3" data-type="simple" data-idPanier="<?= $pagnet['idPagnet']; ?>" name="clientInput" id="clientInput<?= $pagnet['idPagnet']; ?>" autocomplete="off" value="<?= $client['prenom'] . " " . $client['nom']; ?>"/> -->



                                                    <?php

                                                    # code...

                                                    // } else {

                                                    # code...                                                            

                                                    ?>

                                                    <!-- <input type="text" class="client clientInput form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-type="simple" data-idPanier="<?= $pagnet['idPagnet']; ?>" name="clientInput" id="clientInput<?= $pagnet['idPagnet']; ?>" autocomplete="off" placeholder="Client"/> -->



                                                    <?php   //}  
                                                    ?>



                                                    <input type="hidden" name="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                    <input type="hidden" name="totalp" <?php echo  "id=totalp" . $pagnet['idPagnet'] . ""; ?> value="<?php echo $pagnet['totalp']; ?>">

                                                    <!-- <div class="btnAction col-md-1 col-xs-12"> -->

                                                    <button type="submit" style="" name="btnImprimerProforma" <?php echo  "id=btnImprimerFacture" . $pagnet['idPagnet'] . ""; ?> class="btn btn-success terminer" data-toggle="modal"><span class="glyphicon glyphicon-ok"></span></button>

                                                    <button tabindex="1" type="button" class="btn btn-danger annuler" data-toggle="modal" <?php echo  "data-target=#msg_ann_pagnet" . $pagnet['idPagnet']; ?>>

                                                        <span class="glyphicon glyphicon-remove"></span>

                                                    </button>

                                                    <!-- </div> -->

                                                </form>

                                                <!--*******************************Fin Terminer Pagnet****************************************-->

                                                <!--*******************************Debut Annuler Pagnet****************************************-->

                                                <!-- <div class="col-md-1 col-sm-1 col-xs-1">   -->

                                                <!-- </div>   -->



                                                <div class="modal fade" <?php echo  "id=msg_ann_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

                                                    <div class="modal-dialog">

                                                        <!-- Modal content-->

                                                        <div class="modal-content">

                                                            <div class="modal-header panel-primary">

                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                <h4 class="modal-title">Confirmation</h4>

                                                            </div>

                                                            <form class="form-inline noImpr" id="factForm" method="post">

                                                                <div class="modal-body">

                                                                    <p><?php echo "Voulez-vous annuler le panier numéro <b>" . $pagnet['idPagnet'] . "</b>"; ?>
                                                                    </p>

                                                                    <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value='" . $pagnet['idPagnet'] . "'"; ?>>

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

                                        <table <?php echo  "id=tablePanier" . $pagnet['idPagnet'] . ""; ?> class="tabPanier table" width="100%">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th>Référence</th>

                                                    <th>Quantité</th>

                                                    <th>Unité vente</th>

                                                    <th>Prix </th>

                                                    <th>Depot</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php

                                                $sql8 = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $pagnet['idPagnet'] . " ORDER BY numligne DESC";

                                                $res8 = mysql_query($sql8) or die("persoonel requête 2" . mysql_error());

                                                while ($ligne = mysql_fetch_assoc($res8)) { ?>

                                                    <tr>

                                                        <td class="designation">

                                                            <?php

                                                            if ($ligne['classe'] == 2) {

                                                                echo '<input class="form-control" style="width: 100%"  type="text" value="' . $ligne['designation'] . '"

                                                                                    onkeyup="modif_DesignationBon(this.value,' . $ligne['numligne'] . ',' . $pagnet['idPagnet'] . ')" />

                                                                                ';
                                                            } else { ?>

                                                            <?php echo $ligne['designation'];
                                                            }

                                                            ?>

                                                        </td>

                                                        <td>

                                                            <?php if (($ligne['unitevente'] == 'Transaction') || ($ligne['unitevente'] == 'Facture') || ($ligne['unitevente'] == 'Credit')) { ?>



                                                            <?php } else { ?>

                                                                <?php

                                                                if ($ligne['classe'] == 0) {

                                                                    if ($ligne['idStock'] != 0) {

                                                                        $sqlS = "SELECT * FROM `" . $nomtableStock . "` where idStock=" . $ligne['idStock'];

                                                                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                                                                        $stock = mysql_fetch_assoc($resS);



                                                                        $sqlD = "SELECT * FROM `" . $nomtableDesignation . "` where idDesignation=" . $stock['idDesignation'];

                                                                        $resD = mysql_query($sqlD) or die("select stock impossible =>" . mysql_error());

                                                                        $designation = mysql_fetch_assoc($resD);



                                                                        if ($ligne['unitevente'] == $designation['uniteDetails']) {

                                                                ?>

                                                                            <input class="quantite form-control" <?php echo  "id=ligne" . $ligne['numligne'] . ""; ?> <?= (@in_array($ligne['numligne'], $ligneIns)) ? 'style="background-color:#FC8080;' : ''; ?> onkeyup="modif_Quantite(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  $designation['nbreArticleUniteStock']; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 25%" size="3" type="number" <?php echo  "id=quantite" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                        <?php

                                                                        } else if ($ligne['unitevente'] == $designation['uniteStock']) {

                                                                        ?>

                                                                            <input class="quantite form-control" <?php echo  "id=ligne" . $ligne['numligne'] . ""; ?> <?= (@in_array($ligne['numligne'], $ligneIns)) ? 'style="background-color:#FC8080;' : ''; ?> onkeyup="modif_Quantite(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  '1'; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 25%" size="3" type="number" <?php echo  "id=quantite" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                        <?php }
                                                                    } else {

                                                                        $sqlD = "SELECT * FROM `" . $nomtableDesignation . "` where idDesignation=" . $ligne['idDesignation'];

                                                                        $resD = mysql_query($sqlD) or die("select Designation impossible =>" . mysql_error());

                                                                        $designation = mysql_fetch_assoc($resD);

                                                                        ?>

                                                                        <input class="quantite form-control" <?php echo  "id=ligne" . $ligne['numligne'] . ""; ?> <?= (@in_array($ligne['numligne'], $ligneIns)) ? 'style="background-color:#FC8080;' : ''; ?> onkeyup="modif_QuantiteET(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" size="4" type="number" <?php echo  "id=quantite" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                    <?php

                                                                    }
                                                                }

                                                                if ($ligne['classe'] == 1) { ?>

                                                                    <input class="quantite form-control" <?php echo  "id=ligne" . $ligne['numligne'] . ""; ?> onkeyup="modif_QuantiteSD(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                <?php

                                                                }

                                                                if ($ligne['classe'] == 5 || $ligne['classe'] == 7) { ?>

                                                                    <?php echo 'Montant'; ?>

                                                            <?php }
                                                            } ?>

                                                        </td>

                                                        <td class="unitevente ">

                                                            <?php

                                                            if ($ligne['classe'] == 0) {

                                                                if ($ligne['idStock'] != 0) { ?>

                                                                    <?php echo $ligne['unitevente']; ?>

                                                                <?php

                                                                } else {

                                                                ?>

                                                                    <?php

                                                                    if ($ligne['unitevente'] == $designation['uniteStock']) {

                                                                    ?>

                                                                        <select name="uniteVente" class="form-control" onchange="modif_UniteStockET(this.value)">

                                                                            <?php

                                                                            $reqP = 'SELECT * from  `' . $nomtableDesignation . '` where designation="' . $ligne['designation'] . '"';

                                                                            $resP = mysql_query($reqP) or die("select stock impossible =>" . mysql_error());

                                                                            if (mysql_num_rows($resP)) {

                                                                                $produit = mysql_fetch_assoc($resP);

                                                                                echo '<option value="' . $produit["uniteStock"] . '§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§1">' . $produit["uniteStock"] . '</option>';

                                                                                echo '<option value="Demi Gros§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§2">Demi Gros</option>';

                                                                                echo '<option value="Piece§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§1">Piece</option>';
                                                                            }

                                                                            ?>

                                                                        </select>

                                                                    <?php

                                                                    } else {

                                                                    ?>

                                                                        <select name="uniteVente" class="form-control" onchange="modif_UniteStockET(this.value)">

                                                                            <?php

                                                                            $reqP = 'SELECT * from  `' . $nomtableDesignation . '` where designation="' . $ligne['designation'] . '"';

                                                                            $resP = mysql_query($reqP) or die("select stock impossible =>" . mysql_error());

                                                                            if (mysql_num_rows($resP)) {

                                                                                $produit = mysql_fetch_assoc($resP);

                                                                                $demi = $ligne['quantite'] * 2;

                                                                                if ($produit['nbreArticleUniteStock'] == $demi) {

                                                                                    echo '<option value="Demi Gros§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§2">Demi Gros</option>';

                                                                                    echo '<option value="Piece§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§1">Piece</option>';
                                                                                } else {

                                                                                    echo '<option value="Piece§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§1">Piece</option>';

                                                                                    echo '<option value="Demi Gros§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§2">Demi Gros</option>';
                                                                                }

                                                                                if (($produit["uniteStock"] != '') && ($produit["uniteStock"] != null)) {

                                                                                    echo '<option value="' . $produit["uniteStock"] . '§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§1">' . $produit["uniteStock"] . '</option>';
                                                                                }
                                                                            }

                                                                            ?>

                                                                        </select>

                                                                    <?php

                                                                    }

                                                                    ?>

                                                                <?php

                                                                }
                                                            } else if ($ligne['classe'] == 6) { ?>

                                                                <input class="form-control" style="width: 70%" type="text" <?php echo  "id=especes" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['unitevente'] . ""; ?> onkeyup="modif_Espece(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)">

                                                            <?php

                                                            } else  if ($ligne['classe'] != 2 && $ligne['classe'] != 0 && $ligne['classe'] != 6) { ?>

                                                                <?php echo $ligne['unitevente']; ?>

                                                            <?php

                                                            }

                                                            ?>

                                                        </td>

                                                        <td>

                                                            <input <?php echo  "id=prixUniteStock" . $ligne['numligne'] . ""; ?> class="prixunitevente form-control" size="6" type="number" <?php echo  "id=prixunitevente" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['prixunitevente'] . ""; ?> onkeyup="modif_Prix(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)">

                                                        </td>

                                                        <td>

                                                            <?php if ($ligne['classe'] == 0) : ?>

                                                                <select class="form-control" onchange="modif_Depot(this.value)">

                                                                    <?php

                                                                    $reqEp = "SELECT * from  `" . $nomtableEntrepot . "` e

                                                                                    where idEntrepot='" . $ligne['idEntrepot'] . "' ";

                                                                    $resEp = mysql_query($reqEp) or die("select stock impossible =>" . mysql_error());

                                                                    $entrepot = mysql_fetch_assoc($resEp);

                                                                    echo '<option selected value="' . $entrepot["idEntrepot"] . '§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '">' . $entrepot["nomEntrepot"] . '</option>';

                                                                    /***Debut verifier si la ligne du produit existe deja ***/

                                                                    $sqlEl = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet='" . $pagnet['idPagnet'] . "' and idDesignation='" . $ligne['idDesignation'] . "' and idEntrepot!='" . $ligne['idEntrepot'] . "'";

                                                                    $resEl = mysql_query($sqlEl) or die("persoonel requête 2" . mysql_error());

                                                                    /***Debut verifier si la ligne du produit existe deja ***/

                                                                    if (!mysql_num_rows($resEl)) {

                                                                        $reqDp = "SELECT * from  `" . $nomtableEntrepot . "` e

                                                                                        INNER JOIN `" . $nomtableEntrepotStock . "` s ON s.idEntrepot = e.idEntrepot

                                                                                        where s.designation='" . $ligne['designation'] . "' AND s.quantiteStockCourant>0 AND e.idEntrepot<>'" . $entrepot["idEntrepot"] . "' ORDER BY quantiteStockCourant DESC ";

                                                                        $resDp = mysql_query($reqDp) or die("select stock impossible =>" . mysql_error());

                                                                        while ($depot = mysql_fetch_assoc($resDp)) {

                                                                            echo '<option value="' . $depot["idEntrepot"] . '§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '">' . $depot["nomEntrepot"] . '</option>';
                                                                        }
                                                                    }

                                                                    ?>

                                                                </select>

                                                            <?php endif; ?>

                                                        </td>

                                                        <td>

                                                            <button type="submit" class="btn btn-warning pull-right btn_Retourner_Produit" data-toggle="modal" <?php echo  "data-target=#msg_rtrnAvant_ligne" . $ligne['numligne']; ?>>

                                                                <span class="glyphicon glyphicon-remove"></span>Retour

                                                            </button>



                                                            <div class="modal fade" <?php echo  "id=msg_rtrnAvant_ligne" . $ligne['numligne']; ?> role="dialog">

                                                                <div class="modal-dialog">

                                                                    <!-- Modal content-->

                                                                    <div class="modal-content">

                                                                        <div class="modal-header panel-primary">

                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                        </div>

                                                                        <form class="form-inline noImpr" id="factForm" method="post">

                                                                            <div class="modal-body">

                                                                                <p><?php echo  "Voulez-vous annuler cette ligne du panier numéro <b>" . $pagnet['idPagnet'] . "</b>"; ?>
                                                                                </p>

                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                                                <input type="hidden" name="designation" <?php echo  "value=" . $ligne['designation'] . ""; ?>>

                                                                                <input type="hidden" name="numligne" <?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                                                <input type="hidden" name="idStock" <?php echo  "value=" . $ligne['idStock'] . ""; ?>>

                                                                                <input type="hidden" name="unitevente" <?php echo  "value=" . $ligne['unitevente'] . ""; ?>>

                                                                                <input type="hidden" name="quantite" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                                <input type="hidden" name="prixunitevente" <?php echo  "value=" . $ligne['prixunitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=" . $ligne['prixtotal'] . ""; ?>>

                                                                                <input type="hidden" name="totalp" <?php echo  "value=" . $pagnet['totalp'] . ""; ?>>

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

                        } else { ?>

                            <div class="panel panel-danger">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet" . $pagnet['idPagnet'] . ""; ?> class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a class="row" href="#">

                                            <span class="noImpr col-md-2 col-sm-2 col-xs-12"> Panier
                                                <?php echo ': En cours ...'; ?></span>

                                            <span class="noImpr col-md-3 col-sm-3 col-xs-12"> Date:
                                                <?php echo $pagnet['datepagej'] . " " . $pagnet['heurePagnet']; ?> </span>

                                            <!-- <span class="spanDate noImpr col-md-2 col-sm-2 col-xs-12">Heure: <?php // $pagnet['heurePagnet']; 
                                                                                                                    ?></span> -->

                                            <?php $sqlT = "SELECT SUM(prixtotal) FROM `" . $nomtableLigne . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resT = mysql_query($sqlT) or die("persoonel requête 2" . mysql_error());

                                            $TotalT = mysql_fetch_array($resT);

                                            ?>

                                            <?php if ($_SESSION['Pays'] == 'Canada') {   ?>

                                                <span class="noImpr col-md-3 col-sm-3 col-xs-12">Sous-Total : <span <?php echo  "id=somme_Apayer" . $pagnet['idPagnet']; ?>><?php echo $TotalT[0]; ?>
                                                    </span></span>

                                                <span class="noImpr col-md-3 col-sm-3 col-xs-12"> Total : <span <?php echo  "id=somme_ApayerT" . $pagnet['idPagnet']; ?>><?php echo ($TotalT[0] + (($TotalT[0] * 5) / 100) + (($TotalT[0] * 9.975) / 100)); ?>
                                                    </span></span>

                                            <?php } else {   ?>

                                                <span class="noImpr col-md-3 col-sm-3 col-xs-12">Total panier: <span <?php echo  "id=somme_Total" . $pagnet['idPagnet']; ?>><?php echo $TotalT[0]; ?>
                                                    </span></span>

                                                <span class="noImpr col-md-3 col-sm-3 col-xs-12">Total à payer: <span <?php echo  "id=somme_Apayer" . $pagnet['idPagnet']; ?>><?php echo $TotalT[0]; ?>
                                                    </span></span>

                                            <?php } ?>

                                        </a>

                                    </h4>

                                </div>

                                <div <?php echo  "id=pagnet" . $pagnet['idPagnet'] . ""; ?> class="panel-collapse collapse in">

                                    <div class="panel-body">

                                        <div class="cache_btn_Terminer row">

                                            <!--*******************************Debut Ajouter Ligne****************************************-->

                                            <form class="form-inline noImpr ajouterProdForm col-md-4 col-sm-4 col-xs-12" id="ajouterProdFormEt<?= $pagnet['idPagnet']; ?>" onsubmit="return false">

                                                <input type="text" class="inputbasic form-control codeBarreLigneEt" name="codeBarre" id="panier_<?= $pagnet['idPagnet']; ?>" style="width:100%;" autofocus="" autocomplete="off" required />

                                                <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                <!-- <span id="reponseV"></span> -->

                                                <!-- <button type="submit" name="btnEnregistrerCodeBarre"

                                                    id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs " ><span class="glyphicon glyphicon-plus" ></span>

                                                    </button> -->

                                            </form>

                                            <!--*******************************Fin Ajouter Ligne****************************************-->

                                            <div class="col-md-8 col-sm-8 col-xs-12 content2">

                                                <!--*******************************Debut Terminer Pagnet****************************************-->

                                                <form class="form-inline noImpr" id="factForm" method="post">

                                                    <input type="number" <?php echo "id=val_remise" . $pagnet['idPagnet']; ?> name="remise" onkeyup="modif_Remise(this.value, <?php echo  $pagnet['idPagnet']; ?>)" class="remise form-control col-md-2 col-sm-2 col-xs-3" placeholder="Remise....">



                                                    <?php if ($_SESSION['compte'] == 1) { ?>

                                                        <select class="compte <?= $pagnet['idPagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $pagnet['idPagnet']; ?>" name="compte" onchange="showImageCompte(this.value,<?= $pagnet['idPagnet']; ?>)" <?php echo  "id=compte" . $pagnet['idPagnet']; ?>>

                                                            <!-- <option value="caisse">Caisse</option> -->

                                                            <?php

                                                            if ($pagnet['idCompte'] != 0) {



                                                                $sqlGetCompte3 = "SELECT * FROM `" . $nomtableCompte . "` where idCompte = " . $pagnet['idCompte'];

                                                                $resPay3 = mysql_query($sqlGetCompte3) or die("persoonel requête 2" . mysql_error());

                                                                $cpt = mysql_fetch_array($resPay3);

                                                            ?>

                                                                <option value="<?= $pagnet['idCompte']; ?>"><?= $cpt['nomCompte']; ?>
                                                                </option>

                                                            <?php }

                                                            foreach ($cpt_array as $key) { ?>

                                                                <option id="idImageCompte<?= $key['idCompte']; ?>" data-image="<?= $key['image']; ?>" value="<?= $key['idCompte']; ?>">
                                                                    <?= $key['nomCompte']; ?></option>

                                                            <?php } ?>

                                                        </select>

                                                    <?php } ?>



                                                    <?php if ($_SESSION['Pays'] == 'Canada') {  ?>

                                                        <input type="number" name="versement" id="versement<?= $pagnet['idPagnet']; ?>" <?= (@$pagnet['idCompte'] != 0) ? 'style="display:none;"' : ''; ?> class="versement form-control col-md-2 col-sm-2 col-xs-3" placeholder="Espèce...">



                                                    <?php } else {   ?>

                                                        <input type="number" name="versement" id="versement<?= $pagnet['idPagnet']; ?>" <?= (@$pagnet['idCompte'] != 0) ? 'style="display:none;"' : ''; ?> class="versement form-control col-md-2 col-sm-2 col-xs-3" placeholder="Espèce...">



                                                    <?php }



                                                    if ($pagnet['type'] == '30') {



                                                        $sql3 = "SELECT * FROM `" . $nomtableClient . "` where idClient=" . $pagnet['idClient'] . "";

                                                        $res3 = mysql_query($sql3) or die("persoonel requête 3" . mysql_error());

                                                        $client = mysql_fetch_assoc($res3);

                                                    ?>

                                                        <input type="text" class="client clientInput form-control col-md-2 col-sm-2 col-xs-3" data-type="simple" data-idPanier="<?= $pagnet['idPagnet']; ?>" name="clientInput" id="clientInput<?= $pagnet['idPagnet']; ?>" autocomplete="off" value="<?= $client['prenom'] . " " . $client['nom']; ?>" />



                                                    <?php

                                                        # code...

                                                    } else {

                                                        # code...                                                            

                                                    ?>

                                                        <input type="text" class="client clientInput form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-type="simple" data-idPanier="<?= $pagnet['idPagnet']; ?>" name="clientInput" id="clientInput<?= $pagnet['idPagnet']; ?>" autocomplete="off" placeholder="Client" />



                                                    <?php   }  ?>



                                                    <input type="hidden" name="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                    <input type="hidden" name="totalp" <?php echo  "id=totalp" . $pagnet['idPagnet'] . ""; ?> value="<?php echo $pagnet['totalp']; ?>">

                                                    <!-- <div class="btnAction col-md-1 col-xs-12"> -->

                                                    <button type="submit" style="" name="btnImprimerFacture" <?php echo  "id=btnImprimerFacture" . $pagnet['idPagnet'] . ""; ?> class="btn btn-success terminer" data-toggle="modal"><span class="glyphicon glyphicon-ok"></span></button>

                                                    <button tabindex="1" type="button" class="btn btn-danger annuler" data-toggle="modal" <?php echo  "data-target=#msg_ann_pagnet" . $pagnet['idPagnet']; ?>>

                                                        <span class="glyphicon glyphicon-remove"></span>

                                                    </button>

                                                    <!-- </div> -->

                                                </form>

                                                <!--*******************************Fin Terminer Pagnet****************************************-->

                                                <!--*******************************Debut Annuler Pagnet****************************************-->

                                                <!-- <div class="col-md-1 col-sm-1 col-xs-1">   -->

                                                <!-- </div>   -->



                                                <div class="modal fade" <?php echo  "id=msg_ann_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

                                                    <div class="modal-dialog">

                                                        <!-- Modal content-->

                                                        <div class="modal-content">

                                                            <div class="modal-header panel-primary">

                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                <h4 class="modal-title">Confirmation</h4>

                                                            </div>

                                                            <form class="form-inline noImpr" id="factForm" method="post">

                                                                <div class="modal-body">

                                                                    <p><?php echo "Voulez-vous annuler le panier numéro <b>" . $pagnet['idPagnet'] . "</b>"; ?>
                                                                    </p>

                                                                    <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value='" . $pagnet['idPagnet'] . "'"; ?>>

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

                                        <table <?php echo  "id=tablePanier" . $pagnet['idPagnet'] . ""; ?> class="tabPanier table" width="100%">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th>Référence</th>

                                                    <th>Quantité</th>

                                                    <th>Unité vente</th>

                                                    <th>Prix </th>

                                                    <th>Depot</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php

                                                $sql8 = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $pagnet['idPagnet'] . " ORDER BY numligne DESC";

                                                $res8 = mysql_query($sql8) or die("persoonel requête 2" . mysql_error());

                                                while ($ligne = mysql_fetch_assoc($res8)) { ?>

                                                    <tr>

                                                        <td class="designation">

                                                            <?php

                                                            if ($ligne['classe'] == 2) {

                                                                echo '<input class="form-control" style="width: 100%"  type="text" value="' . $ligne['designation'] . '"

                                                                                onkeyup="modif_DesignationBon(this.value,' . $ligne['numligne'] . ',' . $pagnet['idPagnet'] . ')" />

                                                                            ';
                                                            } else { ?>

                                                            <?php echo $ligne['designation'];
                                                            }

                                                            ?>

                                                        </td>

                                                        <td>

                                                            <?php if (($ligne['unitevente'] == 'Transaction') || ($ligne['unitevente'] == 'Facture') || ($ligne['unitevente'] == 'Credit')) { ?>



                                                            <?php } else { ?>

                                                                <?php

                                                                if ($ligne['classe'] == 0) {

                                                                    if ($ligne['idStock'] != 0) {

                                                                        $sqlS = "SELECT * FROM `" . $nomtableStock . "` where idStock=" . $ligne['idStock'];

                                                                        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());

                                                                        $stock = mysql_fetch_assoc($resS);



                                                                        $sqlD = "SELECT * FROM `" . $nomtableDesignation . "` where idDesignation=" . $stock['idDesignation'];

                                                                        $resD = mysql_query($sqlD) or die("select stock impossible =>" . mysql_error());

                                                                        $designation = mysql_fetch_assoc($resD);



                                                                        if ($ligne['unitevente'] == $designation['uniteDetails']) {

                                                                ?>

                                                                            <input class="quantite form-control" <?php echo  "id=ligne" . $ligne['numligne'] . ""; ?> <?= (@in_array($ligne['numligne'], $ligneIns)) ? 'style="background-color:#FC8080;' : ''; ?> onkeyup="modif_QuantiteR(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  $designation['nbreArticleUniteStock']; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 25%" size="3" type="number" <?php echo  "id=quantite" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                        <?php

                                                                        } else if ($ligne['unitevente'] == $designation['uniteStock']) {

                                                                        ?>

                                                                            <input class="quantite form-control" <?php echo  "id=ligne" . $ligne['numligne'] . ""; ?> <?= (@in_array($ligne['numligne'], $ligneIns)) ? 'style="background-color:#FC8080;' : ''; ?> onkeyup="modif_QuantiteR(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  '1'; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 25%" size="3" type="number" <?php echo  "id=quantite" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                        <?php }
                                                                    } else {

                                                                        $sqlD = "SELECT * FROM `" . $nomtableDesignation . "` where idDesignation=" . $ligne['idDesignation'];

                                                                        $resD = mysql_query($sqlD) or die("select Designation impossible =>" . mysql_error());

                                                                        $designation = mysql_fetch_assoc($resD);

                                                                        ?>

                                                                        <input class="quantite form-control" <?php echo  "id=ligne" . $ligne['numligne'] . ""; ?> <?= (@in_array($ligne['numligne'], $ligneIns)) ? 'style="background-color:#FC8080;' : ''; ?> onkeyup="modif_QuantiteSD(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" size="4" type="number" <?php echo  "id=quantite" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                    <?php

                                                                    }
                                                                }

                                                                if ($ligne['classe'] == 1) { ?>

                                                                    <input class="quantite form-control" <?php echo  "id=ligne" . $ligne['numligne'] . ""; ?> onkeyup="modif_QuantiteSD(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" style="width: 30%" size="3" type="number" <?php echo  "id=quantite" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                <?php

                                                                }

                                                                if ($ligne['classe'] == 5 || $ligne['classe'] == 7) { ?>

                                                                    <?php echo 'Montant'; ?>

                                                            <?php }
                                                            } ?>

                                                        </td>

                                                        <td class="unitevente ">

                                                            <?php

                                                            if ($ligne['classe'] == 0) {

                                                                if ($ligne['idStock'] != 0) { ?>

                                                                    <?php echo $ligne['unitevente']; ?>

                                                                <?php

                                                                } else {

                                                                ?>

                                                                    <?php

                                                                    if ($ligne['unitevente'] == $designation['uniteStock']) {

                                                                    ?>

                                                                        <select name="uniteVente" class="form-control" onchange="modif_UniteStockET(this.value)">

                                                                            <?php

                                                                            $reqP = 'SELECT * from  `' . $nomtableDesignation . '` where designation="' . $ligne['designation'] . '"';

                                                                            $resP = mysql_query($reqP) or die("select stock impossible =>" . mysql_error());

                                                                            if (mysql_num_rows($resP)) {

                                                                                $produit = mysql_fetch_assoc($resP);

                                                                                echo '<option value="' . $produit["uniteStock"] . '§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§1">' . $produit["uniteStock"] . '</option>';

                                                                                echo '<option value="Piece§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§1">Piece</option>';
                                                                            }

                                                                            ?>

                                                                        </select>

                                                                    <?php

                                                                    } else {

                                                                    ?>

                                                                        <select name="uniteVente" class="form-control" onchange="modif_UniteStockET(this.value)">

                                                                            <?php

                                                                            $reqP = 'SELECT * from  `' . $nomtableDesignation . '` where designation="' . $ligne['designation'] . '"';

                                                                            $resP = mysql_query($reqP) or die("select stock impossible =>" . mysql_error());

                                                                            if (mysql_num_rows($resP)) {

                                                                                $produit = mysql_fetch_assoc($resP);

                                                                                echo '<option value="Piece§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§1">Piece</option>';

                                                                                if (($produit["uniteStock"] != '') && ($produit["uniteStock"] != null)) {

                                                                                    echo '<option value="' . $produit["uniteStock"] . '§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '§1">' . $produit["uniteStock"] . '</option>';
                                                                                }
                                                                            }

                                                                            ?>

                                                                        </select>

                                                                    <?php

                                                                    }

                                                                    ?>

                                                                <?php

                                                                }
                                                            } else if ($ligne['classe'] == 6) { ?>

                                                                <input class="form-control" style="width: 70%" type="text" <?php echo  "id=especes" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['unitevente'] . ""; ?> onkeyup="modif_Espece(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)">

                                                            <?php

                                                            } else  if ($ligne['classe'] != 2 && $ligne['classe'] != 0 && $ligne['classe'] != 6) { ?>

                                                                <?php echo $ligne['unitevente']; ?>

                                                            <?php

                                                            }

                                                            ?>

                                                        </td>

                                                        <td>

                                                            <input <?php echo  "id=prixUniteStock" . $ligne['numligne'] . ""; ?> class="prixunitevente form-control" size="6" type="number" <?php echo  "id=prixunitevente" . $ligne['numligne'] . ""; ?> <?php echo  "value=" . $ligne['prixunitevente'] . ""; ?> onkeyup="modif_Prix(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)">

                                                        </td>

                                                        <td>

                                                            <?php if ($ligne['classe'] == 0) : ?>

                                                                <select class="form-control" onchange="modif_Depot(this.value)">

                                                                    <?php

                                                                    $reqEp = "SELECT * from  `" . $nomtableEntrepot . "` e

                                                                                    where idEntrepot='" . $ligne['idEntrepot'] . "' ";

                                                                    $resEp = mysql_query($reqEp) or die("select stock impossible =>" . mysql_error());

                                                                    $entrepot = mysql_fetch_assoc($resEp);

                                                                    echo '<option selected value="' . $entrepot["idEntrepot"] . '§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '">' . $entrepot["nomEntrepot"] . '</option>';

                                                                    /***Debut verifier si la ligne du produit existe deja ***/

                                                                    $sqlEl = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet='" . $pagnet['idPagnet'] . "' and idDesignation='" . $ligne['idDesignation'] . "' and idEntrepot!='" . $ligne['idEntrepot'] . "'";

                                                                    $resEl = mysql_query($sqlEl) or die("persoonel requête 2" . mysql_error());

                                                                    /***Debut verifier si la ligne du produit existe deja ***/

                                                                    if (!mysql_num_rows($resEl)) {

                                                                        $reqDp = "SELECT * from  `" . $nomtableEntrepot . "` e

                                                                                        INNER JOIN `" . $nomtableEntrepotStock . "` s ON s.idEntrepot = e.idEntrepot

                                                                                        where s.designation='" . $ligne['designation'] . "' AND e.idEntrepot<>'" . $entrepot["idEntrepot"] . "' ORDER BY quantiteStockCourant DESC ";

                                                                        $resDp = mysql_query($reqDp) or die("select stock impossible =>" . mysql_error());

                                                                        while ($depot = mysql_fetch_assoc($resDp)) {

                                                                            echo '<option value="' . $depot["idEntrepot"] . '§' . $ligne['numligne'] . '§' . $pagnet['idPagnet'] . '">' . $depot["nomEntrepot"] . '</option>';
                                                                        }
                                                                    }

                                                                    ?>

                                                                </select>

                                                            <?php endif; ?>

                                                        </td>

                                                        <td>

                                                            <button type="submit" class="btn btn-warning pull-right btn_Retourner_Produit" data-toggle="modal" <?php echo  "data-target=#msg_rtrnAvant_ligne" . $ligne['numligne']; ?>>

                                                                <span class="glyphicon glyphicon-remove"></span>Retour

                                                            </button>



                                                            <div class="modal fade" <?php echo  "id=msg_rtrnAvant_ligne" . $ligne['numligne']; ?> role="dialog">

                                                                <div class="modal-dialog">

                                                                    <!-- Modal content-->

                                                                    <div class="modal-content">

                                                                        <div class="modal-header panel-primary">

                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                        </div>

                                                                        <form class="form-inline noImpr" id="factForm" method="post">

                                                                            <div class="modal-body">

                                                                                <p><?php echo  "Voulez-vous annuler cette ligne du panier numéro <b>" . $pagnet['idPagnet'] . "</b>"; ?>
                                                                                </p>

                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                                                <input type="hidden" name="designation" <?php echo  "value=" . $ligne['designation'] . ""; ?>>

                                                                                <input type="hidden" name="numligne" <?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                                                <input type="hidden" name="idStock" <?php echo  "value=" . $ligne['idStock'] . ""; ?>>

                                                                                <input type="hidden" name="unitevente" <?php echo  "value=" . $ligne['unitevente'] . ""; ?>>

                                                                                <input type="hidden" name="quantite" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                                <input type="hidden" name="prixunitevente" <?php echo  "value=" . $ligne['prixunitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=" . $ligne['prixtotal'] . ""; ?>>

                                                                                <input type="hidden" name="totalp" <?php echo  "value=" . $pagnet['totalp'] . ""; ?>>

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

                        } ?>

                    <?php   } ?>

                    <!-- Fin Boucle while concernant les Paniers en cours (2 aux maximum) -->



                    <!-- Debut Boucle while concernant les Paniers Vendus -->

                    <?php $n = $nbPaniers - (($currentPage * 10) - 10);

                    while ($pagnet = mysql_fetch_assoc($resP1)) {   ?>

                        <?php
                        // var_dump($pagnet);
                        $idmax = mysql_result($resA, 0); ?>

                        <?php

                        $sql = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                        $res = mysql_query($sql) or die("persoonel requête 2" . mysql_error());

                        $ligne = mysql_fetch_assoc($res);
                        // var_dump($ligne);
                        if (($ligne['classe'] == 0 || $ligne['classe'] == 1) && ($pagnet['type'] == 0 || $pagnet['type'] == 30)) { ?>

                            <div class="panel <?= ($pagnet['idClient'] == 0) ? 'panel-success' : 'panel-info' ?>">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet" . $pagnet['idPagnet'] . ""; ?> class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Panier <?php echo $n; ?>

                                            <span class="spanDate noImpr"> </span>

                                            <!-- <span class="spanDate noImpr"> Date: <?php //echo $pagnet['datepagej']; 
                                                                                        ?> </span> -->

                                            <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                            <?php $sqlT = "SELECT totalp FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resT = mysql_query($sqlT) or die("select stock impossible =>" . mysql_error());

                                            $TotalT = mysql_fetch_array($resT);

                                            $sqlP = "SELECT apayerPagnet FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resP = mysql_query($sqlP) or die("select stock impossible =>" . mysql_error());

                                            $TotalP = mysql_fetch_array($resP);



                                            $sql = "SELECT * from `aaa-utilisateur` where idutilisateur='" . $pagnet['iduser'] . "' ";

                                            $res = mysql_query($sql);

                                            $user = mysql_fetch_array($res);



                                            ?>

                                            <?php if ($_SESSION['Pays'] == 'Canada') {   ?>

                                                <span class="spanTotal noImpr">Sous-Total: <span><?php echo $TotalP[0]; ?>
                                                    </span></span>

                                                <span class="spanTotal noImpr">Total :
                                                    <span><?php echo ($TotalP[0] + (($TotalP[0] * 5) / 100) + (($TotalP[0] * 9.975) / 100)); ?>
                                                    </span></span>

                                            <?php } else {   ?>

                                                <span class="spanTotal noImpr">Total panier: <span><?php echo $TotalT[0]; ?>
                                                    </span></span>

                                                <span class="spanTotal noImpr">Total à payer: <span><?php echo $TotalP[0]; ?>
                                                    </span></span>

                                            <?php } ?>

                                            <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                            <span class="spanDate noImpr">
                                                <?php echo substr(strtoupper($user['prenom']), 0, 3); ?></span>

                                        </a>

                                    </h4>

                                </div>

                                <div <?php echo  "id=pagnet" . $pagnet['idPagnet'] . ""; ?> <?php

                                                                                            if ($idmax == $pagnet['idPagnet']) {

                                                                                            ?> class="panel-collapse collapse in" <?php

                                                                                                                                } else {

                                                                                                                                    ?> class="panel-collapse collapse " <?php

                                                                                                                                                                    }

                                                                                                                                                                        ?>>

                                    <div class="panel-body">

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                        <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet" . $pagnet['idPagnet']; ?>>

                                            <span class="glyphicon glyphicon-remove"></span>Retourner

                                        </button>



                                        <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

                                            <div class="modal-dialog">

                                                <!-- Modal content-->

                                                <div class="modal-content">

                                                    <div class="modal-header panel-primary">

                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                        <h4 class="modal-title">Confirmation</h4>

                                                    </div>

                                                    <form class="form-inline noImpr" id="factForm" method="post">

                                                        <div class="modal-body">

                                                            <p><?php echo "Voulez-vous retourner le panier numéro <b>" . $pagnet['idPagnet'] . "<b>"; ?>
                                                            </p>

                                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value='" . $pagnet['idPagnet'] . "'"; ?>>

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



                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                        <?php if ($_SESSION['proprietaire'] == 1 && $_SESSION['editionPanier'] == 1) { ?>

                                            <button type="button" class="btn btn-primary pull-left modeEditionBtnET btn_disabled_after_click" id="edit-<?= $pagnet['idPagnet']; ?>">

                                                <span class="glyphicon glyphicon-edit"></span> Editer

                                            </button>



                                            <div class="modal fade" <?php echo  "id=msg_edit_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

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

                                        <!--*******************************Fin Retourner Pagnet****************************************-->



                                        <!--*******************************Debut Facture****************************************-->

                                        <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet" . $pagnet['idPagnet']; ?>>

                                            Facture

                                        </button>



                                        <div class="modal fade" <?php echo  "id=msg_fact_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

                                            <div class="modal-dialog">

                                                <!-- Modal content-->

                                                <div class="modal-content">

                                                    <div class="modal-header panel-primary">

                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                        <h4 class="modal-title">Informations Client</h4>

                                                    </div>

                                                    <form class="form-inline noImpr" method="post" action="pdfFacture.php" target="_blank">

                                                        <div class="modal-body">

                                                            <div class="row">

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Prenom(s) Client</label>

                                                                    <input type="text" class="form-control" name="prenom">

                                                                </div>

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Nom Client</label>

                                                                    <input type="text" class="form-control" name="nom">

                                                                </div>

                                                            </div>

                                                            <div class="row">

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Adresse Client</label>

                                                                    <input type="text" class="form-control" name="adresse">

                                                                </div>

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Telephone Client</label>

                                                                    <input type="text" class="form-control" name="telephone">

                                                                </div>

                                                            </div>

                                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value='" . $pagnet['idPagnet'] . "'"; ?>>

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

                                        <button class="btn btn-warning  pull-left" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'recu' . $pagnet['idPagnet']; ?>').submit();">

                                            Reçu

                                        </button>


                                        <form class="form-inline pull-right noImpr" <?php echo  "id=recu" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="recuCaisse.php">

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                        </form>

                                        <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket' . $pagnet['idPagnet']; ?>').submit();">

                                            Ticket de Caisse

                                        </button>


                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="barcodeFacture.php">

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                        </form>



                                        <table class="table ">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th>Référence</th>

                                                    <th>Quantité</th>

                                                    <th>Unité vente</th>

                                                    <th>Prix Unité vente</th>

                                                    <th>Depot</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php

                                                $sql8 = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $pagnet['idPagnet'] . " ORDER BY numligne DESC";

                                                $res8 = mysql_query($sql8) or die("persoonel requête 2" . mysql_error());

                                                while ($ligne = mysql_fetch_assoc($res8)) { ?>

                                                    <tr>

                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                        <td>

                                                            <?php if ($ligne['unitevente'] == 'Transaction') { ?>

                                                                <?php if ($ligne['quantite'] == 1) : ?>

                                                                    <?php echo  'Depot'; ?> <span class="factureFois"></span>

                                                                <?php endif; ?>

                                                                <?php if ($ligne['quantite'] == 0) : ?>

                                                                    <?php echo  'Retrait'; ?> <span class="factureFois"></span>

                                                                <?php endif; ?>

                                                            <?php } else { ?>

                                                                <?php echo  $ligne['quantite']; ?> <span class="factureFois"></span>

                                                            <?php } ?>

                                                        </td>

                                                        <td class="unitevente ">

                                                            <?php echo $ligne['unitevente']; ?>

                                                        </td>

                                                        <td>

                                                            <?php echo  $ligne['prixunitevente']; ?> <span class="factureFois"></span>

                                                        </td>

                                                        <td>

                                                            <?php

                                                            $reqEp = "SELECT * from  `" . $nomtableEntrepot . "` e

                                                                                where idEntrepot='" . $ligne['idEntrepot'] . "' ";

                                                            $resEp = mysql_query($reqEp) or die("select stock impossible =>" . mysql_error());

                                                            $entrepot = mysql_fetch_assoc($resEp);

                                                            echo $entrepot['nomEntrepot'];

                                                            ?>

                                                        </td>

                                                        <td>

                                                            <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne" . $ligne['numligne']; ?>>

                                                                <span class="glyphicon glyphicon-remove"></span>Retour

                                                            </button>



                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne" . $ligne['numligne']; ?> role="dialog">

                                                                <div class="modal-dialog">

                                                                    <!-- Modal content-->

                                                                    <div class="modal-content">

                                                                        <div class="modal-header panel-primary">

                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                        </div>

                                                                        <form class="form-inline noImpr" id="factForm" method="post">

                                                                            <div class="modal-body">

                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>" . $pagnet['idPagnet'] . "</b>"; ?>
                                                                                </p>

                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                                                <input type="hidden" name="designation" <?php echo  "value=" . $ligne['designation'] . ""; ?>>

                                                                                <input type="hidden" name="numligne" <?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                                                <input type="hidden" name="idStock" <?php echo  "value=" . $ligne['idStock'] . ""; ?>>

                                                                                <input type="hidden" name="unitevente" <?php echo  "value=" . $ligne['unitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixunitevente" <?php echo  "value=" . $ligne['prixunitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=" . $ligne['prixtotal'] . ""; ?>>

                                                                                <input type="hidden" name="totalp" <?php echo  "value=" . $pagnet['totalp'] . ""; ?>>

                                                                                <div class="row">

                                                                                    <div class="col-xs-6">

                                                                                        <label for="reference">Ancienne quantite
                                                                                        </label>

                                                                                        <input type="text" disabled="true" class="form-control" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                                    </div>

                                                                                    <div class="col-xs-6">

                                                                                        <label for="reference">Nouvelle quantite</label>

                                                                                        <input type="text" class="form-control" name="quantite" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

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

                                                <?php echo  'TOTAL : ' . $pagnet['totalp'] . '<br/>'; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['remise'] != 0 && $pagnet['remise'] > 0) : ?>

                                                    <?php echo 'Remise :' . $pagnet['remise'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php echo  '<b>Net à payer : ' . $pagnet['apayerPagnet'] . '</b><br/>'; ?>

                                            </div>

                                            <?php if ($_SESSION['compte'] == 1) : ?>

                                                <div>

                                                    <?php if ($pagnet['idCompte'] != 0 && $pagnet['idCompte'] > 0) :

                                                        $sqlGetComptePay2 = "SELECT * FROM `" . $nomtableCompte . "` where idCompte = " . $pagnet['idCompte'];

                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die("persoonel requête 2" . mysql_error());

                                                        $cpt = mysql_fetch_array($resPay2);

                                                    ?>

                                                        <?php echo 'Compte :' . $cpt['nomCompte'] . '<br/>'; ?>

                                                    <?php endif; ?>

                                                </div>

                                                <div>

                                                    <?php if ($pagnet['avance'] != 0 && $pagnet['avance'] > 0) :

                                                    ?>

                                                        <?php echo 'Avance : ' . $pagnet['avance'] . '<br/>'; ?>

                                                        <?php echo 'Reste : ' . ($pagnet['apayerPagnet'] - $pagnet['avance']) . '<br/>'; ?>

                                                    <?php endif; ?>

                                                </div>

                                                <div>

                                                    <?php if ($pagnet['avance'] != 0 && $pagnet['avance'] > 0) :

                                                        $sqlGetCompteAvance = "SELECT * FROM `" . $nomtableVersement . "` v, `" . $nomtableCompte . "` c where v.idCompte=c.idCompte AND idPagnet = " . $pagnet['idPagnet'];

                                                        $resAvance = mysql_query($sqlGetCompteAvance) or die("persoonel requête 2" . mysql_error());

                                                        $cptA = mysql_fetch_array($resAvance);

                                                    ?>

                                                        <?php echo 'Compte avance : ' . $cptA['nomCompte'] . '<br/>'; ?>

                                                    <?php endif; ?>

                                                </div>

                                            <?php endif; ?>

                                            <div>

                                                <?php if ($pagnet['versement'] != 0) : ?>

                                                    <?php echo 'Espèces : ' . $pagnet['versement'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['restourne'] != 0 && $pagnet['restourne'] > 0) : ?>

                                                    <?php echo 'Rendu : ' . $pagnet['restourne'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['livreur'] != '' && $pagnet['livreur'] != null) : ?>

                                                    <?php echo  '<br/>Livreur : ' . $pagnet['livreur']; ?>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                        <!--*******************************Fin Total Facture****************************************-->



                                    </div>

                                </div>

                            </div>

                        <?php

                        } else if ($ligne['classe'] == 2  && $pagnet['type'] == 0) { ?>

                            <div class="panel panel-danger">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet" . $pagnet['idPagnet'] . ""; ?> class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Panier <?php echo $n; ?>

                                            <span class="spanDate noImpr"> </span>

                                            <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

                                            <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                            <?php $sqlT = "SELECT totalp FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resT = mysql_query($sqlT) or die("select stock impossible =>" . mysql_error());

                                            $TotalT = mysql_fetch_array($resT);

                                            $sqlP = "SELECT apayerPagnet FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resP = mysql_query($sqlP) or die("select stock impossible =>" . mysql_error());

                                            $TotalP = mysql_fetch_array($resP);

                                            ?>

                                            <span class="spanTotal noImpr">Total panier: <span><?php echo $TotalT[0]; ?>
                                                </span></span>

                                            <span class="spanTotal noImpr">Total à payer: <span><?php echo $TotalP[0]; ?>
                                                </span></span>

                                            <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                        </a>

                                    </h4>

                                </div>

                                <div <?php echo  "id=pagnet" . $pagnet['idPagnet'] . ""; ?> <?php

                                                                                            if ($idmax == $pagnet['idPagnet']) {

                                                                                            ?> class="panel-collapse collapse in" <?php

                                                                                                                                } else {

                                                                                                                                    ?> class="panel-collapse collapse " <?php

                                                                                                                                                                    }

                                                                                                                                                                        ?>>

                                    <div class="panel-body">

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                        <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet" . $pagnet['idPagnet']; ?>>

                                            <span class="glyphicon glyphicon-remove"></span>Retourner

                                        </button>



                                        <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

                                            <div class="modal-dialog">

                                                <!-- Modal content-->

                                                <div class="modal-content">

                                                    <div class="modal-header panel-primary">

                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                        <h4 class="modal-title">Confirmation</h4>

                                                    </div>

                                                    <form class="form-inline noImpr" id="factForm" method="post">

                                                        <div class="modal-body">

                                                            <p><?php echo "Voulez-vous retourner le panier numéro <b>" . $pagnet['idPagnet'] . "<b>"; ?>
                                                            </p>

                                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value='" . $pagnet['idPagnet'] . "'"; ?>>

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
                                        <button class="btn btn-warning  pull-left" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'recu' . $pagnet['idPagnet']; ?>').submit();">

                                            Reçu

                                        </button>


                                        <form class="form-inline pull-right noImpr" <?php echo  "id=recu" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="recuCaisse.php">

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                        </form>


                                        <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket' . $pagnet['idPagnet']; ?>').submit();">

                                            Ticket de Caisse

                                        </button>


                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="barcodeFacture.php">

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

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

                                                $sql8 = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $pagnet['idPagnet'] . " ORDER BY numligne DESC";

                                                $res8 = mysql_query($sql8) or die("persoonel requête 2" . mysql_error());

                                                while ($ligne = mysql_fetch_assoc($res8)) { ?>

                                                    <tr>

                                                        <td><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne" . $pagnet['idPagnet'] . ""; ?><?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                        </td>

                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                        <td>

                                                            <?php echo  $ligne['prixunitevente']; ?> <span class="factureFois"></span>

                                                        </td>

                                                        <td>

                                                            <button type="submit" disabled="true" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne" . $ligne['numligne']; ?>>

                                                                <span class="glyphicon glyphicon-remove"></span>Retour

                                                            </button>



                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne" . $ligne['numligne']; ?> role="dialog">

                                                                <div class="modal-dialog">

                                                                    <!-- Modal content-->

                                                                    <div class="modal-content">

                                                                        <div class="modal-header panel-primary">

                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                        </div>

                                                                        <form class="form-inline noImpr" id="factForm" method="post">

                                                                            <div class="modal-body">

                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>" . $pagnet['idPagnet'] . "</b>"; ?>
                                                                                </p>

                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                                                <input type="hidden" name="designation" <?php echo  "value=" . $ligne['designation'] . ""; ?>>

                                                                                <input type="hidden" name="numligne" <?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                                                <input type="hidden" name="idStock" <?php echo  "value=" . $ligne['idStock'] . ""; ?>>

                                                                                <input type="hidden" name="unitevente" <?php echo  "value=" . $ligne['unitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixunitevente" <?php echo  "value=" . $ligne['prixunitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=" . $ligne['prixtotal'] . ""; ?>>

                                                                                <input type="hidden" name="totalp" <?php echo  "value=" . $pagnet['totalp'] . ""; ?>>

                                                                                <div class="row">

                                                                                    <div class="col-xs-6">

                                                                                        <label for="reference">Ancienne quantite
                                                                                        </label>

                                                                                        <input type="text" disabled="true" class="form-control" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                                    </div>

                                                                                    <div class="col-xs-6">

                                                                                        <label for="reference">Nouvelle quantite</label>

                                                                                        <input type="text" class="form-control" name="quantite" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

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

                                        <div class="col-sm-11">

                                            <div>

                                                <?php echo  '********************************************* <br/>'; ?>

                                                <?php echo  'TOTAL : ' . $pagnet['totalp'] . '<br/>'; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['remise'] != 0 && $pagnet['remise'] > 0) : ?>

                                                    <?php echo 'Remise :' . $pagnet['remise'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php echo  '<b>Net à payer : ' . $pagnet['apayerPagnet'] . '</b><br/>'; ?>

                                            </div>

                                            <?php if ($_SESSION['compte'] == 1) : ?>

                                                <div>

                                                    <?php if ($pagnet['idCompte'] != 0 && $pagnet['idCompte'] > 0) :

                                                        $sqlGetComptePay2 = "SELECT * FROM `" . $nomtableCompte . "` where idCompte = " . $pagnet['idCompte'];

                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die("persoonel requête 2" . mysql_error());

                                                        $cpt = mysql_fetch_array($resPay2);

                                                    ?>

                                                        <?php echo 'Compte :' . $cpt['nomCompte'] . '<br/>'; ?>

                                                    <?php endif; ?>

                                                </div>

                                            <?php endif; ?>

                                            <div>

                                                <?php if ($pagnet['versement'] != 0) : ?>

                                                    <?php echo 'Espèces : ' . $pagnet['versement'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['restourne'] != 0 && $pagnet['restourne'] > 0) : ?>

                                                    <?php echo 'Rendu : ' . $pagnet['restourne'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                        </div>
                                        <div class="col-sm-1" tyle="text-align:right;">
                                            <br />
                                            <?php if ($pagnet['image'] != null && $pagnet['image'] != ' ') {
                                                $format = substr($pagnet['image'], -3); ?>
                                                <?php if ($format == 'pdf') { ?>
                                                    <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvPanier" . $pagnet['idPagnet']; ?> onclick="imageUpPanier(<?php echo $pagnet['idPagnet']; ?>,<?php echo $pagnet['image']; ?>)" />
                                                <?php } else {
                                                ?>
                                                    <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvPanier" . $pagnet['idPagnet']; ?> onclick="imageUpPanier(<?php echo $pagnet['idPagnet']; ?>,<?php echo $pagnet['image']; ?>)" />
                                                <?php } ?>
                                            <?php } else {
                                            ?>
                                                <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" <?php echo "data-target=#imageNvPanier" . $pagnet['idPagnet']; ?> onclick="imageUpPanier(<?php echo $pagnet['idPagnet']; ?>,<?php echo $pagnet['image']; ?>)" />
                                            <?php } ?>
                                        </div>
                                        <!--*******************************Fin Total Facture****************************************-->

                                    </div>

                                </div>

                            </div>

                        <?php

                        } else if ($ligne['classe'] == 3) { ?>

                            <div class="panel panel-warning">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet" . $pagnet['idPagnet'] . ""; ?> class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Panier <?php echo $n; ?>

                                            <span class="spanDate noImpr"> </span>

                                            <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

                                            <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                            <?php $sqlT = "SELECT totalp FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resT = mysql_query($sqlT) or die("select stock impossible =>" . mysql_error());

                                            $TotalT = mysql_fetch_array($resT);

                                            $sqlP = "SELECT apayerPagnet FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resP = mysql_query($sqlP) or die("select stock impossible =>" . mysql_error());

                                            $TotalP = mysql_fetch_array($resP); ?>

                                            <span class="spanTotal noImpr">Total panier: <span><?php echo $TotalT[0]; ?>
                                                </span></span>

                                            <span class="spanTotal noImpr">Total à payer: <span><?php echo $TotalP[0]; ?>
                                                </span></span>

                                            <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                        </a>

                                    </h4>

                                </div>

                                <div <?php echo  "id=pagnet" . $pagnet['idPagnet'] . ""; ?> <?php

                                                                                            if ($idmax == $pagnet['idPagnet']) {

                                                                                            ?> class="panel-collapse collapse in" <?php

                                                                                                                                } else {

                                                                                                                                    ?> class="panel-collapse collapse " <?php

                                                                                                                                                                    }

                                                                                                                                                                        ?>>

                                    <div class="panel-body">

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                        <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet" . $pagnet['idPagnet']; ?>>

                                            <span class="glyphicon glyphicon-remove"></span>Retourner

                                        </button>



                                        <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

                                            <div class="modal-dialog">

                                                <!-- Modal content-->

                                                <div class="modal-content">

                                                    <div class="modal-header panel-primary">

                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                        <h4 class="modal-title">Confirmation</h4>

                                                    </div>

                                                    <form class="form-inline noImpr" id="factForm" method="post">

                                                        <div class="modal-body">

                                                            <p><?php echo "Voulez-vous retourner le panier numéro <b>" . $pagnet['idPagnet'] . "<b>"; ?>
                                                            </p>

                                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value='" . $pagnet['idPagnet'] . "'"; ?>>

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


                                        <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet" . $pagnet['idPagnet']; ?>>

                                            Facture

                                        </button>


                                        <div class="modal fade" <?php echo  "id=msg_fact_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

                                            <div class="modal-dialog">

                                                <!-- Modal content-->

                                                <div class="modal-content">

                                                    <div class="modal-header panel-primary">

                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                        <h4 class="modal-title">Informations Client</h4>

                                                    </div>

                                                    <form class="form-inline noImpr" method="post" action="pdfFacture.php" target="_blank">

                                                        <div class="modal-body">

                                                            <div class="row">

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Prenom(s) Client</label>

                                                                    <input type="text" class="form-control" name="prenom">

                                                                </div>

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Nom Client</label>

                                                                    <input type="text" class="form-control" name="nom">

                                                                </div>

                                                            </div>

                                                            <div class="row">

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Adresse Client</label>

                                                                    <input type="text" class="form-control" name="adresse">

                                                                </div>

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Telephone Client</label>

                                                                    <input type="text" class="form-control" name="telephone">

                                                                </div>

                                                            </div>

                                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value='" . $pagnet['idPagnet'] . "'"; ?>>

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

                                        <button class="btn btn-warning  pull-left" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'recu' . $pagnet['idPagnet']; ?>').submit();">

                                            Reçu

                                        </button>


                                        <form class="form-inline pull-right noImpr" <?php echo  "id=recu" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="recuCaisse.php">

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                        </form>


                                        <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket' . $pagnet['idPagnet']; ?>').submit();">

                                            Ticket de Caisse

                                        </button>



                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="barcodeFacture.php">

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                        </form>



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

                                                $sql8 = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $pagnet['idPagnet'] . " ORDER BY numligne DESC";

                                                $res8 = mysql_query($sql8) or die("persoonel requête 2" . mysql_error());

                                                while ($ligne = mysql_fetch_assoc($res8)) { ?>

                                                    <tr>

                                                        <td><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne" . $pagnet['idPagnet'] . ""; ?><?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                        </td>

                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                        <td>

                                                            <?php if (($ligne['unitevente'] == 'Transaction') || ($ligne['unitevente'] == 'Facture')) { ?>

                                                                <?php if ($ligne['quantite'] == 1) : ?>

                                                                    <?php echo  'Depot'; ?> <span class="factureFois"></span>

                                                                <?php endif; ?>

                                                                <?php if ($ligne['quantite'] == 0) : ?>

                                                                    <?php echo  'Retrait'; ?> <span class="factureFois"></span>

                                                                <?php endif; ?>

                                                                <?php if (($ligne['quantite'] != 0) && ($ligne['quantite'] != 1)) : ?>

                                                                    <?php

                                                                    $reqT = "SELECT * from `aaa-transaction` where idTransaction='" . $ligne['quantite'] . "'";

                                                                    $resT = mysql_query($reqT) or die("select stock impossible =>" . mysql_error());

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

                                                            <?php echo  $ligne['prixunitevente']; ?> <span class="factureFois"></span>

                                                        </td>

                                                        <td>

                                                            <button type="submit" disabled="true" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne" . $ligne['numligne']; ?>>

                                                                <span class="glyphicon glyphicon-remove"></span>Retour

                                                            </button>



                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne" . $ligne['numligne']; ?> role="dialog">

                                                                <div class="modal-dialog">

                                                                    <!-- Modal content-->

                                                                    <div class="modal-content">

                                                                        <div class="modal-header panel-primary">

                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                        </div>

                                                                        <form class="form-inline noImpr" id="factForm" method="post">

                                                                            <div class="modal-body">

                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>" . $pagnet['idPagnet'] . "</b>"; ?>
                                                                                </p>

                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                                                <input type="hidden" name="designation" <?php echo  "value=" . $ligne['designation'] . ""; ?>>

                                                                                <input type="hidden" name="numligne" <?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                                                <input type="hidden" name="idStock" <?php echo  "value=" . $ligne['idStock'] . ""; ?>>

                                                                                <input type="hidden" name="unitevente" <?php echo  "value=" . $ligne['unitevente'] . ""; ?>>

                                                                                <input type="hidden" name="quantite" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                                <input type="hidden" name="prixunitevente" <?php echo  "value=" . $ligne['prixunitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=" . $ligne['prixtotal'] . ""; ?>>

                                                                                <input type="hidden" name="totalp" <?php echo  "value=" . $pagnet['totalp'] . ""; ?>>

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

                                                <?php echo  'TOTAL : ' . $pagnet['totalp'] . '<br/>'; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['remise'] != 0 && $pagnet['remise'] > 0) : ?>

                                                    <?php echo 'Remise :' . $pagnet['remise'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php echo  '<b>Net à payer : ' . $pagnet['apayerPagnet'] . '</b><br/>'; ?>

                                            </div>

                                            <?php if ($_SESSION['compte'] == 1) : ?>

                                                <div>

                                                    <?php if ($pagnet['idCompte'] != 0 && $pagnet['idCompte'] > 0) :

                                                        $sqlGetComptePay2 = "SELECT * FROM `" . $nomtableCompte . "` where idCompte = " . $pagnet['idCompte'];

                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die("persoonel requête 2" . mysql_error());

                                                        $cpt = mysql_fetch_array($resPay2);

                                                    ?>

                                                        <?php echo 'Compte :' . $cpt['nomCompte'] . '<br/>'; ?>

                                                    <?php endif; ?>

                                                </div>

                                            <?php endif; ?>

                                            <div>

                                                <?php if ($pagnet['versement'] != 0) : ?>

                                                    <?php echo 'Espèces : ' . $pagnet['versement'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['restourne'] != 0 && $pagnet['restourne'] > 0) : ?>

                                                    <?php echo 'Rendu : ' . $pagnet['restourne'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                        <!--*******************************Fin Total Facture****************************************-->

                                    </div>

                                </div>

                            </div>

                        <?php

                        } else if ($ligne['classe'] == 4) { ?>

                            <div class="panel panel-warning">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet" . $pagnet['idPagnet'] . ""; ?> class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Panier <?php echo $n; ?>

                                            <span class="spanDate noImpr"> </span>

                                            <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

                                            <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                            <?php $sqlT = "SELECT totalp FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resT = mysql_query($sqlT) or die("select stock impossible =>" . mysql_error());

                                            $TotalT = mysql_fetch_array($resT);

                                            $sqlP = "SELECT apayerPagnet FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resP = mysql_query($sqlP) or die("select stock impossible =>" . mysql_error());

                                            $TotalP = mysql_fetch_array($resP); ?>

                                            <span class="spanTotal noImpr">Total panier: <span><?php echo $TotalT[0]; ?>
                                                </span></span>

                                            <span class="spanTotal noImpr">Total à payer: <span><?php echo $TotalP[0]; ?>
                                                </span></span>

                                            <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                        </a>

                                    </h4>

                                </div>

                                <div <?php echo  "id=pagnet" . $pagnet['idPagnet'] . ""; ?> <?php

                                                                                            if ($idmax == $pagnet['idPagnet']) {

                                                                                            ?> class="panel-collapse collapse in" <?php

                                                                                                                                } else {

                                                                                                                                    ?> class="panel-collapse collapse " <?php

                                                                                                                                                                    }

                                                                                                                                                                        ?>>

                                    <div class="panel-body">

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                        <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet" . $pagnet['idPagnet']; ?>>

                                            <span class="glyphicon glyphicon-remove"></span>Retourner

                                        </button>



                                        <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

                                            <div class="modal-dialog">

                                                <!-- Modal content-->

                                                <div class="modal-content">

                                                    <div class="modal-header panel-primary">

                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                        <h4 class="modal-title">Confirmation</h4>

                                                    </div>

                                                    <form class="form-inline noImpr" id="factForm" method="post">

                                                        <div class="modal-body">

                                                            <p><?php echo "Voulez-vous retourner le panier numéro <b>" . $pagnet['idPagnet'] . "<b>"; ?>
                                                            </p>

                                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value='" . $pagnet['idPagnet'] . "'"; ?>>

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



                                        <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_fact_pagnet" . $pagnet['idPagnet']; ?>>

                                            Facture

                                        </button>



                                        <div class="modal fade" <?php echo  "id=msg_fact_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

                                            <div class="modal-dialog">

                                                <!-- Modal content-->

                                                <div class="modal-content">

                                                    <div class="modal-header panel-primary">

                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                        <h4 class="modal-title">Informations Client</h4>

                                                    </div>

                                                    <form class="form-inline noImpr" method="post" action="pdfFacture.php" target="_blank">

                                                        <div class="modal-body">

                                                            <div class="row">

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Prenom(s) Client</label>

                                                                    <input type="text" class="form-control" name="prenom">

                                                                </div>

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Nom Client</label>

                                                                    <input type="text" class="form-control" name="nom">

                                                                </div>

                                                            </div>

                                                            <div class="row">

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Adresse Client</label>

                                                                    <input type="text" class="form-control" name="adresse">

                                                                </div>

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Telephone Client</label>

                                                                    <input type="text" class="form-control" name="telephone">

                                                                </div>

                                                            </div>

                                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value='" . $pagnet['idPagnet'] . "'"; ?>>

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

                                        <button class="btn btn-warning  pull-left" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'recu' . $pagnet['idPagnet']; ?>').submit();">

                                            Reçu

                                        </button>


                                        <form class="form-inline pull-right noImpr" <?php echo  "id=recu" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="recuCaisse.php">

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                        </form>

                                        <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket' . $pagnet['idPagnet']; ?>').submit();">

                                            Ticket de Caisse

                                        </button>



                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="barcodeFacture.php">

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                        </form>



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

                                                $sql8 = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $pagnet['idPagnet'] . " ORDER BY numligne DESC";

                                                $res8 = mysql_query($sql8) or die("persoonel requête 2" . mysql_error());

                                                while ($ligne = mysql_fetch_assoc($res8)) { ?>

                                                    <tr>

                                                        <td><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne" . $pagnet['idPagnet'] . ""; ?><?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                        </td>

                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                        <td>

                                                            <?php

                                                            if ($ligne['unitevente'] == 'Credit') { ?>

                                                                <?php if ($ligne['quantite'] == 0) : ?>

                                                                    <?php echo  'Especes'; ?> <span class="factureFois"></span>

                                                                <?php endif; ?>

                                                                <?php if ($ligne['quantite'] != 0) : ?>

                                                                    <?php

                                                                    $reqT = "SELECT * from `aaa-transaction` where idTransaction='" . $ligne['quantite'] . "'";

                                                                    $resT = mysql_query($reqT) or die("select stock impossible =>" . mysql_error());

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

                                                            <?php echo  $ligne['prixunitevente']; ?> <span class="factureFois"></span>

                                                        </td>

                                                        <td>

                                                            <button type="submit" disabled="true" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne" . $ligne['numligne']; ?>>

                                                                <span class="glyphicon glyphicon-remove"></span>Retour

                                                            </button>



                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne" . $ligne['numligne']; ?> role="dialog">

                                                                <div class="modal-dialog">

                                                                    <!-- Modal content-->

                                                                    <div class="modal-content">

                                                                        <div class="modal-header panel-primary">

                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                        </div>

                                                                        <form class="form-inline noImpr" id="factForm" method="post">

                                                                            <div class="modal-body">

                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>" . $pagnet['idPagnet'] . "</b>"; ?>
                                                                                </p>

                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                                                <input type="hidden" name="designation" <?php echo  "value=" . $ligne['designation'] . ""; ?>>

                                                                                <input type="hidden" name="numligne" <?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                                                <input type="hidden" name="idStock" <?php echo  "value=" . $ligne['idStock'] . ""; ?>>

                                                                                <input type="hidden" name="unitevente" <?php echo  "value=" . $ligne['unitevente'] . ""; ?>>

                                                                                <input type="hidden" name="quantite" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                                <input type="hidden" name="prixunitevente" <?php echo  "value=" . $ligne['prixunitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=" . $ligne['prixtotal'] . ""; ?>>

                                                                                <input type="hidden" name="totalp" <?php echo  "value=" . $pagnet['totalp'] . ""; ?>>

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

                                                <?php echo  'TOTAL : ' . $pagnet['totalp'] . '<br/>'; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['remise'] != 0 && $pagnet['remise'] > 0) : ?>

                                                    <?php echo 'Remise :' . $pagnet['remise'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php echo  '<b>Net à payer : ' . $pagnet['apayerPagnet'] . '</b><br/>'; ?>

                                            </div>

                                            <?php if ($_SESSION['compte'] == 1) : ?>

                                                <div>

                                                    <?php if ($pagnet['idCompte'] != 0 && $pagnet['idCompte'] > 0) :

                                                        $sqlGetComptePay2 = "SELECT * FROM `" . $nomtableCompte . "` where idCompte = " . $pagnet['idCompte'];

                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die("persoonel requête 2" . mysql_error());

                                                        $cpt = mysql_fetch_array($resPay2);

                                                    ?>

                                                        <?php echo 'Compte :' . $cpt['nomCompte'] . '<br/>'; ?>

                                                    <?php endif; ?>

                                                </div>

                                            <?php endif; ?>

                                            <div>

                                                <?php if ($pagnet['versement'] != 0) : ?>

                                                    <?php echo 'Espèces : ' . $pagnet['versement'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['restourne'] != 0 && $pagnet['restourne'] > 0) : ?>

                                                    <?php echo 'Rendu : ' . $pagnet['restourne'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                        <!--*******************************Fin Total Facture****************************************-->

                                    </div>

                                </div>

                            </div>

                        <?php

                        } else if ($ligne['classe'] == 5) { ?>

                            <div class="panel panel-info">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet" . $pagnet['idPagnet'] . ""; ?> class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Panier <?php echo $n; ?>

                                            <span class="spanDate noImpr"> </span>

                                            <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

                                            <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                            <?php $sqlT = "SELECT totalp FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resT = mysql_query($sqlT) or die("select stock impossible =>" . mysql_error());

                                            $TotalT = mysql_fetch_array($resT);

                                            $sqlP = "SELECT apayerPagnet FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resP = mysql_query($sqlP) or die("select stock impossible =>" . mysql_error());

                                            $TotalP = mysql_fetch_array($resP); ?>

                                            <span class="spanTotal noImpr">Total panier: <span><?php echo $TotalT[0]; ?>
                                                </span></span>

                                            <span class="spanTotal noImpr">Total à payer: <span><?php echo $TotalP[0]; ?>
                                                </span></span>

                                            <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                        </a>

                                    </h4>

                                </div>

                                <div <?php echo  "id=pagnet" . $pagnet['idPagnet'] . ""; ?> <?php

                                                                                            if ($idmax == $pagnet['idPagnet']) {

                                                                                            ?> class="panel-collapse collapse in" <?php

                                                                                                                                } else {

                                                                                                                                    ?> class="panel-collapse collapse " <?php

                                                                                                                                                                    }

                                                                                                                                                                        ?>>

                                    <div class="panel-body">

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                        <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet" . $pagnet['idPagnet']; ?>>

                                            <span class="glyphicon glyphicon-remove"></span>Retourner

                                        </button>



                                        <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

                                            <div class="modal-dialog">

                                                <!-- Modal content-->

                                                <div class="modal-content">

                                                    <div class="modal-header panel-primary">

                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                        <h4 class="modal-title">Confirmation</h4>

                                                    </div>

                                                    <form class="form-inline noImpr" id="factForm" method="post">

                                                        <div class="modal-body">

                                                            <p><?php echo "Voulez-vous retourner le panier numéro <b>" . $pagnet['idPagnet'] . "<b>"; ?>
                                                            </p>

                                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value='" . $pagnet['idPagnet'] . "'"; ?>>

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

                                        <button class="btn btn-warning  pull-left" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'recu' . $pagnet['idPagnet']; ?>').submit();">

                                            Reçu

                                        </button>


                                        <form class="form-inline pull-right noImpr" <?php echo  "id=recu" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="recuCaisse.php">

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                        </form>


                                        <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket' . $pagnet['idPagnet']; ?>').submit();">

                                            Ticket de Caisse

                                        </button>




                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="barcodeFacture.php">

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                        </form>



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

                                                $sql8 = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $pagnet['idPagnet'] . " ORDER BY numligne DESC";

                                                $res8 = mysql_query($sql8) or die("persoonel requête 2" . mysql_error());

                                                while ($ligne = mysql_fetch_assoc($res8)) { ?>

                                                    <tr>

                                                        <td><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne" . $pagnet['idPagnet'] . ""; ?><?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                        </td>

                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                        <td>

                                                            <?php echo  'Montant'; ?> <span class="factureFois"></span>

                                                        </td>

                                                        <td class="unitevente ">

                                                            <?php echo $ligne['unitevente']; ?>

                                                        </td>

                                                        <td>

                                                            <?php echo  $ligne['prixunitevente']; ?> <span class="factureFois"></span>

                                                        </td>

                                                        <td>

                                                            <button type="submit" disabled="true" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne" . $ligne['numligne']; ?>>

                                                                <span class="glyphicon glyphicon-remove"></span>Retour

                                                            </button>



                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne" . $ligne['numligne']; ?> role="dialog">

                                                                <div class="modal-dialog">

                                                                    <!-- Modal content-->

                                                                    <div class="modal-content">

                                                                        <div class="modal-header panel-primary">

                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                        </div>

                                                                        <form class="form-inline noImpr" id="factForm" method="post">

                                                                            <div class="modal-body">

                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>" . $pagnet['idPagnet'] . "</b>"; ?>
                                                                                </p>

                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                                                <input type="hidden" name="designation" <?php echo  "value=" . $ligne['designation'] . ""; ?>>

                                                                                <input type="hidden" name="numligne" <?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                                                <input type="hidden" name="idStock" <?php echo  "value=" . $ligne['idStock'] . ""; ?>>

                                                                                <input type="hidden" name="unitevente" <?php echo  "value=" . $ligne['unitevente'] . ""; ?>>

                                                                                <input type="hidden" name="quantite" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                                <input type="hidden" name="prixunitevente" <?php echo  "value=" . $ligne['prixunitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=" . $ligne['prixtotal'] . ""; ?>>

                                                                                <input type="hidden" name="totalp" <?php echo  "value=" . $pagnet['totalp'] . ""; ?>>

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

                                                <?php echo  'TOTAL : ' . $pagnet['totalp'] . '<br/>'; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['remise'] != 0 && $pagnet['remise'] > 0) : ?>

                                                    <?php echo 'Remise :' . $pagnet['remise'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php echo  '<b>Net à payer : ' . $pagnet['apayerPagnet'] . '</b><br/>'; ?>

                                            </div>

                                            <?php if ($_SESSION['compte'] == 1) : ?>

                                                <div>

                                                    <?php if ($pagnet['idCompte'] != 0 && $pagnet['idCompte'] > 0) :

                                                        $sqlGetComptePay2 = "SELECT * FROM `" . $nomtableCompte . "` where idCompte = " . $pagnet['idCompte'];

                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die("persoonel requête 2" . mysql_error());

                                                        $cpt = mysql_fetch_array($resPay2);

                                                    ?>

                                                        <?php echo 'Compte :' . $cpt['nomCompte'] . '<br/>'; ?>

                                                    <?php endif; ?>

                                                </div>

                                            <?php endif; ?>

                                            <div>

                                                <?php if ($pagnet['versement'] != 0) : ?>

                                                    <?php echo 'Espèces : ' . $pagnet['versement'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['restourne'] != 0 && $pagnet['restourne'] > 0) : ?>

                                                    <?php echo 'Rendu : ' . $pagnet['restourne'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                        <!--*******************************Fin Total Facture****************************************-->

                                    </div>

                                </div>

                            </div>

                        <?php

                        } else if ($ligne['classe'] == 6 || $ligne['classe'] == 7) { ?>

                            <div class="panel panel-default">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet" . $pagnet['idPagnet'] . ""; ?> class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Panier <?php echo $n; ?>

                                            <span class="spanDate noImpr"> </span>

                                            <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

                                            <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                            <?php $sqlT = "SELECT totalp FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resT = mysql_query($sqlT) or die("select stock impossible =>" . mysql_error());

                                            $TotalT = mysql_fetch_array($resT);

                                            $sqlP = "SELECT apayerPagnet FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resP = mysql_query($sqlP) or die("select stock impossible =>" . mysql_error());

                                            $TotalP = mysql_fetch_array($resP); ?>

                                            <span class="spanTotal noImpr">Total panier: <span><?php echo $TotalT[0]; ?>
                                                </span></span>

                                            <span class="spanTotal noImpr">Total à payer: <span><?php echo $TotalP[0]; ?>
                                                </span></span>

                                            <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                        </a>

                                    </h4>

                                </div>

                                <div <?php echo  "id=pagnet" . $pagnet['idPagnet'] . ""; ?> <?php

                                                                                            if ($idmax == $pagnet['idPagnet']) {

                                                                                            ?> class="panel-collapse collapse in" <?php

                                                                                                                                } else {

                                                                                                                                    ?> class="panel-collapse collapse " <?php

                                                                                                                                                                    }

                                                                                                                                                                        ?>>

                                    <div class="panel-body">

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                        <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet" . $pagnet['idPagnet']; ?>>

                                            <span class="glyphicon glyphicon-remove"></span>Retourner

                                        </button>



                                        <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

                                            <div class="modal-dialog">

                                                <!-- Modal content-->

                                                <div class="modal-content">

                                                    <div class="modal-header panel-primary">

                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                        <h4 class="modal-title">Confirmation</h4>

                                                    </div>

                                                    <form class="form-inline noImpr" id="factForm" method="post">

                                                        <div class="modal-body">

                                                            <p><?php echo "Voulez-vous retourner le panier numéro <b>" . $pagnet['idPagnet'] . "<b>"; ?>
                                                            </p>

                                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value='" . $pagnet['idPagnet'] . "'"; ?>>

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


                                        <button class="btn btn-warning  pull-left" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'recu' . $pagnet['idPagnet']; ?>').submit();">

                                            Reçu

                                        </button>


                                        <form class="form-inline pull-right noImpr" <?php echo  "id=recu" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="recuCaisse.php">

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                        </form>

                                        <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket' . $pagnet['idPagnet']; ?>').submit();">

                                            Ticket de Caisse

                                        </button>




                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="barcodeFacture.php">

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                        </form>



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

                                                $sql8 = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $pagnet['idPagnet'] . " ORDER BY numligne DESC";

                                                $res8 = mysql_query($sql8) or die("persoonel requête 2" . mysql_error());

                                                while ($ligne = mysql_fetch_assoc($res8)) { ?>

                                                    <tr>

                                                        <td><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne" . $pagnet['idPagnet'] . ""; ?><?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                        </td>

                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                        <td>

                                                            <?php echo  'Montant'; ?> <span class="factureFois"></span>

                                                        </td>

                                                        <td class="unitevente ">

                                                            <?php echo $ligne['unitevente']; ?>

                                                        </td>

                                                        <td>

                                                            <?php echo  $ligne['prixunitevente']; ?> <span class="factureFois"></span>

                                                        </td>

                                                        <td>

                                                            <button type="submit" disabled="true" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne" . $ligne['numligne']; ?>>

                                                                <span class="glyphicon glyphicon-remove"></span>Retour

                                                            </button>



                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne" . $ligne['numligne']; ?> role="dialog">

                                                                <div class="modal-dialog">

                                                                    <!-- Modal content-->

                                                                    <div class="modal-content">

                                                                        <div class="modal-header panel-primary">

                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                        </div>

                                                                        <form class="form-inline noImpr" id="factForm" method="post">

                                                                            <div class="modal-body">

                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>" . $pagnet['idPagnet'] . "</b>"; ?>
                                                                                </p>

                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                                                <input type="hidden" name="designation" <?php echo  "value=" . $ligne['designation'] . ""; ?>>

                                                                                <input type="hidden" name="numligne" <?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                                                <input type="hidden" name="idStock" <?php echo  "value=" . $ligne['idStock'] . ""; ?>>

                                                                                <input type="hidden" name="unitevente" <?php echo  "value=" . $ligne['unitevente'] . ""; ?>>

                                                                                <input type="hidden" name="quantite" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                                <input type="hidden" name="prixunitevente" <?php echo  "value=" . $ligne['prixunitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=" . $ligne['prixtotal'] . ""; ?>>

                                                                                <input type="hidden" name="totalp" <?php echo  "value=" . $pagnet['totalp'] . ""; ?>>

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

                                                <?php echo  'TOTAL : ' . $pagnet['totalp'] . '<br/>'; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['remise'] != 0 && $pagnet['remise'] > 0) : ?>

                                                    <?php echo 'Remise :' . $pagnet['remise'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php echo  '<b>Net à payer : ' . $pagnet['apayerPagnet'] . '</b><br/>'; ?>

                                            </div>

                                            <?php if ($_SESSION['compte'] == 1) : ?>

                                                <div>

                                                    <?php if ($pagnet['idCompte'] != 0 && $pagnet['idCompte'] > 0) :

                                                        $sqlGetComptePay2 = "SELECT * FROM `" . $nomtableCompte . "` where idCompte = " . $pagnet['idCompte'];

                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die("persoonel requête 2" . mysql_error());

                                                        $cpt = mysql_fetch_array($resPay2);

                                                    ?>

                                                        <?php echo 'Compte :' . $cpt['nomCompte'] . '<br/>'; ?>

                                                    <?php endif; ?>

                                                </div>

                                            <?php endif; ?>

                                            <div>

                                                <?php if ($pagnet['versement'] != 0) : ?>

                                                    <?php echo 'Espèces : ' . $pagnet['versement'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['restourne'] != 0 && $pagnet['restourne'] > 0) : ?>

                                                    <?php echo 'Rendu : ' . $pagnet['restourne'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                        <!--*******************************Fin Total Facture****************************************-->

                                    </div>

                                </div>

                            </div>

                        <?php

                        } else if (($ligne['classe'] == 0 || $ligne['classe'] == 1) && $pagnet['type'] == 1) { ?>

                            <div class="panel panel-danger">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet" . $pagnet['idPagnet'] . ""; ?> class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Panier <?php echo $n; ?>

                                            <span class="spanDate noImpr"> </span>

                                            <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

                                            <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                            <?php $sqlT = "SELECT totalp FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resT = mysql_query($sqlT) or die("select stock impossible =>" . mysql_error());

                                            $TotalT = mysql_fetch_array($resT);

                                            $sqlP = "SELECT apayerPagnet FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resP = mysql_query($sqlP) or die("select stock impossible =>" . mysql_error());

                                            $TotalP = mysql_fetch_array($resP);



                                            $sql = "SELECT * from `aaa-utilisateur` where idutilisateur='" . $pagnet['iduser'] . "' ";

                                            $res = mysql_query($sql);

                                            $user = mysql_fetch_array($res);

                                            ?>

                                            <?php if ($_SESSION['Pays'] == 'Canada') {   ?>

                                                <span class="spanTotal noImpr">Sous-Total: <span><?php echo $TotalP[0]; ?>
                                                    </span></span>

                                                <span class="spanTotal noImpr">Total :
                                                    <span><?php echo ($TotalP[0] + (($TotalP[0] * 5) / 100) + (($TotalP[0] * 9.975) / 100)); ?>
                                                    </span></span>

                                            <?php } else {   ?>

                                                <span class="spanTotal noImpr">Total panier: <span><?php echo $TotalT[0]; ?>
                                                    </span></span>

                                                <span class="spanTotal noImpr">Total à payer: <span><?php echo $TotalP[0]; ?>
                                                    </span></span>

                                            <?php } ?>

                                            <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                            <span class="spanDate noImpr">
                                                <?php echo substr(strtoupper($user['prenom']), 0, 3); ?></span>

                                        </a>

                                    </h4>

                                </div>

                                <div <?php echo  "id=pagnet" . $pagnet['idPagnet'] . ""; ?> <?php

                                                                                            if ($idmax == $pagnet['idPagnet']) {

                                                                                            ?> class="panel-collapse collapse in" <?php

                                                                                                                                } else {

                                                                                                                                    ?> class="panel-collapse collapse " <?php

                                                                                                                                                                    }

                                                                                                                                                                        ?>>

                                    <div class="panel-body">

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                        <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet" . $pagnet['idPagnet']; ?>>

                                            <span class="glyphicon glyphicon-remove"></span>Retourner

                                        </button>



                                        <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

                                            <div class="modal-dialog">

                                                <!-- Modal content-->

                                                <div class="modal-content">

                                                    <div class="modal-header panel-primary">

                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                        <h4 class="modal-title">Confirmation</h4>

                                                    </div>

                                                    <form class="form-inline noImpr" id="factForm" method="post">

                                                        <div class="modal-body">

                                                            <p><?php echo "Voulez-vous retourner le panier numéro <b>" . $pagnet['idPagnet'] . "<b>"; ?>
                                                            </p>

                                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value='" . $pagnet['idPagnet'] . "'"; ?>>

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



                                        <button class="btn btn-warning  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture' . $pagnet['idPagnet']; ?>').submit();">

                                            Facture

                                        </button>




                                        <?php if ($_SESSION['importExp'] == 1) { ?>

                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="pdfFactureJour.php">

                                                <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                            </form>

                                        <?php } else {

                                        ?>

                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="pdfFacture.php">

                                                <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                            </form>

                                        <?php } ?>

                                        <!--*******************************Fin Facture****************************************-->



                                        <?php if ($_SESSION['caissier'] == 1) { ?>
                                            <button class="btn btn-warning  pull-left" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'recu' . $pagnet['idPagnet']; ?>').submit();">

                                                Reçu

                                            </button>


                                            <form class="form-inline pull-right noImpr" <?php echo  "id=recu" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="recuCaisse.php">

                                                <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                            </form>


                                            <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket' . $pagnet['idPagnet']; ?>').submit();">

                                                Ticket de Caisse

                                            </button>



                                        <?php } ?>



                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="barcodeFacture.php">

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                        </form>



                                        <table class="table ">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th>Référence</th>

                                                    <th>Quantité</th>

                                                    <th>Unité vente</th>

                                                    <th>Prix Unité vente</th>

                                                    <th>Depot</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php

                                                $sql8 = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $pagnet['idPagnet'] . " ORDER BY numligne DESC";

                                                $res8 = mysql_query($sql8) or die("persoonel requête 2" . mysql_error());

                                                while ($ligne = mysql_fetch_assoc($res8)) { ?>

                                                    <tr>

                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                        <td>

                                                            <?php if ($ligne['unitevente'] == 'Transaction') { ?>

                                                                <?php if ($ligne['quantite'] == 1) : ?>

                                                                    <?php echo  'Depot'; ?> <span class="factureFois"></span>

                                                                <?php endif; ?>

                                                                <?php if ($ligne['quantite'] == 0) : ?>

                                                                    <?php echo  'Retrait'; ?> <span class="factureFois"></span>

                                                                <?php endif; ?>

                                                            <?php } else { ?>

                                                                <?php echo  $ligne['quantite']; ?> <span class="factureFois"></span>

                                                            <?php } ?>

                                                        </td>

                                                        <td class="unitevente ">

                                                            <?php echo $ligne['unitevente']; ?>

                                                        </td>

                                                        <td>

                                                            <?php echo  $ligne['prixunitevente']; ?> <span class="factureFois"></span>

                                                        </td>

                                                        <td>

                                                            <?php

                                                            $reqEp = "SELECT * from  `" . $nomtableEntrepot . "` e

                                                                                where idEntrepot='" . $ligne['idEntrepot'] . "' ";

                                                            $resEp = mysql_query($reqEp) or die("select stock impossible =>" . mysql_error());

                                                            $entrepot = mysql_fetch_assoc($resEp);

                                                            echo $entrepot['nomEntrepot'];

                                                            ?>

                                                        </td>

                                                        <td>

                                                            <button type="submit" disabled="true" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne" . $ligne['numligne']; ?>>

                                                                <span class="glyphicon glyphicon-remove"></span>Retour

                                                            </button>



                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne" . $ligne['numligne']; ?> role="dialog">

                                                                <div class="modal-dialog">

                                                                    <!-- Modal content-->

                                                                    <div class="modal-content">

                                                                        <div class="modal-header panel-primary">

                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                        </div>

                                                                        <form class="form-inline noImpr" id="factForm" method="post">

                                                                            <div class="modal-body">

                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>" . $pagnet['idPagnet'] . "</b>"; ?>
                                                                                </p>

                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                                                <input type="hidden" name="designation" <?php echo  "value=" . $ligne['designation'] . ""; ?>>

                                                                                <input type="hidden" name="numligne" <?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                                                <input type="hidden" name="idStock" <?php echo  "value=" . $ligne['idStock'] . ""; ?>>

                                                                                <input type="hidden" name="unitevente" <?php echo  "value=" . $ligne['unitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixunitevente" <?php echo  "value=" . $ligne['prixunitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=" . $ligne['prixtotal'] . ""; ?>>

                                                                                <input type="hidden" name="totalp" <?php echo  "value=" . $pagnet['totalp'] . ""; ?>>

                                                                                <div class="row">

                                                                                    <div class="col-xs-6">

                                                                                        <label for="reference">Ancienne quantite
                                                                                        </label>

                                                                                        <input type="text" disabled="true" class="form-control" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                                    </div>

                                                                                    <div class="col-xs-6">

                                                                                        <label for="reference">Nouvelle quantite</label>

                                                                                        <input type="text" class="form-control" name="quantite" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

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

                                                <?php echo  'TOTAL : ' . $pagnet['totalp'] . '<br/>'; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['remise'] != 0 && $pagnet['remise'] > 0) : ?>

                                                    <?php echo 'Remise :' . $pagnet['remise'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php echo  '<b>Net à payer : ' . $pagnet['apayerPagnet'] . '</b><br/>'; ?>

                                            </div>

                                            <?php if ($_SESSION['compte'] == 1) : ?>

                                                <div>

                                                    <?php if ($pagnet['idCompte'] != 0 && $pagnet['idCompte'] > 0) :

                                                        $sqlGetComptePay2 = "SELECT * FROM `" . $nomtableCompte . "` where idCompte = " . $pagnet['idCompte'];

                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die("persoonel requête 2" . mysql_error());

                                                        $cpt = mysql_fetch_array($resPay2);

                                                    ?>

                                                        <?php echo 'Compte :' . $cpt['nomCompte'] . '<br/>'; ?>

                                                    <?php endif; ?>

                                                </div>

                                            <?php endif; ?>

                                            <div>

                                                <?php if ($pagnet['versement'] != 0) : ?>

                                                    <?php echo 'Espèces : ' . $pagnet['versement'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['restourne'] != 0 && $pagnet['restourne'] > 0) : ?>

                                                    <?php echo 'Rendu : ' . $pagnet['restourne'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                        <!--*******************************Fin Total Facture****************************************-->

                                    </div>

                                </div>

                            </div>

                        <?php

                        } else if (($ligne['classe'] == 0 || $ligne['classe'] == 1) && $pagnet['type'] == 2) { ?>

                            <div class="panel panel-danger">

                                <div class="panel-heading">

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet" . $pagnet['idPagnet'] . ""; ?> class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Panier <?php echo $n; ?>

                                            <span class="spanDate noImpr"> </span>

                                            <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

                                            <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                            <?php $sqlT = "SELECT totalp FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resT = mysql_query($sqlT) or die("select stock impossible =>" . mysql_error());

                                            $TotalT = mysql_fetch_array($resT);

                                            $sqlP = "SELECT apayerPagnet FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resP = mysql_query($sqlP) or die("select stock impossible =>" . mysql_error());

                                            $TotalP = mysql_fetch_array($resP);



                                            $sql = "SELECT * from `aaa-utilisateur` where idutilisateur='" . $pagnet['iduser'] . "' ";

                                            $res = mysql_query($sql);

                                            $user = mysql_fetch_array($res);

                                            ?>

                                            <?php if ($_SESSION['Pays'] == 'Canada') {   ?>

                                                <span class="spanTotal noImpr">Sous-Total: <span><?php echo $TotalP[0]; ?>
                                                    </span></span>

                                                <span class="spanTotal noImpr">Total :
                                                    <span><?php echo ($TotalP[0] + (($TotalP[0] * 5) / 100) + (($TotalP[0] * 9.975) / 100)); ?>
                                                    </span></span>

                                            <?php } else {   ?>

                                                <span class="spanTotal noImpr">Total panier: <span><?php echo $TotalT[0]; ?>
                                                    </span></span>

                                                <span class="spanTotal noImpr">Total à payer: <span><?php echo $TotalP[0]; ?>
                                                    </span></span>

                                            <?php } ?>

                                            <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                            <span class="spanDate noImpr">
                                                <?php echo substr(strtoupper($user['prenom']), 0, 3); ?></span>

                                        </a>

                                    </h4>

                                </div>

                                <div <?php echo  "id=pagnet" . $pagnet['idPagnet'] . ""; ?> <?php

                                                                                            if ($idmax == $pagnet['idPagnet']) {

                                                                                            ?> class="panel-collapse collapse in" <?php

                                                                                                                                } else {

                                                                                                                                    ?> class="panel-collapse collapse " <?php

                                                                                                                                                                    }

                                                                                                                                                                        ?>>

                                    <div class="panel-body">

                                        <!--*******************************Debut Retourner Pagnet****************************************-->

                                        <button type="submit" disabled="true" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet" . $pagnet['idPagnet']; ?>>

                                            <span class="glyphicon glyphicon-remove"></span>Retourner

                                        </button>



                                        <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

                                            <div class="modal-dialog">

                                                <!-- Modal content-->

                                                <div class="modal-content">

                                                    <div class="modal-header panel-primary">

                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                        <h4 class="modal-title">Confirmation</h4>

                                                    </div>

                                                    <form class="form-inline noImpr" id="factForm" method="post">

                                                        <div class="modal-body">

                                                            <p><?php echo "Voulez-vous retourner le panier numéro <b>" . $pagnet['idPagnet'] . "<b>"; ?>
                                                            </p>

                                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value='" . $pagnet['idPagnet'] . "'"; ?>>

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



                                        <button class="btn btn-warning  pull-right" disabled="true" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture' . $pagnet['idPagnet']; ?>').submit();">

                                            Facture

                                        </button>



                                        <?php if ($_SESSION['importExp'] == 1) { ?>

                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="pdfFactureJour.php">

                                                <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                            </form>

                                        <?php } else {

                                        ?>

                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="pdfFacture.php">

                                                <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                            </form>

                                        <?php } ?>

                                        <!--*******************************Fin Facture****************************************-->
                                        <button class="btn btn-warning  pull-left" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'recu' . $pagnet['idPagnet']; ?>').submit();">

                                            Reçu

                                        </button>


                                        <form class="form-inline pull-right noImpr" <?php echo  "id=recu" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="recuCaisse.php">

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                        </form>

                                        <button class="btn btn-info  pull-right" disabled="true" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket' . $pagnet['idPagnet']; ?>').submit();">

                                            Ticket de Caisse

                                        </button>




                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="barcodeFacture.php">

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                        </form>



                                        <table class="table ">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th>Référence</th>

                                                    <th>Quantité</th>

                                                    <th>Unité vente</th>

                                                    <th>Prix Unité vente</th>

                                                    <th>Depot</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php

                                                $sql8 = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $pagnet['idPagnet'] . " ORDER BY numligne DESC";

                                                $res8 = mysql_query($sql8) or die("persoonel requête 2" . mysql_error());

                                                while ($ligne = mysql_fetch_assoc($res8)) { ?>

                                                    <tr>

                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                        <td>

                                                            <?php if ($ligne['unitevente'] == 'Transaction') { ?>

                                                                <?php if ($ligne['quantite'] == 1) : ?>

                                                                    <?php echo  'Depot'; ?> <span class="factureFois"></span>

                                                                <?php endif; ?>

                                                                <?php if ($ligne['quantite'] == 0) : ?>

                                                                    <?php echo  'Retrait'; ?> <span class="factureFois"></span>

                                                                <?php endif; ?>

                                                            <?php } else { ?>

                                                                <?php echo  $ligne['quantite']; ?> <span class="factureFois"></span>

                                                            <?php } ?>

                                                        </td>

                                                        <td class="unitevente ">

                                                            <?php echo $ligne['unitevente']; ?>

                                                        </td>

                                                        <td>

                                                            <?php echo  $ligne['prixunitevente']; ?> <span class="factureFois"></span>

                                                        </td>

                                                        <td>

                                                            <?php

                                                            $reqEp = "SELECT * from  `" . $nomtableEntrepot . "` e

                                                                                where idEntrepot='" . $ligne['idEntrepot'] . "' ";

                                                            $resEp = mysql_query($reqEp) or die("select stock impossible =>" . mysql_error());

                                                            $entrepot = mysql_fetch_assoc($resEp);

                                                            echo $entrepot['nomEntrepot'];

                                                            ?>

                                                        </td>

                                                        <td>

                                                            <button type="submit" disabled="true" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne" . $ligne['numligne']; ?>>

                                                                <span class="glyphicon glyphicon-remove"></span>Retour

                                                            </button>



                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne" . $ligne['numligne']; ?> role="dialog">

                                                                <div class="modal-dialog">

                                                                    <!-- Modal content-->

                                                                    <div class="modal-content">

                                                                        <div class="modal-header panel-primary">

                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                        </div>

                                                                        <form class="form-inline noImpr" id="factForm" method="post">

                                                                            <div class="modal-body">

                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>" . $pagnet['idPagnet'] . "</b>"; ?>
                                                                                </p>

                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                                                <input type="hidden" name="designation" <?php echo  "value=" . $ligne['designation'] . ""; ?>>

                                                                                <input type="hidden" name="numligne" <?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                                                <input type="hidden" name="idStock" <?php echo  "value=" . $ligne['idStock'] . ""; ?>>

                                                                                <input type="hidden" name="unitevente" <?php echo  "value=" . $ligne['unitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixunitevente" <?php echo  "value=" . $ligne['prixunitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=" . $ligne['prixtotal'] . ""; ?>>

                                                                                <input type="hidden" name="totalp" <?php echo  "value=" . $pagnet['totalp'] . ""; ?>>

                                                                                <div class="row">

                                                                                    <div class="col-xs-6">

                                                                                        <label for="reference">Ancienne quantite
                                                                                        </label>

                                                                                        <input type="text" disabled="true" class="form-control" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                                    </div>

                                                                                    <div class="col-xs-6">

                                                                                        <label for="reference">Nouvelle quantite</label>

                                                                                        <input type="text" class="form-control" name="quantite" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

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

                                                <?php echo  'TOTAL : ' . $pagnet['totalp'] . '<br/>'; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['remise'] != 0 && $pagnet['remise'] > 0) : ?>

                                                    <?php echo 'Remise :' . $pagnet['remise'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php echo  '<b>Net à payer : ' . $pagnet['apayerPagnet'] . '</b><br/>'; ?>

                                            </div>

                                            <?php if ($_SESSION['compte'] == 1) : ?>

                                                <div>

                                                    <?php if ($pagnet['idCompte'] != 0 && $pagnet['idCompte'] > 0) :

                                                        $sqlGetComptePay2 = "SELECT * FROM `" . $nomtableCompte . "` where idCompte = " . $pagnet['idCompte'];

                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die("persoonel requête 2" . mysql_error());

                                                        $cpt = mysql_fetch_array($resPay2);

                                                    ?>

                                                        <?php echo 'Compte :' . $cpt['nomCompte'] . '<br/>'; ?>

                                                    <?php endif; ?>

                                                </div>

                                            <?php endif; ?>

                                            <div>

                                                <?php if ($pagnet['versement'] != 0) : ?>

                                                    <?php echo 'Espèces : ' . $pagnet['versement'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['restourne'] != 0 && $pagnet['restourne'] > 0) : ?>

                                                    <?php echo 'Rendu : ' . $pagnet['restourne'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                        <!--*******************************Fin Total Facture****************************************-->

                                    </div>

                                </div>

                            </div>

                        <?php

                        } else if (($ligne['classe'] == 0 || $ligne['classe'] == 1) && $pagnet['type'] == 10) { ?>

                            <div class="panel panel-warning">

                                <div class="panel-heading">

                                    <?php if ($pagnet['terminerProforma'] == 1) { ?>

                                        <span id="intro" style="margin-top: -20px;margin-right: -15px;background: green;color: white;" class="badge bg-success glyphicon glyphicon-ok intro"> </span>

                                    <?php } ?>

                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet" . $pagnet['idPagnet'] . ""; ?> class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Panier <?php echo $n; ?>

                                            <span class="spanDate noImpr"> </span>

                                            <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

                                            <span class="spanDate noImpr">Heure: <?php echo $pagnet['heurePagnet']; ?></span>

                                            <?php $sqlT = "SELECT totalp FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resT = mysql_query($sqlT) or die("select stock impossible =>" . mysql_error());

                                            $TotalT = mysql_fetch_array($resT);

                                            $sqlP = "SELECT apayerPagnet FROM `" . $nomtablePagnet . "` where idPagnet=" . $pagnet['idPagnet'] . " ";

                                            $resP = mysql_query($sqlP) or die("select stock impossible =>" . mysql_error());

                                            $TotalP = mysql_fetch_array($resP);



                                            $sql = "SELECT * from `aaa-utilisateur` where idutilisateur='" . $pagnet['iduser'] . "' ";

                                            $res = mysql_query($sql);

                                            $user = mysql_fetch_array($res);



                                            ?>

                                            <?php if ($_SESSION['Pays'] == 'Canada') {   ?>

                                                <span class="spanTotal noImpr">Sous-Total: <span><?php echo $TotalP[0]; ?>
                                                    </span></span>

                                                <span class="spanTotal noImpr">Total :
                                                    <span><?php echo ($TotalP[0] + (($TotalP[0] * 5) / 100) + (($TotalP[0] * 9.975) / 100)); ?>
                                                    </span></span>

                                            <?php } else {   ?>

                                                <span class="spanTotal noImpr">Total panier: <span><?php echo $TotalT[0]; ?>
                                                    </span></span>

                                                <span class="spanTotal noImpr">Total à payer: <span><?php echo $TotalP[0]; ?>
                                                    </span></span>

                                            <?php } ?>

                                            <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

                                            <span class="spanDate noImpr">
                                                <?php echo substr(strtoupper($user['prenom']), 0, 3); ?></span>

                                        </a>

                                    </h4>

                                </div>

                                <div <?php echo  "id=pagnet" . $pagnet['idPagnet'] . ""; ?> <?php

                                                                                            if ($idmax == $pagnet['idPagnet']) {

                                                                                            ?> class="panel-collapse collapse in" <?php

                                                                                                                                } else {

                                                                                                                                    ?> class="panel-collapse collapse " <?php

                                                                                                                                                                    }

                                                                                                                                                                        ?>>

                                    <div class="panel-body">

                                        <!--*******************************Debut Facture****************************************-->

                                        <!-- Proforma  -->
                                        <?php if ($pagnet['validerProforma'] == 0) { ?>
                                            <button type="button" class="btn btn-primary pull-left" style="margin-left:20px;" data-toggle="modal" data-target="#msg_validerProforma_pagnet<?= $pagnet['idPagnet']; ?>">

                                                Valider la facture

                                            </button>
                                        <?php } ?>

                                        <div class="modal fade" id="msg_validerProforma_pagnet<?= $pagnet['idPagnet']; ?>" role="dialog">

                                            <div class="modal-dialog">

                                                <!-- Modal content-->

                                                <div class="modal-content">

                                                    <div class="modal-header panel-primary">

                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                        <h4 class="modal-title">Confirmation</h4>

                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Êtes-vous sûr de vouloir valider cette facture ?</p>

                                                    </div>

                                                    <div class="modal-footer">

                                                        <button type="button" class="btn btn-default" data-dismiss="modal"> Non
                                                        </button>

                                                        <button type="button" name="btnFacture" class="btn btn-success btn_disabled_after_click btnValiderProforma" onclick="validerProforma(<?= $pagnet['idPagnet']; ?>)"> Oui </button>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!-- Fin Proforma  -->



                                        <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_factEntreprise_pagnet" . $pagnet['idPagnet']; ?>>

                                            Facture Entreprise

                                        </button>



                                        <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_factClient_pagnet" . $pagnet['idPagnet']; ?>>

                                            Facture Client

                                        </button>




                                        <div class="modal fade" <?php echo  "id=msg_factClient_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

                                            <div class="modal-dialog">

                                                <!-- Modal content-->

                                                <div class="modal-content">

                                                    <div class="modal-header panel-primary">

                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                        <h4 class="modal-title">Informations Client</h4>

                                                    </div>

                                                    <form class="form-inline noImpr" method="post" action="pdfFactureProforma.php" target="_blank">

                                                        <div class="modal-body">

                                                            <div class="row">

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Prenom(s) Client</label>

                                                                    <input type="text" class="form-control" name="prenom">

                                                                </div>

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Nom Client</label>

                                                                    <input type="text" class="form-control" name="nom">

                                                                </div>

                                                            </div>

                                                            <div class="row">

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Adresse Client</label>

                                                                    <input type="text" class="form-control" name="adresse">

                                                                </div>

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Telephone Client</label>

                                                                    <input type="text" class="form-control" name="telephone">

                                                                </div>

                                                            </div>

                                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value='" . $pagnet['idPagnet'] . "'"; ?>>

                                                        </div>

                                                        <div class="modal-footer">

                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                            <button type="submit" name="btnFacture" class="btn btn-success">Confirmer</button>

                                                        </div>

                                                    </form>

                                                </div>

                                            </div>

                                        </div>



                                        <div class="modal fade" <?php echo  "id=msg_factEntreprise_pagnet" . $pagnet['idPagnet']; ?> role="dialog">

                                            <div class="modal-dialog">

                                                <!-- Modal content-->

                                                <div class="modal-content">

                                                    <div class="modal-header panel-primary">

                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                        <h4 class="modal-title">Informations Entreprise</h4>

                                                    </div>

                                                    <form class=" noImpr" method="post" action="pdfFactureProformaEntreprise.php" target="_blank">

                                                        <div class="modal-body">

                                                            <div class="form-group">

                                                                <label for="inputEmail3" class="control-label">Nom Entreprise
                                                                </label>

                                                                <input type="text" class="form-control" name="entreprise" required="" placeholder="Le nom de l'Entreprise ici...">

                                                            </div>

                                                            <div class="row">

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Adresse Client</label>

                                                                    <input type="text" class="form-control" name="adresse">

                                                                </div>

                                                                <div class="col-xs-6">

                                                                    <label for="reference">Telephone Client</label>

                                                                    <input type="text" class="form-control" name="telephone">

                                                                </div>

                                                            </div>

                                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value='" . $pagnet['idPagnet'] . "'"; ?>>

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



                                        <?php if ($_SESSION['caissier'] == 1) { ?>

                                            <button class="btn btn-warning  pull-left" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'recu' . $pagnet['idPagnet']; ?>').submit();">

                                                Reçu

                                            </button>


                                            <form class="form-inline pull-right noImpr" <?php echo  "id=recu" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="recuCaisse.php">

                                                <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                            </form>

                                            <button class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket' . $pagnet['idPagnet']; ?>').submit();">

                                                Ticket de Caisse

                                            </button>



                                        <?php } ?>



                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket" . $pagnet['idPagnet']; ?> target="_blank" style="margin-right:20px;" method="post" action="barcodeFacture.php">

                                            <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                        </form>



                                        <table class="table ">

                                            <thead class="noImpr">

                                                <tr>

                                                    <th>Référence</th>

                                                    <th>Quantité</th>

                                                    <th>Unité vente</th>

                                                    <th>Prix Unité vente</th>

                                                    <th>Depot</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php

                                                $sql8 = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $pagnet['idPagnet'] . " ORDER BY numligne DESC";

                                                $res8 = mysql_query($sql8) or die("persoonel requête 2" . mysql_error());

                                                while ($ligne = mysql_fetch_assoc($res8)) { ?>

                                                    <tr>

                                                        <td class="designation"><?php echo $ligne['designation']; ?></td>

                                                        <td>

                                                            <?php if ($ligne['unitevente'] == 'Transaction') { ?>

                                                                <?php if ($ligne['quantite'] == 1) : ?>

                                                                    <?php echo  'Depot'; ?> <span class="factureFois"></span>

                                                                <?php endif; ?>

                                                                <?php if ($ligne['quantite'] == 0) : ?>

                                                                    <?php echo  'Retrait'; ?> <span class="factureFois"></span>

                                                                <?php endif; ?>

                                                            <?php } else { ?>

                                                                <?php echo  $ligne['quantite']; ?> <span class="factureFois"></span>

                                                            <?php } ?>

                                                        </td>

                                                        <td class="unitevente ">

                                                            <?php echo $ligne['unitevente']; ?>

                                                        </td>

                                                        <td>

                                                            <?php echo  $ligne['prixunitevente']; ?> <span class="factureFois"></span>

                                                        </td>

                                                        <td>

                                                            <?php

                                                            $reqEp = "SELECT * from  `" . $nomtableEntrepot . "` e

                                                                                where idEntrepot='" . $ligne['idEntrepot'] . "' ";

                                                            $resEp = mysql_query($reqEp) or die("select stock impossible =>" . mysql_error());

                                                            $entrepot = mysql_fetch_assoc($resEp);

                                                            echo $entrepot['nomEntrepot'];

                                                            ?>

                                                        </td>

                                                        <td>

                                                            <button type="submit" disabled="true" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne" . $ligne['numligne']; ?>>

                                                                <span class="glyphicon glyphicon-remove"></span>Retour

                                                            </button>



                                                            <div class="modal fade" <?php echo  "id=msg_rtrnApres_ligne" . $ligne['numligne']; ?> role="dialog">

                                                                <div class="modal-dialog">

                                                                    <!-- Modal content-->

                                                                    <div class="modal-content">

                                                                        <div class="modal-header panel-primary">

                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                            <h4 class="modal-title">Confirmation</h4>

                                                                        </div>

                                                                        <form class="form-inline noImpr" id="factForm" method="post">

                                                                            <div class="modal-body">

                                                                                <p><?php echo  "Voulez-vous retourner cette ligne du panier numéro <b>" . $pagnet['idPagnet'] . "</b>"; ?>
                                                                                </p>

                                                                                <input type="hidden" name="idPagnet" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?>>

                                                                                <input type="hidden" name="designation" <?php echo  "value=" . $ligne['designation'] . ""; ?>>

                                                                                <input type="hidden" name="numligne" <?php echo  "value=" . $ligne['numligne'] . ""; ?>>

                                                                                <input type="hidden" name="idStock" <?php echo  "value=" . $ligne['idStock'] . ""; ?>>

                                                                                <input type="hidden" name="unitevente" <?php echo  "value=" . $ligne['unitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixunitevente" <?php echo  "value=" . $ligne['prixunitevente'] . ""; ?>>

                                                                                <input type="hidden" name="prixtotal" <?php echo  "value=" . $ligne['prixtotal'] . ""; ?>>

                                                                                <input type="hidden" name="totalp" <?php echo  "value=" . $pagnet['totalp'] . ""; ?>>

                                                                                <div class="row">

                                                                                    <div class="col-xs-6">

                                                                                        <label for="reference">Ancienne quantite
                                                                                        </label>

                                                                                        <input type="text" disabled="true" class="form-control" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

                                                                                    </div>

                                                                                    <div class="col-xs-6">

                                                                                        <label for="reference">Nouvelle quantite</label>

                                                                                        <input type="text" class="form-control" name="quantite" <?php echo  "value=" . $ligne['quantite'] . ""; ?>>

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

                                                <?php echo  'TOTAL : ' . $pagnet['totalp'] . '<br/>'; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['remise'] != 0 && $pagnet['remise'] > 0) : ?>

                                                    <?php echo 'Remise :' . $pagnet['remise'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php echo  '<b>Net à payer : ' . $pagnet['apayerPagnet'] . '</b><br/>'; ?>

                                            </div>

                                            <?php if ($_SESSION['compte'] == 1) : ?>

                                                <div>

                                                    <?php if ($pagnet['idCompte'] != 0 && $pagnet['idCompte'] > 0) :

                                                        $sqlGetComptePay2 = "SELECT * FROM `" . $nomtableCompte . "` where idCompte = " . $pagnet['idCompte'];

                                                        $resPay2 = mysql_query($sqlGetComptePay2) or die("persoonel requête 2" . mysql_error());

                                                        $cpt = mysql_fetch_array($resPay2);

                                                    ?>

                                                        <?php echo 'Compte :' . $cpt['nomCompte'] . '<br/>'; ?>

                                                    <?php endif; ?>

                                                </div>

                                            <?php endif; ?>

                                            <div>

                                                <?php if ($pagnet['versement'] != 0) : ?>

                                                    <?php echo 'Espèces : ' . $pagnet['versement'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                            <div>

                                                <?php if ($pagnet['restourne'] != 0 && $pagnet['restourne'] > 0) : ?>

                                                    <?php echo 'Rendu : ' . $pagnet['restourne'] . '<br/>'; ?>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                        <!--*******************************Fin Total Facture****************************************-->

                                    </div>

                                </div>

                            </div>

                        <?php

                        }

                        ?>

                        <div class="modal fade" <?php echo  "id=imageNvPanier" . $pagnet['idPagnet']; ?> role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header" style="padding:35px 50px;">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Panier :
                                            <b>#<?php echo  $pagnet['idPagnet']; ?></b>
                                        </h4>
                                    </div>
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="modal-body" style="padding:40px 50px;">
                                            <input type="text" style="display:none" name="idPanier" id="idPanier_Upd_Nv" <?php echo  "value=" . $pagnet['idPagnet'] . ""; ?> />
                                            <div class="form-group" style="text-align:center;">
                                                <?php
                                                if ($pagnet['image'] != null && $pagnet['image'] != ' ') {
                                                    $format = substr($pagnet['image'], -3);
                                                ?>
                                                    <input type="file" name="file" value="<?php echo  $pagnet['image']; ?>" accept="image/*" id="input_file_Panier<?php echo  $pagnet['idPagnet']; ?>" onchange="showPreviewPanier(event,<?php echo  $pagnet['idPagnet']; ?>);" /><br />
                                                    <?php if ($format == 'pdf') { ?>
                                                        <iframe id="output_pdf_Panier<?php echo  $pagnet['idPagnet']; ?>" src="./PiecesJointes/<?php echo  $pagnet['image']; ?>" width="100%" height="500px"></iframe>
                                                        <img style="display:none;" width="500px" height="500px" id="output_image_Panier<?php echo  $pagnet['idPagnet'];  ?>" />
                                                    <?php } else { ?>
                                                        <img src="./PiecesJointes/<?php echo  $pagnet['image']; ?>" width="500px" height="500px" id="output_image_Panier<?php echo  $pagnet['idPagnet']; ?>" />
                                                        <iframe id="output_pdf_Panier<?php echo  $pagnet['idPagnet'];  ?>" style="display:none;" width="100%" height="500px"></iframe>
                                                    <?php } ?>
                                                <?php
                                                } else { ?>
                                                    <input type="file" name="file" accept="image/*" id="input_file_Panier<?php echo  $pagnet['idPagnet']; ?>" id="cover_image" onchange="showPreviewPanier(event,<?php echo  $pagnet['idPagnet']; ?>);" /><br />
                                                    <img style="display:none;" width="500px" height="500px" id="output_image_Panier<?php echo  $pagnet['idPagnet']; ?>" />
                                                    <iframe id="output_pdf_Panier<?php echo  $pagnet['idPagnet'];  ?>" style="display:none;" width="100%" height="500px"></iframe>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgPanier" id="btn_upload_Panier<?php echo  $pagnet['idPagnet']; ?>">
                                                <span class="glyphicon glyphicon-upload"></span> Enregistrer
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    <?php $n = $n - 1;
                    } ?>

                    <?php if ($nbPaniers >= 11) { ?>

                        <ul class="pagination pull-right">

                            <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->

                            <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">

                                <a href="insertionLigneLight.php?page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>

                            </li>

                            <?php for ($page = 1; $page <= $pages; $page++) : ?>

                                <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->

                                <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">

                                    <a href="insertionLigneLight.php?page=<?= $page ?>" class="page-link"><?= $page ?></a>

                                </li>

                            <?php endfor ?>

                            <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->

                            <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">

                                <a href="insertionLigneLight.php?page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>

                            </li>

                        </ul>

                    <?php } ?>

                    <!-- Fin Boucle while concernant les Paniers Vendus  -->

                    <div class="modal fade" id="imageNvCompte" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Compte : <b>#
                                            <span id="idCompte_View"></span></b></h4>
                                </div>
                                <form method="post" enctype="multipart/form-data">
                                    <div class="modal-body" style="padding:0px 150px;">
                                        <div class="form-group" style="text-align:center;">
                                            <img style="display:none;" width="300px" height="400px" id="output_image_Compte" />
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

                <script>
                    function showImageCompte(idCompte, idPanier) {
                        var nom = $('#idImageCompte' + idCompte).text();
                        var montant = $('#somme_Apayer' + idPanier).text();
                        $('#idCompte_View').text(nom);
                        $('#idCompte_Montant').text(montant);
                        var file = $('#idImageCompte' + idCompte).attr("data-image");
                        if (file != null && file != '') {
                            $('#imageNvCompte').modal('show');
                            var format = file.substr(file.length - 3);
                            if (format == 'pdf') {
                                document.getElementById('output_pdf_Compte').style.display = "block";
                                document.getElementById('output_image_Compte').style.display = "none";
                                document.getElementById("output_pdf_Compte").src = "./images/" + file;
                            } else {
                                document.getElementById('output_image_Compte').style.display = "block";
                                document.getElementById('output_pdf_Compte').style.display = "none";
                                document.getElementById("output_image_Compte").src = "./images/" + file;
                            }
                        } else {
                            document.getElementById('output_pdf_Compte').style.display = "none";
                            document.getElementById('output_image_Compte').style.display = "none";
                        }
                    }
                </script>



                <?php /*****************************/

                echo '' .

                    '<script>$(document).ready(function(){$("#AjoutLigne").click(function(){$("#AjoutLigneModal").modal();});});</script></body></html>';

                ?>



                <!-- Debut PopUp d'Alerte sur l'ensemble de la Page -->

                <?php

                if (isset($msg_info)) {

                    echo "<script type='text/javascript'>

                                    $(window).on('load',function(){

                                        $('#msg_info').modal('show');

                                    });

                                </script>";

                    echo '<div id="msg_info" class="modal fade " role="dialog">

                                <div class="modal-dialog">

                                    <!-- Modal content-->

                                    <div class="modal-content">

                                        <div class="modal-header panel-primary">

                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                            <h4 class="modal-title">Alerte</h4>

                                        </div>

                                        <div class="modal-body">

                                            <p>' . $msg_info . '</p>

                                            

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



                <!-- Debut PopUp des Transactions -->

                <?php

                if (isset($msg_transaction)) {

                    echo "<script type='text/javascript'>

                                    $(window).on('load',function(){

                                        $('#msg_info').modal('show');

                                    });

                                </script>";

                    echo '<div id="msg_info" class="modal fade " role="dialog">

                                <div class="modal-dialog">

                                    <!-- Modal content-->

                                    <div class="modal-content">

                                        <div class="modal-header panel-primary">

                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                            <h4 class="modal-title">Transaction</h4>

                                        </div>

                                        <form  method="POST" >

                                        <div class="modal-body">

                                            <div class="row">

                                                <div class="col-xs-6">

                                                    <img src="images/' . $image . '.png"  width="200" height="200" border="0" class="img-circle">

                                                    <input type="hidden" name="aliasTrans" value=' . $trans_alias . ' >

                                                    <input type="hidden" name="pagnetTrans" value=' . $trans_pagnet . ' >

                                                </div>

                                                <div class="col-xs-6">

                                                    <div class="well">

                                                            <div class="form-group">

                                                                <label for="operation" class="control-label">Operation</label>

                                                                <select name="typeTrans" id="typeTrans" class="form-control" required="">

                                                                    <option value="">--------</option>

                                                                    <option value="1">Depot</option>

                                                                    <option value="0">Retrait</option>

                                                                    <option value="2">Facture</option>

                                                                </select>

                                                                <span class="help-block"></span>

                                                            </div>

                                                            <div class="form-group" id="listFacture" style="display:none;">

                                                                <label for="operation" class="control-label">Facture</label>

                                                                <select  id="nomFact" name="idFact" class="form-control" >

                                                                    <option value="">--------</option>

                                                                     ';

                    $reqT = "select * from `" . $nomtableDesignation . "` where uniteStock='Facture' and seuil=1 ";

                    $resT = mysql_query($reqT) or die("select stock impossible =>" . mysql_error());

                    while ($facture = mysql_fetch_assoc($resT)) {

                        echo '  <option value="' . $facture["description"] . '">' . $facture["designation"] . '</option>';
                    }

                    echo '    

                                                                </select>

                                                                <span class="help-block"></span>

                                                            </div>

                                                            <div class="form-group">

                                                                <label for="montant" class="control-label">Montant</label>

                                                                <input type="text" class="form-control" id="montant" name="montantTrans" value="" required="" title="Please enter your password">

                                                                <span class="help-block"></span>

                                                            </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="modal-footer">

                                            <button type="submit" name="btnEnregistrerTransaction"

                                             class="btn btn-primary btnxs ">Valider</button>

                                        </div>

                                        </form>

                                    </div>

                                </div>

                            </div>';
                }

                ?>

                <!-- Fin PopUp des Transactions -->



                <!-- Debut PopUp des Credits -->

                <?php

                if (isset($msg_credit)) {

                    echo "<script type='text/javascript'>

                                    $(window).on('load',function(){

                                        $('#msg_info').modal('show');

                                    });

                                </script>";

                    echo '<div id="msg_info" class="modal fade " role="dialog">

                                <div class="modal-dialog">

                                    <!-- Modal content-->

                                    <div class="modal-content">

                                        <div class="modal-header panel-primary">

                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                            <h4 class="modal-title">Vente de credit</h4>

                                        </div>

                                        <form  method="POST" >

                                        <div class="modal-body">

                                            <div class="row">

                                                <div class="col-xs-6">

                                                    <img src="images/' . $image . '.png"  width="200" height="200" border="0" class="img-circle">

                                                    <input type="hidden" name="aliasTrans" value=' . $trans_alias . ' >

                                                    <input type="hidden" name="pagnetTrans" value=' . $trans_pagnet . ' >

                                                    <input type="hidden" name="typeTrans" value="3" >

                                                </div>

                                                <div class="col-xs-6">

                                                    <div class="well">

                                                        <div class="form-group">

                                                            <label for="operation" class="control-label">Service</label>

                                                            <select  id="nomFact" name="idFact" class="form-control" required="">

                                                                <option value="">--------</option>

                                                                <option value="0">Especes</option>

                                                                    ';

                    $reqT = "select * from `" . $nomtableDesignation . "` where uniteStock='Transaction' and seuil=1 ";

                    $resT = mysql_query($reqT) or die("select stock impossible =>" . mysql_error());

                    while ($facture = mysql_fetch_assoc($resT)) {

                        echo '  <option value="' . $facture["description"] . '">' . $facture["designation"] . '</option>';
                    }

                    echo '    

                                                            </select>

                                                            <span class="help-block"></span>

                                                        </div>

                                                        <div class="form-group">

                                                            <label for="montant" class="control-label">Montant</label>

                                                            <input type="text" class="form-control" id="montant" name="montantTrans" value="" required="" title="Please enter your password">

                                                            <span class="help-block"></span>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="modal-footer">

                                            <button type="submit" name="btnEnregistrerTransaction"

                                             class="btn btn-primary btnxs ">Valider</button>

                                        </div>

                                        </form>

                                    </div>

                                </div>

                            </div>';
                }

                ?>

                <!-- Fin PopUp des Credits -->



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

                                </p>

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->



                <!-- Debut Message d'Alerte concernant le Stock du Produit avant la vente -->

                <div id="msg_info_jsET" class="modal fade " role="dialog">

                    <div class="modal-dialog">

                        <!-- Modal content-->

                        <div class="modal-content">

                            <div class="modal-header panel-primary">

                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                <h4 class="modal-title">Alerte</h4>

                            </div>

                            <div class="modal-body">

                                <p>IMPOSSIBLE.</br>

                                    </br> La quantité de ce stock dans cet entrepot est insuffisant.

                                    </br> Il vous reste <span id="qte_stockET"></span> Unités dans le Stock.

                                </p>

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->

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



                <!-- Debut PopUp d'Alerte sur le retard de Paiement -->

                <?php

                if (isset($msg_paiement)) {

                    echo "<script type='text/javascript'>

                                    $(window).on('load',function(){

                                        $('#msg_info').modal('show');

                                    });

                                </script>";

                    echo '<div id="msg_info" class="modal fade " role="dialog">

                                <div class="modal-dialog">

                                    <!-- Modal content-->

                                    <div class="modal-content">

                                        <div class="modal-header panel-primary">

                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                            <h4 class="modal-title">Alerte retard de paiement</h4>

                                        </div>

                                        <div class="modal-body">

                                            <p>' . $msg_paiement . '</p>

                                            

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

        <?php

        } else {
            # code... 
        ?>

            <div class="container" id="entrepot-venteContent">

                <img src="images/loading-gif3.gif" style="margin-left:40%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">

            </div>
        <?php } ?>
        <!-- Fin Container HTML -->


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

                        </p>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

                    </div>

                </div>

            </div>

        </div>

        <!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->



        <!-- Debut Message d'Alerte concernant le Stock du Produit avant la vente -->

        <div id="msg_info_jsET" class="modal fade " role="dialog">

            <div class="modal-dialog">

                <!-- Modal content-->

                <div class="modal-content">

                    <div class="modal-header panel-primary">

                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                        <h4 class="modal-title">Alerte</h4>

                    </div>

                    <div class="modal-body">

                        <p>IMPOSSIBLE.</br>

                            </br> La quantité de ce stock dans cet entrepot est insuffisant.

                            </br> Il vous reste <span id="qte_stockET"></span> Unités dans le Stock.

                        </p>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

                    </div>

                </div>

            </div>

        </div>

        <!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->

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
        <!-- Debut Message d'Alerte concernant client required -->

        <div id="msg_info_ClientRequired" class="modal fade " role="dialog">
            <div class="modal-dialog">



                <!-- Modal content-->



                <div class="modal-content">



                    <div class="modal-header panel-primary">



                        <button type="button" class="close" data-dismiss="modal">&times;</button>



                        <h4 class="modal-title">Alerte</h4>



                    </div>



                    <div class="modal-body">



                        <p> ATTENTION!</br>

                            Vous avez choisi le compte Bon,

                            le nom du client est obligatoire.



                        </p>



                    </div>



                    <div class="modal-footer">



                        <button type="button" id="closeAvance" class="btn btn-primary" data-dismiss="modal">Close</button>



                    </div>



                </div>



            </div>



        </div>
        <!-- Fin Message d'Alerte concernant client required -->

        <div id="msg_info_1" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Alerte</h4>
                    </div>
                    <div class="modal-body" id="msg_info_body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="closeInfo" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>



        <div id="add_infoSup_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Informations supplémentaires</h4>
                    </div>
                    <input type="hidden" name="idLigneInfo" id="idLigneInfo">
                    <div class="modal-body" id="add_infoSup_body">
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="numeroChassis">Numéro châsis</label>
                            <div class="">
                                <input type="text" name="numeroChassis" id="numeroChassis" class="form-control" placeholder="Numéro châsis">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="numeroMoteur">Numéro moteur</label>
                            <div class="">
                                <input type="text" name="numeroMoteur" id="numeroMoteur" class="form-control" placeholder="Numéro moteur">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="couleur">Couleur</label>
                            <div class="">
                                <input type="text" name="couleur" id="couleur" class="form-control" placeholder="Couleur">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <!-- </div>
                <div class="modal-footer"> -->
                        <button type="button" id="closeInfo" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" name="btnAddInfoSup" class="btn btn-success btnAddInfoSup">Ajouter</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="selectNbMoisModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

            <div class="modal-dialog" role="document">

                <div class="modal-content">

                    <div class="modal-header">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Formulaire de paiement</h4>

                    </div>

                    <div class="modal-body">
                        <div name="deleteContainer">
                            <input type="hidden" id="idPsModalSelectNbMois">
                            <input type="hidden" id="montantFormuleInput">
                            <div class="form-group" align="center">
                                <h3>
                                    <span class="">Choisir le nombre de mois à payer</span>
                                </h3>
                                <Select id="selectNbMois" onChange="changeFormule()" class="form-control" style="background-color:gold;width: 100px;">
                                    <option value="1">1</option>
                                    <option value="3">3</option>
                                    <option value="6">6</option>
                                    <option value="12">12</option>
                                </Select>
                            </div><br><br><br>
                            <div class="form-group alert alert-info" id="divFormule" style="width: 250px;">
                                Montant à payer : <span id="montantFormule"></span> FCFA
                            </div>
                            <div class="modal-footer">

                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                                <button type="button" name="btnSelectNbMois" onClick="effectue_paiementWave()" class="btn btn-primary">Valider</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="paiementWave" class="modal fade " role="dialog" align="center">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Paiement Wave </b>
                        </h4>
                    </div>
                    <div class="modal-body" id="bodyPayWave">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>


        <div id="paiementModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Paiement Orange Money</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" class="" align="center">
                            <div class="row">
                                <div class="col-md-6">
                                    <br /><br /><img alt="" style="width: 90%;height: 70%;" src="images/orange-money2.jpeg" id="imgsrc_Upd" />
                                </div>
                                <div class="col-md-6">
                                    <br /><br /><img alt="imgQRCode 8888" id="imgQRCode" />
                                </div>
                            </div>
                            <div class="modal-footer row">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>




    </header>

</body>



<!-- Fin Code HTML -->