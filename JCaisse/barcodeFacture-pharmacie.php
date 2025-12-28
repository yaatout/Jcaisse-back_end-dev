<?php
session_start();

if(!$_SESSION['iduser'])
  header('Location:../index.php');

require('connection.php');
require('declarationVariables.php');

 ?>

<!DOCTYPE html>
<html>
<head>
<style>
p.inline {display: inline-block;}
span { font-size: 15px;}
</style>
<style type="text/css" media="print">
    @page
    {
        size: auto;   /* auto is the initial value */
        margin: 0px;  /* this affects the margin in the printer settings */
        border: none ;
        padding: 0px;

    }
</style>
</head>
<?php require('entetehtml.php'); ?>
<body onload="window.print();">
	<div style="margin-left: 10%">
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
	  
	  
      $total=$pagnet['totalp'];
      $netPayer=$pagnet['apayerPagnet'];
      $restourne=$pagnet['restourne'];
      $idClient=$pagnet['idClient'];
    echo  '<p class="inline" id="pFacture" >
         <span>
            **************************************************
            '.$_SESSION['labelB'].'<br> Adresse : '.$_SESSION['adresseB'].'<br> Tel. portable : '.$_SESSION['telPortable'].'
            **************************************************
         </span>';  ?>
        <?php
		
		$sql2="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient;
		$res2 = mysql_query($sql2) or die ("persoonel requête 1".mysql_error());
		$client = mysql_fetch_array($res2);
	  
          if ($idClient==0) {
            echo ' PAGNET N : '.$idPagnet.'<br>';
          }else {
            echo ' BON N : '.$idPagnet.'<br>';
			echo'CLIENT : '.$client["prenom"].' '.$client["nom"].'<br>';
			
          }

         echo '<span>
              
               DATE : '.$date.' HEURE :'.$heureB.'<br>
			**************************************************	';	
             $sql2="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ORDER BY numligne DESC";
             $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());

             while ($ligne = mysql_fetch_array($res2)) {
                //  echo '   '.$ligne['designation'].'&nbsp '.$ligne['quantite'].'&nbsp x &nbsp'.	$ligne['unitevente'].' &nbsp'.	$ligne['prixunitevente'].' <br>';
                  echo '   '.@htmlspecialchars(trim($ligne['designation'])).'&nbsp '.$ligne['quantite'].'&nbsp x &nbsp'.	$ligne['prixPublic'].' <br>';
             }
      echo  '</span>
            **************************************************
         <span>
             TOTAL : '.$total.'<br>';
             if ($pagnet['remise']!=0) {

                echo 'REMISE : '. $pagnet['remise'].'<br/>';
             }
          echo  '   NET A PAYER : '.$netPayer.'<br>';
            if ($idClient==0) {
              echo ' RESTOURNE : '.$restourne.'<br>';
            }
               echo '**************************************************
              A BIENTOT<br>
         </span>

        </p>&nbsp&nbsp&nbsp&nbsp';

        echo '
          <script type="text/javascript">
            setTimeout("self.close()", 3000 ) // after 3 seconds
          </script>
        ';

}elseif (isset($_POST['barcodeFactureV'])) {
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
	  
	  $idClient = $versement['idClient'];
	  
	  $sql2="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient;
	  $res2 = mysql_query($sql2) or die ("persoonel requête 1".mysql_error());
	  $client = mysql_fetch_array($res2);
      echo  '<p class="inline" id="pFacture" >
           <span>
              **************************************************
              '.$_SESSION['labelB'].'<br> Adresse : '.$_SESSION['adresseB'].'<br> Tel. portable : '.$_SESSION['telPortable'].'
              **************************************************
           </span>
                  <b>VERSEMENT</b>
           <span>
                N : '.$idVersement.'<br>
				CLIENT : '.$client["prenom"].' '.$client["nom"].'<br>
                DATE : '.$dateVersement.' HEURE :'.$heureV.'<br>
				**************************************************
                <b> MONTANT : &nbsp  '.$montant.'</b>&nbsp  <br>';
        echo  '</span>
              **************************************************
           <span>

                A BIENTOT<br>
           </span>

          </p>&nbsp&nbsp&nbsp&nbsp';

          echo '
          <script type="text/javascript">
            setTimeout("self.close()", 3000 ) // after 3 seconds
          </script>
        ';
}




		?>
<br>
	</div>
</body>
</html>
