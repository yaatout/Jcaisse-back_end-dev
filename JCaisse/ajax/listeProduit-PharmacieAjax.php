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
  header('Location:../index.php');
  }

require('../connection.php');

require('../declarationVariables.php');


$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);

// $sql="select * from `".$nomtableDesignation."` where classe=0 order by idDesignation desc";
  // $res=mysql_query($sql);

  // $data=array();
  // $i=1;
  // while($design=mysql_fetch_array($res)){

  //   $sql1="SELECT * FROM `". $nomtableStock."` where idDesignation='".$design['idDesignation']."' ORDER BY idStock DESC";
  //   $res1=mysql_query($sql1);
  //   $stock=mysql_fetch_array($res1);

  //   $rows = array();			
  //   $rows[] = strtoupper($design['designation']);	
  //   $rows[] = strtoupper($design['forme']);
  //   $rows[] = '<input type="number" class="form-control" id="quantiteAStocke-'.$design['idDesignation'].'" min=1 value="" required=""/>';
  //   $rows[] = '<input type="number" class="form-control" id="prixSession-'.$design['idDesignation'].'" min=1 value="'.$stock['prixSession'].'" required=""/>';
  //   $rows[] = '<input type="number" class="form-control" id="prixPublic-'.$design['idDesignation'].'" min=1 value="'.$design['prixPublic'].'" required=""/>';
  //   $rows[] = '<input type="Date" class="form-control" name="dateExpiration-'.$design['idDesignation'].'" id="dateExpiration-'.$design['idDesignation'].'" value=""/>';
  //   $rows[] = '<button type="button" onclick="ajt_Stock_P('.$design["idDesignation"].')" id="btn_AjtStock_P-'.$design['idDesignation'].'" class="btn btn-success">
  //     <i class="glyphicon glyphicon-plus"></i>AJOUTER
  //    </button>';
    
  //   $data[] = $rows; 
  //   $i=$i + 1;
  // }


  // $results = ["sEcho" => 1,
  //           "iTotalRecords" => count($data),
  //           "iTotalDisplayRecords" => count($data),
  //           "aaData" => $data ];

// echo json_encode($results);


if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeASPh = @$_POST["nbEntreeASPh"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= ($nbEntreeASPh!=0) ? $nbEntreeASPh : 10; //item to display per page
  $page_number 		= 0; //page number

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }
  
  //get total number of records from database
  
  if ($query =="") {
    # code...
    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where classe=0";
    $res=mysql_query($sql);
  } else {
    # code...
    //get total number of records from database
    if ($cb==1) {
      # code...
      $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where classe=0 and codeBarredesignation='".$query."'";
      $res=mysql_query($sql);
    }else {
      # code...
      $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where classe=0 and (designation LIKE '%".$query."%' or codeBarredesignation LIKE '%".$query."%')";
      $res=mysql_query($sql);
    }
  }
  $total_rows = mysql_fetch_array($res);

  $total_pages = ceil($total_rows[0]/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  
  if ($query =="") {
    # code...
    $sql="select * from `".$nomtableDesignation."` where classe=0 order by idDesignation desc LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
    // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");
    // $results->execute(); //Execute prepared Query
  } else {
    # code...
    //Limit our results within a specified range. 
    if ($cb==1) {
      # code...
      $sql="select * from `".$nomtableDesignation."` where classe=0 and codeBarredesignation='".$query."' order by idDesignation desc LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    } else {
      # code...
      $sql="select * from `".$nomtableDesignation."` where classe=0 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by idDesignation desc LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    }
    
    // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
    // $results->execute(); //Execute prepared Query
  }

  //Display records fetched from database.
    echo '<table class="table table-striped contents" id="contents" border="1">';
    echo '<thead>
            <tr id="thStock">
              <th>Reference</th>
              <th>CodeBarre</th>
              <th>Quantite</th>
              <th>Prix(Session)</th>
              <th>Prix(Public)</th>
              <th>Expiration</th>
              <th>Operation</th> 
            </tr>
          </thead>';
  
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  // $produits=array();
  while($design=mysql_fetch_array($res)){

    $sql1="SELECT * FROM `". $nomtableStock."` where idDesignation='".$design['idDesignation']."' ORDER BY idStock DESC";
    $res1=mysql_query($sql1);
    $stock=mysql_fetch_array($res1);
    //  var_dump($stock);
    
    // if(in_array($designation['idDesignation'], $produits)){
    //   // echo "Existe.";
    // }
    // else{

      echo '<tr>';
      			
        echo  '<td>'.strtoupper($design['designation']).'</td>';	
        echo  '<td>'.strtoupper($design['codeBarreDesignation']).'</td>';
        echo  '<td><input type="number" class="form-control" id="quantiteAStocke-'.$design['idDesignation'].'" min=1 value="" required=""/></td>';
        echo  '<td><input type="number" class="form-control" id="prixSession-'.$design['idDesignation'].'" min=1 value="'.$stock['prixSession'].'" required=""/></td>';
        echo  '<td><input type="number" class="form-control" id="prixPublic-'.$design['idDesignation'].'" min=1 value="'.$design['prixPublic'].'" required=""/></td>';
        echo  '<td><input type="Date" class="form-control" name="dateExpiration-'.$design['idDesignation'].'" id="dateExpiration-'.$design['idDesignation'].'" value=""/></td>';
        echo  '<td><button type="button" onclick="ajt_Stock_P('.$design["idDesignation"].')" id="btn_AjtStock_P-'.$design['idDesignation'].'" class="btn btn-success">
            <i class="glyphicon glyphicon-plus"></i>AJOUTER
          </button></td>';

        echo '</tr>';

      $i++;
      $cpt++;
      // $produits[] = $designation['idDesignation'];
    // }
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="9" align="center">Données introuvables!</td>';
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

/*****  Tri **/
else if ($operation=="2") {
  # code...
  $nbEntreeASPh = @$_POST["nbEntreeASPh"];
  $query = @$_POST["query"];
  $item_per_page 		= ($nbEntreeASPh!=0) ? $nbEntreeASPh : 10; //item to display per page
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
    $sql0="select COUNT(*) from `".$nomtableDesignation."` where classe=0 order by idDesignation desc";
    $res0=mysql_query($sql0);
  } else {
    # code...
    //get total number of records from database 
    $sql0="select COUNT(*) from `".$nomtableDesignation."` where classe=0 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by idDesignation desc";
    $res0=mysql_query($sql0);
  }
  $total_rows = mysql_fetch_array($res0);

  $total_pages = ceil($total_rows[0]/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);

  
  if ($tri==1) {
    if ($query =="") {
      # code... 
      $sql="select * from `".$nomtableDesignation."` where classe=0 order by designation DESC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    } else {
      # code...
      //Limit our results within a specified range. 
      $sql="select * from `".$nomtableDesignation."` where classe=0 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by designation DESC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    }
  } else {
    if ($query =="") {
      # code... 
      $sql="select * from `".$nomtableDesignation."` where classe=0 order by designation ASC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    } else {
      # code...
      //Limit our results within a specified range. 
      $sql="select * from `".$nomtableDesignation."` where classe=0 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by designation ASC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    }    
  }

  //Display records fetched from database.
    echo '<table class="table table-striped contents" id="contents" border="1">';
    echo '<thead>
            <tr id="thStock">
              <th>Reference</th>
              <th>CodeBarre</th>
              <th>Quantite</th>
              <th>Prix(Session)</th>
              <th>Prix(Public)</th>
              <th>Expiration</th>
              <th>Operation</th> 
            </tr>
          </thead>';
  
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  // $produits=array();
  while($design=mysql_fetch_array($res)){

    $sql1="SELECT * FROM `". $nomtableStock."` where idDesignation='".$design['idDesignation']."' ORDER BY idStock DESC";
    $res1=mysql_query($sql1);
    $stock=mysql_fetch_array($res1);
    //  var_dump($stock);
    
    // if(in_array($designation['idDesignation'], $produits)){
    //   // echo "Existe.";
    // }
    // else{

      echo '<tr>';
      			
        echo  '<td>'.strtoupper($design['designation']).'</td>';	
        echo  '<td>'.strtoupper($design['forme']).'</td>';
        echo  '<td><input type="number" class="form-control" id="quantiteAStocke-'.$design['idDesignation'].'" min=1 value="" required=""/></td>';
        echo  '<td><input type="number" class="form-control" id="prixSession-'.$design['idDesignation'].'" min=1 value="'.$stock['prixSession'].'" required=""/></td>';
        echo  '<td><input type="number" class="form-control" id="prixPublic-'.$design['idDesignation'].'" min=1 value="'.$design['prixPublic'].'" required=""/></td>';
        echo  '<td><input type="Date" class="form-control" name="dateExpiration-'.$design['idDesignation'].'" id="dateExpiration-'.$design['idDesignation'].'" value=""/></td>';
        echo  '<td><button type="button" onclick="ajt_Stock_P('.$design["idDesignation"].')" id="btn_AjtStock_P-'.$design['idDesignation'].'" class="btn btn-success">
            <i class="glyphicon glyphicon-plus"></i>AJOUTER
          </button></td>';

        echo '</tr>';

      $i++;
      $cpt++;
      // $produits[] = $designation['idDesignation'];
    // }
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="9" align="center">Données introuvables!</td>';
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
else if ($operation=="13") {
  # code...
  $nbEntreeASPh = @$_POST["nbEntreeASPh"];
  $query = @$_POST["query"];
  $item_per_page 		= ($nbEntreeASPh!=0) ? $nbEntreeASPh : 10;($nbEntreeASPh!=0) ? $nbEntreeASPh : 10; //item to display per page
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
    $sql0="select COUNT(*) from `".$nomtableDesignation."` where classe=0 order by idDesignation desc";
    $res0=mysql_query($sql0);
  } else {
    # code...
    //get total number of records from database 
    $sql0="select COUNT(*) from `".$nomtableDesignation."` where classe=0 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by idDesignation desc";
    $res0=mysql_query($sql0);
  }
  $total_rows = mysql_fetch_array($res0);

  $total_pages = ceil($total_rows[0]/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  
  if ($query =="") {
    # code... 
    $sql="select * from `".$nomtableDesignation."` where classe=0 order by idDesignation DESC LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
  } else {
    # code...
    //Limit our results within a specified range. 
    $sql="select * from `".$nomtableDesignation."` where classe=0 and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') order by idDesignation DESC LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
  }

  //Display records fetched from database.
    echo '<table class="table table-striped contents" id="contents" border="1">';
    echo '<thead>
            <tr id="thStock">
              <th>Reference</th>
              <th>CodeBarre</th>
              <th>Quantite</th>
              <th>Prix(Session)</th>
              <th>Prix(Public)</th>
              <th>Expiration</th>
              <th>Operation</th> 
            </tr>
          </thead>';
  
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  // $produits=array();
  while($design=mysql_fetch_array($res)){

    $sql1="SELECT * FROM `". $nomtableStock."` where idDesignation='".$design['idDesignation']."' ORDER BY idStock DESC";
    $res1=mysql_query($sql1);
    $stock=mysql_fetch_array($res1);
    //  var_dump($stock);
    
    // if(in_array($designation['idDesignation'], $produits)){
    //   // echo "Existe.";
    // }
    // else{

      echo '<tr>';
      			
        echo  '<td>'.strtoupper($design['designation']).'</td>';	
        echo  '<td>'.strtoupper($design['forme']).'</td>';
        echo  '<td><input type="number" class="form-control" id="quantiteAStocke-'.$design['idDesignation'].'" min=1 value="" required=""/></td>';
        echo  '<td><input type="number" class="form-control" id="prixSession-'.$design['idDesignation'].'" min=1 value="'.$stock['prixSession'].'" required=""/></td>';
        echo  '<td><input type="number" class="form-control" id="prixPublic-'.$design['idDesignation'].'" min=1 value="'.$design['prixPublic'].'" required=""/></td>';
        echo  '<td><input type="Date" class="form-control" name="dateExpiration-'.$design['idDesignation'].'" id="dateExpiration-'.$design['idDesignation'].'" value=""/></td>';
        echo  '<td><button type="button" onclick="ajt_Stock_P('.$design["idDesignation"].')" id="btn_AjtStock_P-'.$design['idDesignation'].'" class="btn btn-success">
            <i class="glyphicon glyphicon-plus"></i>AJOUTER
          </button></td>';

        echo '</tr>';

      $i++;
      $cpt++;
      // $produits[] = $designation['idDesignation'];
    // }
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="9" align="center">Données introuvables!</td>';
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
else if ($operation=="14") {
  # code...

  $nbEntreeASPh = @$_POST["nbEntreeASPh"];
  $query = @$_POST["query"];
  $item_per_page 		= ($nbEntreeASPh!=0) ? $nbEntreeASPh : 10; //item to display per page
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
    $sql0="select COUNT(*) from `".$nomtableDesignation."` where classe=0 order by idDesignation desc";
    $res0=mysql_query($sql0);
  } else {
    # code...
    //get total number of records from database 
    $sql0="select COUNT(*) from `".$nomtableDesignation."` where classe=0 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by idDesignation desc";
    $res0=mysql_query($sql0);
  }
  $total_rows = mysql_fetch_array($res0);

  $total_pages = ceil($total_rows[0]/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  
  if ($query =="") {
    # code... 
    $sql="select * from `".$nomtableDesignation."` where classe=0 order by idDesignation DESC LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
  } else {
    # code...
    //Limit our results within a specified range. 
    $sql="select * from `".$nomtableDesignation."` where classe=0 and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') order by idDesignation DESC LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
  }

  //Display records fetched from database.
    echo '<table class="table table-striped contents" id="contents" border="1">';
    echo '<thead>
            <tr id="thStock">
              <th>Reference</th>
              <th>CodeBarre</th>
              <th>Quantite</th>
              <th>Prix(Session)</th>
              <th>Prix(Public)</th>
              <th>Expiration</th>
              <th>Operation</th> 
            </tr>
          </thead>';
  
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  // $produits=array();
  while($design=mysql_fetch_array($res)){

    $sql1="SELECT * FROM `". $nomtableStock."` where idDesignation='".$design['idDesignation']."' ORDER BY idStock DESC";
    $res1=mysql_query($sql1);
    $stock=mysql_fetch_array($res1);
    //  var_dump($stock);
    
    // if(in_array($designation['idDesignation'], $produits)){
    //   // echo "Existe.";
    // }
    // else{

      echo '<tr>';
      			
        echo  '<td>'.strtoupper($design['designation']).'</td>';	
        echo  '<td>'.strtoupper($design['forme']).'</td>';
        echo  '<td><input type="number" class="form-control" id="quantiteAStocke-'.$design['idDesignation'].'" min=1 value="" required=""/></td>';
        echo  '<td><input type="number" class="form-control" id="prixSession-'.$design['idDesignation'].'" min=1 value="'.$stock['prixSession'].'" required=""/></td>';
        echo  '<td><input type="number" class="form-control" id="prixPublic-'.$design['idDesignation'].'" min=1 value="'.$design['prixPublic'].'" required=""/></td>';
        echo  '<td><input type="Date" class="form-control" name="dateExpiration-'.$design['idDesignation'].'" id="dateExpiration-'.$design['idDesignation'].'" value=""/></td>';
        echo  '<td><button type="button" onclick="ajt_Stock_P('.$design["idDesignation"].')" id="btn_AjtStock_P-'.$design['idDesignation'].'" class="btn btn-success">
            <i class="glyphicon glyphicon-plus"></i>AJOUTER
          </button></td>';

        echo '</tr>';

      $i++;
      $cpt++;
      // $produits[] = $designation['idDesignation'];
    // }
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="9" align="center">Données introuvables!</td>';
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