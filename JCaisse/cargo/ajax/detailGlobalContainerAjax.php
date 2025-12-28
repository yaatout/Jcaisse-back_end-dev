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
$numContainer=@htmlspecialchars($_POST["numContainer"]);
$etat=@htmlspecialchars($_POST["etat"]);
$tri=@htmlspecialchars($_POST["tri"]);


if ($operation=="1" || $operation=="3" || $operation=="4") {

  // $nbEntreeContainer = @$_POST["nbEntreeContainer"];
  // $query = @$_POST["query"];
  // $item_per_page 		= $nbEntreeContainer; //item to display per page
  // $page_number 		= 0; //page number

  //Get page number from Ajax
  // if(isset($_POST["page"])){
  //   $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
  //   if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  // }else{
  //   $page_number = 1; //if there's no page number, set it to 1
  // }
  
  //get total number of records from database
  
  if ($query =="") {
    
    $sql="SELECT * FROM `".$nomtableEnregistrement."` e, `".$nomtableClient."` c where e.idClient=c.idClient and e.idContainer='".$numContainer."' and e.etat=2 ORDER BY CONCAT(c.prenom,' ', c.nom)";
    
    $statement = $bdd->prepare($sql);

  } else {
    
    //get total number of records from database
    $sql="SELECT * FROM `".$nomtableEnregistrement."` e, `".$nomtableClient."` c where e.idClient=c.idClient and e.idContainer='".$numContainer."' and e.etat=2 and (CONCAT(c.prenom,' ', c.nom) LIKE '%".$query."%' or c.telephone LIKE '%".$query."%') ORDER BY CONCAT(c.prenom,' ', c.nom)";

    $statement = $bdd->prepare($sql);
  }
   
  //   var_dump($sql);

  $statement->execute();
    
  $data = $statement->fetchAll(PDO::FETCH_ASSOC); 
  // var_dump($data);

  
  $total_cbm = 0;
  $total_price = 0;
  $total_piece = 0;
    
  // $total_rows = sizeof($data);
  // $total_pages = ceil($total_rows/$item_per_page);

  // //position of records
  // $page_position = (($page_number-1) * $item_per_page);
  
  //Display records fetched from database.
  echo '<table class="table table-striped" id="listeEnregByContainer" border="1">';
  echo '<thead>
          <tr>
            <th>Ordre</th>
            <th>Nom du client</th>
            <th>Date Enreg.</th>
            <th>Date Charg.</th>
            <th>Nat. bagages</th>
            <th>Nb. CBM/KG</th>
            <th>Nb pcs</th>
            <th>Prix CBM/KG</th>
            <th>Opération</th>
          </tr>
        </thead>';

// $data = array_slice($data, $page_position, $item_per_page);
  $i = 1;
  $cpt = 0;
foreach ($data as $key) {

    echo '<tr id="tr'.$key["idEnregistrement"].'">';
      
      echo  '<td>'.$i.'</td>';
      echo  '<td>'.$key['prenom'].' '.$key['nom'].'</td>';
      echo  '<td>'.$key['dateEnregistrement'].' '.$key['heureEnregistrement'].'</td>';
      echo  '<td>'.$key['dateChargement'].' '.$key['heureChargement'].'</td>';
      echo  '<td>'.$key['natureBagage'].'</td>';
      echo  '<td>'.$key['quantite_cbm_fret'].'</td>';
      echo  '<td>'.$key['nbPieces'].'</td>';
      echo  '<td>'.$key['prix_cbm_fret'].'</td>';
      echo  '<td> 
            <a class="btn btn-xs btn-danger" onClick="annulerChargement('.$key["idEnregistrement"].')"><span class="glyphicon glyphicon-remove"></span> Retirer</a>
          </td>';
    echo '</tr>';

    $total_cbm += $key['quantite_cbm_fret'];
    $total_price += $key['prix_cbm_fret']*$key['quantite_cbm_fret'];
    $total_piece += $key['nbPieces'];
    $i++;
    $cpt++;
  }
  if ($cpt==0) {
    
    echo '<tr>';
    echo  '<td colspan="9" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  
    echo '<tr>';
    echo  '<td colspan="3" align="center" style="background-color:green;color:white;"> Total pièces : '.number_format($total_piece, 0, ',', ' ').' PCS</td>';
    echo  '<td colspan="3" align="center" style="background-color:green;color:white;"> Total CBM : '.number_format($total_cbm, 2, ',', ' ').' CBM</td>';
    echo  '<td colspan="3" align="center" style="background-color:yellow;"> Prix total : '.number_format($total_price, 2, ',', ' ').' FCFA</td>';
    echo '</tr>';
    
  echo '</table>';

  // if ($cpt > 0) {
  //   echo '<div class="">';
  //       echo '<div class="col-md-4">';
  //       echo '<ul class="pull-left">';
  //           if ($item_per_page > $total_rows) {
            
  //               echo '1 à '.($total_rows).' sur '.$total_rows.' articles';        
  //           } else {
            
  //           if ($page_number == 1) {
                
  //               echo '1 à '.($item_per_page).' sur '.$total_rows.' articles';
  //           } else {
                
  //               if (($total_rows-($item_per_page * $page_number)) < 0) {
                 
  //               echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows.' sur '.$total_rows.' articles';
  //               } else {
                
  //               echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows.' articles';
  //               }
  //           }
  //           }      
  //       echo '</ul>';
  //       echo '</div>';
  //   }
    // echo '<div class="col-md-8">';
    // // To generate links, we call the pagination function here. 
    // echo paginate_function($item_per_page, $page_number, $total_rows, $total_pages);
    // echo '</div>';
  echo '</div>';

}

?>
<script>
$(document).ready( function () {
  $('#listeEnregByContainer').DataTable();
} );
</script>