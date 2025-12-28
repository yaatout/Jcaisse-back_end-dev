<?php

session_start();

if(!$_SESSION['iduser']){

	header('Location:../index.php');

    }

    

require('connection.php');

require('declarationVariables.php');



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

    $dateString2=$jour.'-'.$mois.'-'.$annee;

/**Fin informations sur la date d'Aujourdhui **/


$idMutuelle=htmlspecialchars(trim($_GET['iDS']));

// $idMutuelle=htmlspecialchars(trim($_GET['iDS']));

// $sql0="SELECT * FROM `".$nomtableMutuelle."` where idMutuelle=".$idMutuelle."";

// $res0 = mysql_query($sql0) or die ("persoonel requête 3".mysql_error());

// $mutuelle = mysql_fetch_assoc($res0);


/***Debut compte qui fait le paiement ***/
if ($_SESSION['compte']==1) {
$sqlGetComptePay="SELECT * FROM `".$nomtableCompte."` where idCompte <> 2 and idCompte <> 3 ORDER BY idCompte";

$resPay = mysql_query($sqlGetComptePay) or die ("persoonel requête 2".mysql_error());
}


/**Debut Button Terminer Versement**/

    if (isset($_POST['btnEnregistrerInitialisation'])) {

        $montant=htmlspecialchars(trim($_POST['montantInitialiser']));

        $dateInitialiser=htmlspecialchars(trim($_POST['dateInitialiser']));



        $sql1="insert into `".$nomtableMutuellePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerMutuelle,apayerPagnet,idMutuelle,verrouiller) values('".$dateInitialiser."','".$heureString."',".$_SESSION['iduser'].",0,'".$montant."','".$montant."',0,'".$idMutuelle."',1)";

        $res1=mysql_query($sql1) or die ("insertion pagnier impossible =>".mysql_error());



        $sql2="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuelle=".$idMutuelle." AND verrouiller=1 AND apayerPagnet=0 AND apayerMutuelle=".$montant." AND totalp=".$montant." AND datepagej='".$dateInitialiser."' ";

        $res2 = mysql_query($sql2) or die ("persoonel requête 3".mysql_error());

        $M_init = mysql_fetch_assoc($res2);

        if($M_init!=null){

            $sql3="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idMutuellePagnet,classe)values('Initialisation',0,0,'Especes',".$montant.",1,".$montant.",".$M_init['idMutuellePagnet'].",9)";

            $res3=mysql_query($sql3) or die ("insertion pagnier impossible 7  =>".mysql_error() );

        }

    }

/**Fin Button Terminer Versement**/


/**Debut Button Annuler Imputation**/

    if (isset($_POST['btnAnnulerInitialisation']) ) {



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

/**Debut Button Annuler Ligne d'un Pagnet**/

    if (isset($_POST['btnRetourAvant'])) {



        $numligne=$_POST['numligne'];

        $forme=$_POST['forme'];



        //on fait la suppression de cette ligne dans la table ligne

        $sql3="DELETE FROM `".$nomtableLigne."` where numligne=".$numligne;

        $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());



    }

/**Fin Button Annuler Ligne d'un Pagnet**/


/**Debut Button Terminer Versement**/

    if (isset($_POST['btnEnregistrerVersement'])) {

        $montant=htmlspecialchars(trim($_POST['montant']));

        $paiement=htmlspecialchars($_POST['typeVersement']);

        $dateActualiser=htmlspecialchars(trim($_POST['dateActualiser']));

        $tab=explode("-",$dateActualiser);

        $dateVersement=$tab[2].'-'.$tab[1].'-'.$tab[0];

        $sql2="INSERT into `".$nomtableVersement."` (idMutuelle,paiement,montant,dateVersement,heureVersement,iduser) values(".$idMutuelle.",'".$paiement."',".$montant.",'".$dateVersement."','".$heureString."',".$_SESSION['iduser'].")";
        // var_dump($sql2);
        $res2=mysql_query($sql2) or die ("insertion Versement impossible =>".mysql_error());

        

        if (isset($_POST['compte'])) {

            $idCompteDebiter=htmlspecialchars(trim($_POST['compte']));

            # code...

            $operation='retrait';

            // $idCompte='2';

            $description="Versement fournisseur";

                                                                    

            $sqlGetV="SELECT * FROM `".$nomtableVersement."` ORDER BY idVersement DESC LIMIT 1";

            $resV = mysql_query($sqlGetV) or die ("persoonel requête 2".mysql_error());

            $idV= mysql_fetch_array($resV);

            // var_dump($idCompte);

            // $description=$refTransf;

            // $newMontant=$compte['montantCompte']+$montantTransfert;



            $sql8="UPDATE `".$nomtableVersement."`  set  idCompte=".$idCompteDebiter." where  idVersement=".$idV['idVersement'];

            //var_dump($sql7);

            $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());



            $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$montant." where  idCompte=".$idCompteDebiter;

            //var_dump($sql7);

            $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());



            $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,idUser) values(".$montant.",'".$operation."',".$idCompteDebiter.",'".$description."','".$dateHeures."','".$dateHeures."',".$idV['idVersement'].",".$_SESSION['iduser'].")";

            //var_dump($sql6);

            $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

        }



    }

/**Fin Button Terminer Versement**/

/**Debut Button Annuler Versement**/

    if (isset($_POST['btnAnnulerVersement'])) {

        $idVersement=htmlspecialchars(trim($_POST['idVersement']));

        $montant=htmlspecialchars(trim($_POST['montant']));



        //on fait la suppression de cette Versement

        $sql3="DELETE FROM `".$nomtableVersement."` where idVersement=".$idVersement;

        $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());



        if (isset($_POST['idCompte'])) {

            # code...

            $idCompte=htmlspecialchars(trim($_POST['idCompte']));

            $sql="UPDATE `".$nomtableComptemouvement."` set  annuler='1' where  idVersement=".$idVersement;

            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());



            $sql2="UPDATE `".$nomtableCompte."` set  montantCompte=montantCompte + '".$montant."' where  idCompte=".$idCompte."";

            $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());

        }

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

        $sql0="SELECT * FROM `".$nomtableMutuelle."` where idMutuelle=".$idMutuelle."";

        $res0 = mysql_query($sql0) or die ("persoonel requête 3".mysql_error());

        $mutuelle = mysql_fetch_assoc($res0);



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

                    SELECT m.idClient FROM `".$nomtableMutuellePagnet."` m where m.idMutuelle='".$idMutuelle."' AND m.verrouiller=1 AND m.type=0 AND (CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')

                        UNION ALL

                    SELECT v.idClient FROM `".$nomtableVersement."` v where v.idMutuelle='".$idMutuelle."' AND (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2))  BETWEEN '".$dateDebut."' AND '".$dateFin."')

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

                    SELECT CONCAT(SUBSTR(m.datepagej,7, 4),'',SUBSTR(m.datepagej,4, 2),'',SUBSTR(m.datepagej,1, 2),'',m.heurePagnet,'+',m.idMutuellePagnet,'+1') AS codePagnet FROM `".$nomtableMutuellePagnet."` m where m.idMutuelle='".$idMutuelle."' AND m.verrouiller=1 AND m.type=0 AND (CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')

                        UNION ALL

                    SELECT CONCAT(SUBSTR(v.dateVersement,7, 4),'',SUBSTR(v.dateVersement,4, 2),'',SUBSTR(v.dateVersement,1, 2),'',v.heureVersement,'+',v.idVersement,'+2') AS codeVersement FROM `".$nomtableVersement."` v where v.idMutuelle='".$idMutuelle."' AND (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')

                    ) AS a ORDER BY codePagnet DESC LIMIT ".$premier.",".$parPage." ";

                    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 3".mysql_error());

                /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/



                $sql12="SELECT SUM(apayerMutuelle) FROM `".$nomtableMutuellePagnet."` where idMutuelle=".$idMutuelle." AND verrouiller=1 AND type=0 AND (CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (datepagej BETWEEN '".$dateDebut."' AND '".$dateFin."') ";

                $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());

                $TotalM = mysql_fetch_array($res12) ;

    

                $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idMutuelle=".$idMutuelle." AND  (CONCAT(CONCAT(SUBSTR(dateVersement,7, 10),'',SUBSTR(dateVersement,3, 4)),'',SUBSTR(dateVersement,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')  ";

                $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());

                $TotalV = mysql_fetch_array($res13) ;



            }

            else{

                $dateDebut=$_SESSION['dateCB'];

                $dateFin=$dateString;



                /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/

                    $sqlC="SELECT

                    COUNT(*) AS total

                    FROM

                    (

                    SELECT m.idClient FROM `".$nomtableMutuellePagnet."` m where m.idMutuelle='".$idMutuelle."' AND m.verrouiller=1 AND m.type=0 AND (CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')

                        UNION ALL

                    SELECT v.idClient FROM `".$nomtableVersement."` v where v.idMutuelle='".$idMutuelle."' AND (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2))  BETWEEN '".$dateDebut."' AND '".$dateFin."')

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

                    SELECT CONCAT(SUBSTR(m.datepagej,7, 4),'',SUBSTR(m.datepagej,4, 2),'',SUBSTR(m.datepagej,1, 2),'',m.heurePagnet,'+',m.idMutuellePagnet,'+1') AS codePagnet FROM `".$nomtableMutuellePagnet."` m where m.idMutuelle='".$idMutuelle."' AND m.verrouiller=1 AND m.type=0 AND (CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')

                        UNION ALL

                    SELECT CONCAT(SUBSTR(v.dateVersement,7, 4),'',SUBSTR(v.dateVersement,4, 2),'',SUBSTR(v.dateVersement,1, 2),'',v.heureVersement,'+',v.idVersement,'+2') AS codeVersement FROM `".$nomtableVersement."` v where v.idMutuelle='".$idMutuelle."' AND (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')

                    ) AS a ORDER BY codePagnet DESC LIMIT ".$premier.",".$parPage." ";

                    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 3".mysql_error());

                /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/



                $sql12="SELECT SUM(apayerMutuelle) FROM `".$nomtableMutuellePagnet."` where idMutuelle=".$idMutuelle." AND verrouiller=1 AND type=0 ";

                $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());

                $TotalM = mysql_fetch_array($res12) ;

    

                $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idMutuelle=".$idMutuelle."  ";

                $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());

                $TotalV = mysql_fetch_array($res13) ;

            }

            

            $T_solde=$TotalM[0] - $TotalV[0];



        ?>



        <div class="jumbotron noImpr">

            <div class="col-sm-2 pull-right" >

                <div aria-label="navigation">

                    <ul class="pager">

                        <li>

                        <input type="text" id="reportrange" />

                        </li>

                    </ul>

                </div>

            </div>

            <h2>Les Operations du Mutuel : <?php echo strtoupper($mutuelle['nomMutuelle']).' ('.$mutuelle['tauxMutuelle'].'%)'; ?> </h2>

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

                            <h4>Total des Bons du Mutuel : <?php echo number_format($TotalM[0], 2, ',', ' '); ?></h4>

                            <h4>Total des Versements : <?php echo number_format($TotalV[0], 2, ',', ' '); ?></h4>

                        </div>

                    </div>

                </div>

            </div>

            <form class="form-inline pull-left noImpr"  target="_blank" style="margin-left:20px;"

                method="post" action="pdfMutuelle.php" >

                <input type="hidden" name="idMutuelle" id="idMutuelle"  <?php echo  "value=".$idMutuelle."" ; ?> >

                <input type="hidden" name="dateDebut"  <?php echo  "value=".$dateDebut."" ; ?> >

                <input type="hidden" name="dateFin"  <?php echo  "value=".$dateFin."" ; ?> >

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

        <!--*******************************Debut Ajouter Versement****************************************-->

        <?php 

                $sqlI="SELECT *

                FROM `".$nomtableLigne."` l

                INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet

                where l.classe=9 &&  m.verrouiller=1 &&  m.type=0  &&  idMutuelle=".$idMutuelle." ";

                $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());

                $M_init = mysql_fetch_array($resI);

                if($M_init!=null){

                }

                else{

                    echo '

                        <button type="button" class="btn btn-success noImpr pull-left" data-toggle="modal" data-target="#addInit">

                            <i class="glyphicon glyphicon-plus"></i> Initialisation

                        </button>

                        <div class="modal fade" id="addInit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

                        <div class="modal-dialog" role="document">

                            <div class="modal-content">

                                <div class="modal-header">

                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                    <h4 class="modal-title" id="myModalLabel">Initialisation Mutuelle</h4>

                                </div>

                                <div class="modal-body">

                                    <form name="formulaireVersement" method="post" action=bonMutuelle.php?iDS='.$idMutuelle.'>

                                        <div class="form-group">

                                            <label for="inputEmail3" class="control-label">Montant</label>

                                            <input type="number" class="form-control" id="montantInitialiser" name="montantInitialiser" placeholder="Montant" required="">

                                            <span class="text-danger" ></span>

                                        </div>

                                        <div class="form-group">

                                            <label for="inputEmail3" class="control-label">Date (jj-mm-aaaa) </label>

                                            <input type="text" size="11" maxLength="10" value="" onKeyUp="masqueSaisieDate(this.form.dateInitialiser)" class="form-control" name="dateInitialiser" placeholder="jj-mm-aaaa" required="">

                                            <span class="text-danger" ></span>

                                        </div>

                                        <div class="modal-footer">

                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                                            <button type="submit" name="btnEnregistrerInitialisation" class="btn btn-primary">Enregistrer</button>

                                        </div>

                                    </form>

                                </div>

        

                            </div>

                        </div>

                    </div>

                    ';

                }

        ?>

            <button type="button" class="btn btn-success noImpr pull-right" data-toggle="modal" data-target="#addVer">

                <i class="glyphicon glyphicon-plus"></i> Versement

            </button><br><br>

            <div class="modal fade" id="addVer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

                <div class="modal-dialog" role="document">

                    <div class="modal-content">

                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                            <h4 class="modal-title" id="myModalLabel">Nouveau versement</h4>

                        </div>

                        <div class="modal-body">

                            <form name="formulaireVersement" method="post" <?php echo  "action=bonMutuelle.php?iDS=". $idMutuelle."" ; ?>>

                                <div class="form-group">

                                    <select name="typeVersement" id="typeVersement" class="form-control">

                                        <option value="Especes">Especes</option>

                                        <option value="Cheque">Cheque</option>

                                        <option value="Transaction">Transaction</option>

                                        <option value="Virement Bancaire">Virement Bancaire</option>

                                    </select>

                                </div>

                                <div class="form-group">

                                    <label for="inputEmail3" class="control-label">Montant</label>

                                    <input type="number" class="form-control" id="montant" name="montant" placeholder="Montant" required="">

                                    <span class="text-danger" ></span>

                                </div>

                                <div class="form-group">

                                    <label for="inputEmail3" class="control-label">Date</label>

                                    <input type="date" class="form-control" id="dateActualiser" name="dateActualiser" placeholder="Date" required="">

                                    <span class="text-danger" ></span>

                                </div>                        

                                <?php if ($_SESSION['compte']==1) { ?>

                                    <select class="form-control compte" name="compte"  <?php echo  "id=compte".$pagnet['idPagnet'] ; ?>>

                                        <!-- <option value="caisse">Caisse</option> -->

                                        <?php

                                            while ($cpt=mysql_fetch_array($resPay)) { ?>

                                                <option value="<?= $cpt['idCompte'] ; ?>"><?= $cpt['nomCompte'] ; ?></option>

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

        <!--*******************************Fin Ajouter Versement****************************************-->



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
                    $sqlP0="SELECT * FROM `".$nomtableMutuellePagnet."` m where m.idMutuelle='".$idMutuelle."' AND m.verrouiller=0 AND m.type=0";
                    $resP0 = mysql_query($sqlP0) or die ("persoonel requête 20".mysql_error());
                /**Fin requete pour Lister les Paniers en cours d'Aujourd'hui (1 au maximum) **/


                /**Debut requete pour Rechercher le dernier Panier Ajouter  **/

                    $reqA="SELECT datepagej

                    FROM

                    (SELECT m.idMutuelle,m.idMutuellePagnet,m.datepagej,m.heurePagnet FROM `".$nomtableMutuellePagnet."` m where m.idMutuelle='".$idMutuelle."' AND m.verrouiller=1 AND m.type=0

                    UNION 

                    SELECT v.idFournisseur,v.idVersement,v.dateVersement,v.heureVersement FROM `".$nomtableVersement."` v where v.idMutuelle='".$idMutuelle."'

                    ) AS a ORDER BY datepagej DESC LIMIT 1";

                    $resA=mysql_query($reqA) or die ("persoonel requête 4".mysql_error());

                /**Fin requete pour Rechercher le dernier Panier Ajouter  **/ ?>         

                
                <!-- Debut Boucle while concernant les Paniers en cours (1 aux maximum) -->

                    <?php while ($pagnets = mysql_fetch_assoc($resP0)) {  

                            $sqlT2="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet='".$pagnets['idMutuellePagnet']."' AND idMutuelle='".$idMutuelle."' ";

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

                                                    <input type="text" class="form-control col-md-3 col-sm-3 col-xs-4 codeBeneficiaire" id="codeBeneficiaire" name="codeBeneficiaire" value="<?php echo $mutuelle['codeBeneficiaire'] ; ?>" placeholder="Code Beneficiaire...."  />

                                                    <input type="text" class="form-control col-md-3 col-sm-3 col-xs-4 numeroRecu" id="numeroRecu" name="numeroRecu" value="<?php echo $mutuelle['numeroRecu'] ; ?>" placeholder="Numero Reçu...."  />

                                                    <input type="date" class="form-control col-md-3 col-sm-3 col-xs-4 dateRecu" id="dateRecu" name="dateRecu" value="<?php echo $mutuelle['dateRecu'] ; ?>" placeholder="Date Reçu.."  />

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

                                                            <option value=""></option>    

                                                            <?php
                                                                if ($mutuelle['idMutuelle']!=0 && $mutuelle['idMutuelle']!=null) {
                                                                    $sqlE="SELECT * FROM `".$nomtableMutuelle."` where idMutuelle=".$mutuelle["idMutuelle"]." ";

                                                                    $resE = mysql_query($sqlE) or die ("persoonel requête 2".mysql_error());

                                                                    if($resE){

                                                                        $exMtlle=mysql_fetch_array($resE);

                                                                        echo '<option selected="true" disabled="disabled" value="'.$exMtlle["idMutuelle"].'§'.$mutuelle['idMutuellePagnet'].'" >'.$exMtlle['nomMutuelle'].'</option>';

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

                                                        <input type="number" name="versementMutuelle" id="versementMutuelle<?= $mutuelle['idMutuellePagnet']; ?>" <?= (@$mutuelle['idCompte']!=0) ? 'style="display:none;"' : '';?>

                                                                class="versementMutuelle form-control col-md-2 col-sm-2 col-xs-3"  <?php echo "value=".$mutuelle['versement'].""; ?> placeholder="Espèce...">
                                                        

                                                        <?php if ($_SESSION['compte']==1) { ?>

                                                            <select class="compte compteMutuelle <?= $mutuelle['idMutuellePagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3"  data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" name="compte" data-type="mutuelle"  style="margin-left:5px;" <?php echo  "id=compte".$mutuelle['idMutuellePagnet'] ; ?>>

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



                                                            <?php if ($mutuelle['type']=='30') {

                                                                    

                                                                $sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$mutuelle['idClient']."";

                                                                $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());

                                                                $client = mysql_fetch_assoc($res3);

                                                            ?>

                                                                <input type="text" class="client clientInput clientMutuelle form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" data-type="mutuelle" name="clientInput" id="clientInput<?= $mutuelle['idMutuellePagnet'];?>" autocomplete="off" value="<?= $client['prenom']." ".$client['nom']; ?>"/>

                                                                <input type="number" class="avanceInput avanceInputM form-control col-md-2 col-sm-2 col-xs-3" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" name="avanceInput" id="avanceInput<?= $mutuelle['idMutuellePagnet'];?>" autocomplete="off" value="<?= $mutuelle['avance']; ?>"/>

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

                                                            <?php

                                                                    # code...

                                                                } else {

                                                                    # code...                                                            

                                                            ?> 

                                                                    <input type="text" class="client clientInput clientMutuelle form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" data-type="mutuelle" name="clientInput" id="clientInput<?= $mutuelle['idMutuellePagnet'];?>" autocomplete="off" placeholder="Client"/>

                                                                    <input type="number" class="avanceInput avanceInputM form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" name="avanceInput" id="avanceInput<?= $mutuelle['idMutuellePagnet'];?>" autocomplete="off" placeholder="Avance"/>

                                                                    <select class="compteAvance compteAvanceM <?= $mutuelle['idMutuellePagnet']; ?> form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" data-idPanier="<?= $mutuelle['idMutuellePagnet'] ; ?>" name="compteAvance" <?php echo "id=compteAvance".$mutuelle['idMutuellePagnet'] ; ?>>

                                                                        <!-- <option value="caisse">Caisse</option> -->

                                                                    <?php foreach ($cpt_array as $key) { 

                                                                        if($key['idCompte'] != 2){

                                                                        ?>

                                                                            <option value="<?= $key['idCompte'];?>"><?= $key['nomCompte'];?></option>

                                                                        <?php }

                                                                        } ?>

                                                                    </select> 

                                                            <?php } ?> 

                                                        <?php } ?>                                                                     



                                                        <input type="hidden" name="idMutuellePagnet"   <?php echo  "value=".$mutuelle['idMutuellePagnet']."" ; ?>>

                                                        <input type="hidden" name="totalp" <?php echo  "id=totalp".$mutuelle['idMutuellePagnet']."" ; ?> value="<?php echo $mutuelle['totalp']; ?>" >

                                                        <button type="button" name="btnTerminerImputation" <?php echo  "id=btnTerminerMutuelle".$mutuelle['idMutuellePagnet']."" ; ?> class="btn btn-success btn_Termine_Panier terminer terminerMutuelle" data-idPanier="<?= $mutuelle['idMutuellePagnet'];?>" ><span class="glyphicon glyphicon-ok"></span></button>

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

                    } ?>

                <!-- Fin Boucle while concernant les Paniers en cours (1 aux maximum) -->


                <!-- Debut Boucle while concernant les Paniers Vendus -->

                    <?php $n=$nbPaniers - (($currentPage * 10) - 10); while ($bons = mysql_fetch_assoc($resP1)) {   ?>

                        <?php	$idmax=mysql_result($resA,0); ?>

                        <?php

                            if($bons['codePagnet']!=null){

                                $bon = explode("+", $bons['codePagnet']);

                                if($bon[2]==1){

                                    $sqlT1="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet='".$bon[1]."' AND idMutuelle='".$idMutuelle."' ";

                                    $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());

                                    $mutuelle = mysql_fetch_assoc($resT1);

                                    if($mutuelle!=null){

                                        $sql="SELECT * FROM `".$nomtableLigne."` where idMutuellePagnet=".$mutuelle['idMutuellePagnet']." ";

                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                                        $ligne = mysql_fetch_assoc($res) ;

                                        if($ligne['classe']==0){?>

                                            <div class="panel panel-success">

                                                <div class="panel-heading">

                                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#mutuelle".$mutuelle['idMutuellePagnet']."" ; ?>  class="panel-title expand">

                                                    <div class="right-arrow pull-right">+</div>

                                                    <a href="#"> Imputation

                                                        <span class="spanDate noImpr"> </span>

                                                        <span class="spanDate noImpr"> Date: <?php echo $mutuelle['datepagej']; ?> </span>

                                                        <span class="spanDate noImpr">Heure: <?php echo $mutuelle['heurePagnet']; ?></span>

                                                        <span class="spanDate noImpr">Total à payer: <?php echo $mutuelle['apayerMutuelle']; ?></span>

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

                                                                <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$mutuelle['idMutuellePagnet'] ; ?>>

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

                                                            <?php if ($_SESSION['proprietaire']==1){ ?>

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

                                                        <?php if ($_SESSION['caissier']==1){ ?>

                                                            <button class="btn btn-warning  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$mutuelle['idMutuellePagnet'] ;?>').submit();">

                                                            Facture

                                                            </button>

                                                        <?php } ?>



                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$mutuelle['idMutuellePagnet'] ; ?>  target="_blank" style="margin-right:20px;"

                                                            method="post" action="pdfFacture.php" >

                                                            <input type="hidden" name="idMutuellePagnet" id="idMutuellePagnet"  <?php echo  "value=".$mutuelle['idMutuellePagnet']."" ; ?> >

                                                        </form>

                                                            

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

                                                                }  ?>

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

                                                        <!--*******************************Fin  Total Facture****************************************-->

                                                    </div>

                                                </div>

                                            </div>

                                            <?php

                                        }

                                        if($ligne['classe']==9){?>

                                            <div class="panel panel-info">

                                                <div class="panel-heading">

                                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#mutuelle".$mutuelle['idMutuellePagnet']."" ; ?>  class="panel-title expand">

                                                    <div class="right-arrow pull-right">+</div>

                                                    <a href="#"> Initialisation

                                                        <span class="spanDate noImpr"> </span>

                                                        <span class="spanDate noImpr"> Date: <?php echo $mutuelle['datepagej']; ?> </span>

                                                        <span class="spanDate noImpr">Heure: <?php echo $mutuelle['heurePagnet']; ?></span>

                                                        <span class="spanDate noImpr">Total à payer: <?php echo $mutuelle['apayerMutuelle']; ?></span>

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

                                                                <button  type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$mutuelle['idMutuellePagnet'] ; ?>>

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

                                                                                <button type="submit" name="btnAnnulerInitialisation" class="btn btn-success btn_Retour_Panier">Confirmer</button>

                                                                            </div>

                                                                        </form>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        <!--*******************************Fin Retourner Pagnet****************************************-->

                                                            



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

                                                                }  ?>

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

                                                        <!--*******************************Fin  Total Facture****************************************-->

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

                                else if($bon[2]==2){

                                    $sqlT2="SELECT * FROM `".$nomtableVersement."` where idVersement='".$bon[1]."' AND idMutuelle='".$idMutuelle."' ";

                                    $resT2 = mysql_query($sqlT2) or die ("persoonel requête 2".mysql_error());

                                    $versement = mysql_fetch_assoc($resT2);

                                    if($versement!=null){

                                        ?>

                                            <div style="padding-top : 2px;" class="panel panel-warning">

                                                <div class="panel-heading">

                                                    <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#versement".$versement['idVersement']."" ; ?>  class="panel-title expand">

                                                    <div class="right-arrow pull-right">+</div>

                                                    <a href="#"> Versement 

                                                        <span class="spanDate noImpr"> </span>

                                                        <span class="spanDate noImpr"> Date: <?php echo $versement['dateVersement']; ?> </span>

                                                        <span class="spanDate noImpr">Heure: <?php echo $versement['heureVersement']; ?></span>

                                                        <span class="spanDate noImpr">Montant: <?php echo ($versement['montant'] * $_SESSION['devise']).' '.$_SESSION['symbole']; ?></span>

                                                        <span class="spanDate noImpr"> Recu : #<?php echo $versement['idVersement']; ?></span>

                                                    </a>

                                                    </h4>

                                                </div>

                                                <div class="panel-collapse collapse " <?php echo  "id=versement".$versement['idVersement']."" ; ?> >

                                                    <div class="panel-body" >

                                                        <?php if ($versement['iduser']==$_SESSION['iduser']){ ?>

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

                                                                            <?php if ($_SESSION['compte']==1){ ?>

                                                                                <input type="hidden" name="idCompte" id="idCompte"  <?php echo  "value=".$versement['idCompte']."" ; ?>>

                                                                            <?php } ?>

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

                                                            

                                                            <?php if ($_SESSION['caissier']==1){ ?>

                                                            

                                                            <button disabled="true" type="submit" class="btn btn-warning pull-right" data-toggle="modal" name="barcodeFactureV">

                                                            <span class="glyphicon glyphicon-lock"></span>Facture

                                                            </button>

                                                            

                                                            <?php } ?>

                                                            

                                                        </form>



                                                        <form class="form-inline pull-right noImpr" style="margin-right:20px;"

                                                            method="post" action="barcodeFacture.php" target="_blank"  >

                                                            <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?> >

                                                            

                                                            <?php if ($_SESSION['caissier']==1){ ?>

                                                            

                                                            <button type="submit" class="btn btn-info pull-right" data-toggle="modal" name="barcodeFactureV">

                                                            <span class="glyphicon glyphicon-lock"></span>Ticket de Caisse

                                                            </button>

                                                            

                                                            <?php } ?>

                                                            

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

                            }

                        ?>

                    <?php $n=$n-1;   } ?>

                    <?php if(isset($_GET['debut']) && isset($_GET['fin'])){ 

                        $dateDebut=@htmlspecialchars($_GET["debut"]);

                        $dateFin=@htmlspecialchars($_GET["fin"]); ?>

                        <?php if($nbPaniers >= 11){ ?>

                            <ul class="pagination pull-right">

                                <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->

                                <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">

                                    <a href="bonMutuelle.php?iDS=<?= $idMutuelle; ?>&&page=<?= $currentPage - 1 ?>&&debut=<?= $dateDebut ?>&&fin=<?= $dateFin ?>" class="page-link">Précédente</a>

                                </li>

                                <?php for($page = 1; $page <= $pages; $page++): ?>

                                    <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->

                                    <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">

                                        <a href="bonMutuelle.php?iDS=<?= $idMutuelle; ?>&&page=<?= $page ?>&&debut=<?= $dateDebut ?>&&fin=<?= $dateFin ?>" class="page-link"><?= $page ?></a>

                                    </li>

                                <?php endfor ?>

                                    <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->

                                    <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">

                                    <a href="bonMutuelle.php?iDS=<?= $idMutuelle; ?>&&page=<?= $currentPage + 1 ?>&&debut=<?= $dateDebut ?>&&fin=<?= $dateFin ?>" class="page-link">Suivante</a>

                                </li>

                            </ul>

                        <?php } ?>

                    <?php 

                        }else { 

                    ?>

                        <?php if($nbPaniers >= 11){ ?>

                            <ul class="pagination pull-right">

                                <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->

                                <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">

                                    <a href="bonMutuelle.php?iDS=<?= $idMutuelle; ?>&&page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>

                                </li>

                                <?php for($page = 1; $page <= $pages; $page++): ?>

                                    <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->

                                    <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">

                                        <a href="bonMutuelle.php?iDS=<?= $idMutuelle; ?>&&page=<?= $page ?>" class="page-link"><?= $page ?></a>

                                    </li>

                                <?php endfor ?>

                                    <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->

                                    <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">

                                    <a href="bonMutuelle.php?iDS=<?= $idMutuelle; ?>&&page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>

                                </li>

                            </ul>

                        <?php } ?>

                    <?php }?>

                <!-- Fin Boucle while concernant les Paniers Vendus  -->



            </div>

        <!-- Fin de l'Accordion pour Tout les Paniers -->



        <script type="text/javascript">

            $(function() {

                    idMutuelle=<?php echo json_encode($idMutuelle); ?>;

                    dateDebut = <?php echo json_encode($dateDebut); ?>;

                    dateFin = <?php echo json_encode($dateFin); ?>;

                    tabDebut=dateDebut.split('-');

                    tabFin=dateFin.split('-');

                    var start = tabDebut[2]+'/'+tabDebut[1]+'/'+tabDebut[0];

                    var end = tabFin[2]+'/'+tabFin[1]+'/'+tabFin[0];

                function cb(start, end) {

                    var debut=start.format('YYYY-MM-DD');

                    var fin=end.format('YYYY-MM-DD');

                    window.location.href = "bonMutuelle.php?iDS="+idMutuelle+"&&debut="+debut+"&&fin="+fin;

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

        </script>



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



