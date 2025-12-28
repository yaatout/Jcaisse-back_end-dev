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

$produits = array();

$produit_0 = array();
$tabIdDesigantion_0 = array();
$tabQuantite_0 = array();
$tabIdStock_0 = array();

$produit = array();
$tabIdDesigantion = array();
$tabIdStock = array();

$sql_0="SELECT d.idDesignation,d.forme FROM `".$nomtableDesignation."` d
WHERE d.classe=0 AND d.archiver<>1 AND idDesignation NOT IN (
  SELECT d.idDesignation FROM `".$nomtableDesignation."` d
  LEFT JOIN `".$nomtableStock."` s ON s.idDesignation = d.idDesignation
  WHERE d.classe=0  AND (s.quantiteStockCourant<>0) AND d.archiver<>1 GROUP BY d.idDesignation 
) ORDER BY d.forme ASC,d.designation ASC ";
$res_0=mysql_query($sql_0);

$sql="SELECT d.idDesignation,d.forme FROM `".$nomtableDesignation."` d
LEFT JOIN `".$nomtableStock."` s ON s.idDesignation = d.idDesignation
WHERE d.classe=0  AND (s.quantiteStockCourant<>0) AND d.archiver<>1 GROUP BY d.idDesignation ORDER BY d.forme ASC,d.designation ASC  ";
$res=mysql_query($sql);

while($stock_0=mysql_fetch_array($res_0)){
  if (in_array($stock_0['idDesignation'], $tabIdDesigantion_0)) {
    # code...
  } else {
    # code...

    $tabIdDesigantion_0[]=$stock_0['idDesignation'];
    $produit_0[]=$stock_0;
  }
  
}


while($stock=mysql_fetch_array($res)){
  if (in_array($stock['idDesignation'], $tabIdDesigantion)) {
    # code...
  } else {
    # code...

    $tabIdDesigantion[]=$stock['idDesignation'];
    $produit[]=$stock;
  }
  
}

$produits =array_merge($produit_0, $produit);

$nbre = count($produits);

$sql2="SELECT d.prixPublic,s.quantiteStockCourant,s.idStock,d.prixSession FROM `".$nomtableStock."` s
LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
WHERE d.classe=0 ORDER BY s.idStock DESC";
$res2=mysql_query($sql2);
$montantPA=0;
$montantPU=0;
while ($stock = mysql_fetch_array($res2)) {
  if($stock['quantiteStockCourant']!=null && $stock['quantiteStockCourant']!=0){
    $montantPA=$montantPA + ($stock['quantiteStockCourant'] * $stock['prixSession']);
    $montantPU=$montantPU + ($stock['quantiteStockCourant'] * $stock['prixPublic']);
  }
}

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

$nb_page=ceil($nbre/$max);

$image='shop.png';
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
$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseU"], 0, "L");
$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telPortable"].' / '.$_SESSION["telFixe"], 0, "L");

// $pdf->SetFont( "Arial", "BU", 10 ); $pdf->SetXY( 5, 38 ) ; $pdf->Cell($pdf->GetStringWidth("Stock"), 0, 'Stock', 0, "L");


//print column titles
$pdf->SetFillColor(192);
$pdf->SetFont('Arial','B',11);
$pdf->SetY($y_axis_initial);
$pdf->SetX(5);
$pdf->Cell(100,6,'Reference',1,0,'L',1);
$pdf->Cell(38,6,'Forme',1,0,'L',1);
//$pdf->Cell(20,6,'P Session',1,0,'L',1);
$pdf->Cell(20,6,'Prix Session',1,0,'L',1);
$pdf->Cell(20,6,'Prix Public',1,0,'L',1);
$pdf->Cell(20,6,'Quantite',1,0,'L',1);


$y_axis = $y_axis + $row_height;

//Select the Products you want to show in your PDF file
$c=0;
$l=1;
$idAvant=0;
$totalTVA=0;
//$produits=array();

foreach ($produits as $stock) {

    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
    $res1=mysql_query($sql1);
    $produit=mysql_fetch_array($res1);

    /*
    $sqlS="SELECT SUM(quantiteStockCourant)
    FROM `".$nomtableStock."`
    where idDesignation ='".$stock['idDesignation']."'  ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_stock = mysql_fetch_array($resS);
    */

    //If the current row is the last one, create new page and print column title
    if ($i == $max)
    {
        $pdf->AddPage();

        $image='shop.png';
        $pdf-> SetFont("Arial","B",10);
        $pdf-> Image('images/'.$image,5,5,20,20);

        $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, $p . '/' . $nb_page, 0, 0, 'C');

        $champ_date = date_create($dateString2); $annee = date_format($champ_date, 'Y');
        // $num_fact = "LISTE DES STOCKS " ;
        // $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 8, "DF");
        // $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');

        $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
        // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
        $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseU"], 0, "L");
        $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telPortable"].' / '.$_SESSION["telFixe"], 0, "L");

        // $pdf->SetFont( "Arial", "BU", 10 ); $pdf->SetXY( 5, 38 ) ; $pdf->Cell($pdf->GetStringWidth("Stock"), 0, 'Stock', 0, "L");

        $y_axis=33;
        $y_axis_initial=33;

        //print column titles
        $pdf->SetFillColor(192);
        $pdf->SetFont('Arial','B',11);
        $pdf->SetY($y_axis_initial);
        $pdf->SetX(5);
        $pdf->Cell(100,6,'Reference',1,0,'L',1);
        $pdf->Cell(38,6,'Forme',1,0,'L',1);
        //$pdf->Cell(20,6,'P Session',1,0,'L',1);
        $pdf->Cell(20,6,'Prix Session',1,0,'L',1);
        $pdf->Cell(20,6,'Prix Public',1,0,'L',1);
        $pdf->Cell(20,6,'Quantite',1,0,'L',1);
        
        //Go to next row
        $y_axis = $y_axis + $row_height;
        
        //Set $i variable to 0 (first row)
        $i = 0;

    }
    
    $designation = strtoupper($produit['designation']);
    $codeBarre = strtoupper($produit['forme']);
    $prixSession = number_format($produit['prixSession'], 0, ',', ' ');
    $prixPublic = number_format($produit['prixPublic'], 0, ',', ' ');
    //$quantite = $S_stock[0];


    $pdf->SetY($y_axis);
    $pdf->SetFillColor(255,255,255);
    //$pdf->SetTextColor(255,255,255);
    $pdf->SetX(5);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(100,6,$designation,1,0,'L',1);
    $pdf->Cell(38,6,$codeBarre,1,0,'L',1);
    //$pdf->Cell(20,6,$prixSession,1,0,'C',1);
    $pdf->Cell(20,6,$prixSession,1,0,'C',1);
    $pdf->Cell(20,6,$prixPublic,1,0,'C',1);
    $pdf->Cell(20,6,' ',1,0,'C',1);


    //Go to next row
    $y_axis = $y_axis + $row_height;
    $i = $i + 1;
    if($i==10){
        $p = $p + 1;
    }

    // $idAvant=$pagnet['idPagnet'];
    $l = $l + 1;

        $c = $c + 1;
        //$produits[] = $produit['idDesignation'];
    //}
}


$pdf->Output("I", '1');

?>