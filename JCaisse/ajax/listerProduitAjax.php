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



/*********************************/

  // $sql="select * from `".$nomtableDesignation."` where classe=0 order by idDesignation desc";

  // $res=mysql_query($sql);



  // $data=array();

  // $i=1;

  // while($design=mysql_fetch_array($res)){



  //   $sql1="SELECT * FROM `". $nomtableStock."` where idDesignation='".$design['idDesignation']."'";

  //   $res1=mysql_query($sql1);



  //   $rows = array();

  //   if($i==1){

  //     $rows[] = $i;

  //     $rows[] = '<span style="color:blue;">'.strtoupper($design['designation']).'</span>';

  //     $rows[] = '<span style="color:blue;">'.strtoupper($design['codeBarreDesignation']).'</span>';		

  //     $rows[] = '<span style="color:blue;">'.strtoupper($design['uniteStock']).'</span>';

  //     $rows[] = '<span style="color:blue;">'.$design['nbreArticleUniteStock'].'</span>';

  //     $rows[] = '<span style="color:blue;">'.$design['prixuniteStock'].'</span>';	

  //     $rows[] = '<span style="color:blue;">'.$design['prix'].'</span>';	

  //     $rows[] = '<span style="color:blue;">'.$design['prixachat'].'</span>';

  //   }	

  //   else{

  //     $rows[] = $i;

  //     $rows[] = strtoupper($design['designation']);

  //     $rows[] = strtoupper($design['codeBarreDesignation']);		

  //     $rows[] = strtoupper($design['uniteStock']);

  //     $rows[] = $design['nbreArticleUniteStock'];

  //     $rows[] = $design['prixuniteStock'];

  //     $rows[] = $design['prix'];

  //     $rows[] = $design['prixachat'];		

  //   }		



  //   if($_SESSION['proprietaire']==1){

  //     if($design["codeBarreDesignation"]!=null){

  //       $rows[] = '<a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;

  //       <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;

  //       <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation('.$design["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a>';   

  //     }

  //     else{

  //       $rows[] = '<a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;

  //       <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;

  //       <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation('.$design["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a>'; 

  //     }

  //   }

  //   else{

  //     if($design["codeBarreDesignation"]!=null){

  //       $rows[] = '<a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;

  //       <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;';  

  //     }

  //     else{

  //       $rows[] = '<a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;

  //       <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;'; 

  //     }

  //   }

    



  //   $data[] = $rows;

  //   $i=$i + 1;

  // }





  // $results = ["sEcho" => 1,

  //           "iTotalRecords" => count($data),

  //           "iTotalDisplayRecords" => count($data),

  //           "aaData" => $data ];



  // echo json_encode($results);

/****************************************************/





if ($operation=="1" || $operation=="3" || $operation=="4") {



  $nbEntree = @$_POST["nbEntree"];

  $query = @$_POST["query"];

  $cb = @$_POST["cb"];

  $item_per_page 		= $nbEntree; //item to display per page

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

    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where classe=0 and archiver<>1";

    $res=mysql_query($sql);

  } else {

    # code...

    //get total number of records from database

    if ($cb==1) {

      # code...

      $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where classe=0 and archiver<>1 and codeBarredesignation='".$query."'";

      $res=mysql_query($sql);

    }else {

      # code...

      $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where classe=0 and archiver<>1 and (designation LIKE '%".$query."%' or codeBarredesignation LIKE '%".$query."%')";

      $res=mysql_query($sql);

    }

  }

  $total_rows = mysql_fetch_array($res);



  $total_pages = ceil($total_rows[0]/$item_per_page);



  //position of records

  $page_position = (($page_number-1) * $item_per_page);

  

  if ($query =="") {

    # code...

    $sql="select * from `".$nomtableDesignation."` where classe=0 and archiver<>1 order by idDesignation desc LIMIT $page_position, $item_per_page";

    $res=mysql_query($sql);

    // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");

    // $results->execute(); //Execute prepared Query

  } else {

    # code...

    //Limit our results within a specified range. 

    if ($cb==1) {

      # code...

      $sql="select * from `".$nomtableDesignation."` where classe=0 and archiver<>1 and codeBarredesignation='".$query."' order by idDesignation desc LIMIT $page_position, $item_per_page";

      $res=mysql_query($sql);

    } else {

      # code...

      $sql="select * from `".$nomtableDesignation."` where classe=0 and archiver<>1 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by idDesignation desc LIMIT $page_position, $item_per_page";

      $res=mysql_query($sql);

    }

    

    // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");

    // $results->execute(); //Execute prepared Query

  }



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

            <th>Prix Unitaire</th>';

            if ($_SESSION['proprietaire'] == 1) {
              # code...              
              echo '<th>Prix Achat</th>';
            }


            echo '

            <th>Operations</th>
            <th>image</th>

          </tr>

        </thead>';

  $i = ($page_number - 1) * $item_per_page +1;

  $cpt = 0;

  while($design=mysql_fetch_array($res)){ //fetch values

    

    $sql1="SELECT * FROM `". $nomtableStock."` where idDesignation='".$design['idDesignation']."'";

    $res1=mysql_query($sql1);

        

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

      if ($_SESSION['proprietaire'] == 1) {
        # code...              
        echo  '<td><span style="color:blue;">'.$design['prixachat'].'</span></td>';
      }

    } else {

      # code...

      echo  '<td>'.$i.'</td>';

      echo  '<td>'.strtoupper($design['designation']).'</td>';

      echo  '<td>'.strtoupper($design['codeBarreDesignation']).'</td>';

      echo  '<td>'.strtoupper($design['uniteStock']).'</td>';

      echo  '<td>'.$design['nbreArticleUniteStock'].'</td>';

      echo  '<td>'.$design['prixuniteStock'].'</td>';

      echo  '<td>'.$design['prix'].'</td>';

      if ($_SESSION['proprietaire'] == 1) {
        # code...              
        echo  '<td>'.$design['prixachat'].'</td>';
      }

    }



    if($_SESSION['proprietaire']==1){

      if($design["archiver"]==0){

        if($design["codeBarreDesignation"]!=null){

          echo  '<td><a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;

          <a onclick="archiver_Designation('.$design["idDesignation"].','.$i.')" data-toggle="modal" id="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:orange" class="glyphicon glyphicon-star-empty"></span></a>&nbsp;

          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;

          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation('.$design["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>';   

        }

        else{

          echo  '<td><a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;

            <a onclick="archiver_Designation('.$design["idDesignation"].','.$i.')" data-toggle="modal" id="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:orange" class="glyphicon glyphicon-star-empty"></span></a>&nbsp;

            <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;

            <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation('.$design["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>'; 

        }

      }

      else{

        if($design["codeBarreDesignation"]!=null){

          echo  '<td><a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;

          <a onclick="archiver_Designation('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:red" class="glyphicon glyphicon-star"></span></a>&nbsp;

          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;

          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation('.$design["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>';   

        }

        else{

          echo  '<td><a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;

          <a onclick="archiver_Designation('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:red" class="glyphicon glyphicon-star"></span></a>&nbsp;

            <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;

            <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation('.$design["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>'; 

        }

      }
      if ($design["image"]) {

        echo  '<td><a><img src="images/iconfinder11.png" align="middle" alt="apperçu" onclick="imgEX_DesignationD('.$design["idDesignation"].')" data-toggle="modal" data-target="#app'.$design["idDesignation"].'" /></a>&nbsp;&nbsp;';
        if ($_SESSION['vitrine'] == 1 && $_SESSION['proprietaire'] == 1) {
          echo  '<a class="glyphicon glyphicon-tag"id="promo'.$design["idDesignation"].'" align="middle" onclick="getDesignationPromo('.$design["idDesignation"].')" data-toggle="modal" data-target="#promo"></a>';
        }
        echo '</td>';
    
      }
    
      else{
    
        echo  '<td><a><img src="images/iconfinder9.png" align="middle" alt="img" onclick="imgND_DesignationD('.$design["idDesignation"].')" data-toggle="modal" data-target="#img'.$design["idDesignation"].'" /></a>&nbsp;&nbsp;';
        if ($_SESSION['vitrine'] == 1 && $_SESSION['proprietaire'] == 1) {
          echo  '<a class="glyphicon glyphicon-tag"id="promo'.$design["idDesignation"].'" align="middle" onclick="getDesignationPromo('.$design["idDesignation"].')" data-toggle="modal" data-target="#promo"></a>';
        }
        echo '</td>';
    
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
      if ($design["image"]) {

        echo  '<td><a><img src="images/iconfinder11.png" align="middle" alt="apperçu" onclick="imgEX_DesignationD('.$design["idDesignation"].')" data-toggle="modal" data-target="#app'.$design["idDesignation"].'" /></a>&nbsp;&nbsp;';
        if ($_SESSION['vitrine'] == 1 && $_SESSION['proprietaire'] == 1) {
          echo  '<a class="glyphicon glyphicon-tag"id="promo'.$design["idDesignation"].'" align="middle" onclick="getDesignationPromo('.$design["idDesignation"].')" data-toggle="modal" data-target="#promo"></a>';
        }
        echo '</td>';
    
      }
    
      else{
    
        echo  '<td><a><img src="images/iconfinder9.png" align="middle" alt="img" onclick="imgND_DesignationD('.$design["idDesignation"].')" data-toggle="modal" data-target="#img'.$design["idDesignation"].'" /></a>&nbsp;&nbsp;';
        if ($_SESSION['vitrine'] == 1 && $_SESSION['proprietaire'] == 1) {
          echo  '<a class="glyphicon glyphicon-tag"id="promo'.$design["idDesignation"].'" align="middle" onclick="getDesignationPromo('.$design["idDesignation"].')" data-toggle="modal" data-target="#promo"></a>';
        }
        echo '</td>';
    
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

if ($operation=="2") {

  # code...

  $nbEntree = @$_POST["nbEntree"];

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

    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where classe=0  and archiver<>1 ";

    $res=mysql_query($sql);

  } else {

    # code...

    //get total number of records from database

    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where classe=0  and archiver<>1 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%')";

    $res=mysql_query($sql);

  }

  $total_rows = mysql_fetch_array($res);



  $total_pages = ceil($total_rows[0]/$item_per_page);



  //position of records

  $page_position = (($page_number-1) * $item_per_page);

  

  if ($tri==0) {

    if ($query =="") {

      # code...

      $sql="select * from `".$nomtableDesignation."` where classe=0 and archiver<>1 order by designation ASC LIMIT $page_position, $item_per_page";

      $res=mysql_query($sql);

      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");

      // $results->execute(); //Execute prepared Query

      $sql="select * from `".$nomtableDesignation."` where classe=0 and archiver<>1 order by idDesignation desc LIMIT 1";

      $res1=mysql_query($sql);

    } else {

      # code...

      //Limit our results within a specified range. 

      $sql="select * from `".$nomtableDesignation."` where classe=0 and archiver<>1 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by designation ASC LIMIT $page_position, $item_per_page";

      $res=mysql_query($sql);

      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");

      // $results->execute(); //Execute prepared Query

      $sql="select * from `".$nomtableDesignation."` where classe=0 and archiver<>1 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by idDesignation desc LIMIT 1";

      $res1=mysql_query($sql);

    }

    

  } else {

    if ($query =="") {

      # code...

      $sql="select * from `".$nomtableDesignation."` where classe=0 and archiver<>1 order by designation DESC LIMIT $page_position, $item_per_page";

      $res=mysql_query($sql);

      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");

      // $results->execute(); //Execute prepared Query

      $sql="select * from `".$nomtableDesignation."` where classe=0 and archiver<>1 order by idDesignation desc LIMIT 1";

      $res1=mysql_query($sql);

    } else {

      # code...

      //Limit our results within a specified range. 

      $sql="select * from `".$nomtableDesignation."` where classe=0 and archiver<>1 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by designation DESC LIMIT $page_position, $item_per_page";

      $res=mysql_query($sql);

      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");

      // $results->execute(); //Execute prepared Query

      $sql="select * from `".$nomtableDesignation."` where classe=0 and archiver<>1 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by idDesignation desc LIMIT 1";

      $res1=mysql_query($sql);

    }

  }

  

  $maxId = mysql_fetch_array($res1);

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

            <th>Prix Unitaire</th>';

            if ($_SESSION['proprietaire'] == 1) {
              # code...              
              echo '<th>Prix Achat</th>';
            }

            echo '
            <th>Operations</th>

          </tr>

        </thead>';

  $i = ($page_number - 1) * $item_per_page +1;

  $cpt = 0;

  while($design=mysql_fetch_array($res)){ //fetch values

    

    $sql1="SELECT * FROM `". $nomtableStock."` where idDesignation='".$design['idDesignation']."'";

    $res1=mysql_query($sql1);

        

    echo '<tr>';

    if ($design['idDesignation']==$maxId[0]) {

      # code...

      echo  '<td>' .$i.'</td>';

      echo  '<td><span style="color:blue;">'.strtoupper($design['designation']).'</span></td>';

      echo  '<td><span style="color:blue;">'.strtoupper($design['codeBarreDesignation']).'</span></td>';

      echo  '<td><span style="color:blue;">'.strtoupper($design['uniteStock']).'</span></td>';

      echo  '<td><span style="color:blue;">'.$design['nbreArticleUniteStock'].'</span></td>';

      echo  '<td><span style="color:blue;">'.$design['prixuniteStock'].'</span></td>';

      echo  '<td><span style="color:blue;">'.$design['prix'].'</span></td>';
      if ($_SESSION['proprietaire'] == 1) {
        # code...              
        echo  '<td><span style="color:blue;">'.$design['prixachat'].'</span></td>';
      }


    } else {

      # code...

      echo  '<td>'.$i.'</td>';

      echo  '<td>'.strtoupper($design['designation']).'</td>';

      echo  '<td>'.strtoupper($design['codeBarreDesignation']).'</td>';

      echo  '<td>'.strtoupper($design['uniteStock']).'</td>';

      echo  '<td>'.$design['nbreArticleUniteStock'].'</td>';

      echo  '<td>'.$design['prixuniteStock'].'</td>';

      echo  '<td>'.$design['prix'].'</td>';

      if ($_SESSION['proprietaire'] == 1) {
        # code...              
        echo  '<td>'.$design['prixachat'].'</td>';
      }

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

    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where classe=0";

    $res=mysql_query($sql);

  } else {

    # code...

    //get total number of records from database

    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where classe=0 and designation LIKE '%".$query."%'";

    $res=mysql_query($sql);

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

  while($design=mysql_fetch_array($res)){ //fetch values

    

    $sql1="SELECT * FROM `". $nomtableStock."` where idDesignation='".$design['idDesignation']."'";

    $res1=mysql_query($sql1);

        

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

    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where classe=0";

    $res=mysql_query($sql);

  } else {

    # code...

    //get total number of records from database

    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where classe=0 and designation LIKE '%".$query."%'";

    $res=mysql_query($sql);

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

  while($design=mysql_fetch_array($res)){ //fetch values

    

    $sql1="SELECT * FROM `". $nomtableStock."` where idDesignation='".$design['idDesignation']."'";

    $res1=mysql_query($sql1);

        

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


/*****  Chercher un produit dans catalogue Boutique pour modifier son image **/

if ($operation=="5") {

  $id=@htmlspecialchars($_POST["id"]);

  $sql="select * from `".$nomtableDesignation."`  WHERE idDesignation= '". $id."'";
  $res=mysql_query($sql);
  $design=mysql_fetch_array($res);


  $result=$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['prixuniteStock'].'<>'.$design['prix'].'<>'.$design['image'];

  exit($result);

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

