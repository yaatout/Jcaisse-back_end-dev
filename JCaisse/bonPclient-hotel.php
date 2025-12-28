<?php


$sql="SELECT * from `aaa-utilisateur` where idutilisateur='".$_SESSION['iduser']."' ";

$resU=mysql_query($sql);

$userAcces=mysql_fetch_array($resU);

// var_dump($userAcces['idEntrepot']);

function find_p_with_position($pns,$des) {
    foreach($pns as $index => $p) {
        if(($p['idDesignation'] == $des)){
            return $index;
        }
    } 
    return FALSE;
  }

  if (isset($_POST['btnEnregistrerAvoir'])) {
    
    $avoir=-1*htmlspecialchars(trim($_POST['montantAvoir']));
    $motifAvoir=htmlspecialchars(trim($_POST['motifAvoir']));
    $dateAvoir=htmlspecialchars(trim($_POST['dateAvoir']));
    // var_dump($avoir);

    $sql="SELECT * FROM `".$nomtableDesignation."` where classe=6 ";

    $res=mysql_query($sql) or die ("select stock impossible =>".mysql_error());

    $design = mysql_fetch_assoc($res);

        
    $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,verrouiller,idClient) values('".$dateAvoir."','".$heureString."',".$_SESSION['iduser'].",0,".$avoir.",".$avoir.",1,'".$idClient."')";

    $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());
    
    $sqlP="SELECT * FROM `".$nomtablePagnet."` Order By idPagnet DESC LIMIT 1 ";

    $resP=mysql_query($sqlP) or die ("select pagnet impossible =>".mysql_error());

    $pagnet = mysql_fetch_assoc($resP);
    
    if ($res6) {
        
        $sql7="insert into `".$nomtableLigne."` (designation, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe) values ('Avoir - ".$motifAvoir."','".$design['idDesignation']."','".$design['uniteStock']."',".$avoir.",1,".$avoir.",".$pagnet['idPagnet'].",6)";

        $res7=mysql_query($sql7) or die ("insertion ligne impossible 7  =>".mysql_error() ); 
    }

}
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

        

            $query = mysql_query("SELECT * FROM `".$nomtablePagnet."` where iduser='".$_SESSION['iduser']."' && idClient='".$idClient."'  && verrouiller=0");

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

    

        $query = mysql_query("SELECT * FROM `".$nomtablePagnet."` where iduser='".$_SESSION['iduser']."' && idClient='".$idClient."'  && verrouiller=0");

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


/**Debut Button Actualiser Pagnet**/

    if (isset($_POST['btnActualiserBon'])) {

        $dateActualiser=htmlspecialchars(trim($_POST['dateActualiser']));

        $tab=explode("-",$dateActualiser);

        $dateBon=$tab[2].'-'.$tab[1].'-'.$tab[0];

        //$dateBon=$dateActualiser;



        $sql1="select * from `".$nomtableJournal."` where annee=".$annee." and mois=".$mois;

        $res1=mysql_query($sql1);



        $query = mysql_query("SELECT * FROM `".$nomtablePagnet."` where iduser='".$_SESSION['iduser']."' && idClient='".$idClient."'  && verrouiller=0");

        $nbre_fois=mysql_num_rows($query);



        if($client['activer'] == 1){

            if(mysql_num_rows($res1)){

                $sql2="select * from `".$nomtablePage."` where datepage='".$dateString."'";

                $res2=mysql_query($sql2);

                if(mysql_num_rows($res2)){

                    if($nbre_fois<1){

                        $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$dateBon."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,'".$idClient."')";

                        $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());

                    }

                }

                else{

                    $sql3="insert into `".$nomtablePage."` (datepage,datetime,iduser,totalVente,totalService,totalVersement,totalBon,totalFrais,totalCaisse) values('".$dateString."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0)";

                    $res3=@mysql_query($sql3) or die ("insertion page journal impossible-1".mysql_error());

        

                    if($nbre_fois<1){

                        $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$dateBon."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,'".$idClient."')";

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

                        $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$dateBon."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,'".$idClient."')";

                        $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error() );

                }

        

            }

        }

        else {

            $msg_info="<b>Ce Client est Desactiver vous ne pouvez pas faire de bon sur lui.</b></br></br>

                        VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STATUT DE CE CLIENT ....";

        }



    }

/**Fin Button Actualiser Pagnet**/



/**Debut Button Ajouter Ligne dans un pagnet**/

    if (isset($_POST['btnEnregistrerCodeBarre'])) {



        $resultat=" =ko"+htmlspecialchars($_POST['codeBarre']);



            if (isset($_POST['codeBarre']) && isset($_POST['idPagnet'])) {

                $codeBarre=htmlspecialchars(trim($_POST['codeBarre']));

                $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));

                $codeBrute=explode('-', $codeBarre);



                $sql="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";

                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                $pagnet = mysql_fetch_assoc($res) ;



                if(!empty($codeBrute[1])  && is_int($codeBrute[0])){



                }

                /**Debut de la Vente des Produits sur la Designation */

                else{

                    $sql="SELECT * FROM `".$nomtableDesignation."` where (codeBarreDesignation='".$codeBarre."' or codeBarreuniteStock='".$codeBarre."' or designation='".$codeBarre."') ";

                    $res=mysql_query($sql) or die ("select stock impossible =>".mysql_error());

                    if(mysql_num_rows($res)){

                        $design = mysql_fetch_assoc($res);

                        if($design['codeBarreDesignation']==$codeBarre){

                            if($pagnet['type']==0){

                                $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$design['idDesignation']."' ";

                                    $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                                    $t_stock = mysql_fetch_array($res_t) ;

                                    $restant = $t_stock[0];

                                    if($restant>0){

                                        /***Debut verifier si la ligne du produit existe deja ***/

                                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=0 ";

                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                                        $ligne = mysql_fetch_assoc($res);

                                        /***Debut verifier si la ligne du produit existe deja ***/

                                        if($ligne != null){

                                            $reqP="SELECT * from  `".$nomtableEntrepotStock."` 

                                            where idDesignation='".$design['idDesignation']."' AND quantiteStockCourant>0 AND idEntrepot!='".$ligne['idEntrepot']."' ";

                                            $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                            $entrepot = mysql_fetch_assoc($resP);  

                                            if(mysql_num_rows($resP)){

                                                /***Debut verifier si la ligne du produit existe deja ***/

                                                $sql1="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' AND idEntrepot='".$entrepot['idEntrepot']."'  and classe=0 ";

                                                $res1 = mysql_query($sql1) or die ("persoonel requête 2".mysql_error());

                                                $ligne1 = mysql_fetch_assoc($res1);

                                                /***Debut verifier si la ligne du produit existe deja ***/

                                                if($ligne1 != null){

                                                    

                                                }

                                                else{

                                                    $reqP1="SELECT * from  `".$nomtableEntrepotStock."` 

                                                    where idDesignation='".$design['idDesignation']."' AND quantiteStockCourant>0 AND idEntrepot!='".$ligne['idEntrepot']."' AND idEntrepot!='".$ligne1['idEntrepot']."'  ";

                                                    $resP1=mysql_query($reqP1) or die ("select stock impossible =>".mysql_error());

                                                    $produit = mysql_fetch_assoc($resP1);

                                                        if(mysql_num_rows($resP1)){

                                                            if($ligne['classe']==0 || $ligne['classe']==1){

                                                                $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','".$produit['idEntrepot']."','".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";

                                                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 

                                                            }

                                                        }

                                                        else{

                                                            

                                                        }

                                                }

                                            }

                                            else{

                                                

                                            }

                                        }

                                        else{

                                            $sqlN="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";

                                            $resN = mysql_query($sqlN) or die ("persoonel requête 2".mysql_error());

                                            $ligneN = mysql_fetch_assoc($resN) ;

                                            if($_SESSION['entrepot']!=0 || $_SESSION['entrepot']!=null){

                                                $reqP="SELECT * from  `".$nomtableEntrepotStock."` 

                                                where idDesignation='".$design['idDesignation']."' AND quantiteStockCourant>0  AND idEntrepot='".$_SESSION['entrepot']."' ";

                                                $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                                if(mysql_num_rows($resP)){

                                                    $entrepot = mysql_fetch_assoc($resP);

                                                    if($ligneN['classe']==0 || $ligneN['classe']==1){

                                                        $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','".$entrepot['idEntrepot']."','".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";

                                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 

                                                    }

                                                }

                                                else{

                                                    $reqP1="SELECT * from  `".$nomtableEntrepotStock."` 

                                                    where idDesignation='".$design['idDesignation']."' AND quantiteStockCourant>0 ORDER BY quantiteStockCourant DESC LIMIT 0,1 ";

                                                    $resP1=mysql_query($reqP1) or die ("select stock impossible =>".mysql_error());

                                                    $depot = mysql_fetch_assoc($resP1);

                                                    $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','".$depot['idEntrepot']."','".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";

                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                                                }

                                            }

                                            else{

                                                $reqP="SELECT * from  `".$nomtableEntrepotStock."` 

                                                where idDesignation='".$design['idDesignation']."' AND quantiteStockCourant>0 ORDER BY quantiteStockCourant DESC LIMIT 0,1 ";

                                                $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                                $entrepot = mysql_fetch_assoc($resP);

                                                if(mysql_num_rows($res)){

                                                    if($ligneN['classe']==0 || $ligneN['classe']==1){

                                                        $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','".$entrepot['idEntrepot']."','".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";

                                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 

                                                    }

                                                }

                                                else{

                                                    $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','".$entrepot['idEntrepot']."','".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";

                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                                                }

                                            }

                                        }

                                    }

                                    else{

                                        $msg_info="<b>LE CODE-BARRE SAISIE CORRESPOND A UN STOCK FINI (TERMINER).</b></br></br>

                                        VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ....";

                                    }

                            }

                            else{

                                /***Debut verifier si la ligne du produit existe deja ***/

                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";

                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                                $ligne1 = mysql_fetch_assoc($res);

                                /***Debut verifier si la ligne du produit existe deja ***/

                                if($ligne1 != null){

                                    $quantite = $ligne1['quantite'] + 1;

                                    $prixTotal=$quantite*$ligne1['prixunitevente'];

                                    $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";

                                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                }

                                else {

                                    $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";

                                    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                                    $ligne = mysql_fetch_assoc($res) ;

                                    if(mysql_num_rows($res)){

                                        if($ligne['classe']==0){

                                            $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";

                                            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 

                                        }

                                    }

                                    else{

                                        $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";

                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                                    }



                                }

                            }

                        }

                        if($design['designation']==$codeBarre){

                            if($design['classe']==0){

                                if($pagnet['type']==0){

                                    $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$design['idDesignation']."' ";

                                    $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                                    $t_stock = mysql_fetch_array($res_t) ;

                                    $restant = $t_stock[0];

                                    if($restant>0){

                                        /***Debut verifier si la ligne du produit existe deja ***/

                                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=0 ";

                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                                        $ligne = mysql_fetch_assoc($res);

                                        /***Debut verifier si la ligne du produit existe deja ***/

                                        if($ligne != null){

                                            $reqP="SELECT * from  `".$nomtableEntrepotStock."` 

                                            where idDesignation='".$design['idDesignation']."' AND quantiteStockCourant>0 AND idEntrepot!='".$ligne['idEntrepot']."' ";

                                            $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                            $entrepot = mysql_fetch_assoc($resP);  

                                            if(mysql_num_rows($resP)){

                                                /***Debut verifier si la ligne du produit existe deja ***/

                                                $sql1="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' AND idEntrepot='".$entrepot['idEntrepot']."'  and classe=0 ";

                                                $res1 = mysql_query($sql1) or die ("persoonel requête 2".mysql_error());

                                                $ligne1 = mysql_fetch_assoc($res1);

                                                /***Debut verifier si la ligne du produit existe deja ***/

                                                if($ligne1 != null){

                                                    

                                                }

                                                else{

                                                    $reqP1="SELECT * from  `".$nomtableEntrepotStock."` 

                                                    where idDesignation='".$design['idDesignation']."' AND quantiteStockCourant>0 AND idEntrepot!='".$ligne['idEntrepot']."' AND idEntrepot!='".$ligne1['idEntrepot']."'  ";

                                                    $resP1=mysql_query($reqP1) or die ("select stock impossible =>".mysql_error());

                                                    $produit = mysql_fetch_assoc($resP1);

                                                        if(mysql_num_rows($resP1)){

                                                            if($ligne['classe']==0 || $ligne['classe']==1){

                                                                $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','".$produit['idEntrepot']."','".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";

                                                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 

                                                            }

                                                        }

                                                        else{

                                                            

                                                        }

                                                }

                                            }

                                            else{

                                                

                                            }

                                        }

                                        else{

                                            $sqlN="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";

                                            $resN = mysql_query($sqlN) or die ("persoonel requête 2".mysql_error());

                                            $ligneN = mysql_fetch_assoc($resN) ;

                                            if($_SESSION['entrepot']!=0 || $_SESSION['entrepot']!=null){

                                                $reqP="SELECT * from  `".$nomtableEntrepotStock."` 

                                                where idDesignation='".$design['idDesignation']."' AND quantiteStockCourant>0  AND idEntrepot='".$_SESSION['entrepot']."' ";

                                                $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                                if(mysql_num_rows($resP)){

                                                    $entrepot = mysql_fetch_assoc($resP);

                                                    if($ligneN['classe']==0 || $ligneN['classe']==1){

                                                        $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','".$entrepot['idEntrepot']."','".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";

                                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 

                                                    }

                                                }

                                                else{

                                                    $reqP1="SELECT * from  `".$nomtableEntrepotStock."` 

                                                    where idDesignation='".$design['idDesignation']."' AND quantiteStockCourant>0 ORDER BY quantiteStockCourant DESC LIMIT 0,1 ";

                                                    $resP1=mysql_query($reqP1) or die ("select stock impossible =>".mysql_error());

                                                    $depot = mysql_fetch_assoc($resP1);

                                                    $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','".$depot['idEntrepot']."','".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";

                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                                                }

                                            }

                                            else{

                                                $reqP="SELECT * from  `".$nomtableEntrepotStock."` 

                                                where idDesignation='".$design['idDesignation']."' AND quantiteStockCourant>0 ORDER BY quantiteStockCourant DESC LIMIT 0,1 ";

                                                $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                                $entrepot = mysql_fetch_assoc($resP);

                                                if(mysql_num_rows($res)){

                                                    if($ligneN['classe']==0 || $ligneN['classe']==1){

                                                        $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','".$entrepot['idEntrepot']."','".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";

                                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 

                                                    }

                                                }

                                                else{

                                                    $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','".$entrepot['idEntrepot']."','".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";

                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                                                }

                                            }

                                        }

                                    }

                                    else{

                                        $msg_info="<b>LE CODE-BARRE SAISIE CORRESPOND A UN STOCK FINI (TERMINER).</b></br></br>

                                        VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ....";

                                    }

                                }

                                else{

                                /***Debut verifier si la ligne du produit existe deja ***/

                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";

                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                                $ligne1 = mysql_fetch_assoc($res);

                                /***Debut verifier si la ligne du produit existe deja ***/

                                if($ligne1 != null){

                                    $quantite = $ligne1['quantite'] + 1;

                                    $prixTotal=$quantite*$ligne1['prixunitevente'];

                                    $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";

                                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                }

                                else {

                                    $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";

                                    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                                    $ligne = mysql_fetch_assoc($res) ;

                                    if(mysql_num_rows($res)){

                                        if($ligne['classe']==0){

                                            $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."',0,'".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";

                                            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 

                                        

                                        }

                                    }

                                    else{

                                        $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."',0,'".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";

                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 

                                    

                                    }



                                }   

                                }

                            }

                            if($design['classe']==1 && $pagnet['type']==0){

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

                                        $prixTotal=$quantite*$ligne1['prixunitevente'];

                                        $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=1  ";

                                        $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    }

                                    else {

                                        if($ligne['classe']==1 || $ligne['classe']==0){

                                            if($design['uniteStock']!='Transaction'){

                                                $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."','".$design['idDesignation']."','".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1)";

                                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                                            }

                                            

                                        }

                                        

                                    }

                                    

                                }

                                else{

                                    if($design['uniteStock']=='Transaction'){

                                    /* $reqT="SELECT * from `aaa-transaction` where idTransaction='".$design['description']."'";

                                        $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());

                                        $transaction = mysql_fetch_array($resT);

                                        $image=$transaction['aliasTransaction'];

                                        $trans_alias=$transaction['aliasTransaction'];

                                        $trans_pagnet=$idPagnet;

                                        $msg_transaction="OK";*/

                                    }

                                    else if($design['uniteStock']=='Credit'){

                                    /* $reqT="SELECT * from `aaa-transaction` where idTransaction='".$design['description']."'";

                                        $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());

                                        $transaction = mysql_fetch_array($resT);

                                        $image=$transaction['aliasTransaction'];

                                        $trans_alias=$transaction['aliasTransaction'];

                                        $trans_pagnet=$idPagnet;

                                        $msg_credit="OK";*/

                                    }

                                    else{

                                        $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1)";

                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                                    }

                                }

                            }

                            if($design['classe']==6 && $pagnet['type']==0){

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

                                            $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",6)";

                                            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                                        }

                                    }

                                }

                                else{

                                    $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",6)";

                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                                }

                            }

                            if($design['classe']==9 && $pagnet['type']==0){

                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";

                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                                $ligne = mysql_fetch_assoc($res) ;

                                if(mysql_num_rows($res)){

                                    /***Debut verifier si la ligne du produit existe deja ***/

                                    $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=9  ";

                                    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                                    $ligne1 = mysql_fetch_assoc($res);

                                    /***Debut verifier si la ligne du produit existe deja ***/

                                    if($ligne1 != null){

                                    }

                                    else {

                                        if($ligne['classe']==9){

                                            $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",9)";

                                            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                                        }

                                    }

                                }

                                else{

                                    $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",9)";

                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                                }

                            }

                            if($design['classe']==8){

                                $sql3="UPDATE `".$nomtablePagnet."` set type='1' where idPagnet=".$idPagnet;

                                $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());

                            }

                            if($design['classe']==10){

                                $sql3="UPDATE `".$nomtablePagnet."` set type='10' where idPagnet=".$idPagnet;

                                $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());

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

    $ligneIns = array();
    $reference = array();
    if (isset($_POST['btnImprimerFacture'])) {
        
        $idPagnet=@$_POST['idPagnet'];

        $sql="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";

        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

        $pagnet = mysql_fetch_assoc($res) ;
        

        $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";

        $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
        $resL2 = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
        
        if ($pagnet['type']==0 || $pagnet['type']==30) {

            while ($ligne=mysql_fetch_assoc($resL2)) {
                if ($ligne['classe'] == 0) {
                    
                    $sqlGetStock="SELECT idDesignation,designation,nbreArticleUniteStock,SUM(`quantiteStockCourant`) as stockTotal FROM `".$nomtableEntrepotStock."` where idEntrepot=".$ligne['idEntrepot']." and idDesignation=".$ligne['idDesignation']." GROUP BY `idDesignation`";
                    $refJcaisse = mysql_query($sqlGetStock) or die ("persoonel requête 1".mysql_error());
                    $stock = mysql_fetch_array($refJcaisse);

                    $qtLigne = 0;
                            
                    $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                    $designation = mysql_fetch_assoc($resS) ;

                    if(mysql_num_rows($resS)){

                        if ($ligne['unitevente']==$designation['uniteStock']) {
                            
                            $qtLigne = $ligne['quantite'] * $designation['nbreArticleUniteStock'];

                        } elseif ($ligne['unitevente']=='Demi Gros') {

                            $qtLigne = $ligne['quantite'] * ($designation['nbreArticleUniteStock']/2);

                        } else {
                            
                            $qtLigne = $ligne['quantite'];

                        }
                    }
                    
                    if ($qtLigne > $stock['stockTotal']) {
                        $ligneIns[] = $ligne['numligne'];
                    }
                }

            }
        }
        // var_dump($ligneIns);
        if (count($ligneIns) > 0) {
            # code...

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



                $sqlTD="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." && unitevente='Transaction' && quantite=0 ";

                $resTD = mysql_query($sqlTD) or die ("persoonel requête 2".mysql_error());

                $TotalTD = mysql_fetch_array($resTD) ;



                //$Total[ = $TotalP[0] - $TotalTD[0];



                $totalp=$TotalP[0] - ($TotalTD[0] * 2);

                if(@$_POST['remise']==''){

                    $remise=0;

                }

                else{

                    $remise=@$_POST['remise'];

                }

                $apayerPagnet=$totalp-$remise;



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

                $query = mysql_query("SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ");

                $nbre_fois=mysql_num_rows($query);

                /*****Fin Nombre de Panier ouvert****/



                /*****Debut Difference entre Total Panier et Remise****/

                $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";

                $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                $TotalT = mysql_fetch_array($resT) ;



                $sqlTD="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." && unitevente='Transaction' && quantite=0 ";

                $resTD = mysql_query($sqlTD) or die ("persoonel requête 2".mysql_error());

                $TotalTD = mysql_fetch_array($resTD) ;



                $difference=$TotalT[0] - $TotalTD[0] - $remise;

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

                                                if ($ligne['unitevente']==$designation['uniteStock']) {

                                                    $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."' AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock ASC ";

                                                    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                                                    $restant=$ligne['quantite']*$designation['nbreArticleUniteStock'];

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

                                                    $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."' AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock ASC ";

                                                    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                                                    $restant=$ligne['quantite']*($designation['nbreArticleUniteStock']/2);

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

                                                    $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."' AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock ASC ";

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



                                $dateEcheance=htmlspecialchars(trim($_POST['dateEcheance']));

                                if($dateEcheance!=null){

                                    $tab=explode("-",$dateEcheance);

                                    $datePaiemenent=$tab[2].'-'.$tab[1].'-'.$tab[0];

                                    $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1', remise=".$remise.",totalp=".$totalp.",apayerPagnet=".$apayerPagnet.",dateecheance='".$datePaiemenent."',livreur='".$livreur."' where idPagnet=".$idPagnet;

                                    $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());

                                }

                                else{

                                    $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1', remise=".$remise.",totalp=".$totalp.",apayerPagnet=".$apayerPagnet.",livreur='".$livreur."' where idPagnet=".$idPagnet;

                                    $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());

                                }

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

                            $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." && type=0 ";

                            $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());

                            $TotalP = mysql_fetch_array($res18) ;



                            $sql19="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." && type=1 ";

                            $res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());

                            $TotalR = mysql_fetch_array($res19) ;



                            $total=$TotalP[0] - $TotalR[0];

                            

                            $sql20="UPDATE `".$nomtableBon."` set montant=".$total.", date='".$dateString."' where idClient=".$idClient;

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

                        }

                        else {

                            $msg_info="<p>IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idPagnet.", il faut verifier la remise.</p>";

                        }

                        

                    }

                    else {

                        $msg_info="<p>IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idPagnet.", il faut au moins ajouter un produit.</p>";

                    }

                }



            }
        }

    }

/**Fin Button Terminer Pagnet**/



/**Debut Button Terminer Pagnet**/

    if (isset($_POST['btnImprimerProforma'])) {

        if (isset($_POST['remise'])) {

            // code...

            $idPagnet=@$_POST['idPagnet'];



            $sql="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";

            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

            $pagnet = mysql_fetch_assoc($res) ;



            $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";

            $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

            $TotalP = mysql_fetch_array($resT) ;



            $sqlTD="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." && unitevente='Transaction' && quantite=0 ";

            $resTD = mysql_query($sqlTD) or die ("persoonel requête 2".mysql_error());

            $TotalTD = mysql_fetch_array($resTD) ;



            //$Total[ = $TotalP[0] - $TotalTD[0];



            $totalp=$TotalP[0] - ($TotalTD[0] * 2);

            if(@$_POST['remise']==''){

                $remise=0;

            }

            else{

                $remise=@$_POST['remise'];

            }

            $apayerPagnet=$totalp-$remise;



            //$msg_info="<p>$totalp</p> <p>$remise</p> <p>$versement</p> <p>$apayerPagnet</p>";



            $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1', remise=".$remise.",totalp=".$totalp.",apayerPagnet=".$apayerPagnet." where idPagnet=".$idPagnet;

            $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());



        }else 

        {}

    }

/**Fin Button Terminer Pagnet**/



/**Debut Button Annuler Pagnet**/

    if (isset($_POST['btnAnnulerPagnet'])) {



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



/**Debut Button Retourner Pagnet**/

    if (isset($_POST['btnRetournerPagnet'])) {



        $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));

        

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

                            if ($ligne['unitevente']==$designation['uniteStock']) {

                                $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC ";

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

                                $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC ";

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

                                $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC ";

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



            /************************************** UPDATE BON Et DU REMISE******************************************/

            $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." && type=0 ";

            $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());

            $TotalP = mysql_fetch_array($res18) ;



            $sql19="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." && type=1 ";

            $res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());

            $TotalR = mysql_fetch_array($res19) ;



            $total=$TotalP[0] - $TotalR[0];

            

            $sql20="UPDATE `".$nomtableBon."` set montant=".$total.", date='".$dateString."' where idClient=".$idClient;

            $res17=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());



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

        }

    }

/**Fin Button Retourner Pagnet**/



/**Debut Button Annuler Ligne d'un Pagnet**/

    if (isset($_POST['btnRetourAvant'])) {



        $numligne=$_POST['numligne'];

        $unitevente=$_POST['unitevente'];



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

                    $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                    $designation = mysql_fetch_assoc($resS) ;

                        if(mysql_num_rows($resS)){

                            if ($ligne['unitevente']==$designation['uniteStock']) {

                                $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC ";

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

                                $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC ";

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

                                $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC ";

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



        }

        else {

            $msg_info="<p>IMPOSSIBLE.</br></br> Vous ne pouvez pas retourner cette ligne du pagnet numéro ".$idPagnet." , car c'est la(les) dernière(s) ligne(s). Cela risque de fausser les calculs surtout quand vous avez effectué des remises.</br></br>Il faut retourner tout le pagnet.</p>";

        }



    }

/**Fin Button Retourner Ligne d'un Pagnet**/



/**Debut Button Terminer Versement**/

    if (isset($_POST['btnEnregistrerVersement'])) {

        $montant=htmlspecialchars(trim($_POST['montant']));

        $paiement=htmlspecialchars($_POST['typeVersement']);

       // $dateActualiser=@htmlspecialchars(trim($_POST['dateActualiser']));

        // if ($dateActualiser=='') {
        //     $dateActualiser=$dateString;
        // }


$dateVersement=$dateString2;

        $sql2="insert into `".$nomtableVersement."` (idClient,paiement,montant,dateVersement,heureVersement,iduser) values(".$idClient.",'".$paiement."',".$montant.",'".$dateVersement."','".$heureString."',".$_SESSION['iduser'].")";
        // var_dump($sql2);

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



                $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,idUser) values(".$montant.",'".$operation2."',".$idCompteBon.",'".$description2."','".$dateHeures."','".$dateHeures."',".$idVersement.",".$_SESSION['iduser'].")";

                $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

            }

        }

    }

/**Fin Button Terminer Versement**/



/**Debut Button Annuler Versement**/

    if (isset($_POST['btnAnnulerVersement'])) {

        $idVersement=htmlspecialchars(trim($_POST['idVersement']));

        $montant=htmlspecialchars(trim($_POST['montant']));



        //Update du solde du client

        $solde=$client['solde'] + $montant;

        $sql3="UPDATE `".$nomtableClient."` set solde=".$solde." where idClient=".$idClient;

        $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());

        

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



        //on fait la suppression de cette Versement

        $sql3="DELETE FROM `".$nomtableVersement."` where idVersement=".$idVersement;

        $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());



    }

/**Fin Button Annuler Versement**/



/**Debut Button Modifier Versement**/

    if (isset($_POST['btnModifierVersement'])) {

        $idVersement=htmlspecialchars(trim($_POST['idVersement']));

        $montant=htmlspecialchars(trim($_POST['montant']));

        $description=htmlspecialchars($_POST['description']);

        $dateVersement=htmlspecialchars(trim($_POST['dateVersement']));

        $sql2="UPDATE `".$nomtableVersement."` set montant='".$montant."',paiement='".$description."',dateVersement='".$dateVersement."' where idVersement='".$idVersement."' ";

        $res2=mysql_query($sql2) or die ("insertion Versement impossible =>".mysql_error()); 

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

/**Debut Button upload Image Bon**/
if (isset($_POST['btnUploadImgBon'])) {
    $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));
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

            $sql2="UPDATE `".$nomtablePagnet."` set image='".$file."' where idPagnet='".$idPagnet."' ";
            $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
        }
        else{
            echo "Une erreur est survenue";
        }
    }
}
/**Fin Button upload Image Bon**/

/**Debut Button Archiver Pagnet et Versement**/
    if (isset($_POST['btnArchiverOperations'])) {

        $sqlP="UPDATE `".$nomtablePagnet."` set archiver=1 where idClient=".$idClient;
        $resP=@mysql_query($sqlP) or die ("mise à jour client  impossible".mysql_error());

        
        $sqlV="UPDATE `".$nomtableVersement."` set archiver=1 where idClient=".$idClient;
        $resV=mysql_query($sqlV) or die ("modification 1 impossible =>".mysql_error());

    }
/**Fin Button Archiver Pagnet et Versement**/

require('entetehtml.php');

?>

<!-- Debut Code HTML -->

<body onLoad="">

<input type="hidden" id="typeVente" value="2"/>
<input type="hidden" id="sessioninfosup" value="<?= $_SESSION['infoSup'];?>">

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

                    (SELECT p.idClient FROM `".$nomtablePagnet."` p where p.idClient='".$idClient."' AND p.archiver=0 AND p.validerProforma=0 AND p.verrouiller=1 AND (p.type=0 || p.type=10 || p.type=11 || p.type=30) AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')  or (p.datepagej BETWEEN '".$dateDebut."' AND '".$dateFin."')

                    UNION ALL

                    SELECT v.idClient FROM `".$nomtableVersement."` v where v.idClient='".$idClient."' AND v.archiver=0  AND (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."')

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

                    (SELECT CONCAT(SUBSTR(p.datepagej,7, 4),'',SUBSTR(p.datepagej,4, 2),'',SUBSTR(p.datepagej,1, 2),'',p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p where p.idClient='".$idClient."' AND p.archiver=0  AND p.validerProforma=0 AND p.verrouiller=1 AND (p.type=0 || p.type=10 || p.type=11 || p.type=30)  AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')  or (p.datepagej BETWEEN '".$dateDebut."' AND '".$dateFin."')

                    UNION ALL

                    SELECT CONCAT(SUBSTR(v.dateVersement,7, 4),'',SUBSTR(v.dateVersement,4, 2),'',SUBSTR(v.dateVersement,1, 2),'',v.heureVersement,'+',v.idVersement,'+2') AS codeVersement FROM `".$nomtableVersement."` v where v.idClient='".$idClient."' AND v.archiver=0  AND  (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."')

                    ) AS a ORDER BY codePagnet DESC LIMIT ".$premier.",".$parPage." ";

                    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 20".mysql_error());

                /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/



                $sql12="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." AND archiver=0  AND verrouiller=1 AND (type=0 || type=30) AND (CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (datepagej BETWEEN '".$dateDebut."' AND '".$dateFin."') ";

                $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());

                $TotalB = mysql_fetch_array($res12) ;

    

                $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient=".$idClient." AND archiver=0  AND  (CONCAT(CONCAT(SUBSTR(dateVersement,7, 10),'',SUBSTR(dateVersement,3, 4)),'',SUBSTR(dateVersement,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."') ";

                $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());

                $TotalV = mysql_fetch_array($res13) ;

            }else{

                $dateDebut=$_SESSION['dateCB'];

                $dateFin=$dateString;

                /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/

                        $sqlC="SELECT

                        COUNT(*) AS total

                    FROM

                    (SELECT p.idClient FROM `".$nomtablePagnet."` p where p.idClient='".$idClient."' AND p.archiver=0  AND p.validerProforma=0 AND p.verrouiller=1 AND (p.type=0 || p.type=10 || p.type=11 || p.type=30)

                    UNION ALL

                    SELECT v.idClient FROM `".$nomtableVersement."` v where v.idClient='".$idClient."' AND v.archiver=0 

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

                    (SELECT CONCAT(SUBSTR(p.datepagej,7, 4),'',SUBSTR(p.datepagej,4, 2),'',SUBSTR(p.datepagej,1, 2),'',p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p where p.idClient='".$idClient."' AND p.archiver=0  AND p.validerProforma=0 AND p.verrouiller=1 AND (p.type=0 || p.type=10 || p.type=11 || p.type=30) 

                    UNION ALL

                    SELECT CONCAT(SUBSTR(v.dateVersement,7, 4),'',SUBSTR(v.dateVersement,4, 2),'',SUBSTR(v.dateVersement,1, 2),'',v.heureVersement,'+',v.idVersement,'+2') AS codeVersement FROM `".$nomtableVersement."` v where v.idClient='".$idClient."' AND v.archiver=0 

                    ) AS a ORDER BY codePagnet DESC LIMIT ".$premier.",".$parPage." ";

                    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 20".mysql_error());

                /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/  



                $sql12="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." AND archiver=0  AND verrouiller=1 AND (type=0 || type=30 || type=11) ";

                $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());

                $TotalB = mysql_fetch_array($res12) ;

    

                $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient=".$idClient." AND archiver=0  ";

                $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());

                $TotalV = mysql_fetch_array($res13) ;

            }



            $T_solde=$TotalB[0] - $TotalV[0];

            //Somme des Pagnets Bons du Client
            $stmtBons = $bdd->prepare("UPDATE  `".$nomtableClient."` SET solde=:solde WHERE idClient=:idClient ");
            $stmtBons->bindValue(':idClient', $client['idClient'], PDO::PARAM_INT);
            $stmtBons->bindValue(':solde', $T_solde);
            $stmtBons->execute();



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

                </div>

            </div>

            <?php if ( $T_solde==0 ) { ?> 
                <form class="form-inline pull-right noImpr"  style="margin-right:20px;" method="post" >
                    <button type="submit" name="btnArchiverOperations" class="btn btn-info pull-right" >
                            <span class="glyphicon glyphicon-refresh">Archiver</span>
                    </button> 
                </form>
            <?php } ?> 

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

        <!--*******************************Debut Rechercher Produit****************************************-->

        <!--<form  class="pull-right" id="searchProdForm" method="post" name="searchProdForm" >

            <input type="hidden" id="clientBon" />

            <input type="text" size="30" class="form-control" name="produit" placeholder="Rechercher ..."  id="produitBon" autocomplete="off" />

                <span id="reponsePV"></span>

        </form>-->

        <!--*******************************Fin Rechercher Produit****************************************-->

        <?php
            $paieMois=$annee-$mois;

            $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."' and YEAR(datePs)='".$annee_paie."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";

            $res0=mysql_query($sql0);

            if(mysql_num_rows($res0)){

                if($jour > 0){

                    if($jour > 4){
                        echo '
                            <button disabled="true" type="button" class="btn btn-success noImpr pull-right" data-toggle="modal" data-target="#addVer">

                                <i class="glyphicon glyphicon-plus"></i>Versement
            
                            </button>
            
                            <button disabled="true" type="button" class="btn btn-warning noImpr pull-right hidden-xs hidden-sm" style="margin-right:400px;" data-toggle="modal" data-target="#actBon">
            
                                <i class="glyphicon glyphicon-plus"></i>Actualiser Bon
            
                            </button> ';
                    }
                    else{
                        echo '
                            <button type="button" class="btn btn-success noImpr pull-right" data-toggle="modal" data-target="#addVer">

                                <i class="glyphicon glyphicon-plus"></i>Versement
            
                            </button>';

                        if ($_SESSION['depotAvoir']==1) {
                            echo '
                                <button type="button" class="btn btn-primary noImpr pull-right" style="margin-right:300px;" data-toggle="modal" data-target="#addAvoir">
            
                                    <i class="glyphicon glyphicon-plus"></i> Ajouter Avoir
                
                                </button>';
                        }
                        echo '
                            <button type="button" class="btn btn-warning noImpr pull-right hidden-xs hidden-sm" style="margin-right:150px;" data-toggle="modal" data-target="#actBon">
            
                                <i class="glyphicon glyphicon-plus"></i>Actualiser Bon
            
                            </button> ';
                    }
                }

                else{
                    echo '
                    <button type="button" class="btn btn-success noImpr pull-right" data-toggle="modal" data-target="#addVer">

                        <i class="glyphicon glyphicon-plus"></i>Versement
    
                    </button>';

                    if ($_SESSION['depotAvoir']==1) {
                        echo '
                            <button type="button" class="btn btn-primary noImpr pull-right" style="margin-right:300px;" data-toggle="modal" data-target="#addAvoir">
        
                                <i class="glyphicon glyphicon-plus"></i> Ajouter Avoir
            
                            </button>';
                    }
                    echo '
    
                    <button type="button" class="btn btn-warning noImpr pull-right hidden-xs hidden-sm" style="margin-right:150px;" data-toggle="modal" data-target="#actBon">
    
                        <i class="glyphicon glyphicon-plus"></i>Actualiser Bon
    
                    </button> ';
                }

            }

            else{
                echo '
                    <button type="button" class="btn btn-success noImpr pull-right" data-toggle="modal" data-target="#addVer">

                        <i class="glyphicon glyphicon-plus"></i>Versement
    
                    </button>';

                    if ($_SESSION['depotAvoir']==1) {
                        echo '
                            <button type="button" class="btn btn-primary noImpr pull-right" style="margin-right:300px;" data-toggle="modal" data-target="#addAvoir">
        
                                <i class="glyphicon glyphicon-plus"></i> Ajouter Avoir
            
                            </button>';
                    }
                    echo '
    
                    <button type="button" class="btn btn-warning noImpr pull-right hidden-xs hidden-sm" style="margin-right:150px;" data-toggle="modal" data-target="#actBon">
    
                        <i class="glyphicon glyphicon-plus"></i>Actualiser Bon
    
                    </button> ';
            }
        ?>

        <!--*******************************Debut Ajouter Versement****************************************-->

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

                                    <label for="inputEmail3" class="control-label">Description</label>

                                    <textarea  type="textarea" class="form-control" id="typeVersement" name="typeVersement" placeholder="Description" ></textarea>

                                    <span class="text-danger" ></span>

                                </div>

                                <div class="form-group">

                                    <label for="inputEmail3" class="control-label">Montant</label>

                                    <input type="number" class="form-control" id="montant" name="montant" placeholder="Montant" required="">

                                    <span class="text-danger" ></span>

                                </div>
                                                                                            

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
                            
                            <form name="formulaireAvoir" method="post" action="bonPclient.php?c=<?= $idClient?>">

                                <div class="form-group">

                                    <label for="motifAvoir" class="control-label">Motif</label>

                                    <input type="text" maxLength="100" class="form-control" id="motifAvoir" name="motifAvoir" placeholder="Motif avoir" required="" autofocus>

                                    <span class="text-danger" ></span>

                                </div>

                                <div class="form-group">

                                    <label for="dateAvoir" class="control-label">Date avoir jj-mm-aaaa</label>

                                    <input type="text" maxLength="10" class="form-control dateInputControl" id="dateAvoir" name="dateAvoir" placeholder="jj-mm-aaaa" required="" autofocus>

                                    <span class="text-danger" ></span>

                                </div>

                                <div class="form-group">

                                    <label for="montantAvoir" class="control-label">Montant avoir</label>

                                    <input type="number" class="form-control" id="montantAvoir" name="montantAvoir" placeholder="Montant avoir" required="" autofocus>

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

        <!--*******************************Debut Ajouter Versement****************************************-->

            <div class="modal fade" id="actBon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

                <div class="modal-dialog" role="document">

                    <div class="modal-content">

                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                            <h4 class="modal-title" id="myModalLabel">Choisir Date</h4>

                        </div>

                        <div class="modal-body">

                        <form name="formulaireVersement" method="post" <?php echo  "action=bonPclient.php?c=". $idClient."" ; ?>>

                                <div class="form-group">

                                    <label for="inputEmail3" class="control-label">Date</label>

                                    <input type="date" class="form-control" id="dateActualiser" name="dateActualiser" placeholder="Date" required="">

                                    <span class="text-danger" ></span>

                                </div>

                                <div class="modal-footer">

                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                                    <button type="submit" name="btnActualiserBon" class="btn btn-primary">Enregistrer</button>

                                </div>

                            </form>

                        </div>



                    </div>

                </div>

            </div>

        <!--*******************************Fin Ajouter Versement****************************************-->

        <?php  

            $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."' and YEAR(datePs)='".$annee_paie."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";

            $res0=mysql_query($sql0);

            if(mysql_num_rows($res0)){

                if($jour > 0 ){

                    if($jour > 4 ){
                        echo ' 
                            <form name="formulairePagnet" method="post" >
                                <button disabled="true" type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente">
                                    <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                                </button>
                            </form>
                        ';
                    }
                    else {
                        echo ' 
                            <form name="formulairePagnet" method="post" >
                                <button type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente">
                                    <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                                </button>
                            </form>
                        ';
                    }

                    echo "<h6><span id='blinker' style='color:red;'>VEUILLEZ EFFECTUER LE PAIEMENT DU SERVICE JCAISSE AVANT LE 5 !</span></h6>";
                    echo '<br>';
                }

                else{

                    echo ' 
                        <form name="formulairePagnet" method="post" >
                            <button type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente">
                                            <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                            </button>
                        </form>
                    ';
                    echo '<br>';

                }

            }

            else if ($client['plafond']!=null && $client['plafond']!=0 && $client['plafond']<=($T_solde * $_SESSION['devise'])){
                
                echo ' 
                    <form name="formulairePagnet" method="post" >
                        <button type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente">
                            <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                        </button>
                    </form>
                ';

                echo "<h6><span id='blinker' style='color:red;'>CE CLIENT A ATTEINT LE PLAFOND DE SON COMPTE.</span></h6>";
                
                echo '<br>';
            }

            else{

                echo ' 
                    <form name="formulairePagnet" method="post" >
                        <button type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente">
                            <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
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
                function showPreviewBon(event,idPagnet) {
                    var file = document.getElementById('input_file_Bon'+idPagnet).value;
                    var reader = new FileReader();
                    reader.onload = function()
                    {
                        var format = file.substr(file.length - 3);
                        var pdf = document.getElementById('output_pdf_Bon'+idPagnet);
                        var image = document.getElementById('output_image_Bon'+idPagnet);
                        if(format=='pdf'){
                            document.getElementById('output_pdf_Bon'+idPagnet).style.display = "block";
                            document.getElementById('output_image_Bon'+idPagnet).style.display = "none";
                            pdf.src = reader.result;
                        }
                        else{
                            document.getElementById('output_image_Bon'+idPagnet).style.display = "block";
                            document.getElementById('output_pdf_Bon'+idPagnet).style.display = "none";
                            image.src = reader.result;
                        }
                    }
                    reader.readAsDataURL(event.target.files[0]);
                    document.getElementById('btn_upload_Bon'+idPagnet).style.display = "block";
                }

            </script>

        <!-- Fin Javascript de l'Accordion pour Tout les Paniers -->



        <!-- Debut de l'Accordion pour Tout les Paniers -->

            <div class="panel-group" id="accordion">



                <?php



                /**Debut requete pour Lister les Paniers en cours d'Aujourd'hui (1 au maximum) **/

                    $sqlP0="SELECT * FROM `".$nomtablePagnet."` where iduser='".$_SESSION['iduser']."' && idClient='".$idClient."'  && verrouiller=0 ORDER BY idPagnet DESC";

                    $resP0 = mysql_query($sqlP0) or die ("persoonel requête 2".mysql_error());

                /**Fin requete pour Lister les Paniers en cours d'Aujourd'hui (1 au maximum) **/



                /**Debut requete pour Lister tout les Paniers d'Aujourd'hui  **/

                    $sql2="SELECT * FROM `".$nomtablePagnet."` where idClient='".$idClient."'  ORDER BY idPagnet DESC";

                    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());

                /**Fin requete pour Lister tout les Paniers d'Aujourd'hui  **/



                /**Debut requete pour Rechercher le dernier Panier Ajouter  **/

                    $reqA="SELECT datepagej

                    FROM

                    (SELECT p.idClient,p.idPagnet,p.datepagej,p.heurePagnet FROM `".$nomtablePagnet."` p where p.idClient='".$idClient."' AND p.verrouiller=1

                    UNION 

                    SELECT v.idClient,v.idVersement,v.dateVersement,v.heureVersement FROM `".$nomtableVersement."` v where v.idClient='".$idClient."'

                    ) AS a ORDER BY datepagej DESC LIMIT 1";

                    $resA=mysql_query($reqA) or die ("persoonel requête 2".mysql_error());

                /**Fin requete pour Rechercher le dernier Panier Ajouter  **/ ?>         

                

                <!-- Debut Boucle while concernant les Paniers en cours (1 aux maximum) -->  

                    <?php while ($pagnet = mysql_fetch_assoc($resP0)) {   ?>

                        <?php

                            if($pagnet['type'] == 0 || $pagnet['type']==30){   ?>

                                <div class="panel <?= ($pagnet['type']==30) ? 'panel-info' : 'panel-primary'?>">

                                    <div class="panel-heading">

                                        <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                            <div class="right-arrow pull-right">+</div>

                                            <a class="row"href="#">

                                                <span class="noImpr col-md-2 col-sm-2 col-xs-12"> Panier <?php echo ': En cours ...'; ?></span>

                                                <span class="noImpr col-md-3 col-sm-3 col-xs-12"> Date: <?php echo $pagnet['datepagej']." ".$pagnet['heurePagnet']; ?> </span>

                                                <!-- <span class="spanDate noImpr col-md-2 col-sm-2 col-xs-12">Heure: <?php // $pagnet['heurePagnet']; ?></span> -->

                                                <?php	$sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";

                                                        $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                                                        $TotalT = mysql_fetch_array($resT) ;

                                                        ?>

                                                <?php if($_SESSION['Pays']=='Canada'){   ?> 

                                                    <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Sous-Total : <span <?php echo  "id=somme_Apayer".$pagnet['idPagnet'] ; ?> ><?php echo $TotalT[0]; ?> </span></span>

                                                    <span class="noImpr col-md-3 col-sm-3 col-xs-12" > Total : <span <?php echo  "id=somme_ApayerT".$pagnet['idPagnet'] ; ?> ><?php echo ( $TotalT[0] + (($TotalT[0] * 5)/100) + (($TotalT[0] * 9.975)/100) ) ; ?> </span></span>

                                                <?php }

                                                else{   ?>

                                                    <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total panier: <span <?php echo  "id=somme_Total".$pagnet['idPagnet']; ?> ><?php echo $TotalT[0]; ?>  </span></span>

                                                    <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total à payer: <span <?php echo  "id=somme_Apayer".$pagnet['idPagnet'] ; ?> ><?php echo ($TotalT[0] - (($TotalT[0] * $pagnet['taux']) / 100)); ?> </span></span>                 

                                                <?php } ?>

                                            </a>

                                        </h4>

                                    </div>

                                    <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?> class="panel-collapse collapse in"  >

                                        <div class="panel-body" >

                                            <div class="cache_btn_Terminer row">

                                                <!--*******************************Debut Ajouter Ligne****************************************-->

                                                <form  class="form-inline noImpr ajouterProdForm col-md-4 col-sm-4 col-xs-12" id="ajouterProdFormEt<?= $pagnet['idPagnet'];?>" onsubmit="return false" >

                                                    <input type="text" class="inputbasic form-control codeBarreLigneEt" name="codeBarre" id="panier_<?= $pagnet['idPagnet'];?>" style="width:100%;" autofocus="" autocomplete="off"  required />

                                                    <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                        <!-- <span id="reponseV"></span> -->

                                                    <!-- <button type="submit" name="btnEnregistrerCodeBarre"

                                                    id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs " ><span class="glyphicon glyphicon-plus" ></span>

                                                    </button> -->

                                                </form>

                                                <!--*******************************Fin Ajouter Ligne****************************************-->

                                                <div class="col-md-8 col-sm-8 col-xs-12 content2">

                                                    <!--*******************************Debut Terminer Pagnet****************************************-->

                                                    <form class="form-inline noImpr" id="factForm" method="post">
                                                    <?php if($_SESSION['listeRemiseClient']==0) { ?> 

                                                        <input type="number" step="0.1" <?php echo "id=val_remise".$pagnet['idPagnet'] ; ?> name="remise" onkeyup="modif_Remise(this.value, <?php echo  $pagnet['idPagnet']; ?>)"

                                                        class="remise form-control col-md-2 col-sm-2 col-xs-3" placeholder="Remise....">
                                                        
                                                        <?php 
                                                            }
                                                         else {

                                                        ?>
                                                        <select class="form-control col-md-2 col-sm-2 col-xs-3 tauxPanier filedReadonly<?= $pagnet['idPagnet'] ; ?>" name="taux" <?php echo  "id=taux".$pagnet['idPagnet'].""; ?>  onchange="modif_TauxPanier(this.value,<?php echo  $pagnet['idPagnet']; ?>)" >
                                                            <option selected="true" disabled="disabled" value="<?= $pagnet['taux']; ?>"><?= $pagnet['taux'].' %' ; ?></option>
                                                            <option value="0">0 %</option>
                                                            <option value="3">1 %</option>
                                                            <option value="5">5 %</option>
                                                            <option value="10">10 %</option>
                                                            <option value="15">15 %</option>
                                                            <option value="20">20 %</option>
                                                            <option value="25">25 %</option>
                                                            <option value="30">30 %</option>
                                                        </select>  

                                                        <?php } ?>                                                     

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

                                                            <button type="submit" style="" name="btnImprimerFacture" <?php echo  "id=btnImprimerFacture".$pagnet['idPagnet']."" ; ?> class="btn btn-success  terminer" data-toggle="modal" ><span class="glyphicon glyphicon-ok"></span></button>

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

                                            <table  <?php echo  "id=tablePanier".$pagnet['idPagnet']."" ; ?> class="tabPanier table"  width="100%" >

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

                                                        $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

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

                                                                        <?php echo $ligne['designation']; 

                                                                    }

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

                                                                            

                                                                                    if($ligne['unitevente']==$designation['uniteDetails']){

                                                                                        ?>  

                                                                                            <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?> <?= (@in_array($ligne['numligne'],$ligneIns)) ? 'style="background-color:#FC8080;' : '' ; ?>

                                                                                        onkeyup="modif_Quantite(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  $designation['nbreArticleUniteStock']; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 25%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                                >

                                                                                        <?php

                                                                                    }

                                                                                    else if($ligne['unitevente']==$designation['uniteStock']){

                                                                                        ?>  

                                                                                            <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?> <?= (@in_array($ligne['numligne'],$ligneIns)) ? 'style="background-color:#FC8080;' : '' ; ?>

                                                                                        onkeyup="modif_Quantite(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  '1'; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 25%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                                >

                                                                                    <?php }

                                                                                }

                                                                                else{

                                                                                    $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

                                                                                    $resD=mysql_query($sqlD) or die ("select Designation impossible =>".mysql_error());

                                                                                    $designation = mysql_fetch_assoc($resD);

                                                                                        ?>  

                                                                                            <input class="quantite form-control" onkeyup="modif_QuantiteET(this.value,<?= $ligne['numligne']; ?>,<?= $pagnet['idPagnet']; ?>)" id="ligne<?= $ligne['numligne']; ?>" <?= (@in_array($ligne['numligne'],$ligneIns)) ? 'style="background-color:#FC8080;' : '' ; ?>

                                                                                            size="4"  type="number" id="quantite<?= $ligne['numligne']; ?>" value="<?= $ligne['quantite']; ?>">  

                                                                                        <?php

                                                                                }

                                                                            }

                                                                            if($ligne['classe']==1) { ?> 

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

                                                                                if($ligne['unitevente']==$designation['uniteStock']){

                                                                                    ?> 

                                                                                        <select name="uniteVente" class="form-control" onchange="modif_UniteStockET(this.value)" >

                                                                                            <?php

                                                                                                $reqP='SELECT * from  `'.$nomtableDesignation.'` where designation="'.$ligne['designation'].'"';

                                                                                                $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                                                                                if(mysql_num_rows($resP)){

                                                                                                    $produit = mysql_fetch_assoc($resP);

                                                                                                    echo '<option value="'.$produit["uniteStock"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§1">'.$produit["uniteStock"].'</option>';

                                                                                                    echo '<option value="Demi Gros§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§2">Demi Gros</option>';

                                                                                                    echo '<option value="Piece§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§1">Piece</option>';

                                                                                                }

                                                                                            ?>    

                                                                                        </select>

                                                                                    <?php 

                                                                                }

                                                                                else{

                                                                                    ?>

                                                                                            <select name="uniteVente" class="form-control" onchange="modif_UniteStockET(this.value)" >

                                                                                            <?php

                                                                                                $reqP='SELECT * from  `'.$nomtableDesignation.'` where designation="'.$ligne['designation'].'"';

                                                                                                $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                                                                                if(mysql_num_rows($resP)){

                                                                                                    $produit = mysql_fetch_assoc($resP);

                                                                                                    $demi=$ligne['quantite']*2;

                                                                                                    if($produit['nbreArticleUniteStock']==$demi){

                                                                                                        echo '<option value="Demi Gros§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§2">Demi Gros</option>';

                                                                                                        echo '<option value="Piece§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§1">Piece</option>';

                                                                                                    }

                                                                                                    else{

                                                                                                        echo '<option value="Piece§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§1">Piece</option>';

                                                                                                        echo '<option value="Demi Gros§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§2">Demi Gros</option>';

                                                                                                    }

                                                                                                    if(($produit["uniteStock"]!='')&&($produit["uniteStock"]!=null)){

                                                                                                        echo '<option value="'.$produit["uniteStock"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§1">'.$produit["uniteStock"].'</option>';

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

                                                                        else if($ligne['classe']==6){?>

                                                                            <input class="form-control" style="width: 70%"  type="text" <?php echo  "id=especes".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['unitevente'].""; ?>

                                                                                onkeyup="modif_Espece(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" >

                                                                            <?php

                                                                        }

                                                                        else  if($ligne['classe']!=2 && $ligne['classe']!=0 && $ligne['classe']!=6){?>

                                                                            <?php echo $ligne['unitevente']; ?>

                                                                            <?php 

                                                                        }

                                                                    ?> 

                                                                </td>

                                                                <td>

                                                                    <input <?php echo  "id=prixUniteStock".$ligne['numligne'].""; ?> class="prixunitevente form-control" size="6"  type="number" <?php echo  "id=prixunitevente".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['prixunitevente'].""; ?>

                                                                                onkeyup="modif_Prix(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" >

                                                                </td>

                                                                <td>

                                                                    <?php if ($ligne['classe']==0): ?>

                                                                        <select  class="form-control" onchange="modif_Depot(this.value)" >

                                                                            <?php

                                                                                $reqEp="SELECT * from  `".$nomtableEntrepot."` e

                                                                                where idEntrepot='".$ligne['idEntrepot']."' ";

                                                                                $resEp=mysql_query($reqEp) or die ("select stock impossible =>".mysql_error());

                                                                                $entrepot = mysql_fetch_assoc($resEp);

                                                                                echo '<option selected value="'.$entrepot["idEntrepot"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'">'.$entrepot["nomEntrepot"].'</option>';

                                                                                /***Debut verifier si la ligne du produit existe deja ***/

                                                                                $sqlEl="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$pagnet['idPagnet']."' and idDesignation='".$ligne['idDesignation']."' and idEntrepot!='".$ligne['idEntrepot']."'";

                                                                                $resEl = mysql_query($sqlEl) or die ("persoonel requête 2".mysql_error());

                                                                                /***Debut verifier si la ligne du produit existe deja ***/

                                                                                if(!mysql_num_rows($resEl) && $userAcces['idEntrepot']==0){

                                                                                    $reqDp="SELECT * from  `".$nomtableEntrepot."` e

                                                                                    INNER JOIN `".$nomtableEntrepotStock."` s ON s.idEntrepot = e.idEntrepot

                                                                                    where s.designation='".$ligne['designation']."' AND s.quantiteStockCourant>0 AND e.idEntrepot<>'".$entrepot["idEntrepot"]."' ORDER BY quantiteStockCourant DESC ";

                                                                                    $resDp=mysql_query($reqDp) or die ("select stock impossible =>".mysql_error());

                                                                                    while ($depot = mysql_fetch_assoc($resDp)) {

                                                                                        echo '<option value="'.$depot["idEntrepot"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'">'.$depot["nomEntrepot"].'</option>';

                                                                                    }

                                                                                }

                                                                            ?>

                                                                        </select> 

                                                                    <?php endif; ?>

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

                                                                                        <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                        <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

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

                                                                    <?php 
                                                                        if ($_SESSION["infoSup"]==1) {
                                                                    ?>
                                                                        <button type="button" class="btn btn-info pull-right btn_add_infoSup" data-toggle="modal" data-target="#add_infoSup_modal<?= $ligne['numligne'];?>">

                                                                            <span class="glyphicon glyphicon-plus"></span>info. sup 

                                                                        </button>
                                                                    <?php 
                                                                        } 
                                                                    ?>
                                                                    
                                                                    <div id="add_infoSup_modal<?= $ligne['numligne'] ; ?>" class="modal fade" role="dialog">
                                                                        <div class="modal-dialog">
                                                                            <!-- Modal content-->
                                                                            <div class="modal-content">
                                                                                <div class="modal-header panel-primary">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    <h4 class="modal-title">Informations supplémentaires</h4>
                                                                                </div>
                                                                                <div class="modal-body" id="add_infoSup_body<?= $ligne['numligne'] ; ?>">
                                                                                    <div class="form-group col-md-6">
                                                                                        <label class="col-form-label" for="numeroChassis<?= $ligne['numligne'] ; ?>">Numéro châsis</label>
                                                                                        <div class="">
                                                                                            <input type="text" name="numeroChassis" id="numeroChassis<?= $ligne['numligne'] ; ?>" class="form-control" placeholder="Numéro châsis">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group col-md-6">
                                                                                        <label class="col-form-label" for="numeroMoteur<?= $ligne['numligne'] ; ?>">Numéro moteur</label>
                                                                                        <div class="">
                                                                                            <input type="text" name="numeroMoteur" id="numeroMoteur<?= $ligne['numligne'] ; ?>" class="form-control" placeholder="Numéro moteur">
                                                                                        </div>
                                                                                    </div>                                                                                        
                                                                                <!-- </div>
                                                                                <div class="modal-footer"> -->
                                                                                    <button type="button" id="closeInfo" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                    <button type="button" onclick="add_infoSup(<?= $ligne['numligne'] ; ?>)" name="btnAddInfoSup" class="btn btn-success">Ajouter</button>
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

                            }

                            else if($pagnet['type'] == 10){   ?>

                                <div class="panel panel-warning">

                                    <div class="panel-heading">

                                        <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                            <div class="right-arrow pull-right">+</div>

                                            <a class="row"href="#">

                                                <span class="noImpr col-md-2 col-sm-2 col-xs-12"> Panier <?php echo ': En cours ...'; ?></span>

                                                <span class="noImpr col-md-3 col-sm-3 col-xs-12"> Date: <?php echo $pagnet['datepagej']." ".$pagnet['heurePagnet']; ?> </span>

                                                <!-- <span class="spanDate noImpr col-md-2 col-sm-2 col-xs-12">Heure: <?php // $pagnet['heurePagnet']; ?></span> -->

                                                <?php	$sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";

                                                        $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                                                        $TotalT = mysql_fetch_array($resT) ;

                                                        ?>

                                                <?php if($_SESSION['Pays']=='Canada'){   ?> 

                                                    <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Sous-Total : <span <?php echo  "id=somme_Apayer".$pagnet['idPagnet'] ; ?> ><?php echo $TotalT[0]; ?> </span></span>

                                                    <span class="noImpr col-md-3 col-sm-3 col-xs-12" > Total : <span <?php echo  "id=somme_ApayerT".$pagnet['idPagnet'] ; ?> ><?php echo ( $TotalT[0] + (($TotalT[0] * 5)/100) + (($TotalT[0] * 9.975)/100) ) ; ?> </span></span>

                                                <?php }

                                                else{   ?>

                                                    <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total panier: <span <?php echo  "id=somme_Total".$pagnet['idPagnet']; ?> ><?php echo $TotalT[0]; ?>  </span></span>

                                                    <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total à payer: <span <?php echo  "id=somme_Apayer".$pagnet['idPagnet'] ; ?> ><?php echo ($TotalT[0] - (($TotalT[0] * $pagnet['taux']) / 100)); ?> </span></span>                 

                                                <?php } ?>

                                            </a>

                                        </h4>

                                    </div>

                                    <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?> class="panel-collapse collapse in"  >

                                        <div class="panel-body" >

                                            <div class="cache_btn_Terminer row">

                                                <!--*******************************Debut Ajouter Ligne****************************************-->

                                                <form  class="form-inline noImpr ajouterProdForm col-md-4 col-sm-4 col-xs-12" id="ajouterProdFormEt<?= $pagnet['idPagnet'];?>" onsubmit="return false" >

                                                    <input type="text" class="inputbasic form-control codeBarreLigneEt" name="codeBarre" id="panier_<?= $pagnet['idPagnet'];?>" style="width:100%;" autofocus="" autocomplete="off"  required />

                                                    <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?>>

                                                        <!-- <span id="reponseV"></span> -->

                                                    <!-- <button type="submit" name="btnEnregistrerCodeBarre"

                                                    id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs " ><span class="glyphicon glyphicon-plus" ></span>

                                                    </button> -->

                                                </form>

                                                <!--*******************************Fin Ajouter Ligne****************************************-->

                                                <div class="col-md-8 col-sm-8 col-xs-12 content2">

                                                    <!--*******************************Debut Terminer Pagnet****************************************-->

                                                    <form class="form-inline noImpr" id="factForm" method="post">

                                                        <input type="number" step="0.1" <?php echo "id=val_remise".$pagnet['idPagnet'] ; ?> name="remise" onkeyup="modif_Remise(this.value, <?php echo  $pagnet['idPagnet']; ?>)"

                                                        class="remise form-control col-md-2 col-sm-2 col-xs-3" placeholder="Remise....">
                                                        
                                                        <?php //if($_SESSION['compte']==1):?>

                                                            <!-- <input type="number" class="avanceInput form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $pagnet['idPagnet'];?>" name="avanceInput" id="avanceInput<?= $pagnet['idPagnet'];?>" autocomplete="off" placeholder="Avance"/>                                                     -->

                                                            <!-- <select class="compteAvance <?= $pagnet['idPagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $pagnet['idPagnet'];?>" name="compteAvance"  <?php echo  "id=compteAvance".$pagnet['idPagnet'] ; ?>> -->

                                                                <!-- <option value="caisse">Caisse</option> -->

                                                            <?php 
                                                            // foreach ($cpt_array as $key) { 

                                                            //     if($key['idCompte'] != 2){

                                                                ?>

                                                                    <!-- <option value="<?= $key['idCompte'];?>"><?= $key['nomCompte'];?></option> -->

                                                                <?php //}

                                                                //} ?>

                                                            <!-- </select> -->

                                                        <?php // endif; ?>

                                                        

                                                        <input type="hidden" name="idPagnet"   <?php echo  "value=".$pagnet['idPagnet']."" ; ?>>

                                                        <input type="hidden" name="totalp" <?php echo  "id=totalp".$pagnet['idPagnet']."" ; ?> value="<?php echo $pagnet['totalp']; ?>" >

                                                        <!-- <div class="btnAction col-md-1 col-xs-12"> -->

                                                            <button type="submit" style="" name="btnImprimerProforma" <?php echo  "id=btnImprimerFacture".$pagnet['idPagnet']."" ; ?> class="btn btn-success  terminer" data-toggle="modal" ><span class="glyphicon glyphicon-ok"></span></button>

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

                                            <table  <?php echo  "id=tablePanier".$pagnet['idPagnet']."" ; ?> class="tabPanier table"  width="100%" >

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

                                                        $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

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

                                                                            <?php echo $ligne['designation']; 

                                                                        }

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

                                                                            

                                                                                    if($ligne['unitevente']==$designation['uniteDetails']){

                                                                                        ?>  

                                                                                            <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?> <?= (@in_array($ligne['numligne'],$ligneIns)) ? 'style="background-color:#FC8080;' : '' ; ?>

                                                                                        onkeyup="modif_Quantite(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  $designation['nbreArticleUniteStock']; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 25%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                                >

                                                                                        <?php

                                                                                    }

                                                                                    else if($ligne['unitevente']==$designation['uniteStock']){

                                                                                        ?>  

                                                                                            <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?> <?= (@in_array($ligne['numligne'],$ligneIns)) ? 'style="background-color:#FC8080;' : '' ; ?>

                                                                                        onkeyup="modif_Quantite(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  '1'; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 25%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                                >

                                                                                    <?php }

                                                                                }

                                                                                else{

                                                                                    $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

                                                                                    $resD=mysql_query($sqlD) or die ("select Designation impossible =>".mysql_error());

                                                                                    $designation = mysql_fetch_assoc($resD);

                                                                                        ?>  

                                                                                            <input class="quantite form-control" onkeyup="modif_QuantiteET(this.value,<?= $ligne['numligne']; ?>,<?= $pagnet['idPagnet']; ?>)" id="ligne<?= $ligne['numligne']; ?>" <?= (@in_array($ligne['numligne'],$ligneIns)) ? 'style="background-color:#FC8080;' : '' ; ?>

                                                                                            size="4"  type="number" id="quantite<?= $ligne['numligne']; ?>" value="<?= $ligne['quantite']; ?>">     

                                                                                        <?php

                                                                                }

                                                                            }

                                                                            if($ligne['classe']==1) { ?> 

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

                                                                                if($ligne['unitevente']==$designation['uniteStock']){

                                                                                    ?> 

                                                                                        <select name="uniteVente" class="form-control" onchange="modif_UniteStockET(this.value)" >

                                                                                            <?php

                                                                                                $reqP='SELECT * from  `'.$nomtableDesignation.'` where designation="'.$ligne['designation'].'"';

                                                                                                $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                                                                                if(mysql_num_rows($resP)){

                                                                                                    $produit = mysql_fetch_assoc($resP);

                                                                                                    echo '<option value="'.$produit["uniteStock"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§1">'.$produit["uniteStock"].'</option>';

                                                                                                    echo '<option value="Demi Gros§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§2">Demi Gros</option>';

                                                                                                    echo '<option value="Piece§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§1">Piece</option>';

                                                                                                }

                                                                                            ?>    

                                                                                        </select>

                                                                                    <?php 

                                                                                }

                                                                                else{

                                                                                    ?>

                                                                                            <select name="uniteVente" class="form-control" onchange="modif_UniteStockET(this.value)" >

                                                                                            <?php

                                                                                                $reqP='SELECT * from  `'.$nomtableDesignation.'` where designation="'.$ligne['designation'].'"';

                                                                                                $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                                                                                if(mysql_num_rows($resP)){

                                                                                                    $produit = mysql_fetch_assoc($resP);

                                                                                                    $demi=$ligne['quantite']*2;

                                                                                                    if($produit['nbreArticleUniteStock']==$demi){

                                                                                                        echo '<option value="Demi Gros§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§2">Demi Gros</option>';

                                                                                                        echo '<option value="Piece§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§1">Piece</option>';

                                                                                                    }

                                                                                                    else{

                                                                                                        echo '<option value="Piece§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§1">Piece</option>';

                                                                                                        echo '<option value="Demi Gros§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§2">Demi Gros</option>';

                                                                                                    }

                                                                                                    if(($produit["uniteStock"]!='')&&($produit["uniteStock"]!=null)){

                                                                                                        echo '<option value="'.$produit["uniteStock"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§1">'.$produit["uniteStock"].'</option>';

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

                                                                        else if($ligne['classe']==6){?>

                                                                            <input class="form-control" style="width: 70%"  type="text" <?php echo  "id=especes".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['unitevente'].""; ?>

                                                                                onkeyup="modif_Espece(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" >

                                                                            <?php

                                                                        }

                                                                        else  if($ligne['classe']!=2 && $ligne['classe']!=0 && $ligne['classe']!=6){?>

                                                                            <?php echo $ligne['unitevente']; ?>

                                                                            <?php 

                                                                        }

                                                                    ?> 

                                                                </td>

                                                                <td>

                                                                    <input <?php echo  "id=prixUniteStock".$ligne['numligne'].""; ?> class="prixunitevente form-control" size="6"  type="number" <?php echo  "id=prixunitevente".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['prixunitevente'].""; ?>

                                                                                onkeyup="modif_Prix(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" >

                                                                </td>

                                                                <td>

                                                                    <?php if ($ligne['classe']==0): ?>

                                                                        <select  class="form-control" onchange="modif_Depot(this.value)" >

                                                                            <?php

                                                                                $reqEp="SELECT * from  `".$nomtableEntrepot."` e

                                                                                where idEntrepot='".$ligne['idEntrepot']."' ";

                                                                                $resEp=mysql_query($reqEp) or die ("select stock impossible =>".mysql_error());

                                                                                $entrepot = mysql_fetch_assoc($resEp);

                                                                                echo '<option selected value="'.$entrepot["idEntrepot"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'">'.$entrepot["nomEntrepot"].'</option>';

                                                                                /***Debut verifier si la ligne du produit existe deja ***/

                                                                                $sqlEl="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$pagnet['idPagnet']."' and idDesignation='".$ligne['idDesignation']."' and idEntrepot!='".$ligne['idEntrepot']."'";

                                                                                $resEl = mysql_query($sqlEl) or die ("persoonel requête 2".mysql_error());

                                                                                /***Debut verifier si la ligne du produit existe deja ***/

                                                                                if(!mysql_num_rows($resEl)){

                                                                                    $reqDp="SELECT * from  `".$nomtableEntrepot."` e

                                                                                    INNER JOIN `".$nomtableEntrepotStock."` s ON s.idEntrepot = e.idEntrepot

                                                                                    where s.designation='".$ligne['designation']."' AND s.quantiteStockCourant>0 AND e.idEntrepot<>'".$entrepot["idEntrepot"]."' ORDER BY quantiteStockCourant DESC ";

                                                                                    $resDp=mysql_query($reqDp) or die ("select stock impossible =>".mysql_error());

                                                                                    while ($depot = mysql_fetch_assoc($resDp)) {

                                                                                        echo '<option value="'.$depot["idEntrepot"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'">'.$depot["nomEntrepot"].'</option>';

                                                                                    }

                                                                                }

                                                                            ?>

                                                                        </select> 

                                                                    <?php endif; ?>

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

                                                                                        <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                        <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

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

                            else {?>

                                <div class="panel panel-danger">

                                    <div class="panel-heading">

                                        <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                            <div class="right-arrow pull-right">+</div>

                                            <a class="row"href="#">

                                                <span class="noImpr col-md-2 col-sm-2 col-xs-12"> Panier <?php echo ': En cours ...'; ?></span>

                                                <span class="noImpr col-md-3 col-sm-3 col-xs-12"> Date: <?php echo $pagnet['datepagej']." ".$pagnet['heurePagnet']; ?> </span>

                                                <!-- <span class="spanDate noImpr col-md-2 col-sm-2 col-xs-12">Heure: <?php // $pagnet['heurePagnet']; ?></span> -->

                                                <?php	$sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";

                                                        $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                                                        $TotalT = mysql_fetch_array($resT) ;

                                                        ?>

                                                <?php if($_SESSION['Pays']=='Canada'){   ?> 

                                                    <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Sous-Total : <span <?php echo  "id=somme_Apayer".$pagnet['idPagnet'] ; ?> ><?php echo $TotalT[0]; ?> </span></span>

                                                    <span class="noImpr col-md-3 col-sm-3 col-xs-12" > Total : <span <?php echo  "id=somme_ApayerT".$pagnet['idPagnet'] ; ?> ><?php echo ( $TotalT[0] + (($TotalT[0] * 5)/100) + (($TotalT[0] * 9.975)/100) ) ; ?> </span></span>

                                                <?php }

                                                else{   ?>

                                                    <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total panier: <span <?php echo  "id=somme_Total".$pagnet['idPagnet']; ?> ><?php echo $TotalT[0]; ?>  </span></span>

                                                    <span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total à payer: <span <?php echo  "id=somme_Apayer".$pagnet['idPagnet'] ; ?> ><?php echo ($TotalT[0] - (($TotalT[0] * $pagnet['taux']) / 100)); ?> </span></span>                 

                                                <?php } ?>

                                            </a>

                                        </h4>

                                    </div>

                                    <div <?php echo  "id=pagnet".$pagnet['idPagnet']."" ; ?> class="panel-collapse collapse in"  >

                                        <div class="panel-body" >

                                            <div class="cache_btn_Terminer row">

                                                <!--*******************************Debut Ajouter Ligne****************************************-->

                                                <form  class="form-inline noImpr ajouterProdForm col-md-4 col-sm-4 col-xs-12" id="ajouterProdFormEt<?= $pagnet['idPagnet'];?>" onsubmit="return false" >

                                                    <input type="text" class="inputbasic form-control codeBarreLigneEt" name="codeBarre" id="panier_<?= $pagnet['idPagnet'];?>" style="width:100%;" autofocus="" autocomplete="off"  required />

                                                    <input type="hidden" name="idPagnet" id="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?>>

                                                        <!-- <span id="reponseV"></span> -->

                                                    <!-- <button type="submit" name="btnEnregistrerCodeBarre"

                                                    id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs " ><span class="glyphicon glyphicon-plus" ></span>

                                                    </button> -->

                                                </form>

                                                <!--*******************************Fin Ajouter Ligne****************************************-->

                                                <div class="col-md-8 col-sm-8 col-xs-12 content2">

                                                    <!--*******************************Debut Terminer Pagnet****************************************-->

                                                    <form class="form-inline noImpr" id="factForm" method="post">

                                                        <input type="number" step="0.1" <?php echo "id=val_remise".$pagnet['idPagnet'] ; ?> name="remise" onkeyup="modif_Remise(this.value, <?php echo  $pagnet['idPagnet']; ?>)"

                                                        class="remise form-control col-md-2 col-sm-2 col-xs-3" placeholder="Remise....">

                                                        

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

                                                            <button type="submit" style="" name="btnImprimerFacture" <?php echo  "id=btnImprimerFacture".$pagnet['idPagnet']."" ; ?> class="btn btn-success  terminer" data-toggle="modal" ><span class="glyphicon glyphicon-ok"></span></button>

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

                                            <table  <?php echo  "id=tablePanier".$pagnet['idPagnet']."" ; ?> class="tabPanier table"  width="100%" >

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

                                                        $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

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

                                                                        <?php echo $ligne['designation']; 

                                                                    }

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

                                                                            

                                                                                    if($ligne['unitevente']==$designation['uniteDetails']){

                                                                                        ?>  

                                                                                            <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?> <?= (@in_array($ligne['numligne'],$ligneIns)) ? 'style="background-color:#FC8080;' : '' ; ?>

                                                                                        onkeyup="modif_QuantiteR(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  $designation['nbreArticleUniteStock']; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 25%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                                >

                                                                                        <?php

                                                                                    }

                                                                                    else if($ligne['unitevente']==$designation['uniteStock']){

                                                                                        ?>  

                                                                                            <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?> <?= (@in_array($ligne['numligne'],$ligneIns)) ? 'style="background-color:#FC8080;' : '' ; ?>

                                                                                        onkeyup="modif_QuantiteR(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>,<?php echo  '1'; ?>,<?php echo  $stock['quantiteStockCourant']; ?>)" style="width: 25%" size="3" type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                                >

                                                                                    <?php }

                                                                                }

                                                                                else{

                                                                                    $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

                                                                                    $resD=mysql_query($sqlD) or die ("select Designation impossible =>".mysql_error());

                                                                                    $designation = mysql_fetch_assoc($resD);

                                                                                        ?>  

                                                                                            <input class="quantite form-control" <?php echo  "id=ligne".$ligne['numligne'].""; ?> <?= (@in_array($ligne['numligne'],$ligneIns)) ? 'style="background-color:#FC8080;' : '' ; ?>

                                                                                        onkeyup="modif_QuantiteSD(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" size="4"  type="number" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>

                                                                                                                > 

                                                                                        <?php

                                                                                }

                                                                            }

                                                                            if($ligne['classe']==1) { ?> 

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

                                                                                if($ligne['unitevente']==$designation['uniteStock']){

                                                                                    ?> 

                                                                                        <select name="uniteVente" class="form-control" onchange="modif_UniteStockET(this.value)" >

                                                                                            <?php

                                                                                                $reqP='SELECT * from  `'.$nomtableDesignation.'` where designation="'.$ligne['designation'].'"';

                                                                                                $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                                                                                if(mysql_num_rows($resP)){

                                                                                                    $produit = mysql_fetch_assoc($resP);

                                                                                                    echo '<option value="'.$produit["uniteStock"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§1">'.$produit["uniteStock"].'</option>';

                                                                                                    echo '<option value="Piece§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§1">Piece</option>';

                                                                                                }

                                                                                            ?>    

                                                                                        </select>

                                                                                    <?php 

                                                                                }

                                                                                else{

                                                                                    ?>

                                                                                            <select name="uniteVente" class="form-control" onchange="modif_UniteStockET(this.value)" >

                                                                                            <?php

                                                                                                $reqP='SELECT * from  `'.$nomtableDesignation.'` where designation="'.$ligne['designation'].'"';

                                                                                                $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());

                                                                                                if(mysql_num_rows($resP)){

                                                                                                    $produit = mysql_fetch_assoc($resP);

                                                                                                    echo '<option value="Piece§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§1">Piece</option>';

                                                                                                    if(($produit["uniteStock"]!='')&&($produit["uniteStock"]!=null)){

                                                                                                        echo '<option value="'.$produit["uniteStock"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'§1">'.$produit["uniteStock"].'</option>';

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

                                                                        else if($ligne['classe']==6){?>

                                                                            <input class="form-control" style="width: 70%"  type="text" <?php echo  "id=especes".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['unitevente'].""; ?>

                                                                                onkeyup="modif_Espece(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" >

                                                                            <?php

                                                                        }

                                                                        else  if($ligne['classe']!=2 && $ligne['classe']!=0 && $ligne['classe']!=6){?>

                                                                            <?php echo $ligne['unitevente']; ?>

                                                                            <?php 

                                                                        }

                                                                    ?> 

                                                                </td>

                                                                <td>

                                                                    <input <?php echo  "id=prixUniteStock".$ligne['numligne'].""; ?> class="prixunitevente form-control" size="6"  type="number" <?php echo  "id=prixunitevente".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['prixunitevente'].""; ?>

                                                                                onkeyup="modif_Prix(this.value,<?php echo  $ligne['numligne']; ?>,<?php echo  $pagnet['idPagnet']; ?>)" >

                                                                </td>

                                                                <td>

                                                                    <?php if ($ligne['classe']==0): ?>

                                                                        <select  class="form-control" onchange="modif_Depot(this.value)" >

                                                                            <?php

                                                                                $reqEp="SELECT * from  `".$nomtableEntrepot."` e

                                                                                where idEntrepot='".$ligne['idEntrepot']."' ";

                                                                                $resEp=mysql_query($reqEp) or die ("select stock impossible =>".mysql_error());

                                                                                $entrepot = mysql_fetch_assoc($resEp);

                                                                                echo '<option selected value="'.$entrepot["idEntrepot"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'">'.$entrepot["nomEntrepot"].'</option>';

                                                                                /***Debut verifier si la ligne du produit existe deja ***/

                                                                                $sqlEl="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$pagnet['idPagnet']."' and idDesignation='".$ligne['idDesignation']."' and idEntrepot!='".$ligne['idEntrepot']."'";

                                                                                $resEl = mysql_query($sqlEl) or die ("persoonel requête 2".mysql_error());

                                                                                /***Debut verifier si la ligne du produit existe deja ***/

                                                                                if(!mysql_num_rows($resEl) && $userAcces['idEntrepot']==0){

                                                                                    $reqDp="SELECT * from  `".$nomtableEntrepot."` e

                                                                                    INNER JOIN `".$nomtableEntrepotStock."` s ON s.idEntrepot = e.idEntrepot

                                                                                    where s.designation='".$ligne['designation']."' AND e.idEntrepot<>'".$entrepot["idEntrepot"]."' ORDER BY quantiteStockCourant DESC ";

                                                                                    $resDp=mysql_query($reqDp) or die ("select stock impossible =>".mysql_error());

                                                                                    while ($depot = mysql_fetch_assoc($resDp)) {

                                                                                        echo '<option value="'.$depot["idEntrepot"].'§'.$ligne['numligne'].'§'.$pagnet['idPagnet'].'">'.$depot["nomEntrepot"].'</option>';

                                                                                    }

                                                                                }

                                                                            ?>

                                                                        </select> 

                                                                    <?php endif; ?>

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

                                                                                        <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                        <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                        <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

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

                        ?>

                    <?php   } ?>

                <!-- Fin Boucle while concernant les Paniers en cours (1 aux maximum) -->



                <!-- Debut Boucle while concernant les Paniers Vendus -->

                    <?php $n=$nbPaniers - (($currentPage * 10) - 10); 

                        while ($bons = mysql_fetch_assoc($resP1)) {   ?>

                        <?php	$idmax=mysql_result($resA,0); ?>

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

                                        if(($ligne['classe']==0 || $ligne['classe']==1 || $ligne['classe']==2 || $ligne['classe']==5) && ($pagnet['type']==0 || $pagnet['type']==11 || $pagnet['type']==30)){?>

                                            <div class="panel <?= ($pagnet['type']==30) ? 'panel-info' : 'panel-success'?>">

                                                <div class="panel-heading">

                                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                                        <div class="right-arrow pull-right">+</div>

                                                        <a href="#"> <?= ($pagnet['type']==11) ? 'En ligne' : 'Panier';?> <?php echo $n; ?>

                                                            <span class="spanDate noImpr"> </span>

                                                            <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>

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

                                                            <?php if($_SESSION['Pays']=='Canada'){   ?> 

                                                                <span class="spanTotal noImpr" >Sous-Total: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                                <span class="spanTotal noImpr" >Total : <span ><?php echo ( $TotalP[0] + (($TotalP[0] * 5)/100) + (($TotalP[0] * 9.975)/100) ); ?> </span></span>

                                                            <?php }

                                                            else{   ?>

                                                                <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                                                <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                            <?php } ?> 

                                                            <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

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

                                                                                <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>

                                                                            </div>

                                                                        </form>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                
                                                        <!--*******************************Debut Facture****************************************-->


                                                        <button class="btn btn-warning  pull-right" style="margin-right:20px;" <?php echo  "id=btn_facture".$pagnet['idPagnet'] ; ?>>

                                                        Facture

                                                        </button>


                                                        <?php if ($_SESSION['adresseB']=='Bignona'){ ?>

                                                            <script>
                                                                $(document).ready(function() { 
                                                                    $("#btn_facture<?php echo $pagnet['idPagnet']; ?>").on( "click", function (e){
                                                                        $("#factureBL<?php echo $pagnet['idPagnet']; ?>").submit();
                                                                        $("#facture<?php echo $pagnet['idPagnet']; ?>").submit();
                                                                    });
                                                                });
                                                            </script>

                                                        <?php } else { ?>

                                                            <script>
                                                                $(document).ready(function() { 
                                                                    $("#btn_facture<?php echo $pagnet['idPagnet']; ?>").on( "click", function (e){
                                                                        $("#facture<?php echo $pagnet['idPagnet']; ?>").submit();
                                                                    });
                                                                });
                                                            </script>

                                                        <?php } ?>

                                                        
                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=factureBL".$pagnet['idPagnet'] ; ?>  style="margin-right:20px;"

                                                            method="post" action="pdfFactureBL.php" >

                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                        </form>

                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?> target="_blank" style="margin-right:20px;"

                                                            method="post" action="pdfFacture.php" >

                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                        </form>

                                                        <!--*******************************Fin Facture****************************************-->

                                                        

                                                            <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                                Ticket de Caisse

                                                            </button>
                                                        

                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                            method="post" action="barcodeFacture.php" >

                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                        </form>


                                                        <!--*******************************Debut Editer Pagnet****************************************-->


                                                        <div id="factForm">
                                                            <input type="text" placeholder="Changer le client" class="client clientInput form-control col-md-2 col-sm-2 col-xs-3 alert alert-info" data-type="simple" data-idPanier="<?= $pagnet['idPagnet'];?>" name="clientInput" id="clientInput<?= $pagnet['idPagnet'];?>" autocomplete="off" />&ensp;<span class="btn btn-success btn-sm glyphicon glyphicon-ok" id="changedOk<?= $pagnet['idPagnet'];?>" style="display:none"></span>
                                                        </div>
                                                        <?php if ($_SESSION['proprietaire']==1 && $_SESSION['editionPanier']==1){ ?>
 
                                                            <button type="button" style="margin-right:20px;" class="btn btn-primary pull-right modeEditionBtnET btn_disabled_after_click" id="edit-<?= $pagnet['idPagnet'] ; ?>">

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

                                                                    $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";

                                                                    $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

                                                                    while ($ligne = mysql_fetch_assoc($res8)) {?>

                                                                        <tr>

                                                                            <td class="designation">
                                                                                <?php echo $ligne['designation']; ?>
                                                                                <?php if (@$ligne['numeroChassis']) {
                                                                                    # code...
                                                                                    echo '\n Numéro Châssis'.$ligne['numeroChassis'];
                                                                                    echo '\n Numéro moteur'.$ligne['numeroMoteur'];
                                                                                } ?>
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

                                                                                <?php

                                                                                    $reqEp="SELECT * from  `".$nomtableEntrepot."` e

                                                                                    where idEntrepot='".$ligne['idEntrepot']."' ";

                                                                                    $resEp=mysql_query($reqEp) or die ("select stock impossible =>".mysql_error());

                                                                                    $entrepot = mysql_fetch_assoc($resEp);

                                                                                    echo $entrepot['nomEntrepot']; 

                                                                                ?>

                                                                            </td>

                                                                            <td>

                                                                                <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

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

                                                                                                    <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                                    <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

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

                                                                <div>

                                                                    <?php if ($pagnet['livreur']!='' && $pagnet['livreur']!=null): ?>

                                                                    <?php echo  '<br/>Livreur : '.$pagnet['livreur']; ?>

                                                                    <?php endif; ?>

                                                                </div>

                                                            </div>

                                                        <!--*******************************Fin Total Facture****************************************-->

                                                                

                                                    </div>

                                                </div>

                                            </div>

                                            <?php

                                        }

                                        else if(($ligne['classe']==6 || $ligne['classe']==9)  && $pagnet['type']==0){?>

                                            <div class="panel panel-default">

                                                <div class="panel-heading">

                                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#pagnet".$pagnet['idPagnet']."" ; ?>  class="panel-title expand">

                                                        <div class="right-arrow pull-right">+</div>

                                                        <a href="#"><?= ($ligne['prixtotal']<0) ? "Avoir" : "Bon" ; ?>

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

                                                            <span class="spanTotal noImpr" >Total panier: <span ><?php echo abs($TotalT[0]); ?> </span></span>

                                                            <span class="spanTotal noImpr" >Total à payer: <span ><?php echo abs($TotalP[0]); ?> </span></span>

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

                                                                                <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>

                                                                            </div>

                                                                        </form>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                                        

                                                            <button class="btn btn-warning  pull-right" style="margin-right:20px;" <?php echo  "id=btn_facture".$pagnet['idPagnet'] ; ?>>

                                                            Facture

                                                            </button>

                                                        

                                                        <?php if ($_SESSION['adresseB']=='Bignona'){ ?>

                                                            <script>
                                                                $(document).ready(function() { 
                                                                    $("#btn_facture<?php echo $pagnet['idPagnet']; ?>").on( "click", function (e){
                                                                        $("#factureBL<?php echo $pagnet['idPagnet']; ?>").submit();
                                                                        $("#facture<?php echo $pagnet['idPagnet']; ?>").submit();
                                                                    });
                                                                });
                                                            </script>

                                                        <?php } else { ?>

                                                            <script>
                                                                $(document).ready(function() { 
                                                                    $("#btn_facture<?php echo $pagnet['idPagnet']; ?>").on( "click", function (e){
                                                                        $("#facture<?php echo $pagnet['idPagnet']; ?>").submit();
                                                                    });
                                                                });
                                                            </script>

                                                        <?php } ?>

                                                        
                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=factureBL".$pagnet['idPagnet'] ; ?>  style="margin-right:20px;"

                                                            method="post" action="pdfFactureBL.php" >

                                                            <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                        </form>

                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?> target="_blank" style="margin-right:20px;"

                                                            method="post" action="pdfFacture.php" >

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

                                                                            <?php echo  "Montant"; ?>

                                                                            </td>

                                                                            <td class="unitevente ">

                                                                                <?php echo $ligne['unitevente']; ?>

                                                                            </td>

                                                                            <td>

                                                                                <?php echo  abs($ligne['prixunitevente']); ?>  <span class="factureFois" ></span>

                                                                            </td>

                                                                            <td>

                                                                                <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

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

                                                                                                    <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                                    <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >

                                                                                                    <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

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

                                                            <div  class="col-sm-11 ">

                                                                <div>

                                                                    <?php echo  '********************************************* <br/>'; ?>

                                                                    <?php echo  'TOTAL : '.abs($pagnet['totalp']).'<br/>'; ?>

                                                                </div>

                                                                <div>

                                                                    <?php if($pagnet['remise']!=0 && $pagnet['remise']>0): ?>

                                                                        <?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>

                                                                    <?php endif; ?>

                                                                </div>

                                                                <div>

                                                                    <?php echo  '<b>Net à payer : '.abs($pagnet['apayerPagnet']).'</b><br/>'; ?>

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
                                                                        <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvBon".$pagnet['idPagnet'] ; ?> onclick="imageUpBon(<?php echo $pagnet['idPagnet']; ?>,<?php echo $pagnet['image']; ?>)" 	 />
                                                                    <?php }
                                                                        else { 
                                                                    ?>
                                                                        <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvBon".$pagnet['idPagnet'] ; ?> onclick="imageUpBon(<?php echo $pagnet['idPagnet']; ?>,<?php echo $pagnet['image']; ?>)" 	 />
                                                                    <?php } ?>
                                                                <?php }
                                                                    else { 
                                                                ?>
                                                                    <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" <?php echo "data-target=#imageNvBon".$pagnet['idPagnet'] ; ?> onclick="imageUpBon(<?php echo $pagnet['idPagnet']; ?>,<?php echo $pagnet['image']; ?>)" 	 />
                                                                <?php } ?>
                                                            </div>

                                                        <!--*******************************Fin Total Facture****************************************-->

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="modal fade" <?php echo  "id=imageNvBon".$pagnet['idPagnet'] ; ?> role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header" style="padding:35px 50px;">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Bon : <b>#<?php echo  $pagnet['idPagnet'] ; ?></b></h4>
                                                        </div>
                                                        <form   method="post" enctype="multipart/form-data">
                                                            <div class="modal-body" style="padding:40px 50px;">
                                                                <input  type="text" style="display:none" name="idPagnet" id="idBon_Upd_Nv" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> />
                                                                <div class="form-group" style="text-align:center;" >
                                                                    <?php 
                                                                        if($pagnet['image']!=null && $pagnet['image']!=' '){ 
                                                                            $format=substr($pagnet['image'], -3);
                                                                            ?>
                                                                                <input type="file" name="file" value="<?php echo  $pagnet['image']; ?>" accept="image/*" id="input_file_Bon<?php echo  $pagnet['idPagnet']; ?>" onchange="showPreviewBon(event,<?php echo  $pagnet['idPagnet']; ?>);"/><br />
                                                                                <?php if($format=='pdf'){ ?>
                                                                                    <iframe id="output_pdf_Bon<?php echo  $pagnet['idPagnet']; ?>" src="./PiecesJointes/<?php echo  $pagnet['image']; ?>" width="100%" height="500px"></iframe>
                                                                                    <img style="display:none;" width="500px" height="500px" id="output_image_Bon<?php echo  $pagnet['idPagnet'];  ?>"/>
                                                                                <?php }
                                                                                else { ?>
                                                                                    <img  src="./PiecesJointes/<?php echo  $pagnet['image']; ?>" width="500px" height="500px" id="output_image_Bon<?php echo  $pagnet['idPagnet']; ?>"/>
                                                                                    <iframe id="output_pdf_Bon<?php echo  $pagnet['idPagnet'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                                <?php } ?>
                                                                            <?php 
                                                                        }
                                                                        else{ ?>
                                                                            <input type="file" name="file" accept="image/*" id="input_file_Bon<?php echo  $pagnet['idPagnet']; ?>" id="cover_image" onchange="showPreviewBon(event,<?php echo  $pagnet['idPagnet']; ?>);"/><br />
                                                                            <img  style="display:none;" width="500px" height="500px" id="output_image_Bon<?php echo  $pagnet['idPagnet']; ?>"/>
                                                                            <iframe id="output_pdf_Bon<?php echo  $pagnet['idPagnet'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                        <?php
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                    <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgBon" id="btn_upload_Bon<?php echo  $pagnet['idPagnet']; ?>" >
                                                                        <span class="glyphicon glyphicon-upload"></span> Enregistrer
                                                                    </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php

                                        }

                                        else if(($ligne['classe']==0 || $ligne['classe']==1 || $ligne['classe']==2 || $ligne['classe']==5) && $pagnet['type']==1 ){?>

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



                                                                    $sql="SELECT * from `aaa-utilisateur` where idutilisateur='".$pagnet['iduser']."' ";

                                                                    $res=mysql_query($sql);

                                                                    $user=mysql_fetch_array($res);

                                                            ?>

                                                            <?php if($_SESSION['Pays']=='Canada'){   ?> 

                                                                <span class="spanTotal noImpr" >Sous-Total: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                                <span class="spanTotal noImpr" >Total : <span ><?php echo ( $TotalP[0] + (($TotalP[0] * 5)/100) + (($TotalP[0] * 9.975)/100) ); ?> </span></span>

                                                            <?php }

                                                            else{   ?>

                                                                <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                                                <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                            <?php } ?> 

                                                            <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

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



                                                            <?php if ($_SESSION['importExp']==1){ ?>

                                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                method="post" action="pdfFactureJour.php" >

                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                            </form>

                                                            <?php }

                                                            else {

                                                            ?>

                                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                method="post" action="pdfFacture.php" >

                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                            </form>

                                                            <?php } ?>

                                                        <!--*******************************Fin Facture****************************************-->

                                                        

                                                        

                                                            <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                                Ticket de Caisse

                                                            </button>


                                                        

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

                                                                    <th>Depot</th>

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

                                                                                <?php

                                                                                    $reqEp="SELECT * from  `".$nomtableEntrepot."` e

                                                                                    where idEntrepot='".$ligne['idEntrepot']."' ";

                                                                                    $resEp=mysql_query($reqEp) or die ("select stock impossible =>".mysql_error());

                                                                                    $entrepot = mysql_fetch_assoc($resEp);

                                                                                    echo $entrepot['nomEntrepot']; 

                                                                                ?>

                                                                            </td>

                                                                            <td>

                                                                                <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

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

                                                                                                    <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                                    <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

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

                                        else if(($ligne['classe']==0 || $ligne['classe']==1 || $ligne['classe']==2 || $ligne['classe']==5) && $pagnet['type']==2 ){?>

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

    

                                                                    $sql="SELECT * from `aaa-utilisateur` where idutilisateur='".$pagnet['iduser']."' ";

                                                                    $res=mysql_query($sql);

                                                                    $user=mysql_fetch_array($res);

                                                            ?>

                                                            <?php if($_SESSION['Pays']=='Canada'){   ?> 

                                                                <span class="spanTotal noImpr" >Sous-Total: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                                <span class="spanTotal noImpr" >Total : <span ><?php echo ( $TotalP[0] + (($TotalP[0] * 5)/100) + (($TotalP[0] * 9.975)/100) ); ?> </span></span>

                                                            <?php }

                                                            else{   ?>

                                                                <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                                                <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                            <?php } ?> 

                                                            <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

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

                                                            <button type="submit" disabled="true" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$pagnet['idPagnet'] ; ?>>

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

                                                          

                                                                <button class="btn btn-warning  pull-right" disabled="true" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$pagnet['idPagnet'] ;?>').submit();">

                                                                Facture

                                                                </button>
    

                                                            <?php if ($_SESSION['importExp']==1){ ?>

                                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                method="post" action="pdfFactureJour.php" >

                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                            </form>

                                                            <?php }

                                                            else {

                                                            ?>

                                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$pagnet['idPagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                                method="post" action="pdfFacture.php" >

                                                                <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                            </form>

                                                            <?php } ?>

                                                        <!--*******************************Fin Facture****************************************-->

                                                        

                                                            <button  class="btn btn-info  pull-right" disabled="true" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                                Ticket de Caisse

                                                            </button>

                                                        
                                                        

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

                                                                    <th>Depot</th>

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

                                                                                <?php

                                                                                    $reqEp="SELECT * from  `".$nomtableEntrepot."` e

                                                                                    where idEntrepot='".$ligne['idEntrepot']."' ";

                                                                                    $resEp=mysql_query($reqEp) or die ("select stock impossible =>".mysql_error());

                                                                                    $entrepot = mysql_fetch_assoc($resEp);

                                                                                    echo $entrepot['nomEntrepot']; 

                                                                                ?>

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

                                                                                                    <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

                                                                                                    <input type="hidden" name="designation" <?php echo  "value=".$ligne['designation'].""; ?> >

                                                                                                    <input type="hidden" name="numligne" <?php echo  "value=".$ligne['numligne'].""; ?> >

                                                                                                    <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >

                                                                                                    <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                                    <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

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

                                                                <?php if($_SESSION['compte']==1):?>

                                                                <div>

                                                                    <?php if($pagnet['idCompte']!=0 && $pagnet['idCompte']>0): 

                                                                            $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$pagnet['idCompte'];

                                                                            $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());

                                                                            $cpt = mysql_fetch_array($resPay2);

                                                                        ?>

                                                                        <?php  echo 'Compte :'. $cpt['nomCompte'].'<br/>';?>

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

                                        else if(($ligne['classe']==0 || $ligne['classe']==1 || $ligne['classe']==2 || $ligne['classe']==5) && $pagnet['type']==10 ){?>

                                            <div class="panel panel-warning">

                                                <div class="panel-heading">

                                                    <?php if($pagnet['terminerProforma']==1 && $pagnet['validerProforma']==0) { ?>

                                                        <span id="intro" style="margin-top: -20px;margin-right: -15px;background: green;color: white;" class="badge bg-success glyphicon glyphicon-ok intro"> </span>

                                                    <?php } ?>

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



                                                                    $sql="SELECT * from `aaa-utilisateur` where idutilisateur='".$pagnet['iduser']."' ";

                                                                    $res=mysql_query($sql);

                                                                    $user=mysql_fetch_array($res);

                                                                    

                                                            ?>

                                                            <?php if($_SESSION['Pays']=='Canada'){   ?> 

                                                                <span class="spanTotal noImpr" >Sous-Total: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                                <span class="spanTotal noImpr" >Total : <span ><?php echo ( $TotalP[0] + (($TotalP[0] * 5)/100) + (($TotalP[0] * 9.975)/100) ); ?> </span></span>

                                                            <?php }

                                                            else{   ?>

                                                                <span class="spanTotal noImpr" >Total panier: <span ><?php echo $TotalT[0]; ?> </span></span>

                                                                <span class="spanTotal noImpr" >Total à payer: <span ><?php echo $TotalP[0]; ?> </span></span>

                                                            <?php } ?> 

                                                            <span class="spanDate noImpr"> Facture : #<?php echo $pagnet['idPagnet']; ?></span>

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
                                                        
                                                        <!--*******************************Debut Editer Pagnet****************************************-->
                                                        <?php if ($_SESSION['proprietaire']==1 && $_SESSION['editionPanier']==1){ ?>
                                                            <button type="button" class="btn btn-primary pull-left modeEditionBtnET btn_disabled_after_click" id="edit-<?= $pagnet['idPagnet'] ; ?>">
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

                                                        <!-- Proforma  -->
                                                        
                                                        <?php if($pagnet['validerProforma']==0) { ?>

                                                            <button type="button"<?= ($pagnet['terminerProforma']==0) ? "disabled" : "" ; ?>  class="btn btn-info pull-left" style="margin-left:20px;" data-toggle="modal" data-target="#msg_validerProforma_pagnet<?= $pagnet['idPagnet'] ; ?>">

                                                                Valider la facture

                                                            </button>

                                                            <?php } ?>

                                                            <div class="modal fade" id="msg_validerProforma_pagnet<?= $pagnet['idPagnet'] ; ?>" role="dialog">

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

                                                                            <button type="button" class="btn btn-default" data-dismiss="modal"> Non </button>

                                                                            <button type="button" name="btnFacture" class="btn btn-success btn_disabled_after_click btnValiderProforma" onclick="validerProforma(<?= $pagnet['idPagnet'] ; ?>)"> Oui </button>

                                                                        </div>
                                                                    </div>

                                                                </div>

                                                            </div>
                                                            <!-- Fin Proforma  -->

                                                           

                                                                <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_factEntreprise_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                    Facture Entreprise

                                                                </button>



                                                                <button type="submit" class="btn btn-warning pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_factClient_pagnet".$pagnet['idPagnet'] ; ?>>

                                                                    Facture Client

                                                                </button>

                                                            


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

                                                        

                                                            <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$pagnet['idPagnet'] ;?>').submit();">

                                                                Ticket de Caisse

                                                            </button>

                                                        
                                                        

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

                                                                    <th>Depot</th>

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

                                                                                <?php

                                                                                    $reqEp="SELECT * from  `".$nomtableEntrepot."` e

                                                                                    where idEntrepot='".$ligne['idEntrepot']."' ";

                                                                                    $resEp=mysql_query($reqEp) or die ("select stock impossible =>".mysql_error());

                                                                                    $entrepot = mysql_fetch_assoc($resEp);

                                                                                    echo $entrepot['nomEntrepot']; 

                                                                                ?>

                                                                            </td>

                                                                            <td>

                                                                                <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrnApres_ligne".$ligne['numligne'] ; ?>>

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

                                                                                                    <input type="hidden" name="unitevente" <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                                    <input type="hidden" name="prixunitevente" 	<?php echo  "value=".$ligne['prixunitevente'].""; ?> >

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

                                    $sqlT2="SELECT * FROM `".$nomtableVersement."` where idVersement='".$bon[1]."' AND idClient='".$idClient."' ";

                                    $resT2 = mysql_query($sqlT2) or die ("persoonel requête 2".mysql_error());

                                    $versement = mysql_fetch_assoc($resT2);

                                    if($versement!=null){?>

                                        <div style="padding-top : 2px;" class="panel panel-warning">

                                            <div class="panel-heading">

                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#versement".$versement['idVersement']."" ; ?>  class="panel-title expand">

                                                <div class="right-arrow pull-right">+</div>

                                                <a href="#"> Versement 

                                                    <span class="spanDate noImpr"> </span>

                                                    <span class="spanDate noImpr"> Date: <?php echo $versement['dateVersement']; ?> </span>

                                                    <span class="spanDate noImpr">Heure: <?php echo $versement['heureVersement']; ?></span>

                                                    <span class="spanDate noImpr">Montant: <?php echo ($versement['montant'] * $_SESSION['devise']).' '.$_SESSION['symbole']; ?></span>

                                                    <span class="spanDate noImpr"> Facture : #<?php echo $versement['idVersement']; ?></span>

                                                    <span class="spanDate noImpr"> <?php 

                                                        $sql="SELECT * from `aaa-utilisateur` where idutilisateur='".$versement['iduser']."' ";

                                                        $res=mysql_query($sql);

                                                        $user=mysql_fetch_array($res);

                                                        echo substr(strtoupper($user['prenom']),0,3); 

                                                        ?>

                                                    </span>

                                                </a>

                                                </h4>

                                            </div>

                                            <div class="panel-collapse collapse " <?php echo  "id=versement".$versement['idVersement']."" ; ?> >

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

                                                                        <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?>>

                                                                        <input type="hidden" name="montant" id="montant"  <?php echo  "value=".$versement['montant']."" ; ?>>

                                                                    </div>

                                                                    <div class="modal-footer">

                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                        <button type="submit" name="btnAnnulerVersement" class="btn btn-success">Confirmer</button>

                                                                    </div>

                                                                </form>

                                                            </div>

                                                        </div>

                                                    </div>



                                                    <button class="btn btn-warning  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'recu'.$versement['idVersement'] ;?>').submit();">

                                                            Recu

                                                            </button>



                                                    <form class="form-inline pull-right noImpr" <?php echo  "id=recu".$versement['idVersement'] ; ?>  target="_blank" style="margin-right:20px;"

                                                        method="post" action="pdfRecu.php" >

                                                        <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?> >

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

                                                                            if($versement['paiement']!=''){

                                                                                echo  $versement['paiement'] ;

                                                                            }

                                                                            else{

                                                                                echo 'versement';

                                                                            }



                                                                            if($_SESSION['compte']==1){

                                                                            echo  "<br /> Type paiement : " ;

                                                                                $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$versement['idCompte'];

                                                                                $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());

                                                                                $cpt = mysql_fetch_array($resPay2);

                                                                                // echo $cpt['nomCompte'];

                                                                                $retVal = ($cpt['nomCompte']) ? $cpt['nomCompte'] : '----' ;

                                                                                echo $retVal;

                                                                            }

                                                                            else{

                                                                                echo '<br /> Type paiement : Espèces';

                                                                            }

                                                                        ?>

                                                                    </td>

                                                                    <td>

                                                                        <?php echo  ($versement['montant'] * $_SESSION['devise']).' '.$_SESSION['symbole']; ?>

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

                                                                        <td><input type="number" class="form-control" value="<?php echo $versement['montant'];?>" name="montant" placeholder="Montant" required=""></td>

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

                                </p>

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

                            </div>

                            </div>

                    </div>

            </div>

        <!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->



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
                    <!-- </div>
                    <div class="modal-footer"> -->
                        <button type="button" id="closeInfo" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" name="btnAddInfoSup" class="btn btn-success btnAddInfoSup">Ajouter</button>
                    </div>
                </div>
            </div>
        </div>

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



    </div>

    <!-- Fin Container HTML -->

</header>

</body>

<!-- Fin Code HTML -->



