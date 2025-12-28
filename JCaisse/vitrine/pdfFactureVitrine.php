<?php

session_start();

if(!$_SESSION['iduser']){
    header('Location:../../index.php');
}

if($_SESSION['vitrine']==0){
header('Location:../accueil.php');
}

require('../connection.php');
require('../connectionVitrine.php');

require('../declarationVariables.php');

require('../fpdf/fpdf.php');

include('../phpqrcode/qrlib.php');

if (isset($_POST['idPanier'])) {

    $idPanier=htmlspecialchars(trim($_POST['idPanier']));
    /*
    $sql1="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
    $res1 = mysql_query($sql1) or die ("persoonel requête 1".mysql_error());
    $pagnet = mysql_fetch_array($res1);
    */
    $req1 = $bddV->prepare("SELECT * FROM panier WHERE idPanier =:idPanier");
    $req1->execute(array(
        'idPanier' =>$idPanier
        )) or die(print_r($req1->errorInfo()));
    $pagnet=$req1->fetch();

    /*
    $sqlC="SELECT COUNT(DISTINCT l.numligne)
    FROM `".$nomtableLigne."` l
    WHERE idPagnet ='".$idPagnet."' ORDER BY l.numligne ASC ";
    $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
    $nbre = mysql_fetch_array($resC) ;
    */

    $req2 = $bddV->prepare("SELECT COUNT(DISTINCT idArticle) FROM ligne WHERE idPanier =:idPanier AND idBoutique =:idBoutique ORDER BY idArticle ASC");
    $req2->execute(array(
        'idPanier' =>$idPanier,
        'idBoutique' =>$_SESSION['idBoutique'],
        )) or die(print_r($req2->errorInfo()));
    $nbre=$req2->fetch();


//Create new pdf file
$pdf=new FPDF();

//Disable automatic page break
$pdf->SetAutoPageBreak(false);

//Add first page
$pdf->AddPage();

//set initial y axis position per page
$y_axis_initial = 65;

//Set Row Height
$row_height = 6;

$y_axis=65;

//Set maximum rows per page
$max = 19;
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
$pdf-> Image('../images/'.$image,7,5,20,20);

// logo : 80 de largeur et 55 de hauteur
//$pdf->Image('images/'.$image, 10, 10, 80, 55);

$pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 150, 7 ) ; $pdf->MultiCell(190, 4, 'Date : ', 0, "L");
$pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 161, 7 ) ; $pdf->MultiCell(190, 4, $pagnet['dateConfirmer'], 0, "L");
//$champ_date = date_create($dateString2); $annee = date_format($champ_date, 'Y');
$num_fact = "FACTURE_#".$pagnet["idPanier"] ;
$numfact = "FACTURE  : #".$pagnet["idPanier"] ;
$pdf->SetLineWidth(0.3); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 81, 9, "DF");
$pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 80, 8, $numfact, 0, 0, 'C');

    // observations
$pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 27, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
// $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
$pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 25, 14 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
$pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 25, 20 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");

$text = "https://yaatout.org/".$_SESSION['labelB'];

// $path variable store the location where to 
// store image and $file creates directory name
// of the QR code file by using 'uniqid'
// uniqid creates unique id based on microtime
$path = '../images/';
$file = $path.uniqid().".png";
  
// $ecc stores error correction capability('L')
$ecc = 'L';
$pixel_Size = 4;
$frame_Size = 10;
  
// Generates QR Code and Stores it in directory given
QRcode::png($text, $file, $ecc, $pixel_Size);

$pdf->Image($file,50,35,25,25);

$req3 = $bddV->prepare("SELECT * FROM client WHERE idClient =:idClient");
$req3->execute(array(
    'idClient' =>$pagnet["idClient"]
    )) or die(print_r($req3->errorInfo()));
$client=$req3->fetch();

  if ($client!=null) {
    $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 38 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
    $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 134, 38 ) ; $pdf->MultiCell(190, 4, $client["nom"], 0, "L");
    $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 43 ) ; $pdf->MultiCell(190, 4, 'Prenom :', 0, "L");
    $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 140, 43 ) ; $pdf->MultiCell(190, 4, $client["prenom"], 0, "L");
    $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 48 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
    $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 140, 48 ) ; $pdf->MultiCell(190, 4, $client["adresse"], 0, "L");
    $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 53 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
    $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 144, 53 ) ; $pdf->MultiCell(190, 4, $client["telephone"], 0, "L");
  }


// ***********************
// le cadre des articles
// ***********************
// cadre avec 18 lignes max ! et 118 de hauteur --> 95 + 118 = 213 pour les traits verticaux
$pdf->SetLineWidth(0.3); $pdf->Rect(7, 70, 196, 162, "D");
// cadre titre des colonnes
$pdf->Line(7, 80, 203, 80);
// les traits verticaux colonnes
$pdf->Line(130, 70, 130, 232); $pdf->Line(150, 70, 150, 232);  $pdf->Line(174, 70, 174, 232);
// titre colonne
$pdf->SetXY( 1, 71 ); $pdf->SetFont('Arial','B',10); $pdf->Cell( 140, 8, "DESIGNATION", 0, 0, 'C');
$pdf->SetXY( 133, 71 ); $pdf->SetFont('Arial','B',10); $pdf->Cell( 13, 8, "QUANTITE", 0, 0, 'C');
$pdf->SetXY( 151, 71 ); $pdf->SetFont('Arial','B',10); $pdf->Cell( 22, 8, "PRIX UNITE", 0, 0, 'C');
//$pdf->SetXY( 177, 71 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 10, 8, "TVA", 0, 0, 'C');
$pdf->SetXY( 179, 71 ); $pdf->SetFont('Arial','B',10); $pdf->Cell( 22, 8, "PRIX TOTAL", 0, 0, 'C');

$y_axis = $y_axis + $row_height;

//Select the Products you want to show in your PDF file
$c=0;
$l=1;
$idAvant=0;
$totalTVA=0;
if($pagnet!=null){
    /*
    $sql3="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ORDER BY numligne DESC";
    $res3 = mysql_query($sql3) or die ("persoonel requête 2".mysql_error());
    */
    $req4 = $bddV->prepare("SELECT * FROM ligne WHERE idPanier =:idPanier AND idBoutique =:idBoutique AND barrer = 0 ORDER BY idArticle DESC");
    $req4->execute(array(
        'idPanier' =>$idPanier,
        'idBoutique' =>$_SESSION['idBoutique'],
        )) or die(print_r($req4->errorInfo()));
    $pdf->SetFont('Arial','',10);
    $y = 73;
    while ($ligne=$req4->fetch()) {


                //If the current row is the last one, create new page and print column title
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
                    $pdf-> Image('../images/'.$image,7,5,20,20);

                    // logo : 80 de largeur et 55 de hauteur
                    //$pdf->Image('images/'.$image, 10, 10, 80, 55);
                    $y = 73;

                    $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 150, 7 ) ; $pdf->MultiCell(190, 4, 'Date : ', 0, "L");
                    $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 161, 7 ) ; $pdf->MultiCell(190, 4, $pagnet['	dateConfirmer'], 0, "L");
                    //$champ_date = date_create($dateString2); $annee = date_format($champ_date, 'Y');
                    $num_fact = "FACTURE_#".$pagnet["idPanier"] ;
                    $numfact = "FACTURE  : #".$pagnet["idPanier"] ;
                    $pdf->SetLineWidth(0.3); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 81, 9, "DF");
                    $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 80, 8, $numfact, 0, 0, 'C');

                            // observations
                    $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 27, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
                    // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
                    $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 25, 14 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
                    $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 25, 20 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");

                    
                    $text = "https://yaatout.org/".$_SESSION['labelB'];

                    // $path variable store the location where to 
                    // store image and $file creates directory name
                    // of the QR code file by using 'uniqid'
                    // uniqid creates unique id based on microtime
                    $path = '../images/';
                    $file = $path.uniqid().".png";
                    
                    // $ecc stores error correction capability('L')
                    $ecc = 'L';
                    $pixel_Size = 4;
                    $frame_Size = 10;
                    
                    // Generates QR Code and Stores it in directory given
                    QRcode::png($text, $file, $ecc, $pixel_Size);

                    $pdf->Image($file,50,35,25,25);
                    
                    $req3 = $bddV->prepare("SELECT * FROM client WHERE idClient =:idClient");
                    $req3->execute(array(
                        'idClient' =>$pagnet["idClient"]
                        )) or die(print_r($req3->errorInfo()));
                    $client=$req3->fetch();

                    if ($client!=null) {
                        $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 38 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
                        $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 134, 38 ) ; $pdf->MultiCell(190, 4, $client["nom"], 0, "L");
                        $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 43 ) ; $pdf->MultiCell(190, 4, 'Prenom :', 0, "L");
                        $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 140, 43 ) ; $pdf->MultiCell(190, 4, $client["prenom"], 0, "L");
                        $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 48 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
                        $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 140, 48 ) ; $pdf->MultiCell(190, 4, $client["adresse"], 0, "L");
                        $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 53 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
                        $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 144, 53 ) ; $pdf->MultiCell(190, 4, $client["telephone"], 0, "L");
                    }

                    // ***********************
                    // le cadre des articles
                    // ***********************
                    // cadre avec 18 lignes max ! et 118 de hauteur --> 95 + 118 = 213 pour les traits verticaux
                    $pdf->SetLineWidth(0.3); $pdf->Rect(7, 70, 196, 162, "D");
                    // cadre titre des colonnes
                    $pdf->Line(7, 80, 203, 80);
                    // les traits verticaux colonnes
                    $pdf->Line(130, 70, 130, 232); $pdf->Line(150, 70, 150, 232);  $pdf->Line(174, 70, 174, 232);
                    // titre colonne
                    $pdf->SetXY( 1, 71 ); $pdf->SetFont('Arial','B',10); $pdf->Cell( 140, 8, "DESIGNATION", 0, 0, 'C');
                    $pdf->SetXY( 133, 71 ); $pdf->SetFont('Arial','B',10); $pdf->Cell( 13, 8, "QUANTITE", 0, 0, 'C');
                    $pdf->SetXY( 151, 71 ); $pdf->SetFont('Arial','B',10); $pdf->Cell( 22, 8, "PRIX UNITE", 0, 0, 'C');
                    //$pdf->SetXY( 177, 71 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 10, 8, "TVA", 0, 0, 'C');
                    $pdf->SetXY( 179, 71 ); $pdf->SetFont('Arial','B',10); $pdf->Cell( 22, 8, "PRIX TOTAL", 0, 0, 'C');

                    //Go to next row
                    $y_axis = $y_axis + $row_height;

                    //Set $i variable to 0 (first row)
                    $i = 0;

                }

                $pdf->SetXY( 7, $y+10 ); $pdf->Cell( 140, 5, utf8_decode($ligne['designation']), 0, 0, 'L');
                // qte
                $pdf->SetXY( 130, $y+10 ); $pdf->Cell( 13, 5, strrev(wordwrap(strrev($ligne['quantite']), 3, ' ', true)), 0, 0, 'R');
                // PU
                $nombre_format_francais = number_format($ligne['prix'], 0, ',', ' ');
                $pdf->SetXY( 156, $y+10 ); $pdf->Cell( 18, 5, $nombre_format_francais, 0, 0, 'R');
                // Taux
                $nombre_format_francais = number_format($ligne['prix']*$ligne['quantite'], 0, ',', ' ');
                $pdf->SetXY( 182, $y+10 ); $pdf->Cell( 18, 5, $nombre_format_francais, 0, 0, 'R');


                $pdf->Line(7, $y+15, 203, $y+15);

                $y += 8;


                //Go to next row
                $y_axis = $y_axis + $row_height;
                $i = $i + 1;
                if($i==10){
                    $p = $p + 1;
                }

                $l = $l + 1;



                  // var_dump($nbre[0].'----'.$l);
        // si derniere page alors afficher total
        //if ($nbre[0]==$l)
        //{
            // les totaux, on n'affiche que le HT. le cadre après les lignes, demarre a 213
            $pdf->SetLineWidth(0.3); $pdf->SetFillColor(192); $pdf->Rect(7, 232, 90, 8, "DF");
            // HT, la TVA et TTC sont calculés après

            // trait vertical cadre totaux, 8 de hauteur -> 213 + 8 = 221
            $pdf->Rect(7, 232, 196, 8, "D"); $pdf->Line(95, 232, 95, 232); $pdf->Line(165, 232, 165, 232);

            $nombre_format_francais = "Net a payer TTC : " . number_format($pagnet['total'], 0, ',', ' ') . " FCFA";
            $pdf->SetFont('Arial','B',12); $pdf->SetXY( 5, 232 ); $pdf->Cell( 90, 8, $nombre_format_francais, 0, 0, 'C');

            $sqlU="select * from `aaa-utilisateur` where idutilisateur='".$_SESSION['iduser']."' ";
            $resU=mysql_query($sqlU);
            $user=mysql_fetch_array($resU);
            $pdf->SetFont( "Arial", "B", 8 ); $pdf->SetXY( 8, 242 ) ; $pdf->MultiCell(75, 4, 'Fait par : '.$user['prenom'].' '.strtoupper($user['nom']), 0, "L");

            $pdf->SetY(240);
            $pdf->SetFont('Arial','B',8);
            $pdf->SetX(130);
            $pdf->Cell(33,8,'Total Prix TTC',1,0,'L',1);
            $pdf->Cell(40,8,number_format($pagnet['total'], 0, ',', ' ')." FCFA",1,1,'L',0);
            $pdf->SetX(130);
            $pdf->Cell(33,8,'Remise',1,0,'L',1);
            $pdf->Cell(40,8,number_format(0, 0, ',', ' ')." FCFA",1,1,'L',0);
       // }


        $c = $c + 1;

        $y1 = 270;
        //Positionnement en bas et tout centrer
        $pdf->SetXY( 1, $y1 ); $pdf->SetFont('Arial','B',10);
        $pdf->Cell( $pdf->GetPageWidth(), 5, strtoupper($_SESSION["labelB"]), 0, 0, 'C');

        $pdf->SetFont('Arial','',10);
        if($_SESSION['RegistreCom']!='' && $_SESSION['RegistreCom']!=null){
            $pdf->SetXY( 1, $y1 + 4 );
            $pdf->Cell( $pdf->GetPageWidth(), 5, "REGISTRE DE COMMERCE : ".$_SESSION['RegistreCom'], 0, 0, 'C');
        }
        if($_SESSION['Ninea']!='' && $_SESSION['Ninea']!=null){
            $pdf->SetXY( 1, $y1 + 8 );
            $pdf->Cell( $pdf->GetPageWidth(), 5, "NINEA : ".$_SESSION['Ninea'], 0, 0, 'C');
        }
        $pdf->SetXY( 1, $y1 + 12 );
        $pdf->Cell( $pdf->GetPageWidth(), 5, "ADRESSE : ".$_SESSION["adresseB"], 0, 0, 'C');

        $pdf->SetXY( 1, $y1 + 16 );
        $pdf->Cell( $pdf->GetPageWidth(), 5, "TELEPHONE : ".$_SESSION["telBoutique"], 0, 0, 'C');
    }
}
else{
    $pdf->SetFont( "Arial", "BU", 12); $pdf->SetXY( 90, 70 ) ; $pdf->Cell($pdf->GetStringWidth("Aucune Vente"), 0, 'Aucune Vente', 0, "L");
}


  if ($client!=null) {
    $pdf->Output("D",$client["prenom"].' '.strtoupper($client["nom"]).' '.$num_fact.'.pdf');
   }
   else{
    $pdf->Output("I", $idPanier);
   }


}

?>
