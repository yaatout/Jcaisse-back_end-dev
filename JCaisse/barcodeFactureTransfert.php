<?php
session_start();

if(!$_SESSION['iduser'])
  header('Location:../index.php');

require('connection.php');
require('declarationVariables.php');
include('phpqrcode/qrlib.php');

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

    // var_dump($_POST['idPagnet']);

if (isset($_POST['printTransfertChecked'])) {
  // code...
  $transfertCheckedList=htmlspecialchars($_POST['transfertCheckedList']);
  $transfertCheckedList=explode(' | ', $transfertCheckedList);

  // var_dump($transfertCheckedList);
	  
  // $date2 = new DateTime($date1);
  // //R�cup�ration de l'ann�e
  // $annee =$date2->format('Y');
  // //R�cup�ration du mois
  // $mois =$date2->format('m');
  // //R�cup�ration du jours
  // $jour =$date2->format('d');
	// $date=$jour.'-'.$mois.'-'.$annee;
	  
  if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
    
          echo  '<table class="table"> '; 
              echo '
              <tr align="center">
                  <th colspan="5"><p align="center"><span style="font-size:17px;">'.strtoupper($_SESSION['labelB']).' </span></p></th>
              </tr> 
              <tr align="center">
                <td colspan="5" style="border-bottom: 1px solid black;border-top: 1px solid black;"><span>ADRESSE : '.$_SESSION['adresseB'].' <br> TELEPHONE : '.$_SESSION['telBoutique'].' </span></td>
              </tr>
              ';
              
              echo  '
              <tr>
              <td  style="border-bottom: 1px solid black;border-top: 1px solid black;">
              </td>
            </tr>
          ';
            
          echo ' 
          <tr  style="border-bottom: 1px solid black;border-top: 1px solid black;">
            <td colspan="2"><span><strong>REFERENCE<strong></span></td>
            <td style="border: 1px solid black;"><span><strong>QTE<strong></span></td>
            <td style="border: 1px solid black;"><span><strong>DEPOT<strong></span></td>
            <td style="border: 1px solid black;"><span><strong>DATE<strong></span></td>
          </tr>';
            foreach ($transfertCheckedList as $key) {
              # code...
            // }

              $sql2="SELECT * FROM `".$nomtableEntrepotTransfert."` where idEntrepotTransfert=".$key."";
              // var_dump($sql2);
              $res2 = mysql_query($sql2) or die ("persoonel requête 2r".mysql_error());
              $transfert = mysql_fetch_array($res2);
              // var_dump($transfert);
              
              $sql20="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$transfert['idEntrepot']."' ";
              $res20=mysql_query($sql20);
              $depot=mysql_fetch_array($res20);

              $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$transfert['idDesignation']."' ";
              $res1=mysql_query($sql1);
              $designation=mysql_fetch_array($res1);

                    // while ($ligne = mysql_fetch_array($res2)) {

                      // $design = ucfirst(strtolower($ligne['designation']));
                      echo '<tr>';
                        echo '<td colspan="2" style="border-bottom: 1px solid black;border-top: 1px solid black;"><span>'.$transfert['designation'].'</span></td>
                              <td style="border: 1px solid black;"><span>'.$transfert['quantite'].' '.$designation['uniteStock'].'</span></td>
                              <td style="border: 1px solid black;"><span>'.$depot['nomEntrepot'].'</span></td>
                              <td style="border: 1px solid black;"><span>'.$transfert['dateTransfert'].'</span></td>
                            </tr>';

                    // }
                         
              }
              echo  '
          </table>
        ';

        echo '
        <script type="text/javascript">
          setTimeout("self.close()", 3000 ) // after 3 seconds
        </script>
      ';
    
  }

}




		?>
<br>
	</div>
</body>
</html>
