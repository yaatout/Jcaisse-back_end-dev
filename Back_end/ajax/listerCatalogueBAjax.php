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
if(!$_SESSION['iduserBack']){
	header('Location:../index.php');
}




require('../connectionPDO.php');



require('../declarationVariables.php');

$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);
$id = @$_POST["id"];
//var_dump($id);
            $sql0="SELECT * FROM `aaa-catalogueTotal` WHERE id = :id";
$req0 = $bdd->prepare($sql0);
$req0->execute(array('id' => $id));
if ($req0) {
    $tab0 = $req0->fetch(PDO::FETCH_ASSOC);
    $catalogueTotal='aaa-catalogueTotal';
    $type=$tab0['type'];
    $categorie=$tab0['categorie'];
    $typeCategorie=$tab0['type']."-".$tab0['categorie'];
    $catalogueTypeCateg='aaa-catalogue-'.$typeCategorie;
    $categorieTypeCateg='aaa-categorie-'.$typeCategorie;
    $formeTypeCategPharmacie='aaa-forme-'.$typeCategorie;
    $tableauTypeCategPharmacie='aaa-tableau-'.$typeCategorie;
} else {
    $tab0=0;
}
$typeCategorie=$tab0['type']."-".$tab0['categorie'];
$catalogueTypeCateg='aaa-catalogue-'.$typeCategorie;
$sql3="SELECT * FROM `".$catalogueTypeCateg."` ORDER BY designation ASC";
$req3 = $bdd->query($sql3);
            

if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeB = @$_POST["nbEntreeBO"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= $nbEntreeB; //item to display per page
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
    $sql="SELECT COUNT(*) as total_row FROM `".$catalogueTypeCateg."`";
    $req = $bdd->query($sql);
    
  } else {
    //get total number of records from database
    if ($cb==1) {
      $sql="SELECT COUNT(*) as total_row FROM `".$catalogueTypeCateg."` WHERE codeBarredesignation = :query";
      $req = $bdd->prepare($sql);
      $req->execute(array('query' => $query));
    } else {
      $sql="SELECT COUNT(*) as total_row FROM `".$catalogueTypeCateg."` WHERE designation LIKE :query1 OR codeBarreDesignation LIKE :query2";
      $req = $bdd->prepare($sql);
      $req->execute(array(
        'query1' => '%'.$query.'%',
        'query2' => '%'.$query.'%'
      ));
    }
  }
  $total_rows = $req->fetch(PDO::FETCH_ASSOC);
  
  $total_pages = ceil($total_rows['total_row']/$item_per_page);
  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  if ($query =="") {
    $sql="SELECT * FROM `".$catalogueTypeCateg."` ORDER BY designation ASC LIMIT $page_position, $item_per_page";
    $req = $bdd->prepare($sql);
    $req->execute();
  } else {
    //Limit our results within a specified range. 
    if ($cb==1) {
      $sql="SELECT * FROM `".$catalogueTypeCateg."` WHERE codeBarredesignation = :query ORDER BY designation ASC LIMIT $page_position, $item_per_page";
      $req = $bdd->prepare($sql);
      $req->execute(array(
        'query' => $query
      )) or die(print_r($req->errorInfo()));
    } else {
      $sql="SELECT * FROM `".$catalogueTypeCateg."` WHERE designation LIKE :query1 OR codeBarreDesignation LIKE :query2 ORDER BY designation ASC LIMIT $page_position, $item_per_page";
      $req = $bdd->prepare($sql);
      $req->execute(array(
        'query1' => '%'.$query.'%',
        'query2' => '%'.$query.'%'
      )) or die(print_r($req->errorInfo()));
    }
  }

  //Display records fetched from database.
  echo '<table class="table table-striped contents display tabDesign tableau3" id="tableDesignation" border="1">';
  echo '<thead>
          <tr id="thDesignation">
          <th>DESIGNATION</th>
          <th>CATEGORIE</th>
          <th>UNITE STOCK</th>
          <th>ARTICLE PAR UNITE DE STOCK</th>
          <th>PRIX</th>
          <th>PRIX ACHAT</th>
          <th>PRIX UNITE STOCK</th>
          <th>CODE BARRE</th>
          <th>OPERATION</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  
  while($design = $req->fetch(PDO::FETCH_ASSOC)){ //fetch values

    $selC='<td><span style="color:blue;" >
            <select class="form-control" id="categorieD-'.$design['id'].'" >
                <option  selected value="'.$design["categorie"].'" >'.$design['categorie'].'</option>';
                $sql111="SELECT * FROM `".$categorieTypeCateg."` ORDER BY `nomCategorie` ASC";
                $req111 = $bdd->query($sql111);
                while($op = $req111->fetch(PDO::FETCH_ASSOC)){
                    $selC=$selC."<option  value='".$op["nomCategorie"]."' > ".$op["nomCategorie"].'</option>';
                }
            $selC=$selC.'</select></span></td>';
    
     $selUniteStock='<td><span style="" >
            <select class="form-control" id="uniteStockD-'.$design['id'].'" >
                <option  selected value="'.$design["uniteStock"].'" >'.$design['uniteStock'].'</option>';
                $sql111="SELECT * FROM `aaa-unitestock` ORDER BY `nomUniteStock` ASC";
                $req111 = $bdd->query($sql111);
                while($op = $req111->fetch(PDO::FETCH_ASSOC)){
                    $selUniteStock=$selUniteStock."<option  value='".$op["nomUniteStock"]."' > ".$op["nomUniteStock"].'</option>';
                }
            $selUniteStock=$selUniteStock.'</select></span></td>';
            

    $sql1="SELECT * FROM `".$catalogueTypeCateg."`";
    $req1 = $bdd->query($sql1);
    
    echo '<tr>';
    if ($i==1) {
      # code...
      echo  '<td><span style="color:blue;"><form class="form form-inline" role="form"  method="post" >
      <input type="text"  class="form-control" id="designationD-'.$design['id'].'" min=1 value="'.$design['designation'].'" required=""/></span></td>';
      /*echo  '<td><span style="color:blue;" onmouseover="selectCategorieBD('.$design["id"].','.$id.')"><select class="form-control" id="categorieD-'.$design['id'].'"  >
      <option  selected value= "'.$design['categorie'].'" >'.$design['categorie'].'</option></select></span></td>';*/
            
      echo  $selC;
      echo  $selUniteStock;
      echo  '<td><span style="color:blue;"><input type="text" size="40" class="form-control" id="nbrArtUSD-'.$design['id'].'" min=1 value="'.$design['nbreArticleUniteStock'].'" required=""/></span></td>';
      echo  '<td><span style="color:blue;"><input type="text" size="40" class="form-control" id="prixuniteStockD-'.$design['id'].'" min=1 value="'.$design['prixuniteStock'].'" required=""/></span></td>';
      echo  '<td><span style="color:blue;"><input type="text" size="40" class="form-control" id="prixD-'.$design['id'].'" min=1 value="'.$design['prix'].'" required=""/></span></td>';
      echo  '<td><span style="color:blue;"><input type="text" size="40" class="form-control" id="prixachatD-'.$design['id'].'" min=1 value="'.$design['prixAchat'].'" required=""/></span></td>';
      echo  '<td><span style="color:blue;"><input type="text" size="40" class="form-control" id="codeBarreDesignD-'.$design['id'].'" min=1 value="'.$design['codeBarreDesignation'].'" required=""/></span></form></td>';
    ;
      } else {
      # code...
      echo  '<td><span ><form class="form form-inline" role="form"  method="post" >
      <input type="text" class="form-control" id="designationD-'.$design['id'].'" min=1 value="'.$design['designation'].'" required=""/></span></td>';
      /*echo  '<td><span onmouseover="selectCategorieBD('.$design["id"].','.$id.')"><select class="form-control" id="categorieD-'.$design['id'].'"  >
      <option selected value= "'.$design['categorie'].'" >'.$design['categorie'].'</option></select></span></td>';*/
      echo  $selC;
      echo  $selUniteStock;
      echo  '<td><span ><input type="text" size="40" class="form-control" id="nbrArtUSD-'.$design['id'].'" min=1 value="'.$design['nbreArticleUniteStock'].'" required=""/></span></td>';
      echo  '<td><span ><input type="text" size="40" class="form-control" id="prixuniteStockD-'.$design['id'].'" min=1 value="'.$design['prixuniteStock'].'" required=""/></span></td>';
      echo  '<td><span ><input type="text" size="40" class="form-control" id="prixD-'.$design['id'].'" min=1 value="'.$design['prix'].'" required=""/></span></td>';
      echo  '<td><span ><input type="text" size="40" class="form-control" id="prixachatD-'.$design['id'].'" min=1 value="'.$design['prixAchat'].'" required=""/></span></td>';
      echo  '<td><span ><input type="text" size="40" class="form-control" id="codeBarreDesignD-'.$design['id'].'" min=1 value="'.$design['codeBarreDesignation'].'" required=""/></span></form></td>';
    ;
    } if ($design["image"]) {
      echo '<td><a onclick="mdf_Designation_B('.$design["id"].','.$id.','.$i.')" id="pencilmoD-'.$design['id'].'" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
        <a onclick="spm_Designation_B('.$design["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
       <a><img src="images/iconfinder11.png" align="middle" alt="apperçu"  onclick="imgE_DesignationC('.$design["id"].','.$id.','.$i.')" /></a>
       </td>';
      }
      else{
        echo '<td><a onclick="mdf_Designation_B('.$design["id"].','.$id.','.$i.')" id="pencilmoD-'.$design['id'].'" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
        <a onclick="spm_Designation_B('.$design["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
       <a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  onclick="imgN_DesignationC('.$design["id"].','.$id.','.$i.')" /></a>
       </td>';
      }
    echo '</tr>';
    $i++;
    $cpt++;
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
        if ($item_per_page > $total_rows['total_row']) {
          # code...
            echo '1 à '.($total_rows['total_row']).' sur '.$total_rows['total_row'].' articles';        
        } else {
          # code...
          if ($page_number == 1) {
            # code...
            echo '1 à '.($item_per_page).' sur '.$total_rows['total_row'].' articles';
          } else {
            # code...
            if (($total_rows['total_row']-($item_per_page * $page_number)) < 0) {
              # code... 
              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows['total_row'].' sur '.$total_rows['total_row'].' articles';
            } else {
              # code...
              echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows['total_row'].' articles';
            }
          }
        }      
      echo '</ul>';
    echo '</div>';
    echo '<div class="col-md-8">';
    // To generate links, we call the pagination function here. 
    echo paginate_function($item_per_page, $page_number, $total_rows['total_row'], $total_pages);
    echo '</div>';
  echo '</div>';
}

/*****  Tri **/
if ($operation=="2") {
  # code...
  $nbEntree = @$_POST["nbEntreeBO"];
  $query = @$_POST["query"];
  $item_per_page 		= $nbEntree; //item to display per page
  $page_number 		= 0; //page number
  $tabIdDesigantion = array();
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
    $sql="SELECT COUNT(*) as total_row FROM `".$nomtableDesignation."` WHERE classe=0";
    $req = $bdd->query($sql);
  } else {
    # code...
    //get total number of records from database
    $sql="SELECT COUNT(*) as total_row FROM `".$nomtableDesignation."` WHERE classe=0 AND (designation LIKE :query1 OR codeBarreDesignation LIKE :query2)";
    $req = $bdd->prepare($sql);
    $req->execute(array(
      'query1' => '%'.$query.'%',
      'query2' => '%'.$query.'%'
    ));
  }
  $total_rows = $req->fetch(PDO::FETCH_ASSOC);
  $total_pages = ceil($total_rows['total_row']/$item_per_page);
  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  if ($tri==0) {
    if ($query =="") {
      # code...
      $sql="SELECT * FROM `".$nomtableDesignation."` WHERE classe=0 ORDER BY designation ASC LIMIT :page_position, :item_per_page";
      $req = $bdd->prepare($sql);
      $req->execute(array(
        'page_position' => $page_position,
        'item_per_page' => $item_per_page
      ));
      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");
      // $results->execute(); //Execute prepared Query
      $sql1="SELECT * FROM `".$nomtableDesignation."` WHERE classe=0 ORDER BY idDesignation DESC LIMIT 1";
      $req1 = $bdd->query($sql1);
    } else {
      # code...
      //Limit our results within a specified range. 
      $sql="SELECT * FROM `".$nomtableDesignation."` WHERE classe=0 AND (designation LIKE :query1 OR codeBarreDesignation LIKE :query2) ORDER BY designation ASC LIMIT :page_position, :item_per_page";
      $req = $bdd->prepare($sql);
      $req->execute(array(
        'query1' => '%'.$query.'%',
        'query2' => '%'.$query.'%',
        'page_position' => $page_position,
        'item_per_page' => $item_per_page
      ));
      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
      // $results->execute(); //Execute prepared Query
      $sql1="SELECT * FROM `".$nomtableDesignation."` WHERE classe=0 AND (designation LIKE :query1 OR codeBarreDesignation LIKE :query2) ORDER BY idDesignation DESC LIMIT 1";
      $req1 = $bdd->prepare($sql1);
      $req1->execute(array(
        'query1' => '%'.$query.'%',
        'query2' => '%'.$query.'%'
      ));
    }
  } else {
    if ($query =="") {
      # code...
      $sql="SELECT * FROM `".$nomtableDesignation."` WHERE classe=0 ORDER BY designation DESC LIMIT :page_position, :item_per_page";
      $req = $bdd->prepare($sql);
      $req->execute(array(
        'page_position' => $page_position,
        'item_per_page' => $item_per_page
      ));
      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");
      // $results->execute(); //Execute prepared Query
      $sql1="SELECT * FROM `".$nomtableDesignation."` WHERE classe=0 ORDER BY idDesignation DESC LIMIT 1";
      $req1 = $bdd->query($sql1);
    } else {
      # code...
      //Limit our results within a specified range. 
      $sql="SELECT * FROM `".$nomtableDesignation."` WHERE classe=0 AND (designation LIKE :query1 OR codeBarreDesignation LIKE :query2) ORDER BY designation DESC LIMIT :page_position, :item_per_page";
      $req = $bdd->prepare($sql);
      $req->execute(array(
        'query1' => '%'.$query.'%',
        'query2' => '%'.$query.'%',
        'page_position' => $page_position,
        'item_per_page' => $item_per_page
      ));
      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
      // $results->execute(); //Execute prepared Query
      $sql1="SELECT * FROM `".$nomtableDesignation."` WHERE classe=0 AND (designation LIKE :query1 OR codeBarreDesignation LIKE :query2) ORDER BY idDesignation DESC LIMIT 1";
      $req1 = $bdd->prepare($sql1);
      $req1->execute(array(
        'query1' => '%'.$query.'%',
        'query2' => '%'.$query.'%'
      ));
    }
  }

  $maxId = $req1->fetch(PDO::FETCH_ASSOC);
  // var_dump($tabIdDesigantion);
  // $results->bind_result($id, $name, $message); //bind variables to prepared statement
  //Display records fetched from database.
  echo '<table class="table table-striped contents display tabDesign tableau3" id="tableDesignation" border="1">';
  echo '<thead>
          <tr id="thDesignation">
            <th>Ordre</th>
            <th>Reference</th>
            <th>CodeBarre</th>
            <th>Unite Stock (U.S)</th>
            <th>Nombre Articles U.S</th>
            <th>Prix U.S</th>
            <th>Prix Unitaire</th>
            <th>Prix Achat</th>
            <th>Operations</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  while($design = $req->fetch(PDO::FETCH_ASSOC)){ //fetch values
    $sql1="SELECT * FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation";
    $req1 = $bdd->prepare($sql1);
    $req1->execute(array('idDesignation' => $design['idDesignation']));
    echo '<tr>';
    if ($design['idDesignation']==$maxId['idDesignation']) {
      # code...
      echo  '<td>' .$i.'</td>';
      echo  '<td><span style="color:blue;">'.strtoupper($design['designation']).'</span></td>';
      echo  '<td><span style="color:blue;">'.strtoupper($design['codeBarreDesignation']).'</span></td>';
      echo  '<td><span style="color:blue;">'.strtoupper($design['uniteStock']).'</span></td>';
      echo  '<td><span style="color:blue;">'.$design['nbreArticleUniteStock'].'</span></td>';
      echo  '<td><span style="color:blue;">'.$design['prixuniteStock'].'</span></td>';
      echo  '<td><span style="color:blue;">'.$design['prix'].'</span></td>';
      echo  '<td><span style="color:blue;">'.$design['prixachat'].'</span></td>';
    } else {
      # code...
      echo  '<td>'.$i.'</td>';
      echo  '<td>'.strtoupper($design['designation']).'</td>';
      echo  '<td>'.strtoupper($design['codeBarreDesignation']).'</td>';
      echo  '<td>'.strtoupper($design['uniteStock']).'</td>';
      echo  '<td>'.$design['nbreArticleUniteStock'].'</td>';
      echo  '<td>'.$design['prixuniteStock'].'</td>';
      echo  '<td>'.$design['prix'].'</td>';
      echo  '<td>'.$design['prixachat'].'</td>';
    }
      if($design["codeBarreDesignation"]!=null){
        echo  '<td>
        <a onclick="mdf_Designation('.$design["idDesignation"].')"  id="pencilmoD-'.$design['id'].'" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>&nbsp;
        <a onclick="spm_Designation('.$design["idDesignation"].')"  ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>';   
      }
      else{
        echo  '<td>
          <a onclick="mdf_Designation('.$design["idDesignation"].')"  id="pencilmoD-'.$design['id'].'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>&nbsp;
          <a onclick="spm_Designation('.$design["idDesignation"].')"  ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>'; 
      }
    
    echo '</tr>';
    $i++;
    $cpt++;
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
        if ($item_per_page > $total_rows['total_row']) {
          # code...
            echo '1 à '.($total_rows['total_row']).' sur '.$total_rows['total_row'].' articles';        
        } else {
          # code...
          if ($page_number == 1) {
            # code...
            echo '1 à '.($item_per_page).' sur '.$total_rows['total_row'].' articles';
          } else {
            # code...
            if (($total_rows['total_row']-($item_per_page * $page_number)) < 0) {
              # code... 
              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows['total_row'].' sur '.$total_rows['total_row'].' articles';
            } else {
              # code...
              echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows['total_row'].' articles';
            }
          }
        }  
      echo '</ul>';
    echo '</div>';
    echo '<div class="col-md-8">';
    // To generate links, we call the pagination function here. 
    echo paginate_function($item_per_page, $page_number, $total_rows['total_row'], $total_pages);
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
    $sql="SELECT COUNT(*) as total_row FROM `".$nomtableDesignation."` WHERE classe=0";
    $req = $bdd->query($sql);
  } else {

    # code...

    //get total number of records from database

    $sql="SELECT COUNT(*) as total_row FROM `".$nomtableDesignation."` WHERE classe=0 AND designation LIKE :query";
    $req = $bdd->prepare($sql);
    $req->execute(array('query' => '%'.$query.'%'));

  }

  $total_rows = $req->fetch(PDO::FETCH_ASSOC);



  $total_pages = ceil($total_rows['total_row']/$item_per_page);



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

    $sql="select * from `".$nomtableDesignation."` where classe=0 and designation LIKE '%".$query."%' order by idDesignation desc LIMIT $page_position, $item_per_page";

    $res=mysql_query($sql);

    // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");

    // $results->execute(); //Execute prepared Query

  }



  //Display records fetched from database.

  echo '<table class="table table-striped contents" id="contents" border="1">';

  echo '<thead>

          <tr id="thDesignation">

            <th>Ordre</th>

            <th>Reference</th>

            <th>CodeBarre</th>

            <th>Unite Stock (U.S)</th>

            <th>Nombre Articles U.S</th>

            <th>Prix U.S</th>

            <th>Prix Unitaire</th>

            <th>Prix Achat</th>

            <th>Operations</th>

          </tr>

        </thead>';

  $i = ($page_number - 1) * $item_per_page +1;

  $cpt = 0;

  while($design = $req->fetch(PDO::FETCH_ASSOC)){ //fetch values

    

    $sql1="SELECT * FROM `". $nomtableStock."` WHERE idDesignation = :idDesignation";
    $req1 = $bdd->prepare($sql1);
    $req1->execute(array('idDesignation' => $design['idDesignation']));

        

    echo '<tr>';

    if ($i==1) {

      # code...

      echo  '<td>' .$i.'</td>';

      echo  '<td><span style="color:blue;">'.strtoupper($design['designation']).'</span></td>';

      echo  '<td><span style="color:blue;">'.strtoupper($design['codeBarreDesignation']).'</span></td>';

      echo  '<td><span style="color:blue;">'.strtoupper($design['uniteStock']).'</span></td>';

      echo  '<td><span style="color:blue;">'.$design['nbreArticleUniteStock'].'</span></td>';

      echo  '<td><span style="color:blue;">'.$design['prixuniteStock'].'</span></td>';

      echo  '<td><span style="color:blue;">'.$design['prix'].'</span></td>';

      echo  '<td><span style="color:blue;">'.$design['prixachat'].'</span></td>';

    } else {

      # code...

      echo  '<td>'.$i.'</td>';

      echo  '<td>'.strtoupper($design['designation']).'</td>';

      echo  '<td>'.strtoupper($design['codeBarreDesignation']).'</td>';

      echo  '<td>'.strtoupper($design['uniteStock']).'</td>';

      echo  '<td>'.$design['nbreArticleUniteStock'].'</td>';

      echo  '<td>'.$design['prixuniteStock'].'</td>';

      echo  '<td>'.$design['prix'].'</td>';

      echo  '<td>'.$design['prixachat'].'</td>';

    }



    if($_SESSION['proprietaire']==1){

      if($design["codeBarreDesignation"]!=null){

        echo  '<td><a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;

        <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;

        <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation('.$design["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>';   

      }

      else{

        echo  '<td><a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;

          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;

          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation('.$design["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>'; 

      }

    }

    else{

      if($design["codeBarreDesignation"]!=null){

        echo  '<td><a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;

          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;</td>';  

      }

      else{

        echo  '<td><a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;

          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;</td>'; 

      }

    }

    echo '</tr>';



    $i++;

    $cpt++;

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

        if ($item_per_page > $total_rows['total_row']) {

          # code...

            echo '1 à '.($total_rows['total_row']).' sur '.$total_rows['total_row'].' articles';        

        } else {

          # code...

          if ($page_number == 1) {

            # code...

            echo '1 à '.($item_per_page).' sur '.$total_rows['total_row'].' articles';

          } else {

            # code...

            if (($total_rows['total_row']-($item_per_page * $page_number)) < 0) {

              # code... 

              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows['total_row'].' sur '.$total_rows['total_row'].' articles';

            } else {

              # code...

              echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows['total_row'].' articles';

            }

          }

        }  

      echo '</ul>';

    echo '</div>';

    echo '<div class="col-md-8">';

    // To generate links, we call the pagination function here. 

    echo paginate_function($item_per_page, $page_number, $total_rows['total_row'], $total_pages);

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

    $sql="SELECT COUNT(*) as total_row FROM `".$nomtableDesignation."` WHERE classe=0";
    $req = $bdd->query($sql);

  } else {

    # code...

    //get total number of records from database

    $sql="SELECT COUNT(*) as total_row FROM `".$nomtableDesignation."` WHERE classe=0 AND designation LIKE :query";
    $req = $bdd->prepare($sql);
    $req->execute(array('query' => '%'.$query.'%'));

  }

  $total_rows = $req->fetch(PDO::FETCH_ASSOC);



  $total_pages = ceil($total_rows['total_row']/$item_per_page);



  //position of records

  $page_position = (($page_number-1) * $item_per_page);

  

  if ($query =="") {

    # code...

    $sql="select * from `".$nomtableDesignation."` where classe=0 order by idDesignation desc LIMIT $page_position, $item_per_page";

    $req = $bdd->prepare($sql);
    $req->execute();

    // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");

    // $results->execute(); //Execute prepared Query

  } else {

    # code...

    //Limit our results within a specified range. 

    $sql="select * from `".$nomtableDesignation."` where classe=0 and designation LIKE '%".$query."%' order by idDesignation desc LIMIT $page_position, $item_per_page";

    $req = $bdd->prepare($sql);
    $req->execute();

    // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");

    // $results->execute(); //Execute prepared Query

  }



  //Display records fetched from database.

  echo '<table class="table table-striped contents" id="contents" border="1">';

  echo '<thead>

          <tr id="thDesignation">

            <th>Ordre</th>

            <th>Reference</th>

            <th>CodeBarre</th>

            <th>Unite Stock (U.S)</th>

            <th>Nombre Articles U.S</th>

            <th>Prix U.S</th>

            <th>Prix Unitaire</th>

            <th>Prix Achat</th>

            <th>Operations</th>

          </tr>

        </thead>';

  $i = ($page_number - 1) * $item_per_page +1;

  $cpt = 0;

  while($design = $req->fetch(PDO::FETCH_ASSOC)){ //fetch values

    

    $sql1="SELECT * FROM `". $nomtableStock."` WHERE idDesignation = :idDesignation";
    $req1 = $bdd->prepare($sql1);
    $req1->execute(array('idDesignation' => $design['idDesignation']));

        

    echo '<tr>';

    if ($i==1) {

      # code...

      echo  '<td>' .$i.'</td>';

      echo  '<td><span style="color:blue;">'.strtoupper($design['designation']).'</span></td>';

      echo  '<td><span style="color:blue;">'.strtoupper($design['codeBarreDesignation']).'</span></td>';

      echo  '<td><span style="color:blue;">'.strtoupper($design['uniteStock']).'</span></td>';

      echo  '<td><span style="color:blue;">'.$design['nbreArticleUniteStock'].'</span></td>';

      echo  '<td><span style="color:blue;">'.$design['prixuniteStock'].'</span></td>';

      echo  '<td><span style="color:blue;">'.$design['prix'].'</span></td>';

      echo  '<td><span style="color:blue;">'.$design['prixachat'].'</span></td>';

    } else {

      # code...

      echo  '<td>'.$i.'</td>';

      echo  '<td>'.strtoupper($design['designation']).'</td>';

      echo  '<td>'.strtoupper($design['codeBarreDesignation']).'</td>';

      echo  '<td>'.strtoupper($design['uniteStock']).'</td>';

      echo  '<td>'.$design['nbreArticleUniteStock'].'</td>';

      echo  '<td>'.$design['prixuniteStock'].'</td>';

      echo  '<td>'.$design['prix'].'</td>';

      echo  '<td>'.$design['prixachat'].'</td>';

    }



    if($_SESSION['proprietaire']==1){

      if($design["codeBarreDesignation"]!=null){

        echo  '<td><a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;

        <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;

        <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation('.$design["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>';   

      }

      else{

        echo  '<td><a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;

          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;

          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation('.$design["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>'; 

      }

    }

    else{

      if($design["codeBarreDesignation"]!=null){

        echo  '<td><a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;

          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;</td>';  

      }

      else{

        echo  '<td><a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;

          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;</td>'; 

      }

    }

    echo '</tr>';



    $i++;

    $cpt++;

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

        if ($item_per_page > $total_rows['total_row']) {

          # code...

            echo '1 à '.($total_rows['total_row']).' sur '.$total_rows['total_row'].' articles';        

        } else {

          # code...

          if ($page_number == 1) {

            # code...

            echo '1 à '.($item_per_page).' sur '.$total_rows['total_row'].' articles';

          } else {

            # code...

            if (($total_rows['total_row']-($item_per_page * $page_number)) < 0) {

              # code... 

              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows['total_row'].' sur '.$total_rows['total_row'].' articles';

            } else {

              # code...

              echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows['total_row'].' articles';

            }

          }

        }  

      echo '</ul>';

    echo '</div>';

    echo '<div class="col-md-8">';

    // To generate links, we call the pagination function here. 

    echo paginate_function($item_per_page, $page_number, $total_rows['total_row'], $total_pages);

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

