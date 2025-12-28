<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP; ,EL hadji mamadou korka
Date de création : 20/03/2016
Date derni�re modification : 20/04/2016; 15-04-2018
*/
session_start();
if(!$_SESSION['iduser']){
  header('Location:../../../index.php');
  }

require('../../connection.php');
require('../../connectionPDO.php');

require('../../declarationVariables.php');

$operation=@htmlspecialchars($_POST["operation"]);
$idPorteur=@htmlspecialchars($_POST["idPorteur"]);


if ($operation=="1") {

  $query = @$_POST["query"];
  
  //get total number of records from database
  
  if ($query =="") {
    
    $sql="SELECT * FROM `".$nomtablePagnet."` p, `".$nomtableLigne."` l where p.idPagnet=l.idPagnet and p.type=0 and l.classe=2 and (numContainer=".$idPorteur." or idAvion=".$idPorteur.") ORDER BY p.idPagnet DESC";
    
    $statement = $bdd->prepare($sql);

  } else {
    
    //get total number of records from database
    $sql="SELECT * FROM `".$nomtablePagnet."` p, `".$nomtableLigne."` l where p.idPagnet=l.idPagnet and p.type=0 and l.classe=2 and (numContainer=".$idPorteur." or idAvion=".$idPorteur.") and (l.designation LIKE '%".$query."%') ORDER BY p.idPagnet DESC";

    $statement = $bdd->prepare($sql);
  }
   
  //   var_dump($sql);

  $statement->execute();
    
  $data = $statement->fetchAll(PDO::FETCH_ASSOC); 

  $total_depenses = 0;
  // var_dump($data);
    
  // $total_rows = sizeof($data);
  // $total_pages = ceil($total_rows/$item_per_page);

  // //position of records
  // $page_position = (($page_number-1) * $item_per_page);
  
  //Display records fetched from database.
  echo '<table class="table table-striped" id="listeDepensesByPorteur" border="1">';
  echo '<thead>
          <tr>
            <th>Ordre</th>
            <th>Référence</th>
            <th>Montant</th>
            <th>Date dépense</th>
            <th>Opération</th>
          </tr>
        </thead>';

// $data = array_slice($data, $page_position, $item_per_page);
  $i = 1;
  $cpt = 0;
foreach ($data as $key) {

    echo '<tr>';
      
      echo  '<td>'.$i.'</td>';
      echo  '<td>'.$key['designation'].'</td>';
      echo  '<td>'.$key['prixtotal'].'</td>';
      echo  '<td>'.$key['datepagej'].'</td>';
    echo  '<td> 
            <a class="btn btn-xs btn-danger" disabled><span class="glyphicon glyphicon-remove"></span> Retirer</a>
          </td>';
    echo '</tr>';

    $total_depenses += $key['prixtotal'];
    $i++;
    $cpt++;
  }
  if ($cpt==0) {
    
    echo '<tr>';
    echo  '<td colspan="5" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
   
    echo '<tr>';
    echo  '<td colspan="5" align="center" style="background-color:green;color:white;"> Total des dépenses : '.number_format($total_depenses, 0, ',', ' ').' FCFA</td>';
    echo '</tr>';
  echo '</table>';

  echo '</div>';

}

?>
<script>
$(document).ready( function () {
  $('#listeDepensesByPorteur').DataTable();
} );
</script>