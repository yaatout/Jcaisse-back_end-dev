<?php
session_start();

if(!$_SESSION['iduser']){
  header('Location:../index.php');
}

if($_SESSION['vitrine']==0){
  header('Location:accueil.php');
}

require('connection.php');
require('connectionVitrine.php');

require('declarationVariables.php');

 ?>

<!DOCTYPE html>
<html>
<head>
<style>
p.inline {display: inline-block;}
span { font-size: 16px;}
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
	<div style="margin-left: 4%">
		<?php
		//include 'libPHP/barcode128.php';
if (isset($_POST['idPanier'])) {
  // code...
  /*
  $sql1="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet."";
	$res1 = mysql_query($sql1) or die ("persoonel requête 1".mysql_error());
    $pagnet = mysql_fetch_array($res1);
  */
  $idPanier=htmlspecialchars(trim($_POST['idPanier']));
  $req1 = $bddV->prepare("SELECT * FROM panier WHERE idPanier =:idPanier");
  $req1->execute(array(
      'idPanier' =>$idPanier
      )) or die(print_r($req1->errorInfo()));
  $pagnet=$req1->fetch();


	  
	  
	  
      $total=$pagnet['total'];
      $netPayer=$pagnet['total'];
      $restourne=0;
      $idClient=$pagnet['idClient'];
    echo  '
         <span>
            **************************************************
            '.$_SESSION['labelB'].'<br> Adresse : '.$_SESSION['adresseB'].'<br> Telephone : '.$_SESSION['telBoutique'].'
            **************************************************
         </span>';  ?>
        <?php
		/*
		$sql2="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient;
		$res2 = mysql_query($sql2) or die ("persoonel requête 1".mysql_error());
    $client = mysql_fetch_array($res2);
    */
    
    $req2 = $bddV->prepare("SELECT * FROM client WHERE idClient =:idClient");
    $req2->execute(array(
        'idClient' =>$pagnet["idClient"]
        )) or die(print_r($req2->errorInfo()));
    $client=$req2->fetch();
	  
    echo ' COMMANDE N : '.$idPanier.'<br>';
    echo'CLIENT : '.$client["prenom"].' '.$client["nom"].'<br>';

         echo '<span>
              
               DATE : '.$pagnet['dateConfirmer'].'<br>
      **************************************************	';
            /*	
             $sql2="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ORDER BY numligne DESC";
             $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
             */

             $req3 = $bddV->prepare("SELECT * FROM ligne WHERE idPanier =:idPanier AND idBoutique =:idBoutique ORDER BY idArticle DESC");
             $req3->execute(array(
                 'idPanier' =>$idPanier,
                 'idBoutique' =>$_SESSION['idBoutique'],
                 )) or die(print_r($req3->errorInfo()));

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
                      while ($ligne=$req3->fetch()) {
                        if (strlen($ligne['designation']) >= 15) {
                          $design=substr($ligne['designation'], 0, 15)."... " ;
                        }
                        else{
                          $restant=(15 - strlen($ligne['designation']));
                          $vide=str_repeat(".", $restant);
                          $design=$ligne['designation'].' '.$vide;
                        }
                        echo '<tr>';
                        echo '<td>'.$design.'</td><td>'.$ligne['quantite'].'</td><td>'.	$ligne['prix'].'</td>';
                        echo '</tr>';
                      }
                      echo '
                    </tbody>
                  </table>
                ';
              
 
      echo  '</span>
      <p>   </p>
            <span>';
            echo 'TOTAL : '.$total.'<br>';
            echo  '   NET A PAYER : '.$netPayer.'<br>';

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
</body>
</html>
