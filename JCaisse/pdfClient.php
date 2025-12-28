<?php

session_start();

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}
/*
Résumé : Ce code permet d'inserer une ligne (une entrée ou une sortie) dans le journal d'une boutique.
Commentaire : Ce code contient un formulaire récupérant l'ensemble des informations (typeligne, designation,prix unitaire,quantite,remise,prix total) sur une de journal de la boutique.
Pour facilité le remplissage de ce formulaire ce code est associé avec du code AJAX (JavaScript:verificationdesignation.js et PHP:verificationdesig.php),
qui vérifie le champ désignation si il est vide et si il existe ou il est absent de la base de données et qui compléte les champs : prix unitaire et prix total.
Il insère ces informations dans la table commençant par le nom de la boutique et suivi de : -lignepj. Pour cela ce code à partir de la date courrante regarde si pour cette ligne y'a une page déja créer sinon il le crée et regarde aussi si pour cette page de la date courrante si le mois et l'année ya un journal déjà créer sinon il le crée.
Ainsi de façon automatique le code pour une ligne donnée le relie avec une page et un journal si ils existent. sinon le les créent avant de les associer avec cette nouvelle ligne.
Ce code permet d'afficher la liste des lignes (des entrées ou des sorties) du journal d'une boutique pour la date courrante et de modifier et de supprimer une ligne de la liste des lignes du journal.
Version : 2.0
see also : modifierLigne.php et supprimerLigne.php
Auteur : Ibrahima DIOP
Date de création : 20/03/2016
Date dernière modification : 20/04/2016
*/
require('connection.php');

require('declarationVariables.php');

require('fpdf/fpdf.php');

if (isset($_POST['dateImp'])) {

    $dateString2=htmlspecialchars(trim($_POST['dateImp']));

/**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
    $sqlP1="SELECT DISTINCT idClient
    FROM
    (SELECT p.idClient FROM `".$nomtablePagnet."` p WHERE p.datepagej ='".$dateString2."' and p.idClient!=0
    UNION 
    SELECT v.idClient FROM `".$nomtableVersement."` v WHERE v.dateVersement ='".$dateString2."' 
    ) AS a ";
    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
/**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

/**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
    $sqlC="SELECT COUNT(DISTINCT idClient)
    FROM
    (SELECT p.idClient FROM `".$nomtablePagnet."` p WHERE p.datepagej ='".$dateString2."' and p.idClient!=0
    UNION 
    SELECT v.idClient FROM `".$nomtableVersement."` v WHERE v.dateVersement ='".$dateString2."' 
    ) AS a ";
    $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
    $nbre = mysql_fetch_array($resC) ;
/**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ 

/**Debut requete pour Calculer la Somme des Depenses d'Aujourd'hui  **/
    $sqlBE="SELECT DISTINCT p.idPagnet
        FROM `".$nomtablePagnet."` p
        INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
        WHERE l.classe=6 && p.idClient!=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."'  ORDER BY p.idPagnet DESC";
    $resBE = mysql_query($sqlBE) or die ("persoonel requête 2".mysql_error());
    $T_bonEsp = 0 ;
    $S_bonEsp = 0;
    while ($pagnet = mysql_fetch_array($resBE)) {
        $sqlS="SELECT SUM(apayerPagnet)
        FROM `".$nomtablePagnet."`
        where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";
        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
        $S_bonEsp = mysql_fetch_array($resS);
        $T_bonEsp = $S_bonEsp[0] + $T_bonEsp;
    }

    $sqlBP="SELECT DISTINCT p.idPagnet
        FROM `".$nomtablePagnet."` p
        INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
        WHERE l.classe!=6 && p.idClient!=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0  ORDER BY p.idPagnet DESC";
    $resBP = mysql_query($sqlBP) or die ("persoonel requête 2".mysql_error());
    $T_bonP = 0 ;
    $S_bonP = 0;
    while ($pagnet = mysql_fetch_array($resBP)) {
        $sqlS="SELECT SUM(apayerPagnet)
        FROM `".$nomtablePagnet."`
        where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."' && type=0  ORDER BY idPagnet DESC";
        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
        $S_bonP = mysql_fetch_array($resS);
        $T_bonP = $S_bonP[0] + $T_bonP;
    }

    $sqlBR="SELECT DISTINCT p.idPagnet
        FROM `".$nomtablePagnet."` p
        INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
        WHERE l.classe!=6 && p.idClient!=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=1  ORDER BY p.idPagnet DESC";
    $resBR = mysql_query($sqlBR) or die ("persoonel requête 2".mysql_error());
    $T_bonR = 0 ;
    $S_bonR = 0;
    while ($pagnet = mysql_fetch_array($resBR)) {
        $sqlS="SELECT SUM(apayerPagnet)
        FROM `".$nomtablePagnet."`
        where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."' && type=1  ORDER BY idPagnet DESC";
        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
        $S_bonR = mysql_fetch_array($resS);
        $T_bonR = $S_bonR[0] + $T_bonR;
    }

    $T_bons=$T_bonP - $T_bonR;

    $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where  dateVersement  ='".$dateString2."'  ORDER BY idVersement DESC";
        $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());
    $T_versements = mysql_fetch_array($resP5) ;
/**Fin requete pour Calculer la Somme des Depenses d'Aujourd'hui  **/

//Create new pdf file
$pdf=new FPDF();

//Disable automatic page break
$pdf->SetAutoPageBreak(false);

//Add first page
$pdf->AddPage();

//set initial y axis position per page
$y_axis_initial = 43;

//Set Row Height
$row_height = 6;

$y_axis=43;

//Set maximum rows per page
$max = 25;
$i = 0;
$p=1;

$nb_page=ceil($nbre[0]/$max);

$image='';
if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
    $image='pharmacie.png'; 
}
else{
    $image='shop.png';
}
$pdf-> SetFont("Arial","B",10);
$pdf-> Image('images/'.$image,5,5,20,20);

$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, $p . '/' . $nb_page, 0, 0, 'C');
        
$champ_date = date_create($dateString2); $annee = date_format($champ_date, 'Y');
$num_fact = "Journal de Caisse du " . $dateString2 ;
$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 8, "DF");
$pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');

// observations
$pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
// $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");

$pdf->SetFont( "Arial", "BU", 10 ); $pdf->SetXY( 5, 38 ) ; $pdf->Cell($pdf->GetStringWidth("Clients"), 0, 'Clients', 0, "L");


if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
    //print column titles
    $pdf->SetFillColor(192);
    $pdf->SetFont('Arial','B',12);
    $pdf->SetY($y_axis_initial);
    $pdf->SetX(5);
    $pdf->Cell(110,6,'Prenom & Nom ',1,0,'L',1);
    $pdf->Cell(30,6,'Bon Especes',1,0,'L',1);
    $pdf->Cell(30,6,'Bon Produit',1,0,'L',1);
    $pdf->Cell(30,6,'Versement',1,0,'L',1);
}
else{
    //print column titles
    $pdf->SetFillColor(192);
    $pdf->SetFont('Arial','B',12);
    $pdf->SetY($y_axis_initial);
    $pdf->SetX(5);
    $pdf->Cell(80,6,'Prenom & Nom ',1,0,'L',1);
    $pdf->Cell(30,6,'Bon Especes',1,0,'L',1);
    $pdf->Cell(30,6,'Bon Produit',1,0,'L',1);
    $pdf->Cell(30,6,'Bon Retour',1,0,'L',1);
    $pdf->Cell(30,6,'Versement',1,0,'L',1);
}

$y_axis = $y_axis + $row_height;

//Select the Products you want to show in your PDF file
$c=0;
$l=1;
$idAvant=0;

if(mysql_num_rows($resP1)){
    while ($client = mysql_fetch_array($resP1)) { 

        $sqlN="select * from `".$nomtableClient."` where idClient='".$client["idClient"]."'";
        $resN=mysql_query($sqlN);
        $N_client = mysql_fetch_array($resN);

        $sqlSbe="SELECT SUM(apayerPagnet)
            FROM `".$nomtablePagnet."` p
            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
            where l.classe=6 && p.idClient='".$client["idClient"]."' &&  p.datepagej ='".$dateString2."'";
        $resSbe=mysql_query($sqlSbe) or die ("select stock impossible =>".mysql_error());
        $Som_bonE = mysql_fetch_array($resSbe);

        $sqlSbp="SELECT DISTINCT p.idPagnet
            FROM `".$nomtablePagnet."` p
            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
            WHERE l.classe!=6 && p.idClient='".$client["idClient"]."'  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0 ORDER BY p.idPagnet DESC";
        $resSbp = mysql_query($sqlSbp) or die ("persoonel requête 2".mysql_error());
        $T_Bonp = 0 ;
        $S_Bonp = 0;
        while ($pagnet = mysql_fetch_array($resSbp)) {
            $sqlB="SELECT SUM(apayerPagnet)
            FROM `".$nomtablePagnet."`
            where idClient='".$client["idClient"]."' &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."' && type=0 ORDER BY idPagnet DESC";
            $resB=mysql_query($sqlB) or die ("select stock impossible =>".mysql_error());
            $S_Bonp = mysql_fetch_array($resB);
            $T_Bonp = $S_Bonp[0] + $T_Bonp;
        }

        $sqlSbr="SELECT DISTINCT p.idPagnet
            FROM `".$nomtablePagnet."` p
            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
            WHERE l.classe!=6 && p.idClient='".$client["idClient"]."'  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=1 ORDER BY p.idPagnet DESC";
        $resSbr = mysql_query($sqlSbr) or die ("persoonel requête 2".mysql_error());
        $T_Bonr = 0 ;
        $S_Bonr = 0;
        while ($pagnet = mysql_fetch_array($resSbr)) {
            $sqlB="SELECT SUM(apayerPagnet)
            FROM `".$nomtablePagnet."`
            where idClient='".$client["idClient"]."' &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."' && type=1 ORDER BY idPagnet DESC";
            $resB=mysql_query($sqlB) or die ("select stock impossible =>".mysql_error());
            $S_Bonr = mysql_fetch_array($resB);
            $T_Bonr = $S_Bonr[0] + $T_Bonr;
        }

        $sqlSve="SELECT SUM(montant)
        FROM `".$nomtableVersement."`
        where idClient='".$client["idClient"]."' &&  dateVersement ='".$dateString2."'";
        $resSve=mysql_query($sqlSve) or die ("select stock impossible =>".mysql_error());
        $Som_versement = mysql_fetch_array($resSve);

            if ($i == $max)
            {
                $pdf->AddPage();

                $image='';
                if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                    $image='pharmacie.png'; 
                }
                else{
                    $image='shop.png';
                }
                $pdf-> SetFont("Arial","B",10);
                $pdf-> Image('images/'.$image,5,5,20,20);

                $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, $p . '/' . $nb_page, 0, 0, 'C');

                $champ_date = date_create($dateString2); $annee = date_format($champ_date, 'Y');
                $num_fact = "Journal de Caisse du " . $dateString2 ;
                $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 8, "DF");
                $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');

                $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
                // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
                $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
                $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");

                $pdf->SetFont( "Arial", "BU", 10 ); $pdf->SetXY( 5, 38 ) ; $pdf->Cell($pdf->GetStringWidth("Clients"), 0, 'Clients', 0, "L");

                $y_axis=43;

                if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                    //print column titles for the current page
                    $pdf->SetY($y_axis_initial);
                    $pdf->SetX(5);
                    $pdf->Cell(110,6,'Prenom & Nom ',1,0,'L',1);
                    $pdf->Cell(30,6,'Bon Especes',1,0,'L',1);
                    $pdf->Cell(30,6,'Bon Produit',1,0,'L',1);
                    $pdf->Cell(30,6,'Versement',1,0,'L',1);
                }
                else{
                    //print column titles for the current page
                    $pdf->SetY($y_axis_initial);
                    $pdf->SetX(5);
                    $pdf->Cell(80,6,'Prenom & Nom ',1,0,'L',1);
                    $pdf->Cell(30,6,'Bon Especes',1,0,'L',1);
                    $pdf->Cell(30,6,'Bon Produit',1,0,'L',1);
                    $pdf->Cell(30,6,'Bon Retour',1,0,'L',1);
                    $pdf->Cell(30,6,'Versement',1,0,'L',1);
                }
                            
                //Go to next row
                $y_axis = $y_axis + $row_height;
                
                //Set $i variable to 0 (first row)
                $i = 0;

            }

            $prenom=$N_client["prenom"];
            $nom=$N_client["nom"];
            $telephone=$N_client["telephone"];
            $bon_E = number_format($Som_bonE[0], 2, ',', ' ');
            $bon_P = number_format($T_Bonp, 2, ',', ' ');
            $bon_R = number_format($T_Bonr, 2, ',', ' ');
            $versement = number_format($Som_versement[0], 2, ',', ' ');

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                $pdf->SetY($y_axis);
                $pdf->SetFillColor(255,255,255);
                //$pdf->SetTextColor(255,255,255);
                $pdf->SetX(5);
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(110,6,$prenom.' '.$nom.' ('.$telephone.')',1,0,'L',1);
                $pdf->Cell(30,6,$bon_E,1,0,'L',1);
                $pdf->Cell(30,6,$bon_P,1,0,'L',1);
                $pdf->Cell(30,6,$versement,1,0,'L',1);
            }
            else{
                $pdf->SetY($y_axis);
                $pdf->SetFillColor(255,255,255);
                //$pdf->SetTextColor(255,255,255);
                $pdf->SetX(5);
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(80,6,$prenom.' '.$nom.' ('.$telephone.')',1,0,'L',1);
                $pdf->Cell(30,6,$bon_E,1,0,'L',1);
                $pdf->Cell(30,6,$bon_P,1,0,'L',1);
                $pdf->Cell(30,6,$bon_R,1,0,'L',1);
                $pdf->Cell(30,6,$versement,1,0,'L',1);
            }

            //Go to next row
            $y_axis = $y_axis + $row_height;
            $i = $i + 1;
            if($i==10){
                $p = $p + 1;
            }

            $idAvant=$pagnet['idPagnet'];
            $l = $l + 1;


        // si derniere page alors afficher total
        if ($nbre[0]==($l - 1))
        {
            $pdf->SetY($y_axis);
            $pdf->SetFillColor(192);
            //$pdf->SetTextColor(255,255,255);
            
            $pdf->SetFont('Arial','B',8);
            $pdf->SetX(105);
            $pdf->Cell(50,8,'Total Bon Especes',1,0,'L',1);
            $pdf->Cell(50,8,number_format($T_bonEsp, 2, ',', ' '),1,1,'L',0);
            $pdf->SetX(105);
            $pdf->Cell(50,8,'Total Bon Produit',1,0,'L',1);
            $pdf->Cell(50,8,number_format($T_bonP, 2, ',', ' '),1,1,'L',0);
            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

            }
            else{
                $pdf->SetX(105);
                $pdf->Cell(50,8,'Total Bon Retour',1,0,'L',1);
                $pdf->Cell(50,8,number_format($T_bonR, 2, ',', ' '),1,1,'L',0);
            }
            $pdf->SetX(105);
            $pdf->Cell(50,8,'Total Versements',1,0,'L',1);
            $pdf->Cell(50,8,number_format($T_versements[0], 2, ',', ' '),1,1,'L',0);
            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                $sqlTva="SELECT DISTINCT p.idPagnet
                    FROM `".$nomtablePagnet."` p
                    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
                    WHERE l.classe=0 && p.idClient!=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0 ORDER BY p.idPagnet DESC";
                $resTva = mysql_query($sqlTva) or die ("persoonel requête 2".mysql_error());
                $totalTVA=0;
                while ($pagnet = mysql_fetch_array($resTva)) {
                    $sql8="SELECT * 
                    FROM `".$nomtableLigne."` l
                    WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne ASC";
                    $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                    while ($ligne = mysql_fetch_array($res8)) {
                        $sqlD="select * from `".$nomtableDesignation."` where idDesignation='".$ligne["idDesignation"]."' ";
                        $resD=mysql_query($sqlD);
                        $produit = mysql_fetch_assoc($resD);
                        $totalTVA=$totalTVA + (($ligne['prixtotal'] * 18)/100);
                    }
        
                }
                $pdf->SetX(105);
                $pdf->Cell(50,8,'Total TVA 18%',1,0,'L',1);
                $pdf->Cell(50,8,number_format($totalTVA, 2, ',', ' '),1,1,'L',0);
            }
        /*  $pdf->Cell(50,8,'Total Versements',1,0,'L',1);
            $pdf->Cell(50,8,number_format(($T_App - $T_Rcaisse - $T_depenses), 2, ',', ' '),1,0,'L',0);*/
            
        }
                

        $c = $c + 1;
    }
}
else{
    $pdf->SetFont( "Arial", "BU", 12 ); $pdf->SetXY( 90, 70 ) ; $pdf->Cell($pdf->GetStringWidth("Aucun Client"), 0, 'Aucun Client', 0, "L");
}


$pdf->Output("I", '1');

}

?>