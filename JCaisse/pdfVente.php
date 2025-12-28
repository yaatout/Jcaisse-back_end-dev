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
    $sqlP1="SELECT DISTINCT p.idPagnet
    FROM `".$nomtablePagnet."` p
    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation 
    WHERE (l.classe = 0 or l.classe = 1 or l.classe=2)  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0  ORDER BY p.idPagnet ASC";
    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
/**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

/**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
    $sqlC="SELECT COUNT(DISTINCT l.numligne)
    FROM `".$nomtableLigne."` l
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet 
    WHERE (l.classe = 0 or l.classe = 1 or l.classe=2) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0   ORDER BY l.numligne ASC ";
    $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
    $nbre = mysql_fetch_array($resC) ; 
/**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ 

/**Debut requete pour Calculer la Somme des Ventes d'Aujourd'hui  **/
    $sqlV="SELECT DISTINCT p.idPagnet, l.classe
    FROM `".$nomtablePagnet."` p
    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
    WHERE (l.classe=0 || l.classe=1 || l.classe=2) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0   ORDER BY p.idPagnet DESC";
    $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());
    $T_ventes = 0 ;
    $S_ventes = 0;
    $T_remises = 0 ;
    $S_remises = 0;
    $T_depenses = 0;
    $S_depenses = 0;
    while ($pagnet = mysql_fetch_array($resV)) {
        if ($pagnet['classe']==0 || $pagnet['classe']==1) {
           
            $sqlS="SELECT SUM(totalp)
            FROM `".$nomtablePagnet."`
            where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."' && type=0  ORDER BY idPagnet DESC";
            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
            $S_ventes = mysql_fetch_array($resS);
            $T_ventes = $S_ventes[0] + $T_ventes;

            $sqlS="SELECT SUM(remise)
            FROM `".$nomtablePagnet."`
            where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."' && type=0   ORDER BY idPagnet DESC";
            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
            $S_remises = mysql_fetch_array($resS);
            $T_remises = $S_remises[0] + $T_remises;

        } else if ($pagnet['classe']==2) {
            
            $sqlS="SELECT SUM(totalp)
            FROM `".$nomtablePagnet."`
            where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."' && type=0  ORDER BY idPagnet DESC";
            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
            $S_depenses = mysql_fetch_array($resS);
            $T_depenses = $S_depenses[0] + $T_depenses;
        }
    }
/**Fin requete pour Calculer la Somme des Ventes d'Aujourd'hui **/

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

$pdf->SetFont( "Arial", "BU", 10 ); $pdf->SetXY( 5, 38 ) ; $pdf->Cell($pdf->GetStringWidth("Ventes"), 0, 'Ventes', 0, "L");

if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
    //print column titles
    $pdf->SetFillColor(192);
    $pdf->SetFont('Arial','B',12);
    $pdf->SetY($y_axis_initial);
    $pdf->SetX(5);
    $pdf->Cell(90,6,'Reference',1,0,'L',1);
    $pdf->Cell(15,6,'Qte',1,0,'L',1);
    $pdf->Cell(20,6,'Prix',1,0,'L',1);
    $pdf->Cell(20,6,'Prix TT',1,0,'L',1);
    $pdf->Cell(20,6,'TVA 18%',1,0,'L',1);
    $pdf->Cell(20,6,'Remise',1,0,'L',1);
    $pdf->Cell(15,6,'Heure',1,0,'L',1);
}
else{
    //print column titles
    $pdf->SetFillColor(192);
    $pdf->SetFont('Arial','B',12);
    $pdf->SetY($y_axis_initial);
    $pdf->SetX(5);
    $pdf->Cell(90,6,'Reference',1,0,'L',1);
    $pdf->Cell(20,6,'Unite',1,0,'L',1);
    $pdf->Cell(15,6,'Qte',1,0,'L',1);
    $pdf->Cell(20,6,'Prix',1,0,'L',1);
    $pdf->Cell(20,6,'Prix TT',1,0,'L',1);
    $pdf->Cell(20,6,'Remise',1,0,'L',1);
    $pdf->Cell(15,6,'Heure',1,0,'L',1);
}


$y_axis = $y_axis + $row_height;

//Select the Products you want to show in your PDF file
$c=0;
$l=1;
$idAvant=0;
$totalTVA=0;
if(mysql_num_rows($resP1)){
    while ($pagnets = mysql_fetch_array($resP1)) { 

        $sqlP="select * from `".$nomtablePagnet."` where idPagnet='".$pagnets["idPagnet"]."' ";
        $resP=mysql_query($sqlP);
        $pagnet = mysql_fetch_array($resP); 

        $sql8="SELECT * 
        FROM `".$nomtableLigne."` l
        WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne ASC";
        $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
        while ($ligne = mysql_fetch_array($res8)) {

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

                $pdf->SetFont( "Arial", "BU", 10 ); $pdf->SetXY( 5, 38 ) ; $pdf->Cell($pdf->GetStringWidth("Ventes"), 0, 'Ventes', 0, "L");

                $y_axis=43;
                
                if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                    //print column titles
                    $pdf->SetFillColor(192);
                    $pdf->SetFont('Arial','B',12);
                    $pdf->SetY($y_axis_initial);
                    $pdf->SetX(5);
                    $pdf->Cell(90,6,'Reference',1,0,'L',1);
                    $pdf->Cell(15,6,'Qte',1,0,'L',1);
                    $pdf->Cell(20,6,'Prix',1,0,'L',1);
                    $pdf->Cell(20,6,'Prix TT',1,0,'L',1);
                    $pdf->Cell(20,6,'TVA 18%',1,0,'L',1);
                    $pdf->Cell(20,6,'Remise',1,0,'L',1);
                    $pdf->Cell(15,6,'Heure',1,0,'L',1);
                }
                else{
                    //print column titles
                    $pdf->SetFillColor(192);
                    $pdf->SetFont('Arial','B',12);
                    $pdf->SetY($y_axis_initial);
                    $pdf->SetX(5);
                    $pdf->Cell(90,6,'Reference',1,0,'L',1);
                    $pdf->Cell(20,6,'Unite',1,0,'L',1);
                    $pdf->Cell(15,6,'Qte',1,0,'L',1);
                    $pdf->Cell(20,6,'Prix',1,0,'L',1);
                    $pdf->Cell(20,6,'Prix TT',1,0,'L',1);
                    $pdf->Cell(20,6,'Remise',1,0,'L',1);
                    $pdf->Cell(15,6,'Heure',1,0,'L',1);
                }
                
                //Go to next row
                $y_axis = $y_axis + $row_height;
                
                //Set $i variable to 0 (first row)
                $i = 0;

            }

            $sqlD="select * from `".$nomtableDesignation."` where idDesignation='".$ligne["idDesignation"]."' ";
            $resD=mysql_query($sqlD);
            $produit = mysql_fetch_assoc($resD);

            $designation = strtoupper($ligne['designation']);
            $quantite = $ligne['quantite'];
            $categorie = strtoupper($produit['categorie']);
            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                $prixU = number_format($ligne['prixPublic'], 2, ',', ' ');
            }
            else{
                $prixU = number_format($ligne['prixunitevente'], 2, ',', ' ');
                $unite = $ligne['unitevente'];
            }
            $prixT = number_format($ligne['prixtotal'], 2, ',', ' ');
            $remise = $pagnet['remise'];
            $heure = $pagnet['heurePagnet'];

            if($c%2==0){
                $pdf->SetY($y_axis);
                if ($ligne['classe'] == 2) {
                    
                    $pdf->SetFillColor(222,18,6);
                } else {
                    
                    $pdf->SetFillColor(255,255,255);
                }
                //$pdf->SetTextColor(255,255,255);
                $pdf->SetX(5);
                $pdf->SetFont('Arial','B',8);
                if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                    $pdf->Cell(90,6,$designation.' '.$categorie,1,0,'L',1);
                    $pdf->Cell(15,6,$quantite,1,0,'L',1);
                    $pdf->Cell(20,6,$prixU,1,0,'L',1);
                    $pdf->Cell(20,6,$prixT,1,0,'L',1);
                    if($produit['tva']==1){
                        $tva=number_format((($ligne['prixtotal'] * 18)/100), 2, ',', ' ');
                        $pdf->Cell(20,6,$tva,1,0,'L',1);
                        $totalTVA=$totalTVA + (($ligne['prixtotal'] * 18)/100);
                    }
                    else{
                        $pdf->Cell(20,6,'0.00',1,0,'L',1);
                    }
                    if($idAvant==$pagnet['idPagnet']){
                        if($i==($max - 1) || $nbre[0]==$l){
                            $pdf->Cell(20,6,'  ',1,0,'R',0);
                        }
                        else{
                            $pdf->Cell(20,6,'  ',0,0,'R',0);
                        }
                    }
                    else{
                        $pdf->Cell(20,6,$remise,1,0,'R',1);
                    }
                    $pdf->Cell(15,6,$heure,1,0,'R',1);
                    }
                else{
                    $pdf->Cell(90,6,$designation.' '.$categorie,1,0,'L',1);
                    $pdf->Cell(20,6,$unite,1,0,'L',1);
                    $pdf->Cell(15,6,$quantite,1,0,'L',1);
                    $pdf->Cell(20,6,$prixU,1,0,'L',1);
                    $pdf->Cell(20,6,$prixT,1,0,'L',1);
                    if($idAvant==$pagnet['idPagnet']){
                        if($i==($max - 1) || $nbre[0]==$l){
                            $pdf->Cell(20,6,'  ',1,0,'R',0);
                        }
                        else{
                            $pdf->Cell(20,6,'  ',0,0,'R',0);
                        }
                    }
                    else{
                        $pdf->Cell(20,6,$remise,1,0,'R',1);
                    }
                    $pdf->Cell(15,6,$heure,1,0,'R',1);
                }
                
            }
            else{
                $pdf->SetY($y_axis);
                if ($ligne['classe'] == 2) {
                    
                    $pdf->SetFillColor(222,18,6);
                } else {
                    
                    $pdf->SetFillColor(200,210,230);
                }
                //$pdf->SetTextColor(255,255,255);
                $pdf->SetX(5);
                $pdf->SetFont('Arial','B',8);
                if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                    $pdf->Cell(90,6,$designation.' '.$categorie,1,0,'L',1);
                    $pdf->Cell(15,6,$quantite,1,0,'L',1);
                    $pdf->Cell(20,6,$prixU,1,0,'L',1);
                    $pdf->Cell(20,6,$prixT,1,0,'L',1);
                    if($produit['tva']==1){
                        $tva=number_format((($ligne['prixtotal'] * 18)/100), 2, ',', ' ');
                        $pdf->Cell(20,6,$tva,1,0,'L',1);
                        $totalTVA=$totalTVA + (($ligne['prixtotal'] * 18)/100);
                    }
                    else{
                        $pdf->Cell(20,6,'0.00',1,0,'L',1);
                    }
                    if($idAvant==$pagnet['idPagnet']){
                        if($i==($max - 1) || $nbre[0]==$l){
                            $pdf->Cell(20,6,'  ',1,0,'R',0);
                        }
                        else{
                            $pdf->Cell(20,6,'  ',0,0,'R',0);
                        }
                    }
                    else{
                        $pdf->Cell(20,6,$remise,1,0,'R',1);
                    }
                    $pdf->Cell(15,6,$heure,1,0,'R',1);
                }
                else{
                    $pdf->Cell(90,6,$designation.' '.$categorie,1,0,'L',1);
                    $pdf->Cell(20,6,$unite,1,0,'L',1);
                    $pdf->Cell(15,6,$quantite,1,0,'L',1);
                    $pdf->Cell(20,6,$prixU,1,0,'L',1);
                    $pdf->Cell(20,6,$prixT,1,0,'L',1);
                    if($idAvant==$pagnet['idPagnet']){
                        if($i==($max - 1) || $nbre[0]==$l){
                            $pdf->Cell(20,6,'  ',1,0,'R',0);
                        }
                        else{
                            $pdf->Cell(20,6,'  ',0,0,'R',0);
                        }
                    }
                    else{
                        $pdf->Cell(20,6,$remise,1,0,'R',1);
                    }
                    $pdf->Cell(15,6,$heure,1,0,'R',1);
                }

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
            $pdf->Cell(50,8,number_format($T_ventes, 2, ',', ' '),1,1,'L',0);
            $pdf->SetX(120);
            $pdf->Cell(35,8,'Total Remise',1,0,'L',1);
            $pdf->Cell(50,8,number_format($T_remises, 2, ',', ' '),1,1,'L',0);
            $pdf->SetX(120);
            $pdf->Cell(35,8,'TOTAL',1,0,'L',1);
            $pdf->Cell(50,8,number_format(($T_ventes - $T_remises - $T_depenses), 2, ',', ' '),1,1,'L',0);
            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                $pdf->SetX(120);
                $pdf->Cell(35,8,'TOTAL TVA 18%',1,0,'L',1);
                $pdf->Cell(50,8,number_format($totalTVA, 2, ',', ' '),1,0,'L',0);
            }


            
        }
                

        $c = $c + 1;
    }
    
}
else{
    $pdf->SetFont( "Arial", "BU", 12); $pdf->SetXY( 90, 70 ) ; $pdf->Cell($pdf->GetStringWidth("Aucune Vente"), 0, 'Aucune Vente', 0, "L");
}

$pdf->Output("I",$num_fact.'.pdf');
}

?>