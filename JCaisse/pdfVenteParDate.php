<?php

session_start();

if (!$_SESSION['iduser']) {
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

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);

$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString2=$annee.'-'.$mois.'-'.$jour ;
$dateString=$jour.'-'.$mois.'-'.$annee ;
$produits = array();
$tabIdDesigantion = array();

$date1VD=@htmlspecialchars($_POST["date1VD"]);
$date2VD=@htmlspecialchars($_POST["date2VD"]);


// if ($date1VD !=="" && $date2VD !=="") {
//     # code...
    
//     $sql="select DISTINCT p.datepagej from `".$nomtableDesignation."` d, `".$nomtablePagnet."` p, `".$nomtableLigne."` l WHERE l.idDesignation = d.idDesignation && p.idPagnet= l.idPagnet &&
//     d.classe=0  && p.idClient=0  && p.verrouiller=1 && p.type=0 && ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$date1VD."' AND '".$date2VD."') or (p.datepagej BETWEEN '".$date1VD."' AND '".$date2VD."')) ORDER BY p.idPagnet DESC ";
//     $res=mysql_query($sql);

// } else {
    
//     $sql="select DISTINCT p.datepagej from `".$nomtableDesignation."` d, `".$nomtablePagnet."` p, `".$nomtableLigne."` l WHERE l.idDesignation = d.idDesignation && p.idPagnet= l.idPagnet &&
//     d.classe=0  && p.idClient=0  && p.verrouiller=1 && p.type=0 ORDER BY p.idPagnet DESC ";
//     $res=mysql_query($sql);
// }

if ($date1VD !=="" && $date2VD !=="") {
    # code...
    
    // $sql="select DISTINCT p.datepagej from `".$nomtableDesignation."` d, `".$nomtablePagnet."` p, `".$nomtableLigne."` l WHERE l.idDesignation = d.idDesignation && p.idPagnet= l.idPagnet &&
    // d.classe=0  && p.idClient=0  && p.verrouiller=1 && p.type=0 && ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$date1VD."' AND '".$date2VD."') or (p.datepagej BETWEEN '".$date1VD."' AND '".$date2VD."')) ORDER BY p.idPagnet DESC ";
    // $res=mysql_query($sql);
    $sql="select DISTINCT p.datepagej from `".$nomtablePagnet."` p, `".$nomtableLigne."` l WHERE p.idPagnet= l.idPagnet &&
    l.classe=0  && p.idClient=0  && p.verrouiller=1 && p.type=0 && ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$date1VD."' AND '".$date2VD."') or (p.datepagej BETWEEN '".$date1VD."' AND '".$date2VD."')) ORDER BY p.idPagnet DESC ";
    $res=mysql_query($sql);

} else {
    
    // $sql="select DISTINCT p.datepagej from `".$nomtableDesignation."` d, `".$nomtablePagnet."` p, `".$nomtableLigne."` l WHERE l.idDesignation = d.idDesignation && p.idPagnet= l.idPagnet &&
    // d.classe=0  && p.idClient=0  && p.verrouiller=1 && p.type=0 ORDER BY p.idPagnet DESC ";
    // $res=mysql_query($sql);
    $sql="select DISTINCT p.datepagej from `".$nomtablePagnet."` p, `".$nomtableLigne."` l WHERE p.idPagnet= l.idPagnet &&
    l.classe=0  && p.idClient=0  && p.verrouiller=1 && p.type=0 ORDER BY p.idPagnet DESC ";
    $res=mysql_query($sql);
}

$rows = [];
$t = 0;
while($vd = mysql_fetch_array($res)){
    $rows[] = $vd;
    $t = $t + 1;
}
$nbre=$t;
if ($t==0) {
    $nbre=1;
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
$max = 40;
$i = 0;
$p=1;

// var_dump($nbre[0]);
$nb_page=ceil($nbre/$max);

$image='shop.png';
$pdf-> SetFont("Arial","B",10);
$pdf-> Image('images/'.$image,5,5,20,20);

$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, $p . '/' . $nb_page, 0, 0, 'C');
        
$champ_date = date_create($dateString2); $annee = date_format($champ_date, 'Y');

// observations
$pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
// $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseU"], 0, "L");
$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telPortable"].' / '.$_SESSION["telFixe"], 0, "L");

$num_fact = strtoupper("Liste des totaux des ventes classees par date") ;
$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(40, 30, 130, 8, "DF");
$pdf->SetXY( 40, 30 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 130, 8, $num_fact, 0, 0, 'C');

//print column titles
$pdf->SetFillColor(192);
$pdf->SetFont('Arial','B',11);
$pdf->SetY($y_axis_initial);
$pdf->SetX(5);
$pdf->Cell(110,6,'DATE VENTE',1,0,'C',1);
$pdf->Cell(90,6,'MONTANT TOTAL DES VENTES (FCFA)',1,0,'C',1);


$y_axis = $y_axis + $row_height;

//Select the Products you want to show in your PDF file
$c=0;
$l=1;
$idAvant=0;
$totalTVA=0;
// $produits=array();
// var_dump($produits);
foreach ($rows as $row) {
      // var_dump($stock);

    // $sql="select SUM(prixtotal) as total_ventes from `".$nomtableDesignation."` d, `".$nomtablePagnet."` p, `".$nomtableLigne."` l WHERE l.idDesignation = d.idDesignation && p.idPagnet= l.idPagnet &&
    // d.classe=0  && p.idClient=0  && p.verrouiller=1 && p.type=0 && p.datepagej='".$row['datepagej']."' Group By p.datepagej";
    $sql="select SUM(prixtotal) as total_ventes from `".$nomtablePagnet."` p, `".$nomtableLigne."` l WHERE p.idPagnet=l.idPagnet &&
    l.classe=0  && p.idClient=0  && p.verrouiller=1 && p.type=0 && p.datepagej='".$row['datepagej']."'";
    $res=mysql_query($sql);
    // var_dump($sql);
    $sommeVente = mysql_fetch_array($res);
    $sommeVente = $sommeVente['total_ventes'];//number_format($sommeVente, 0, ',', ' ')
    $dateVente = $row['datepagej'];//

    //If the current row is the last one, create new page and print column title
    if ($i == $max)
    {
        $pdf->AddPage();

        $image='shop.png';
        $pdf-> SetFont("Arial","B",10);
        $pdf-> Image('images/'.$image,5,5,20,20);

        $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, $p . '/' . $nb_page, 0, 0, 'C');

        $champ_date = date_create($dateString2); $annee = date_format($champ_date, 'Y');

        $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
        // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
        $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseU"], 0, "L");
        $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telPortable"].' / '.$_SESSION["telFixe"], 0, "L");

        // $pdf->SetFont( "Arial", "BU", 10 ); $pdf->SetXY( 5, 38 ) ; $pdf->Cell($pdf->GetStringWidth("Stock"), 0, 'Stock', 0, "L");

        // $num_fact = "LISTE DES STOCKS " ;
        // $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 8, "DF");
        // $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');

        $y_axis=33;
        $y_axis_initial=33;

        //print column titles
        $pdf->SetFillColor(192);
        $pdf->SetFont('Arial','B',11);
        $pdf->SetY($y_axis_initial);
        $pdf->SetX(5);
        $pdf->Cell(110,6,'DATE VENTE',1,0,'L',1);
        $pdf->Cell(90,6,'MONTANT TOTAL DES VENTES (FCFA)',1,0,'L',1);
        
        //Go to next row
        $y_axis = $y_axis + $row_height;
        
        //Set $i variable to 0 (first row)
        $i = 0;

    }

    // $designation = strtoupper($produit['designation']);
    // $qte = ' ';


    $pdf->SetY($y_axis);
    $pdf->SetFillColor(255,255,255);
    //$pdf->SetTextColor(255,255,255);
    $pdf->SetX(5);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(110,6,$dateVente,1,0,'C',1);
    $pdf->Cell(90,6,number_format($sommeVente, 0, ',', ' '),1,0,'C',1);


    //Go to next row
    $y_axis = $y_axis + $row_height;
    $i = $i + 1;
    if($i==10){
        $p = $p + 1;
    }

    $l = $l + 1;

    $c = $c + 1;
}

// var_dump($pdf);
$pdf->Output("I", '1');

?>