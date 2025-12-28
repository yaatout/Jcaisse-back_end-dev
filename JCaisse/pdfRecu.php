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


if (isset($_POST['idVersement'])) {

    // le mettre au debut car plante si on declare $mysqli avant !
    $pdf = new FPDF( 'P', 'mm', 'A4' );

    $idVersement=htmlspecialchars(trim($_POST['idVersement']));
    $sql1="SELECT * FROM `".$nomtableVersement."` where idVersement=".$idVersement." ";
    $res1 = mysql_query($sql1) or die ("persoonel requête 1".mysql_error());
    $versement = mysql_fetch_array($res1);

    // on declare $mysqli apres !
    //$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    // cnx a la base
    //mysqli_select_db($mysqli, DATABASE) or die('Erreur de connection à la BDD : ' .mysqli_connect_error());
    // FORCE UTF-8
//    mysqli_query($mysqli, "SET NAMES UTF8");

//$var_id_facture = $_GET['id_param'];

    // on sup les 2 cm en bas
    $pdf->SetAutoPagebreak(False);
    $pdf->SetMargins(0,0,0);

    // nb de page pour le multi-page : 18 lignes
    $nb_page = 1;

    $num_page = 1; $limit_inf = 0; $limit_sup = 18;
    while ($num_page <= $nb_page)
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
        $pdf-> Image('images/'.$image,7,5,20,20);
        
        // logo : 80 de largeur et 55 de hauteur
        //$pdf->Image('images/'.$image, 10, 10, 80, 55);

        $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 150, 7 ) ; $pdf->MultiCell(190, 4, 'Date : ', 0, "L");
        $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 161, 7 ) ; $pdf->MultiCell(190, 4, $versement['dateVersement'], 0, "L"); 
    //$champ_date = date_create($dateString2); $annee = date_format($champ_date, 'Y');
    $num_fact = "RECU_#".$versement["idVersement"] ;
    $numfact = "RECU  : #".$versement["idVersement"] ;
    $pdf->SetLineWidth(0.3); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 81, 9, "DF");
    $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 80, 8, $numfact, 0, 0, 'C');

            // observations
    $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 27, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
   // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
    $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 25, 14 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
    $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 25, 20 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");

   // $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 5, 38 ) ; $pdf->MultiCell(190, 4, 'No Facture :', 0, "L");
   // $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 27, 38 ) ; $pdf->MultiCell(190, 4, '#'.$pagnet["idPagnet"], 0, "L");

    //$pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 5, 46 ) ; $pdf->MultiCell(190, 4, 'Mode de Reglement : '.$dateString2, 0, "L");
        
        // nom du fichier final
        $nom_file = "fact_" . $annee .'-' . str_pad($versement['dateVersement'], 4, '0', STR_PAD_LEFT) . ".pdf";
        
        // si derniere page alors afficher total
        if ($num_page == $nb_page)
        {
            // les totaux, on n'affiche que le HT. le cadre après les lignes, demarre a 213
            $pdf->SetLineWidth(0.3); $pdf->SetFillColor(192); $pdf->Rect(7, 230, 90, 8, "DF");
            // HT, la TVA et TTC sont calculés après

            // trait vertical cadre totaux, 8 de hauteur -> 213 + 8 = 221
            $pdf->Rect(7, 230, 196, 8, "D"); $pdf->Line(95, 230, 95, 230); $pdf->Line(165, 230, 165, 230);

        }
        
        $sql2="SELECT * FROM `".$nomtableClient."` where idClient=".$versement["idClient"];
        $res2 = mysql_query($sql2) or die ("persoonel requête 1".mysql_error());
        $client = mysql_fetch_array($res2);

        $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 38 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
        $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 134, 38 ) ; $pdf->MultiCell(190, 4, $client["nom"], 0, "L");
        $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 43 ) ; $pdf->MultiCell(190, 4, 'Prenom :', 0, "L");
        $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 140, 43 ) ; $pdf->MultiCell(190, 4, $client["prenom"], 0, "L");
        $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 48 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
        $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 140, 48 ) ; $pdf->MultiCell(190, 4, $client["adresse"], 0, "L");
        $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 53 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
        $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 144, 53 ) ; $pdf->MultiCell(190, 4, $client["telephone"], 0, "L");
         
        // ***********************
        // le cadre des articles
        // ***********************
        // cadre avec 18 lignes max ! et 118 de hauteur --> 95 + 118 = 213 pour les traits verticaux
        $pdf->SetLineWidth(0.3); $pdf->Rect(7, 70, 196, 160, "D");
        // cadre titre des colonnes
        $pdf->Line(7, 80, 203, 80);
        // les traits verticaux colonnes
        $pdf->Line(110, 70, 110, 230);  $pdf->Line(160, 70, 160, 230);
        // titre colonne
        $pdf->SetXY( 1, 71 ); $pdf->SetFont('Arial','B',10); $pdf->Cell( 120, 8, "MOTIF", 0, 0, 'C');
        $pdf->SetXY( 128, 71 ); $pdf->SetFont('Arial','B',10); $pdf->Cell( 13, 8, "TYPE", 0, 0, 'C');
        $pdf->SetXY( 170, 71 ); $pdf->SetFont('Arial','B',10); $pdf->Cell( 22, 8, "MONTANT", 0, 0, 'C');
        
        // les articles
        $pdf->SetFont('Arial','',10);
        $y = 73;
        $pdf->SetXY( 1, $y+10 ); $pdf->Cell( 120, 5, 'VERSEMENT', 0, 0, 'C');
        if($versement['paiement']!=null){
            $pdf->SetXY( 130, $y+10 ); $pdf->Cell( 11, 5, strtoupper($versement['paiement']), 0, 0, 'C');
         }
         else{
            $pdf->SetXY( 130, $y+10 ); $pdf->Cell( 11, 5, 'ESPECES', 0, 0, 'C');
         }
        $nombre_format_francais = number_format($versement['montant'], 0, ',', ' ');
        $pdf->SetXY( 172, $y+10 ); $pdf->Cell( 18, 5, $nombre_format_francais, 0, 0, 'C');
        /*
        $sql3="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ORDER BY numligne DESC";
        $res3 = mysql_query($sql3) or die ("persoonel requête 2".mysql_error());

        while ($ligne =  mysql_fetch_assoc($res3))
        {
            // libelle
            $pdf->SetXY( 7, $y+10 ); $pdf->Cell( 140, 5, strtoupper($ligne['designation']), 0, 0, 'L');
            // qte
            $pdf->SetXY( 130, $y+10 ); $pdf->Cell( 13, 5, strrev(wordwrap(strrev($ligne['quantite']), 3, ' ', true)), 0, 0, 'R');
            // PU
            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                $nombre_format_francais = number_format($ligne['prixPublic'], 2, ',', ' ');
                $pdf->SetXY( 156, $y+10 ); $pdf->Cell( 18, 5, $nombre_format_francais, 0, 0, 'R');
                // Taux
                $nombre_format_francais = number_format($ligne['prixPublic']*$ligne['quantite'], 2, ',', ' ');
                $pdf->SetXY( 180, $y+10 ); $pdf->Cell( 18, 5, $nombre_format_francais, 0, 0, 'R');
            }
            else{
                $nombre_format_francais = number_format($ligne['prixunitevente'], 2, ',', ' ');
                $pdf->SetXY( 156, $y+10 ); $pdf->Cell( 18, 5, $nombre_format_francais, 0, 0, 'R');
                // Taux
                $nombre_format_francais = number_format($ligne['prixunitevente']*$ligne['quantite'], 2, ',', ' ');
                $pdf->SetXY( 182, $y+10 ); $pdf->Cell( 18, 5, $nombre_format_francais, 0, 0, 'R');
            }

            
            $pdf->Line(7, $y+15, 203, $y+15);
            
            $y += 8;
        }
        */
        // si derniere page alors afficher cadre des TVA
        if ($num_page == $nb_page)
        {
            $nombre_format_francais = "Net verser TTC : " . number_format($versement['montant'], 0, ',', ' ') . " FCFA";
            $pdf->SetFont('Arial','B',12); $pdf->SetXY( 5, 230 ); $pdf->Cell( 90, 8, $nombre_format_francais, 0, 0, 'C');
        }

        $sqlU="select * from `aaa-utilisateur` where idutilisateur='".$versement['iduser']."' ";
        $resU=mysql_query($sqlU);
        $user=mysql_fetch_array($resU);
        $pdf->SetFont( "Arial", "B", 8 ); $pdf->SetXY( 8, 240 ) ; $pdf->MultiCell(75, 4, 'Fait par : '.$user['prenom'].' '.strtoupper($user['nom']), 0, "L");

        // **************************
        // pied de page
        // **************************
        
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
        
        // par page de 18 lignes
        $num_page++; $limit_inf += 18; $limit_sup += 18; 
    }
    
    //if ($client!=null) {
     $pdf->Output("D",$client["prenom"].' '.strtoupper($client["nom"]).' '.$num_fact.'.pdf');
    //}
    //$pdf->Output("I", $client["prenom"].' '.strtoupper($client["nom"]).' '.$num_fact);
    //$pdf->Output('factures/'.$num_fact.'.pdf','F');
    /*
    if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste") && ($_SESSION['proprietaire']==1)) {
        $filename = 'factures/'.$num_fact.'.pdf';
        $result="0";
        if (file_exists($filename)) {
            // envoie d'un mail avec une pièce jointe

            // Destinataires. 
            //$destinataires = "camalack@gmail.com"; 

            // Objet. 
            $objet = "Facture numéro : ".$num_fact; 


            // Entêtes supplémentaires. 
            $entêtes  = ""; 
            // -> origine du message 
            $entêtes .= "From: \"JCAISSE\" <facturation@jcaisse.shop>\r\n"; 
            // -> message au format Multipart MIME 
            $entêtes .= "MIME-Version: 1.0\r\n"; 
            $entêtes .= "Content-Type: multipart/mixed; "; 
            $entêtes .= "boundary=\"=J=C=A=I=S=S=E=\"\r\n"; 


            // Message. 
            $message  = ""; 
            //$message = "Je vous informe que ceci est un message au format MIME 1.0 multipart/mixed.\r\n";

            // -> première partie du message (texte proprement dit) 
            //    -> entête de la partie 
            $message .= "--=J=C=A=I=S=S=E=\r\n"; 
            $message .= "Content-Type: text/plain; "; 
            $message .= "charset=iso-8859-1\r\n "; 
            $message .= "Content-Transfer-Encoding: 8bit\r\n"; 
            $message .= "\r\n";   // ligne vide 

            //    -> données de la partie 
            $message .= "Bonjour, \nvoici la facture numero : ".$num_fact." .\n \nCordialement.\nL'equipe JCaisse.\r\n\n\n"; 
            $message .= "\r\n";   // ligne vide 

            // -> deuxième partie du message (pièce-jointe) 
            //    -> entête de la partie 
            $message .= "--=J=C=A=I=S=S=E=\r\n"; 
            $message .= "Content-Type: application/octet-stream; "; 
            $message .= "name=\"".$num_fact.".PDF\"\r\n"; 
            $message .= "Content-Transfer-Encoding: base64\r\n"; 
            $message .= "Content-Disposition: attachment; "; 
            $message .= "filename=\"".$num_fact.".pdf\"\r\n"; 
            $message .= "\r\n";             // ligne vide 

            // lecture du fichier en pièce jointe 
            $sFileAdd = file_get_contents('factures/'.$num_fact.".pdf"); 

            // encodage et découpage des données 
            $sFileAdd = chunk_split(base64_encode($sFileAdd)); 

            // pièce jointe de la partie (intégration dans le message) 
            $message .= "$sFileAdd\r\n"; 
            $message .= "\r\n";             // ligne vide 

            // -> dernière partie du message (texte proprement dit) 
            //    -> entête de la partie
            $message .= "--=J=C=A=I=S=S=E=\r\n";
            $message .= "Content-Type: text/html; charset=\"utf-8\"\r\n";
            $message .= "Content-Transfer-Encoding:8bit\r\n";
            $message .= "\r\n";
            $message .= "\r\n";

            // Délimiteur de fin du message. 
            $message .= "--=J=C=A=I=S=S=E=--\r\n";
            //$email="fallndiaga10@hotmail.fr";
            $email="assanethiam061@gmail.com,fallndiaga10@hotmail.fr,kcamir@hotmail.fr,kcamalack@gmail.com";

            if (mail($email,$objet,$message,$entêtes)) {
                $result="1";
            } 
        }

        exit($result);
    }
    */
}

?>