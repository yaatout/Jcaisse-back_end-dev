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
  header('Location:../../index.php');
}

if($_SESSION['vitrine']==0){
	header('Location:../../accueil.php');
}

require('../../connection.php');
require('../../connectionVitrine.php');

require('../../declarationVariables.php');


$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);

$sql0="select * from `aaa-utilisateur` where idutilisateur = ".$_SESSION['iduser'];
$res0=mysql_query($sql0);
$user=mysql_fetch_array($res0);
// var_dump($user);  

if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntree = @$_POST["nbEntree"];
  $query = @$_POST["query"];
  $item_per_page 		= $nbEntree; //item to display per page
  $page_number 		= 0; //page number
  $produits = array();
  $tabIdDesigantion = array();
  $tabIdStock = array();

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }
  
  //get total number of records from database
  
  // if ($query =="") {
  //   # code...
  //   $req2 = $bddV->prepare("SELECT COUNT(*) from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and image='' and id BETWEEN ".$user['debut']." and ".$user['arret']."");
  //   $req2->execute() or die(print_r($req2->errorInfo()));
  // } else {
  //   # code...
  //   //get total number of records from database
  //   $req2 = $bddV->prepare("SELECT COUNT(*) from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and image='' and id BETWEEN ".$user['debut']." and ".$user['arret']." and designation LIKE '%".$query."%'");
  //   $req2->execute() or die(print_r($req2->errorInfo()));
  // }
  
  if ($query =="") {
    # code...
    // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0) and image='' ORDER BY idDesignation ASC LIMIT ".$user['arret']." OFFSET ".$user['debut']);
    $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0) and image='' ORDER BY id ASC");
    $results->execute(); //Execute prepared Query
  } else {
    # code...
    //Limit our results within a specified range. 
    // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0) and image='' and designation LIKE '%".$query."%' ORDER BY idDesignation ASC LIMIT ".$user['arret']." OFFSET ".$user['debut']);
    $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0) and image='' and designation LIKE '%".$query."%' ORDER BY id ASC");
    $results->execute(); //Execute prepared Query
  }

  // var_dump($results);

  // while($stock=mysql_fetch_array($res)){$r=$results->fetch()
  while($stock=$results->fetch()){
    if (in_array($stock['idDesignation'], $tabIdDesigantion)) {
      # code...
    } else {
      # code...
      $tabIdDesigantion[]=$stock['idDesignation'];
      // $tabIdStock[]=$stock['idStock'];
      $produits[]=$stock;
    }
    
  }


  // $total_rows = $req2->fetch();
  //get total number of records 
  $total_rows = count($produits);

  $total_pages = ceil($total_rows/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);

  //Display records fetched from database.
  echo '<table class="table table-striped contents" id="contents" border="1">';
  echo '<thead>
          <tr id="thStock">
            <th id="th1">ORDRE</th>
            <th>REFERENCE</th>
            <th>REFERENCE E-COMMERCE</th>
            <th>CATEGORIE</th>
            <th>UNITE STOCK</th>
            <th>UNITE DETAILS</th>
            <th>PRIX </th>
            <th>PRIX UNITE STOCK</th>
            <th>OPERATIONS</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;

  // var_dump($produits);

  $produits = array_slice($produits, $page_position, $item_per_page);
  // var_dump($produits);

  // $produits=array();
  foreach ($produits as $r) {
  // while($r=$results->fetch()){ //fetch values
    echo '<tr>';
    echo  '<td>' .$i.'</td>';
    echo  '<td>' .strtoupper($r['designationJcaisse']).'</td>';
    echo  '<td>' .strtoupper($r['designation']).'</td>';
    echo  '<td>' .strtoupper($r['categorie']).'</td>';
    echo  '<td>' .strtoupper($r['uniteStock']).'</td>';
    echo  '<td>' .strtoupper($r['uniteDetails']).'</td>';
    echo  '<td>' .$r['prixuniteStock'].'</td>';
    echo  '<td>' .$r['prix'].'</td>';
    if ($r["image"]) {
      echo  '<td><a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgsup'.$r["idDesignation"].'" /></a>&nbsp;
        <a><img id="image'.$r["id"].'" src="images/iconfinder11.png" align="middle" alt="apperçu" onclick="imgEX_DesignationVT('.$r["id"].')" data-toggle="modal" data-target="#app'.$r["id"].'" /></a>&nbsp;
        <a><img src="images/edit.png" align="middle" alt="modifier" onclick="edit_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgedit'.$r["idDesignation"].'" /></a></td>';
      }
      else{
        echo  '<td><a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgsup'.$r["idDesignation"].'" /></a>&nbsp;
        <a><img id="'.$r["id"].'" src="images/iconfinder9.png" align="middle" alt="img" onclick="imgNV_DesignationVT('.$r["id"].')" data-toggle="modal" data-target="#img'.$r["id"].'" /></a>&nbsp;
        <a><img src="images/edit.png" align="middle" alt="modifier" onclick="edit_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgedit'.$r["idDesignation"].'" /></a></td>';
      }
    echo '</tr>';
    $i++;
    $cpt++;
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="10" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';

  echo '<div class="">';
    echo '<div class="col-md-4">';
      echo '<ul class="pull-left">';
        if ($item_per_page > $total_rows) {
          # code...
            echo '1 à '.($total_rows).' sur '.$total_rows.' articles';        
        } else {
          # code...
          if ($page_number == 1) {
            # code...
            echo '1 à '.($item_per_page).' sur '.$total_rows.' articles';
          } else {
            # code...
            if (($total_rows-($item_per_page * $page_number)) < 0) {
              # code... 
              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows.' sur '.$total_rows.' articles';
            } else {
              # code...
              echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows.' articles';
            }
          }
        }      
      echo '</ul>';
    echo '</div>';
    echo '<div class="col-md-8">';
    // To generate links, we call the pagination function here. 
    echo paginate_function($item_per_page, $page_number, $total_rows, $total_pages);
    echo '</div>';
  echo '</div>';

}

/*****  Tri **/
if ($operation=="12") {
  # code...
  $nbEntree = @$_POST["nbEntree"];
  $query = @$_POST["query"];
  $item_per_page 		= $nbEntree; //item to display per page
  $page_number 		= 0; //page number

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }

  //get total number of records from database
  
  if ($query =="") {
    # code...
    $req2 = $bddV->prepare("SELECT COUNT(*) from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and image='' and id BETWEEN ".$user['debut']." and ".$user['arret']."");
    $req2->execute() or die(print_r($req2->errorInfo()));
  } else {
    # code...
    //get total number of records from database
    $req2 = $bddV->prepare("SELECT COUNT(*) from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and image='' and id BETWEEN ".$user['debut']." and ".$user['arret']." and designation LIKE '%".$query."%'");
    $req2->execute() or die(print_r($req2->errorInfo()));
  }
  $total_rows = $req2->fetch();

  $total_pages = ceil($total_rows[0]/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);

  //Limit our results within a specified range. 
  if ($tri==1) {
    if ($query =="") {
      # code...
      $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and image='' and id BETWEEN ".$user['debut']." and ".$user['arret']." ORDER BY designation ASC LIMIT $page_position, $item_per_page");
      $results->execute(); //Execute prepared Query
    } else {
      # code...
      //Limit our results within a specified range. 
      $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and image='' and id BETWEEN ".$user['debut']." and ".$user['arret']." and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
      $results->execute(); //Execute prepared Query
    }
    
  } else {
    # code...
    if ($query =="") {
      $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and image='' and id BETWEEN ".$user['debut']." and ".$user['arret']." ORDER BY designation DESC LIMIT $page_position, $item_per_page");
      $results->execute(); //Execute prepared Query
    } else {
      //Limit our results within a specified range. 
      $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and image='' and id BETWEEN ".$user['debut']." and ".$user['arret']." and designation LIKE '%".$query."%' ORDER BY designation DESC LIMIT $page_position, $item_per_page");
      $results->execute(); //Execute prepared Query
    }
  }
  
  // $results->bind_result($id, $name, $message); //bind variables to prepared statement

  //Display records fetched from database.
  echo '<table class="table table-striped contents" id="contents" border="1">';
  echo '<thead>
          <tr id="thStock">
            <th id="th1">ORDRE</th>
            <th>REFERENCE</th>
            <th>REFERENCE E-COMMERCE</th>
            <th>CATEGORIE</th>
            <th>CATEGORIE VITRINE</th>
            <th>UNITE STOCK</th>
            <th>UNITE DETAILS</th>
            <th>PRIX </th>
            <th>PRIX UNITE STOCK</th>
            <th>OPERATIONS</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  while($r=$results->fetch()){ //fetch values
    echo '<tr>';
    echo  '<td>' .$i.'</td>';
    echo  '<td>' .strtoupper($r['designationJcaisse']).'</td>';
    echo  '<td>' .strtoupper($r['designation']).'</td>';
    echo  '<td>' .strtoupper($r['categorie']).'</td>';
    echo  '<td>' .strtoupper($r['uniteStock']).'</td>';
    echo  '<td>' .strtoupper($r['uniteDetails']).'</td>';
    echo  '<td>' .$r['prixuniteStock'].'</td>';
    echo  '<td>' .$r['prix'].'</td>';
    if ($r["image"]) {
      echo  '<td><a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgsup'.$r["idDesignation"].'" /></a>&nbsp;
        <a><img id="image'.$r["id"].'" src="images/iconfinder11.png" align="middle" alt="apperçu" onclick="imgEX_DesignationVT('.$r["id"].')" data-toggle="modal" data-target="#app'.$r["id"].'" /></a>&nbsp;
        <a><img src="images/edit.png" align="middle" alt="modifier" onclick="edit_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgedit'.$r["idDesignation"].'" /></a></td>';
      }
      else{
        echo  '<td><a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgsup'.$r["idDesignation"].'" /></a>&nbsp;
        <a><img id="'.$r["id"].'" src="images/iconfinder9.png" align="middle" alt="img" onclick="imgNV_DesignationVT('.$r["id"].')" data-toggle="modal" data-target="#img'.$r["id"].'" /></a>&nbsp;
        <a><img src="images/edit.png" align="middle" alt="modifier" onclick="edit_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgedit'.$r["idDesignation"].'" /></a></td>';
      }
    echo '</tr>';
    $i++;
    $cpt++;
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="10" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';

  echo '<div class="">';
    echo '<div class="col-md-4">';
      echo '<ul class="pull-left">';
        if ($item_per_page > $total_rows[0]) {
          # code...
            echo '1 à '.($total_rows[0]).' sur '.$total_rows[0].' articles';        
        } else {
          # code...
          if ($page_number == 1) {
            # code...
            echo '1 à '.($item_per_page).' sur '.$total_rows[0].' articles';
          } else {
            # code...
            if (($total_rows[0]-($item_per_page * $page_number)) < 0) {
              # code... 
              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows[0].' sur '.$total_rows[0].' articles';
            } else {
              # code...
              echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows[0].' articles';
            }
          }
        }  
      echo '</ul>';
    echo '</div>';
    echo '<div class="col-md-8">';
    // To generate links, we call the pagination function here. 
    echo paginate_function($item_per_page, $page_number, $total_rows[0], $total_pages);
    echo '</div>';
  echo '</div>';

}

/****** Rechercher *****/
if ($operation=="13") {
  # code...
  $nbEntree = @$_POST["nbEntree"];
  $query = @$_POST["query"];
  $item_per_page 		= $nbEntree; //item to display per page
  $page_number 		= 0; //page number

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }

  //get total number of records from database
  
  if ($query =="") {
    # code...
    $req2 = $bddV->prepare("SELECT COUNT(*) from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and image=''");
    $req2->execute() or die(print_r($req2->errorInfo()));
  } else {
    # code...
    //get total number of records from database
    $req2 = $bddV->prepare("SELECT COUNT(*) from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and image='' and designation LIKE '%".$query."%'");
    $req2->execute() or die(print_r($req2->errorInfo()));
  }
  $total_rows = $req2->fetch();

  $total_pages = ceil($total_rows[0]/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  
  if ($query =="") {
    # code...
    $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and image='' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
    $results->execute(); //Execute prepared Query
  } else {
    # code...
    //Limit our results within a specified range. 
    $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and image='' and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
    $results->execute(); //Execute prepared Query
  }

  //Display records fetched from database.
  echo '<table class="table table-striped contents" id="contents" border="1">';
  echo '<thead>
          <tr id="thStock">
            <th id="th1">ORDRE</th>
            <th>REFERENCE</th>
            <th>REFERENCE E-COMMERCE</th>
            <th>CATEGORIE</th>
            <th>CATEGORIE VITRINE</th>
            <th>UNITE STOCK</th>
            <th>UNITE DETAILS</th>
            <th>PRIX </th>
            <th>PRIX UNITE STOCK</th>
            <th>OPERATIONS</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  while($r=$results->fetch()){ //fetch values
    echo '<tr>';
    echo  '<td>' .$i.'</td>';
    echo  '<td>' .strtoupper($r['designationJcaisse']).'</td>';
    echo  '<td>' .strtoupper($r['designation']).'</td>';
    echo  '<td>' .strtoupper($r['categorie']).'</td>';
    echo  '<td>' .strtoupper($r['uniteStock']).'</td>';
    echo  '<td>' .strtoupper($r['uniteDetails']).'</td>';
    echo  '<td>' .$r['prixuniteStock'].'</td>';
    echo  '<td>' .$r['prix'].'</td>';
    if ($r["image"]) {
      echo  '<td><a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgsup'.$r["idDesignation"].'" /></a>&nbsp;
        <a><img id="image'.$r["id"].'" src="images/iconfinder11.png" align="middle" alt="apperçu" onclick="imgEX_DesignationVT('.$r["id"].')" data-toggle="modal" data-target="#app'.$r["id"].'" /></a>&nbsp;
        <a><img src="images/edit.png" align="middle" alt="modifier" onclick="edit_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgedit'.$r["idDesignation"].'" /></a></td>';
      }
      else{
        echo  '<td><a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgsup'.$r["idDesignation"].'" /></a>&nbsp;
        <a><img id="'.$r["id"].'" src="images/iconfinder9.png" align="middle" alt="img" onclick="imgNV_DesignationVT('.$r["id"].')" data-toggle="modal" data-target="#img'.$r["id"].'" /></a>&nbsp;
        <a><img src="images/edit.png" align="middle" alt="modifier" onclick="edit_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgedit'.$r["idDesignation"].'" /></a></td>';
      }
    echo '</tr>';
    $i++;
    $cpt++;
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="10" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';

  echo '<div class="">';
    echo '<div class="col-md-4">';
      echo '<ul class="pull-left">';
        if ($item_per_page > $total_rows[0]) {
          # code...
            echo '1 à '.($total_rows[0]).' sur '.$total_rows[0].' articles';        
        } else {
          # code...
          if ($page_number == 1) {
            # code...
            echo '1 à '.($item_per_page).' sur '.$total_rows[0].' articles';
          } else {
            # code...
            if (($total_rows[0]-($item_per_page * $page_number)) < 0) {
              # code... 
              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows[0].' sur '.$total_rows[0].' articles';
            } else {
              # code...
              echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows[0].' articles';
            }
          }
        }  
      echo '</ul>';
    echo '</div>';
    echo '<div class="col-md-8">';
    // To generate links, we call the pagination function here. 
    echo paginate_function($item_per_page, $page_number, $total_rows[0], $total_pages);
    echo '</div>';
  echo '</div>';

}

/*****  Add number of records **/
if ($operation=="14") {
  # code...

  $nbEntree = @$_POST["nbEntree"];
  $query = @$_POST["query"];
  $item_per_page 		= $nbEntree; //item to display per page
  $page_number 		= 0; //page number

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }

  //get total number of records from database
  
  if ($query =="") {
    # code...
    $req2 = $bddV->prepare("SELECT COUNT(*) from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and image=''");
    $req2->execute() or die(print_r($req2->errorInfo()));
  } else {
    # code...
    //get total number of records from database
    $req2 = $bddV->prepare("SELECT COUNT(*) from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and image='' and designation LIKE '%".$query."%'");
    $req2->execute() or die(print_r($req2->errorInfo()));
  }
  $total_rows = $req2->fetch();

  $total_pages = ceil($total_rows[0]/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  
  if ($query =="") {
    # code...
    $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and image='' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
    $results->execute(); //Execute prepared Query
  } else {
    # code...
    //Limit our results within a specified range. 
    $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and image='' and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
    $results->execute(); //Execute prepared Query
  }
  
  //Display records fetched from database.
  echo '<table class="table table-striped contents" id="contents" border="1">';
  echo '<thead>
          <tr id="thStock">
            <th id="th1">ORDRE</th>
            <th>REFERENCE</th>
            <th>REFERENCE E-COMMERCE</th>
            <th>CATEGORIE</th>
            <th>CATEGORIE VITRINE</th>
            <th>UNITE STOCK</th>
            <th>UNITE DETAILS</th>
            <th>PRIX </th>
            <th>PRIX UNITE STOCK</th>
            <th>OPERATIONS</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  while($r=$results->fetch()){ //fetch values
    echo '<tr>';
    echo  '<td>' .$i.'</td>';
    echo  '<td>' .strtoupper($r['designationJcaisse']).'</td>';
    echo  '<td>' .strtoupper($r['designation']).'</td>';
    echo  '<td>' .strtoupper($r['categorie']).'</td>';
    echo  '<td>' .strtoupper($r['uniteStock']).'</td>';
    echo  '<td>' .strtoupper($r['uniteDetails']).'</td>';
    echo  '<td>' .$r['prixuniteStock'].'</td>';
    echo  '<td>' .$r['prix'].'</td>';
    if ($r["image"]) {
      echo  '<td><a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgsup'.$r["idDesignation"].'" /></a>&nbsp;
        <a><img id="image'.$r["id"].'" src="images/iconfinder11.png" align="middle" alt="apperçu" onclick="imgEX_DesignationVT('.$r["id"].')" data-toggle="modal" data-target="#app'.$r["id"].'" /></a>&nbsp;
        <a><img src="images/edit.png" align="middle" alt="modifier" onclick="edit_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgedit'.$r["idDesignation"].'" /></a></td>';
      }
      else{
        echo  '<td><a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgsup'.$r["idDesignation"].'" /></a>&nbsp;
        <a><img id="'.$r["id"].'" src="images/iconfinder9.png" align="middle" alt="img" onclick="imgNV_DesignationVT('.$r["id"].')" data-toggle="modal" data-target="#img'.$r["id"].'" /></a>&nbsp;
        <a><img src="images/edit.png" align="middle" alt="modifier" onclick="edit_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgedit'.$r["idDesignation"].'" /></a></td>';
      }
    echo '</tr>';
    $i++;
    $cpt++;
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="10" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';

  echo '<div class="">';
    echo '<div class="col-md-4">';
      echo '<ul class="pull-left">';
        if ($item_per_page > $total_rows[0]) {
          # code...
            echo '1 à '.($total_rows[0]).' sur '.$total_rows[0].' articles';        
        } else {
          # code...
          if ($page_number == 1) {
            # code...
            echo '1 à '.($item_per_page).' sur '.$total_rows[0].' articles';
          } else {
            # code...
            if (($total_rows[0]-($item_per_page * $page_number)) < 0) {
              # code... 
              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows[0].' sur '.$total_rows[0].' articles';
            } else {
              # code...
              echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows[0].' articles';
            }
          }
        }  
      echo '</ul>';
    echo '</div>';
    echo '<div class="col-md-8">';
    // To generate links, we call the pagination function here. 
    echo paginate_function($item_per_page, $page_number, $total_rows[0], $total_pages);
    echo '</div>';
  echo '</div>';
}

function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
  {
      $pagination = '';
      if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="pagination pull-right">';
          
          if ($current_page == 1) {
            # code...
            $pagination .= '<li class="page-item disabled"><a data-page="1" class="page-link"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></a>
            </li>';
          } else {
            # code...
            $pagination .= '<li class="page-item"><a href="#" data-page="'.($current_page - 1).'" class="page-link"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></a></li>';
          }        
        
          if ($total_pages <= 5) {                                            
            for($page = 1; $page <= $total_pages; $page++){
              if ($current_page == $page) {
                # code...
                $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
              } else {
                # code...
                $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
              }
              }
            }else {  
              if ($current_page == 1) {
                # code...
                $pagination .= '<li class="page-item active"><a href="#" data-page="1" class="page-link">1</a></li>';
              } else {
                # code...
                $pagination .= '<li class="page-item"><a href="#" data-page="1" class="page-link">1</a></li>';
              }
                                                              
              if($current_page == 1 || $current_page == 2){ 
                for($page = 2 ; $page <= 3; $page++){
                  if ($current_page == $page) {
                    # code...
                    $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  } else {
                    # code...
                    $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  }                  
                }         
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
                                            
                } else if(($current_page > 2) and ($current_page < $total_pages - 2)){  
                  $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';

                  for($page =$current_page ; $page <= ($current_page + 1); $page++){ 
                    if ($current_page == $page) {
                      # code...
                      $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                    } else {
                      # code...
                      $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                    }
                }
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
              
              }else{
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
                for($page = ($total_pages - 2) ; $page <= ($total_pages - 1); $page++){
                  if ($current_page == $page) {
                    # code...
                    $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  } else {
                    # code...
                    $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  }
                }
              }
              if ($current_page == $total_pages) {
                # code...
                $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$total_pages.'" class="page-link">'.$total_pages.'</a></li>';
              } else {
                # code...
                $pagination .= '<li class="page-item page"><a href="#" data-page="'.$total_pages.'" class="page-link">'.$total_pages.'</a></li>';
              }
              }
          if ($current_page == $total_pages) {
            # code...
            $pagination .= '<li class="page-item disabled"><a data-page="'.$total_pages.'" class="page-link"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a></li>';
          } else {
            # code...
            $pagination .= '<li class="page-item"><a href="#" data-page="'.($current_page + 1).'" class="page-link"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a></li>';
          }
          
          $pagination .= '</ul>'; 
      }
      return $pagination; //return pagination links
  }


  // function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
  // {

    //     $pagination = '';
    //     if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
    //         $pagination .= '<ul class="pagination">';
            
    //         $right_links    = $current_page + 3; 
    //         $previous       = $current_page - 3; //previous link 
    //         $next           = $current_page + 1; //next link
    //         $first_link     = true; //boolean var to decide our first link
            
    //         if($current_page > 1){
    //           $previous_link = ($previous==0)?1:$previous;
    //           $pagination .= '<li class="first"><a href="#" data-page="1" title="First">«</a></li>'; //first link
    //           $pagination .= '<li><a href="#" data-page="'.$previous_link.'" title="Previous"><</a></li>'; //previous link
    //               for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
    //                   if($i > 0){
    //                       $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
    //                   }
    //               }   
    //           $first_link = false; //set first link to false
    //         }
            
    //         if($first_link){ //if current active page is first link
    //             $pagination .= '<li class="first active">'.$current_page.'</li>';
    //         }elseif($current_page == $total_pages){ //if it's the last active link
    //             $pagination .= '<li class="last active">'.$current_page.'</li>';
    //         }else{ //regular current link
    //             $pagination .= '<li class="active">'.$current_page.'</li>';
    //         }
                    
    //         for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
    //             if($i<=$total_pages){
    //                 $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
    //             }
    //         }
    //         if($current_page < $total_pages){ 
    //           $next_link = ($i > $total_pages)? $total_pages : $i;
    //           $pagination .= '<li><a href="#" data-page="'.$next_link.'" title="Next">></a></li>'; //next link
    //           $pagination .= '<li class="last"><a href="#" data-page="'.$total_pages.'" title="Last">»</a></li>'; //last link
    //         }
            
    //         $pagination .= '</ul>'; 
    //     }
    //     return $pagination; //return pagination links
    // }




?>
