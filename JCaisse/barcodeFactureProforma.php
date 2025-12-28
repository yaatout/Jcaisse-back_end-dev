<?php
session_start();

if(!$_SESSION['iduser'])
  header('Location:../index.php');

require('connection.php');
require('declarationVariables.php');
include('phpqrcode/qrlib.php');

$sqlU = "SELECT * FROM `aaa-utilisateur` where idutilisateur=".$_SESSION['iduser'];
$resU = mysql_query($sqlU) or die ("persoonel requête 2".mysql_error());
$user = mysql_fetch_array($resU);

// $iduser = $user['idutilisateur'];

 ?>

<!DOCTYPE html>
<html>
<head>
<style>
p.inline {display: inline-block;}
span { font-size: 10px; font-weight : bold; }
</style>
<style type="text/css" media="print">
    @page
    {
        size: auto;   /* auto is the initial value */
        margin: 0px;  /* this affects the margin in the printer settings */
        border: none ;
        padding: 0px;

    }

    body {
  background-image: url('images/pharmacie.png');
  background-repeat: no-repeat;
}

</style>
</head>
<?php require('entetehtml.php'); ?>
<body onload="window.print();">
	<div style="margin-right: 4%;">
		<?php
		//include 'libPHP/barcode128.php';
if (isset($_POST['idPagnet'])) {
  // code...
  $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));
  $sql1="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet."";
	$res1 = mysql_query($sql1) or die ("persoonel requête 1".mysql_error());
  $pagnet = mysql_fetch_array($res1);
	$heureB=$pagnet['heurePagnet'];
  $date1=$pagnet['datepagej'];
	  
  $date2 = new DateTime($date1);
  //R�cup�ration de l'ann�e
  $annee =$date2->format('Y');
  //R�cup�ration du mois
  $mois =$date2->format('m');
  //R�cup�ration du jours
  $jour =$date2->format('d');
	$date=$jour.'-'.$mois.'-'.$annee;
	  
  if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

    $total=$pagnet['totalp'];
    $netPayer=$pagnet['apayerPagnet'];
    $restourne=$pagnet['restourne'];
    $idClient=$pagnet['idClient'];
    $nbColis=htmlspecialchars(trim($_POST['nbColis']));

          echo  '<table class="table"> '; 
              echo '
              <tr align="center">
                  <th colspan="4"><p align="center"><span style="font-size:17px;">'.strtoupper($_SESSION['labelB']).' </span></p></th>
              </tr> 
              <tr align="center">
                <td colspan="4" style="border-bottom: 1px solid black;border-top: 1px solid black;"><span>ADRESSE : '.$_SESSION['adresseB'].' <br> TELEPHONE : '.$_SESSION['telBoutique'].' </span></td>
              </tr>
              ';
              $sql2="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient;
              $res2 = mysql_query($sql2) or die ("persoonel requête 1".mysql_error());
              $client = mysql_fetch_array($res2);
              if ($idClient==0) {
                echo  '
                      <tr>
                        <td colspan="4" style="border-bottom: 1px solid black;border-top: 1px solid black;">
                            <span style="float: left"> PANIER </span> <span style="float: right">#'.$idPagnet.' </span> <br> 
                            <span style="float: left"> DATE </span> <span style="float: right">'.$date.' </span> <br> 
                            <span style="float: left"> HEURE </span> <span style="float: right">'.$heureB.' </span>
                        </td>
                      </tr>
                ';
              }else {
                echo  '
                    <tr>
                    <td colspan="4" style="border-bottom: 1px solid black;border-top: 1px solid black;">
                        <span style="float: left"> PANIER </span> <span style="float: right">#'.$idPagnet.' </span> <br> 
                        <span style="float: left"> CLIENT </span> <span style="float: right">'.$client["prenom"].' '.$client["nom"].' </span> <br> 
                        <span style="float: left"> DATE </span> <span style="float: right">'.$date.' </span> <br> 
                        <span style="float: left"> HEURE </span> <span style="float: right">'.$heureB.' </span>
                    </td>
                  </tr>
              ';
              }

              $sqlV="SELECT DISTINCT l.idEntrepot
              FROM `".$nomtablePagnet."` p
              INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
              WHERE p.idPagnet=".$idPagnet." and l.idEntrepot=".$user['idEntrepot'];
              $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

              while ($vente = mysql_fetch_array($resV)) { 
                $sqlE="SELECT * FROM `". $nomtableEntrepot."` WHERE idEntrepot=".$vente['idEntrepot']." ";
                $resE=mysql_query($sqlE);
                $entrepot = mysql_fetch_array($resE);
          
                    $sql5="SELECT *
                      FROM `aaa-utilisateur`  
                    WHERE idutilisateur =".$_SESSION['iduser'];
                    $res5 = mysql_query($sql5);
                    $utilisateur = mysql_fetch_array($res5);
          
                    if($utilisateur['idEntrepot']!=0 && $utilisateur['idEntrepot']!=null){
                      $sql1="SELECT * FROM `". $nomtableEntrepot."` WHERE idEntrepot=".$utilisateur['idEntrepot']." ";
                      $res1=mysql_query($sql1);
                      $depot = mysql_fetch_array($res1);
                      echo  '
                      <tr align="center">
                        <td colspan="4" style="border-bottom: 1px solid black;border-top: 1px solid black;">
                          <strong>
                            <span> DEPOT : '.$entrepot['nomEntrepot'].' </span><br>
                            <span>ADRESSE : '.$entrepot['adresseEntrepot'].' </span><br> 
                            <span>TELEPHONE : '.$_SESSION['telPortable'].' </span>
                          </strong>
                        </td>
                      </tr>';
                    }
                    else{
                      echo  '
                      <tr align="center">
                        <td colspan="4" style="border-bottom: 1px solid black;border-top: 1px solid black;">
                          <strong>
                            <span style="font-size:16px;" >DEPOT : '.$entrepot['nomEntrepot'].' <br>   
                            <span>ADRESSE : '.$entrepot['adresseEntrepot'].' </span><br> 
                            <span>TELEPHONE : '.$_SESSION['telBoutique'].' </span>
                          </strong>
                        </td>
                      </tr>';
                    }
                   

                    $sql2="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." AND idEntrepot=".$entrepot['idEntrepot']." ORDER BY numligne DESC";
                    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
                    echo ' 
                      <tr  style="border-bottom: 1px solid black;border-top: 1px solid black;">
                        <td colspan="2"><span><strong>REFERENCE<strong></span></td>
                        <td style="border: 1px solid black;"><span><strong>QTE<strong></span></td>
                      </tr>';
                          while ($ligne = mysql_fetch_array($res2)) {
                            /*
                            if (strlen($ligne['designation']) >= 15) {
                              $design=substr($ligne['designation'], 0, 15)."... " ;
                            }
                            else{
                              $restant=(15 - strlen($ligne['designation']));
                              $vide=str_repeat(".", $restant);
                              $design=$ligne['designation'].' '.$vide;
                            } */
                            $design = ucfirst(strtolower($ligne['designation']));
                            echo '<tr>';
                              echo '<td colspan="2" style="border-bottom: 1px solid black;border-top: 1px solid black;"><span>'.$design.'</span></td>
                              <td style="border: 1px solid black;"><span>'.$ligne['quantite'].'</span></td>
                            </tr>';

                          }
                         
              }
              echo  '
          </table> 
        ';

        echo '<span style="margin-left: 10px"><strong><u>Nombre de colis : '.$nbColis.'</u><strong></span><br><br>';
        echo '<br><br><span style="margin-left: 10px"><strong><u>Signature du receveur</u><strong></span><br><br><br>';

        echo '
        <script type="text/javascript">
          setTimeout("self.close()", 3000 ) // after 3 seconds
        </script>
      ';
    
  }
  else{
    $total=$pagnet['totalp'];
    $netPayer=$pagnet['apayerPagnet'];
    $restourne=$pagnet['restourne'];
    $idClient=$pagnet['idClient'];

    echo  '<table class="table" > ';
               /*
              if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                echo  ' <tr align="center">
                      <th colspan="4" ><p align="center"><img class="img-responsive" src="images/pharmacie.png" alt="Chania" width="50px" height="50px"></p></th>
                  </tr> ';
              }
              else {
               
                echo  ' <tr align="center">
                      <th colspan="4" ><p align="center"><img class="img-responsive" src="images/shop.png" alt="Chania" width="50px" height="50px"></p></th>
                  </tr> ';
                  
              }
              */
              echo '
              <tr align="center">
                  <th colspan="4"><p align="center"><span style="font-size:17px;">'.strtoupper($_SESSION['labelB']).' </span></p></th>
              </tr> 
              <tr align="center">
                <td colspan="4" style="border-bottom: 1px solid black;border-top: 1px solid black;"><span>ADRESSE : '.$_SESSION['adresseB'].' <br> TELEPHONE : '.$_SESSION['telBoutique'].' </span></td>
              </tr>
              ';
                  $sql2="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient;
                  $res2 = mysql_query($sql2) or die ("persoonel requête 1".mysql_error());
                  $client = mysql_fetch_array($res2);
              if ($idClient==0) {
                echo  '
                      <tr>
                        <td colspan="4" style="border-bottom: 1px solid black;border-top: 1px solid black;">
                            <span style="float: left"> PANIER </span> <span style="float: right">#'.$idPagnet.' </span> <br> 
                            <span style="float: left"> DATE </span> <span style="float: right">'.$date.' </span> <br> 
                            <span style="float: left"> HEURE </span> <span style="float: right">'.$heureB.' </span>
                        </td>
                      </tr>
                ';
              }else {
                echo  '
                    <tr>
                    <td colspan="4" style="border-bottom: 1px solid black;border-top: 1px solid black;">
                        <span style="float: left"> PANIER </span> <span style="float: right">#'.$idPagnet.' </span> <br> 
                        <span style="float: left"> CLIENT </span> <span style="float: right">'.$client["prenom"].' '.$client["nom"].' </span> <br> 
                        <span style="float: left"> DATE </span> <span style="float: right">'.$date.' </span> <br> 
                        <span style="float: left"> HEURE </span> <span style="float: right">'.$heureB.' </span>
                    </td>
                  </tr>
              ';
              }
              echo ' 
              <tr  style="border-bottom: 1px solid black;border-top: 1px solid black;">
                <td colspan="2"><span><strong>REFERENCE<strong></span></td>
                <td style="border: 1px solid black;"><span><strong>QTE<strong></span></td>
                <td><span><strong>PRIX<strong></span></td>
              </tr>';
              $sql2="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ORDER BY numligne DESC";
              $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
                    while ($ligne = mysql_fetch_array($res2)) {
                      /*
                      if (strlen($ligne['designation']) >= 15) { <strong>
                        $design=substr($ligne['designation'], 0, 15)."... " ;
                      }
                      else{
                        $restant=(15 - strlen($ligne['designation']));
                        $vide=str_repeat(".", $restant);
                        $design=$ligne['designation'].' '.$vide;
                      } */
                      //$design = strtoupper($ligne['designation']);
                      $design = ucfirst(strtolower($ligne['designation']));
                      echo '<tr>';
                      if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                        echo '<td colspan="2" style="border-bottom: 1px solid black;border-top: 1px solid black;"><span>'.$design.'</span></td>
                        <td style="border: 1px solid black;"><span>'.$ligne['quantite'].'</span></td>
                        <td style="border-bottom: 1px solid black;border-top: 1px solid black;"><span>'.	$ligne['prixPublic'].'</span></td>';
                      }
                      else{
                        echo '<td colspan="2" style="border-bottom: 1px solid black;border-top: 1px solid black;"><span>'.$design.'</span></td>
                        <td style="border: 1px solid black;"><span>'.$ligne['quantite'].'</span></td>
                        <td style="border-bottom: 1px solid black;border-top: 1px solid black;"><span>'.	$ligne['prixunitevente'].'</span></td>';
                      }
                      echo '</tr>';
                    }
              echo  '
              <tr>
                <td colspan="4">
                    <span style="float: left"> TOTAL </span> <span style="float: right">'.$total.' </span> <br> ';
                    if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                      if ($pagnet['taux']!=0) {
                        echo '<span style="float: left"> REMISE ('.$pagnet['taux'].' %) </span> <span style="float: right">'.(($total * $pagnet['taux']) / 100).'  </span> <br> ';
                      }
                    }
                    if ($pagnet['remise']!=0) {
                      echo '<span style="float: left"> REMISE </span> <span style="float: right">'.$pagnet['remise'].'  </span> <br> ';
                    }
                    echo '<span style="float: left"> NET A PAYER </span> <span style="float: right">'.$netPayer.' </span> <br> ';
                    if ($pagnet['versement']!=0) {
                    echo '<span style="float: left"> ESPECES </span> <span style="float: right">'.$pagnet['versement'].' </span> <br>
                    <span style="float: left"> RENDU </span> <span style="float: right">'.($pagnet['versement'] - $netPayer).' </span>';
                    } 
                    if ($_SESSION['compte']==1) {
                      if ($pagnet['idCompte']!=0) {                
                        $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$pagnet['idCompte'];
                        $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());
                        $cpt = mysql_fetch_array($resPay2);
                        echo '<br><span style="float: left"> COMPTE </span> <span style="float: right"> '.$cpt['nomCompte'].' </span>';
                      }
                
                      if ($pagnet['avance']!=0) {
                        echo ' <br><span style="float: left"> AVANCE </span> <span style="float: right"> '.$pagnet['avance'].' </span>';
                        echo ' <br><span style="float: left">RESTE </span> <span style="float: right"> '.($pagnet['apayerPagnet'] - $pagnet['avance']).' </span>';
                      }
                    }
                    echo '
                </td>
              </tr>
          </table>
          **************************************************
          <p align="center"><strong><span class="align-middle"  style="font-size:12px; font-family: Lucida, Lucida Calligraphy, Georgia, serif;"> MERCI DE VOTRE VISITE </span></strong> </p>
          **************************************************
        ';
       
        if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

        }
        else {
          if($_SESSION['vitrine']==1){
            $text = "https://yaatout.org/".str_replace(" ","",$_SESSION['labelB']);
    
            // $path variable store the location where to 
            // store image and $file creates directory name
            // of the QR code file by using 'uniqid'
            // uniqid creates unique id based on microtime
            $path = 'images/';
            $file = $path.uniqid().".png";
              
            // $ecc stores error correction capability('L')
            $ecc = 'L';
            $pixel_Size = 4;
            $frame_Size = 10;
              
            // Generates QR Code and Stores it in directory given
            QRcode::png($text, $file, $ecc, $pixel_Size);
              
            // Displaying the stored QR code from directory
            echo "<center><img src='".$file."'></center>";
          }
        }


      echo '
      <script type="text/javascript">
        setTimeout("self.close()", 3000 ) // after 3 seconds
      </script>
    ';
     
  }

}
if (isset($_POST['idMutuellePagnet'])) {
  // code...
  $idMutuellePagnet=htmlspecialchars(trim($_POST['idMutuellePagnet']));
  $sql1="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet."";
	$res1 = mysql_query($sql1) or die ("persoonel requête 1".mysql_error());
  	$pagnet = mysql_fetch_array($res1);
	$heureB=$pagnet['heurePagnet'];
      $date1=$pagnet['datepagej'];
	  
	  $date2 = new DateTime($date1);
		//R�cup�ration de l'ann�e
		$annee =$date2->format('Y');
		//R�cup�ration du mois
		$mois =$date2->format('m');
		//R�cup�ration du jours
		$jour =$date2->format('d');
	$date=$jour.'-'.$mois.'-'.$annee;

    $total=$pagnet['totalp'];
    $netPayer=$pagnet['apayerPagnet'];
    $adherant=$pagnet['adherant'];
    $idClient=$pagnet['idClient'];


    echo  '<table class="table"> 
    <tr align="center">
        <th colspan="4"><p align="center"><span style="font-size:17px;">'.strtoupper($_SESSION['labelB']).' </span></p></th>
    </tr> 
    <tr align="center">
      <td colspan="4" style="border-bottom: 1px solid black;border-top: 1px solid black;"><span>ADRESSE : '.$_SESSION['adresseB'].' <br> TELEPHONE : '.$_SESSION['telBoutique'].' </span></td>
    </tr>
    <tr>
    <td colspan="4" style="border-bottom: 1px solid black;border-top: 1px solid black;">
        <span style="float: left"> PANIER </span> <span style="float: right">#'.$idMutuellePagnet.' </span> <br> 
        <span style="float: left"> ADHERANT </span> <span style="float: right">'.$pagnet["adherant"].' </span> <br> 
        <span style="float: left"> CODE ADHERANT </span> <span style="float: right">'.$pagnet["codeAdherant"].' </span> <br> 
        <span style="float: left"> BENEFICIAIRE </span> <span style="float: right">#'.$pagnet["codeBeneficiaire"].' </span> <br> 
        <span style="float: left"> DATE </span> <span style="float: right">'.$date.' </span> <br> 
        <span style="float: left"> HEURE </span> <span style="float: right">'.$heureB.' </span>
    </td>
  </tr>
  ';
    $sql2="SELECT * FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ORDER BY numligne DESC";
    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
        echo ' 
        <tr  style="border-bottom: 1px solid black;border-top: 1px solid black;">
          <td colspan="2"><span><strong>REFERENCE<strong></span></td>
          <td style="border: 1px solid black;"><span><strong>QTE<strong></span></td>
          <td><span><strong>PRIX<strong></span></td>
        </tr>';
              while ($ligne = mysql_fetch_array($res2)) {
                /*
                if (strlen($ligne['designation']) >= 15) {
                  $design=substr($ligne['designation'], 0, 15)."... " ;
                }
                else{
                  $restant=(15 - strlen($ligne['designation']));
                  $vide=str_repeat(".", $restant);
                  $design=$ligne['designation'].' '.$vide;
                } */
                //$design = strtoupper($ligne['designation']); 
                $design = ucfirst(strtolower($ligne['designation']));
                echo '<tr>
                        <td colspan="2" style="border-bottom: 1px solid black;border-top: 1px solid black;"><span>'.$design.'</span></td>
                        <td style="border: 1px solid black;"><span>'.$ligne['quantite'].'</span></td>
                        <td style="border-bottom: 1px solid black;border-top: 1px solid black;"><span>'.	$ligne['prixPublic'].'</span></td>
                    </tr>';
              }
              echo  '
              <tr>
                <td colspan="4">
                    <span style="float: left"> TOTAL </span> <span style="float: right">'.$total.' </span> <br> 
                    <span style="float: left"> MUTUELLE ('.$pagnet['taux'].' %) </span> <span style="float: right">'.$pagnet['apayerMutuelle'].' </span> <br>';
                    if ($pagnet['remise']!=0) {
                      echo '<span style="float: left"> REMISE </span> <span style="float: right">'.$pagnet['remise'].'  </span> <br> ';
                    }
                    echo '<span style="float: left"> NET A PAYER </span> <span style="float: right">'.$pagnet['apayerPagnet'].' </span> <br> ';
                    if ($pagnet['versement']!=0) {
                    echo '<span style="float: left"> ESPECES </span> <span style="float: right">'.$pagnet['versement'].' </span> <br>
                    <span style="float: left"> RENDU </span> <span style="float: right">'.($pagnet['versement'] - $netPayer).' </span>';
                    } 
                    if ($_SESSION['compte']==1) {
                      if ($pagnet['idCompte']!=0) {                
                        $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$pagnet['idCompte'];
                        $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());
                        $cpt = mysql_fetch_array($resPay2);
                        echo '<br><span style="float: left"> COMPTE </span> <span style="float: right"> '.$cpt['nomCompte'].' </span>';
                      }
                
                      if ($pagnet['avance']!=0) {
                        echo ' <br><span style="float: left"> AVANCE </span> <span style="float: right"> '.$pagnet['avance'].' </span>';
                        echo ' <br><span style="float: left">RESTE </span> <span style="float: right"> '.($pagnet['apayerPagnet'] - $pagnet['avance']).' </span>';
                      }
                    }
                    echo '
                </td>
              </tr>
          </table>
          **************************************************
          <p align="center"><strong><span class="align-middle"  style="font-size:12px; font-family: Lucida, Lucida Calligraphy, Georgia, serif;"> MERCI DE VOTRE VISITE </span></strong></p>
          **************************************************
        ';

    echo '
    <script type="text/javascript">
    setTimeout("self.close()", 3000 ) // after 3 seconds
    </script>
    ';  

}
elseif (isset($_POST['barcodeFactureV'])) {
  // VERSEMENT
  $idVersement=htmlspecialchars(trim($_POST['idVersement']));
  $sql1="SELECT * FROM `".$nomtableVersement."` where idVersement=".$idVersement;
  $res1 = mysql_query($sql1) or die ("persoonel requête 1".mysql_error());
    $versement = mysql_fetch_array($res1);
    $montant=$versement['montant'];
	$heureV=$versement['heureVersement'];
    $dateVersement1=$versement['dateVersement'];
		$date = new DateTime($dateVersement1);
		//R�cup�ration de l'ann�e
		$annee =$date->format('Y');
		//R�cup�ration du mois
		$mois =$date->format('m');
		//R�cup�ration du jours
		$jour =$date->format('d');
	$dateVersement=$jour.'-'.$mois.'-'.$annee;

  echo  '<table class="table"> ';
    /*
      if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
        echo  ' <tr align="center">
              <th colspan="4" ><p align="center"><img class="img-responsive" src="images/pharmacie.png" alt="Chania" width="50px" height="50px"></p></th>
          </tr> ';
      }
      else {
        
        echo  ' <tr align="center">
              <th colspan="4" ><p align="center"><img class="img-responsive" src="images/shop.png" alt="Chania" width="50px" height="50px"></p></th>
          </tr> ';
          
      }
    */
    echo '
    <tr align="center">
        <th colspan="4"><p align="center"><span style="font-size:17px;">'.strtoupper($_SESSION['labelB']).' </span></p></th>
    </tr> 
    ';
    $sql2="SELECT * FROM `".$nomtableClient."` where idClient=".$versement['idClient'];
    $res2 = mysql_query($sql2) or die ("persoonel requête 1".mysql_error());
    $client = mysql_fetch_array($res2);
    if ($client!=null) {
      $sql5="SELECT *
      FROM `aaa-utilisateur`  
      WHERE idutilisateur =".$_SESSION['iduser'];
      $res5 = mysql_query($sql5);
      $utilisateur = mysql_fetch_array($res5);

      if($utilisateur['idEntrepot']!=0 && $utilisateur['idEntrepot']!=null){
        $sql1="SELECT * FROM `". $nomtableEntrepot."` WHERE idEntrepot=".$utilisateur['idEntrepot']." ";
        $res1=mysql_query($sql1);
        $depot = mysql_fetch_array($res1);
        echo  '
        <tr align="center">
          <td colspan="4" style="border-bottom: 1px solid black;border-top: 1px solid black;">
            <span> ADRESSE : '.$depot['adresseEntrepot'].' </span><br> 
            <span>TELEPHONE : '.$_SESSION['telPortable'].' </span>
          </td>
        </tr>';
      }
      else{
        echo  '
        <tr align="center">
          <td colspan="4" style="border-bottom: 1px solid black;border-top: 1px solid black;">
            <span>ADRESSE : '.$_SESSION['adresseB'].' </span><br> 
            <span>TELEPHONE : '.$_SESSION['telBoutique'].' </span>
          </td>
        </tr>';
      }
      echo  '
        <tr>
          <td colspan="4" style="border-bottom: 1px solid black;border-top: 1px solid black;">
              <span style="float: left"> VERSEMENT </span> <span style="float: right">#'.$idVersement.' </span> <br> 
              <span style="float: left"> CLIENT </span> <span style="float: right">'.$client["prenom"].' '.$client["nom"].' </span> <br> 
              <span style="float: left"> DATE </span> <span style="float: right">'.$dateVersement.' </span> <br> 
              <span style="float: left"> HEURE </span> <span style="float: right">'.$heureV.' </span>
          </td>
        </tr>
      ';
    }
    echo  '
    <tr>
      <td colspan="4">
        <span style="float: left"> TOTAL </span> <span style="float: right">'.$montant.' </span>
      </td>
    </tr>
    </table>
    **************************************************
    <p align="center"><strong><span class="align-middle"  style="font-size:12px; font-family: Lucida, Lucida Calligraphy, Georgia, serif;"> MERCI DE VOTRE VISITE </span></strong></p>
    **************************************************
  ';

  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

  }
  else {
    if($_SESSION['vitrine']==1){
      $text = "https://yaatout.org/".str_replace(" ","",$_SESSION['labelB']);

    // $path variable store the location where to 
    // store image and $file creates directory name
    // of the QR code file by using 'uniqid'
    // uniqid creates unique id based on microtime
    $path = 'images/';
    $file = $path.uniqid().".png";
      
    // $ecc stores error correction capability('L')
    $ecc = 'L';
    $pixel_Size = 4;
    $frame_Size = 10;
      
    // Generates QR Code and Stores it in directory given
    QRcode::png($text, $file, $ecc, $pixel_Size);
      
    // Displaying the stored QR code from directory
    echo "<center><img src='".$file."'></center>";
    }
  }


  echo '
  <script type="text/javascript">
  setTimeout("self.close()", 3000 ) // after 3 seconds
  </script>
  ';
	  
	 
}
elseif (isset($_POST['dateTicket'])) {
  // VERSEMENT
  $dateTicket=htmlspecialchars(trim($_POST['dateTicket']));
  $appCaisse=htmlspecialchars(trim($_POST['appCaisse']));
  $retCaisse=htmlspecialchars(trim($_POST['retCaisse']));
  $ventes=htmlspecialchars(trim($_POST['ventes']));
  $transaction=htmlspecialchars(trim($_POST['transaction']));
  $credit=htmlspecialchars(trim($_POST['credit']));
  $depenses=htmlspecialchars(trim($_POST['depenses']));
  $bonEsp=htmlspecialchars(trim($_POST['bonEsp']));
  $versement=htmlspecialchars(trim($_POST['versement']));
  $bonP=htmlspecialchars(trim($_POST['bonP']));
  $tva=htmlspecialchars(trim($_POST['tva']));

      echo  '
           <span>
              <span style="font-size:18px">'.strtoupper($_SESSION['labelB']).'</span><br> Adresse : '.$_SESSION['adresseB'].'<br> Telephone : '.$_SESSION['telBoutique'].'
              **************************************************
           </span>
                <b>ETAT DE LA CAISSE </b>
                
                DU <b>'.$dateTicket.'</b> <br>
           <span>
        ************************************************** <br>';
              echo '
              <table class="table table-bordered">
              <thead>
              <tr>
                <th></th>
                <th></th>
              </tr>
              </thead>
              <tbody>';
                  echo '<tr><td> APPROVISIONNEMENT </td><td>'.$appCaisse.'</td></tr>';
                  echo '<tr><td> RETRAIT </td><td>'.$retCaisse.'</td></tr>';
                  echo '<tr><td> VENTES </td><td>'.$ventes.'</td></tr>';
                  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
                  }
                  else{
                    echo '<tr><td> TRANSACTION </td><td>'.$transaction.'</td></tr>';
                    echo '<tr><td> CREDIT </td><td>'.$credit.'</td></tr>';
                  }
                  echo '<tr><td> DEPENSES </td><td>'.$depenses.'</td></tr>';
                  echo '<tr><td> BON ESP </td><td>'.$bonEsp.'</td></tr>';
                  echo '<tr><td> VERSEMENT </td><td>'.$versement.'</td></tr>';
                  echo '<tr><td> <b>TOTAL CAISSE </b></td><td> <b>'.($appCaisse - $retCaisse + $ventes + $transaction + $credit - $depenses - $bonEsp + $versement).' </b></td></tr>';
                  echo '<tr><td> <b>TOTAL BON PROD</b></td><td> <b>'.$bonP.' </b></td></tr>';
                  echo '<tr><td> <b>TOTAL TVA 18%</b></td><td> <b>'.$tva.' </b></td></tr>';

                echo '
              </tbody>
            </table>';
                
        echo  '</span>
              **************************************************
           <span>

           <br> A BIENTOT<br>
           </span>

          &nbsp&nbsp&nbsp&nbsp';

          echo '
          <script type="text/javascript">
            setTimeout("self.close()", 3000 ) // after 3 seconds
          </script>
        ';
}
elseif (isset($_POST['idClient'])) {
  $idClient=htmlspecialchars(trim($_POST['idClient']));
  $sql2="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient;
  $res2 = mysql_query($sql2) or die ("persoonel requête 1".mysql_error());
  $client = mysql_fetch_array($res2);
  $sql12="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." AND (type=0 || type=30) ";
  $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
  $TotalB = mysql_fetch_array($res12) ;
  $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient=".$idClient." ";
  $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
  $TotalV = mysql_fetch_array($res13) ;
  echo  '
  <span>
      <span style="font-size:18px">'.strtoupper($_SESSION['labelB']).'</span><br> Adresse : '.$_SESSION['adresseB'].'<br> Telephone : '.$_SESSION['telBoutique'].'
      **************************************************
  </span>';  ?>
 <?php
  echo '<b>CLIENT </b>: '.$client["prenom"].' '.$client["nom"].'<br>';
  echo '<b>TOTAL BONS </b>: '.number_format($TotalB[0], 2, ',', ' ').'<br>';
  echo '<b>TOTAL VERSEMENT </b>: '.number_format($TotalV[0], 2, ',', ' ').'<br>';
  echo '<b>MONTANT A VERSER </b>: '.number_format(($TotalB[0] - $TotalV[0]), 2, ',', ' ').'<br>
    **************************************************';

  /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
    $sqlP1="SELECT *,CONCAT(CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)),' ',heurePagnet) AS dateHeure
    FROM
    (SELECT p.idClient,p.idPagnet,p.datepagej,p.heurePagnet FROM `".$nomtablePagnet."` p where p.idClient='".$idClient."' AND p.verrouiller=1 AND (p.type=0 || p.type=30)
    UNION 
    SELECT v.idClient,v.idVersement,v.dateVersement,v.heureVersement FROM `".$nomtableVersement."` v where v.idClient='".$idClient."'
    ) AS a ORDER BY dateHeure DESC ";
    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
  /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/
  if(mysql_num_rows($resP1)){
    while ($bons = mysql_fetch_array($resP1)) { 
      echo  '
      <span>
         Date : '.$bons['dateHeure'].'<br> ';
         
      $sqlP="select * from `".$nomtablePagnet."` where idPagnet='".$bons["idPagnet"]."' AND idClient='".$idClient."' ";
      $resP=mysql_query($sqlP);
      $pagnet = mysql_fetch_array($resP); 
      if($pagnet!=null){
        echo ' BON N : '.$bons["idPagnet"].'<br>';
        $sql2="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";
        $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
        if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
          echo ' 
          <table class="table-bordered">
              <thead>
              <tr>
                  <th>Designation</th>
                  <th>Qte</th>
                  <th>Prix</th>
              </tr>
              </thead>
              <tbody>';
                while ($ligne = mysql_fetch_array($res2)) {
                  if (strlen($ligne['designation']) >= 15) {
                    $design=substr($ligne['designation'], 0, 15)."... " ;
                    $design = strtoupper($design);
                  }
                  else{
                    $restant=(15 - strlen($ligne['designation']));
                    $vide=str_repeat(".", $restant);
                    $design=$ligne['designation'].' '.$vide;
                    $design = strtoupper($design);
                  }
                  echo '<tr>';
                  echo '<td>'.$design.'</td><td>'.$ligne['quantite'].'</td><td>'.	$ligne['prixPublic'].'</td>';
                  echo '</tr>';
                }
                echo '
              </tbody>
            </table>
          ';
        }
        else{
          echo ' 
          <table class="table-bordered">
              <thead>
              <tr>
                <th>Designation</th>
                <th>Qte</th>
                <th>Prix</th>
              </tr>
              </thead>
              <tbody>';
                while ($ligne = mysql_fetch_array($res2)) {
                  if (strlen($ligne['designation']) >= 15) {
                    $design=substr($ligne['designation'], 0, 15)."... " ;
                  }
                  else{
                    $restant=(15 - strlen($ligne['designation']));
                    $vide=str_repeat(".", $restant);
                    $design=$ligne['designation'].' '.$vide;
                  }
                  echo '<tr>';
                  echo '<td>'.$design.'</td><td>'.$ligne['quantite'].'</td><td>'.	$ligne['prixunitevente'].'</td>';
                  echo '</tr>';
                }
                echo '
              </tbody>
            </table>
          ';
        }
        echo '<b> TOTAL : '.number_format($pagnet['totalp'], 2, ',', ' ').'</b><br>';
        echo '<b> NET A PAYER : '.number_format($pagnet['apayerPagnet'], 2, ',', ' ').'</b><br>';
      }
      $sqlV="SELECT * FROM `".$nomtableVersement."` where idVersement='".$bons['idPagnet']."' AND idClient='".$idClient."' ";
      $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());
      $versement = mysql_fetch_assoc($resV);
      if($versement!=null){
        echo ' VERSEMENT N° : '.$bons["idPagnet"].'<br>';
        echo '<b> MONTANT : '.number_format($versement['montant'], 2, ',', ' ').'</b><br>';
      }
      echo '
         ----------------------------------------------
        <br>
      </span>';
    }
  }

  echo '**************************************************
              A BIENTOT<br>
         </span>

        &nbsp&nbsp&nbsp&nbsp';
        echo '
        <script type="text/javascript">
          setTimeout("self.close()", 3000 ) // after 3 seconds
        </script>
      ';

}




		?>
<br>
	</div>
  <script type="text/javascript"> 
    // window.addEventListener("beforeprint", (event) => {
    //   alert("Before print");
    // });   
  </script>
</body>
</html>
