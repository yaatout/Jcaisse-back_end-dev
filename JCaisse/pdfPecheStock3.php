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

$idEntrepotInventaire = @$_POST["idEntrepotInventaire"];
 if($idEntrepotInventaire!=''){
    $sql3="SELECT * FROM `".$nomtableEntrepot."` where idEntrepot=".$idEntrepotInventaire."";
    $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
    $entrepot = mysql_fetch_assoc($res3);
    $nomEntrepot=$entrepot['nomEntrepot'];
    
    $sql="SELECT DISTINCT d.idDesignation FROM `".$nomtableEntrepotStock."` s, `".$nomtableDesignation."` d, `".$nomtableLigne."` l, `".$nomtablePagnet."` p
    WHERE s.idDesignation = d.idDesignation and d.idDesignation = l.idDesignation and l.idPagnet=p.idPagnet and s.idEntrepot='".$idEntrepotInventaire."' and p.datepagej='".$dateString."' and d.classe=0 ORDER BY d.designation";
    $res=mysql_query($sql);
    
 } else {
    # code...
    $sql="SELECT DISTINCT d.idDesignation FROM `".$nomtableDesignation."` d, `".$nomtableLigne."` l, `".$nomtablePagnet."` p
    WHERE d.idDesignation = l.idDesignation and l.idPagnet=p.idPagnet and p.datepagej='".$dateString."' and d.classe=0 ORDER BY d.designation";
    $res=mysql_query($sql);
 }

// $sql="SELECT * FROM `".$nomtableStock."` s
// LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
// WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0)  ORDER BY s.idStock DESC";
// $res=mysql_query($sql);

// $data = mysql_fetch_array($res); 
// var_dump($data);
while ($stock=mysql_fetch_array($res)) {      
    if ($idEntrepotInventaire != '') {
        # code...
        
        $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
        $res1=mysql_query($sql1);
        $designation=mysql_fetch_array($res1);

        $sqlS="SELECT SUM(quantiteStockCourant)
        FROM `".$nomtableEntrepotStock."`
        where idDesignation ='".$stock['idDesignation']."' AND  idEntrepot ='".$idEntrepotInventaire."' ";
        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
        $S_stock = mysql_fetch_array($resS);
        
        if (($S_stock[0]/$designation['nbreArticleUniteStock'] > 20)) {
            # code...
            $tabIdDesigantion[]=$stock['idDesignation'];
            // $tabIdStock[]=$stock['idStock'];
            $produits[]=$stock;
        }
    } else {
        # code...
        $sqlS="SELECT SUM(quantiteStockCourant)
        FROM `".$nomtableStock."`
        where idDesignation ='".$stock['idDesignation']."'  ";
        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
        $S_stock = mysql_fetch_array($resS);
        
        if ($S_stock[0] > 20) {
        # code...
        $tabIdDesigantion[]=$stock['idDesignation'];
        // $tabIdStock[]=$stock['idStock'];
        $produits[]=$stock;
        }
    }
    # code...
}

// $sqlC="SELECT COUNT(DISTINCT d.designation) FROM `".$nomtableDesignation."` d, `".$nomtableLigne."` l, `".$nomtablePagnet."` p
//       WHERE d.idDesignation = l.idDesignation and l.idPagnet=p.idPagnet and p.datepagej='".$dateString."' and d.classe=0";
// $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
$nbre = count($produits); 
if ($nbre==0) {
    $nbre=1;
}

// var_dump($nbre);


// $sql2="SELECT d.prix,s.quantiteStockCourant,s.idStock,d.prixachat FROM `".$nomtableStock."` s
// LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
// WHERE d.classe=0 ORDER BY s.idStock DESC";
// $res2=mysql_query($sql2);
// $montantPA=0;
// $montantPU=0;
// while ($stock = mysql_fetch_array($res2)) {
//   if($stock['quantiteStockCourant']!=null && $stock['quantiteStockCourant']!=0){
//     $montantPA=$montantPA + ($stock['quantiteStockCourant'] * $stock['prixachat']);
//     $montantPU=$montantPU + ($stock['quantiteStockCourant'] * $stock['prix']);
//   }
// }

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
// $num_fact = "LISTE DES STOCKS : Valeur P.A =  ".number_format(($montantPA * $_SESSION['devise']), 2, ',', ' ')." ".$_SESSION['symbole']." <=>  Valeur P.U =  ".number_format(($montantPU * $_SESSION['devise']), 2, ',', ' ')." ".$_SESSION['symbole'] ;
// $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(60, 30, 140, 8, "DF");
// $pdf->SetXY( 60, 30 ); $pdf->SetFont( "Arial", "B", 11 ); $pdf->Cell( 100, 8, $num_fact, 0, 0, 'C');

// observations
$pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
// $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseU"], 0, "L");
$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telPortable"].' / '.$_SESSION["telFixe"], 0, "L");

if($idEntrepotInventaire!=''){
    $pdf->SetFillColor(192);
    $pdf->SetFont('Arial','B',11);
    $pdf->SetFont( "Arial", "BU", 10 ); $pdf->SetXY( 15, 35 ) ; $pdf->Cell($pdf->GetStringWidth("ENTREPOT : ".strtoupper($nomEntrepot)), 0, 'ENTREPOT : '.strtoupper($nomEntrepot).' => Quantite superieure a 20', 0, "L");
}

//print column titles
$pdf->SetFillColor(192);
$pdf->SetFont('Arial','B',11);
$pdf->SetY($y_axis_initial);
$pdf->SetX(5);
$pdf->Cell(110,6,'REFERENCE',1,0,'L',1);
$pdf->Cell(90,6,'QUANTITE PHYSIQUE',1,0,'L',1);


$y_axis = $y_axis + $row_height;

//Select the Products you want to show in your PDF file
$c=0;
$l=1;
$idAvant=0;
$totalTVA=0;
// $produits=array();
// var_dump($produits);
foreach ($produits as $stock) {
      // var_dump($stock);

    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
    $res1=mysql_query($sql1);
    $produit=mysql_fetch_array($res1);
     
    if ($idEntrepotInventaire !='') {
        # code...
        $sqlS="SELECT SUM(quantiteStockCourant)
        FROM `".$nomtableEntrepotStock."`
        where idDesignation ='".$stock['idDesignation']."' AND  idEntrepot ='".$idEntrepotInventaire."' ";
        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
        $S_stock = mysql_fetch_array($resS);

    } else {
    # code...
        $sqlS="SELECT SUM(quantiteStockCourant)
        FROM `".$nomtableStock."`
        where idDesignation ='".$stock['idDesignation']."'  ";
        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
        $S_stock = mysql_fetch_array($resS);
    }

    // $sqlS="SELECT SUM(quantiteStockCourant)
    // FROM `".$nomtableStock."`
    // where idDesignation ='".$produit['idDesignation']."' ";
    // $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    // $S_produit = mysql_fetch_array($resS);
    
    // if(in_array($produit['idDesignation'], $produits)){
    //   // echo "Existe.";
    // }
    //  else{

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
                $pdf->Cell(110,6,'REFERENCE',1,0,'L',1);
                $pdf->Cell(90,6,'QUANTITE PHYSIQUE',1,0,'L',1);
                
                //Go to next row
                $y_axis = $y_axis + $row_height;
                
                //Set $i variable to 0 (first row)
                $i = 0;
    
            }
    
            $designation = strtoupper($produit['designation']);
            // $qte = ' ';


            $pdf->SetY($y_axis);
            $pdf->SetFillColor(255,255,255);
            //$pdf->SetTextColor(255,255,255);
            $pdf->SetX(5);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(110,6,$designation,1,0,'L',1);
            $pdf->Cell(90,6,'',1,0,'L',1);


            //Go to next row
            $y_axis = $y_axis + $row_height;
            $i = $i + 1;
            if($i==10){
                $p = $p + 1;
            }
    
           // $idAvant=$pagnet['idPagnet'];
            $l = $l + 1;

        $c = $c + 1;
    //     $produits[] = $produit['idDesignation'];
    // }
}

// var_dump($pdf);
$pdf->Output("I", '1');

?>