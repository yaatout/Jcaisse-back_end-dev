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


    /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
    $sqlP1="SELECT DISTINCT p.idPagnet
    FROM `".$nomtablePagnet."` p
    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation 
    WHERE (l.classe = 0 or l.classe = 1)  && p.idClient=0  && p.verrouiller=1  ORDER BY p.idPagnet ASC";
    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
/**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

/**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
    $sqlC="SELECT COUNT(DISTINCT l.numligne)
    FROM `".$nomtableLigne."` l
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet 
    WHERE (l.classe = 0 or l.classe = 1) && p.idClient=0  && p.verrouiller=1  ORDER BY l.numligne ASC ";
    $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
    $nbre = mysql_fetch_array($resC) ; 
/**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ 



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
$max = 35;
$i = 0;
$p=1;

$nb_page=ceil($nbre[0]/$max);

$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, $p . '/' . $nb_page, 0, 0, 'C');
        
$champ_date = date_create($dateString2); $annee = date_format($champ_date, 'Y');
$num_fact = "Journal de Caisse du " . $dateString2 ;
$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 8, "DF");
$pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');

        // observations
$pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 5, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
$pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 5, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 5, 17 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseU"], 0, "L");
$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 5, 20 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telPortable"].' / '.$_SESSION["telFixe"], 0, "L");
$pdf->SetFont( "Arial", "BU", 10 ); $pdf->SetXY( 5, 38 ) ; $pdf->Cell($pdf->GetStringWidth("Transaction"), 0, 'Transaction', 0, "L");


//print column titles
$pdf->SetFillColor(192);
$pdf->SetFont('Arial','B',12);
$pdf->SetY($y_axis_initial);
$pdf->SetX(5);
$pdf->Cell(50,6,'Service',1,0,'L',1);
$pdf->Cell(40,6,'Operation',1,0,'L',1);
$pdf->Cell(40,6,'Type',1,0,'L',1);
$pdf->Cell(40,6,'Montant',1,0,'L',1);
$pdf->Cell(30,6,'Heure',1,0,'R',1);

$y_axis = $y_axis + $row_height;

//Select the Products you want to show in your PDF file
$c=0;
$l=1;
$idAvant=0;
while ($pagnets = mysql_fetch_array($resP1)) { 

    $sqlP="select * from `".$nomtablePagnet."` where idPagnet='".$pagnets["idPagnet"]."' ";
    $resP=mysql_query($sqlP);
    $pagnet = mysql_fetch_array($resP); 

    $sql8="SELECT * 
    FROM `".$nomtableLigne."` l
    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation 
    WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne ASC";
    $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
    while ($ligne = mysql_fetch_array($res8)) {

        //If the current row is the last one, create new page and print column title
        if ($i == $max)
        {
            $pdf->AddPage();

            $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, $p . '/' . $nb_page, 0, 0, 'C');


            $champ_date = date_create($dateString2); $annee = date_format($champ_date, 'Y');
            $num_fact = "Journal de Caisse du " . $dateString2 ;
            $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 8, "DF");
            $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');

            $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 5, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
            $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 5, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 5, 17 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseU"], 0, "L");
            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 5, 20 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telPortable"].' / '.$_SESSION["telFixe"], 0, "L");
            $pdf->SetFont( "Arial", "BU", 10 ); $pdf->SetXY( 5, 38 ) ; $pdf->Cell($pdf->GetStringWidth("Transaction"), 0, 'Transaction', 0, "L");

            $y_axis=43;
            
            //print column titles for the current page
            $pdf->SetY($y_axis_initial);
            $pdf->SetX(5);
            $pdf->Cell(50,6,'Service',1,0,'L',1);
            $pdf->Cell(40,6,'Operation',1,0,'L',1);
            $pdf->Cell(40,6,'Type',1,0,'L',1);
            $pdf->Cell(40,6,'Montant',1,0,'L',1);
            $pdf->Cell(30,6,'Heure',1,0,'R',1);
            
            //Go to next row
            $y_axis = $y_axis + $row_height;
            
            //Set $i variable to 0 (first row)
            $i = 0;

        }

        $designation = $ligne['designation'];
        $quantite = $ligne['quantite'];
        $unite = $ligne['unitevente'];
        $prixU = $ligne['prixunitevente'];
        $prixT = $ligne['prixtotal'];
        $remise = $pagnet['remise'];
        $heure = $pagnet['heurePagnet'];

        if($c%2==0){
            $pdf->SetY($y_axis);
            $pdf->SetFillColor(255,255,255);
            //$pdf->SetTextColor(255,255,255);
            $pdf->SetX(5);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(50,6,$designation,1,0,'L',1);
            $pdf->Cell(40,6,$quantite,1,0,'L',1);
            $pdf->Cell(40,6,$unite,1,0,'R',1);
            $pdf->Cell(40,6,$prixU,1,0,'L',1);
            $pdf->Cell(30,6,$heure,1,0,'R',1);
        }
        else{
            $pdf->SetY($y_axis);
            $pdf->SetFillColor(200,210,230);
            //$pdf->SetTextColor(255,255,255);
            $pdf->SetX(5);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(50,6,$designation,1,0,'L',1);
            $pdf->Cell(40,6,$quantite,1,0,'L',1);
            $pdf->Cell(40,6,$unite,1,0,'R',1);
            $pdf->Cell(40,6,$prixU,1,0,'L',1);
            $pdf->Cell(30,6,$heure,1,0,'R',1);
        }

        //Go to next row
        $y_axis = $y_axis + $row_height;
        $i = $i + 1;
        if($i==10){
            $p = $p + 1;
        }

        $idAvant=$pagnet['idPagnet'];
        $l = $l + 1;

    }

    // si derniere page alors afficher total
    if ($nbre[0]==($l - 1))
    {
        $pdf->SetY($y_axis);
        $pdf->SetFillColor(192);
        //$pdf->SetTextColor(255,255,255);
        
        $pdf->SetFont('Arial','B',8);
        $pdf->SetX(120);
        $pdf->Cell(35,8,'Total Prix TTC',1,0,'L',1);
        $pdf->Cell(50,8,' ',1,1,'L',0);
        $pdf->SetX(120);
        $pdf->Cell(35,8,'Total Remise',1,0,'L',1);
        $pdf->Cell(50,8,'',1,1,'L',0);
        $pdf->SetX(120);
        $pdf->Cell(35,8,'TOTAL',1,0,'L',1);
        $pdf->Cell(50,8,' ',1,0,'L',0);
        
    }
            

    $c = $c + 1;
}


$pdf->Output("I", '1');

?>