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

require('../../connection.php');
require('../../declarationVariables.php');

$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);
$id=1;
//var_dump($id);
           
            $sql3="SELECT * from  `".$nomtableDesignation."` ORDER BY designation ASC ";
            $res3=mysql_query($sql3);

if ($operation=="image") {
    $idD = @$_POST["idD"];
    $sql13="SELECT * from  `".$nomtableDesignation."` where idDesignation='".$idD."'";
    //var_dump($sql13);
  	$res13 = mysql_query($sql13) or die ("persoonel requête 3".mysql_error());
  	$design = mysql_fetch_assoc($res13);

    $result=$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['prixuniteStock'].'<>'.$design['prix'].'<>'.$design['image'];
    exit($result);
     
}
if ($operation=="1" || $operation=="3" || $operation=="4") {  
  $nbEntreePh = @$_POST["nbEntreePh"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= $nbEntreePh; //item to display per page
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
    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where classe=0 ";
    $res=mysql_query($sql);
  } else {
    //get total number of records from database
    if ($cb==1) {
      $sql="select COUNT(*) as total_row from `".$nomtableDesignation."`  where codeBarredesignation='".$query."'";
      $res=mysql_query($sql);
    }else {
      $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where designation LIKE '%".$query."%' or codeBarredesignation LIKE '%".$query."%'";
      $res=mysql_query($sql);
    }
  }
  //var_dump($sql);
  //var_dump($item_per_page);
  $total_rows = mysql_fetch_array($res);
  
  //var_dump($total_rows[0]);
  $total_pages = ceil($total_rows[0]/$item_per_page);
  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  if ($query =="") {
    $sql="select * from `".$nomtableDesignation."` where classe=0   order by codeBarreDesignation ASC LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
  } else {
    //Limit our results within a specified range. 
    if ($cb==1) {
      $sql="select * from `".$nomtableDesignation."` where classe=0 and codeBarredesignation='".$query."' order by codeBarreDesignation ASC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    } else {
      $sql="select * from `".$nomtableDesignation."` where classe=0 and designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%' order by codeBarreDesignation ASC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    }
  }
  //Display records fetched from database.
  echo '<table class="table table-striped contents display tabDesign " id="tableDesignation" border="1">';
  echo '<thead>
          <tr id="thDesignation">
            <th>Référence</th>
            <th>Catégorie</th>
            <th>Forme</th>
            <th>Tableau</th>
            <th>Prix Session</th>
            <th>Prix Public</th>
            <th>CodebarresDesignation</th>
            <th>Operations</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  while($design=mysql_fetch_array($res)){ //fetch values

    /******************************* */
    $selCategorie='<td><span style="" >
            <select class="form-control" id="categorieD-'.$design['idDesignation'].'" >
                <option  selected value="'.$design["categorie"].'" >'.$design['categorie'].'</option>';
                $sql111="SELECT * FROM `".$nomtableCategorie."` ORDER BY `nomcategorie` ASC";
                $res111=mysql_query($sql111);
                while($op=mysql_fetch_array($res111)){
                    $selCategorie=$selCategorie."<option  value='".$op["nomcategorie"]."' > ".$op["nomcategorie"].'</option>';
                }
            $selCategorie=$selCategorie.'</select></span></td>';

    $selForme='<td><span style="" >
            <select class="form-control" id="formeD-'.$design['idDesignation'].'" >
                <option  selected value="'.$design["forme"].'" >'.$design['forme'].'</option>';
                $sql111="SELECT * FROM `aaa-forme-pharmacie-detaillant` ORDER BY `nomForme` ASC";
                $res111=mysql_query($sql111);
                while($op=mysql_fetch_array($res111)){
                    $selForme=$selForme."<option  value='".$op["nomForme"]."' > ".$op["nomForme"].'</option>';
                }
            $selForme=$selForme.'</select></span></td>';
    
    $selTableau='<td><span style="" >
            <select class="form-control" id="tableauD-'.$design['idDesignation'].'" >
                <option  selected value="'.$design["tableau"].'" >'.$design['tableau'].'</option>';
               
                    $selTableau=$selTableau.'<option  value="HORS TABLEAU" >Sans Tableau</option>
                                            <option  value="A" >A</option>
                                            <option  value="B" >B</option>
                                            <option  value="C" >C</option>';
               
            $selTableau=$selTableau.'</select></span></td>';
    /******************************** */

    $sql1="SELECT * FROM `". $nomtableDesignation."` ";
    $res1=mysql_query($sql1);
    echo '<tr>';
    //if ($i==1) {
      echo  '<td><span style="color:blue;"><form class="form form-inline" role="form"  method="post" >
							<input type="text" size="40" class="form-control" id="designationD-'.$design['idDesignation'].'" min=1 value="'.$design['designation'].'" required=""/></span></td>';
      echo $selCategorie;
      echo $selForme;
      echo $selTableau;
      echo  '<td><span style="color:blue;"><input type="number" size="6" class="form-control" id="prixSessionD-'.$design['idDesignation'].'" min=0 value="'.strtoupper($design['prixSession']).'" required=""/></span></td>';
      echo  '<td><span ><input type="number" size="6" class="form-control" id="prixPublicD-'.$design['idDesignation'].'" min=0 value="'.strtoupper($design['prixPublic']).'" required=""/>
      </span></td>';
      echo  '<td><span ><input type="text" size="6" class="form-control" id="codebarreD-'.$design['idDesignation'].'" min=0 value="'.strtoupper($design['codeBarreDesignation']).'" >
      </form></span></td>';
   /* } else {
      echo  '<td>'.$i.'</td>';
      echo  '<td><form class="form form-inline" role="form"  method="post" >
      <input type="text" size="40" class="form-control" id="designationD-'.$design['id'].'" min=1 value="'.$design['designation'].'" required=""/></td>';
      echo  '<td><input size="4" type="text" class="form-control"  value="'.strtoupper($design['idFusion']).'" disabled/></td>';
      echo  '<td><span onmouseover="selectCategorie('.$design["id"].','.$id.')"><select class="form-control" id="categorieD-'.$design['id'].'"  >
      <option selected value= "'.$design['categorie'].'"
       >'.$design['categorie'].'</option></select></span></td>';
      echo  '<td><span onmouseover="selectForme('.$design["id"].','.$id.')"><select class="form-control" id="formeD-'.$design['id'].'"  >
      <option selected value= "'.$design['forme'].'"
       >'.$design['forme'].'</option></select></span></td>';
      echo  '<td><input type="text" size="4" class="form-control" id="tableauD-'.$design['id'].'" value="'.strtoupper($design['tableau']).'" required=""/></td>';
      echo  '<td><input type="number" size="6" class="form-control" id="prixSessionD-'.$design['id'].'" min=0 value="'.strtoupper($design['prixSession']).'" required=""/></td>';
      echo  '<td><input type="number" size="6" class="form-control" id="prixPublicD-'.$design['id'].'" min=0 value="'.strtoupper($design['prixPublic']).'" required=""/>
      </form></td>';
    }*/
    
        if($design["codeBarreDesignation"]!=null){
          echo '<td><a onclick="mdf_Designation_PH('.$design["idDesignation"].','.$id.','.$i.')" id="pencilmoD-'.$design['idDesignation'].'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
            <a onclick="spm_Designation_PH('.$design["idDesignation"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
            <a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  onclick="img_Designation_PH('.$design["idDesignation"].','.$id.','.$i.')" /></a></td>';
        }
        else{
          echo '<td><a onclick="mdf_Designation_PH('.$design["idDesignation"].','.$id.','.$i.')" id="pencilmoD-'.$design['idDesignation'].'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
            <a onclick="spm_Designation_PH('.$design["idDesignation"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
            <a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  onclick="img_Designation_PH('.$design["idDesignation"].','.$id.','.$i.')" /></a></td>';
        }
    echo '</tr>';
    $i++;
    $cpt++;
  }
if ($cpt==0) {
    echo '<tr>';
    echo  '<td colspan="9" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';
  echo '<div class="">';
    echo '<div class="col-md-4">';
      echo '<ul class="pull-left">';
        if ($item_per_page > $total_rows[0]) {
            echo '1 à '.($total_rows[0]).' sur '.$total_rows[0].' articles';        
        } else {
          if ($page_number == 1) {
            echo '1 à '.($item_per_page).' sur '.$total_rows[0].' articles';
          } else {
            if (($total_rows[0]-($item_per_page * $page_number)) < 0) {
              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows[0].' sur '.$total_rows[0].' articles';
            } else {
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
  $nbEntreePh = @$_POST["nbEntreePh"];
  $query = @$_POST["query"];
  $item_per_page 		= $nbEntreePh; //item to display per page
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
    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` ";
    $res=mysql_query($sql);
  } else {
    //get total number of records from database
    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%'";
    $res=mysql_query($sql);
  }
  $total_rows = mysql_fetch_array($res);
  $total_pages = ceil($total_rows[0]/$item_per_page);
  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  if ($tri==0) {
    if ($query =="") {
      $sql="select * from `".$nomtableDesignation."` order by codeBarreDesignation ASC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
      $sql="select * from `".$nomtableDesignation."`  order by codeBarreDesignation desc LIMIT 1";
      $res1=mysql_query($sql);
    } else {
      //Limit our results within a specified range. 
      $sql="select * from `".$nomtableDesignation."` where designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%' order by codeBarreDesignation ASC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
      $sql="select * from `".$nomtableDesignation."` where designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%' order by codeBarreDesignation ASC LIMIT 1";
      $res1=mysql_query($sql);  
    }
  } else {
    if ($query =="") {
      $sql="select * from `".$nomtableDesignation."` order by designation DESC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
      $sql="select * from `".$nomtableDesignation."` order by idDesignation desc LIMIT 1";
      $res1=mysql_query($sql);
    } else {
      //Limit our results within a specified range. 
      $sql="select * from `".$nomtableDesignation."` where designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%' order by codeBarreDesignation ASC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
      $sql="select * from `".$nomtableDesignation."` where designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%' order by codeBarreDesignation ASC LIMIT $page_position, $item_per_page";
      $res1=mysql_query($sql);
    }
  }
  $maxId = mysql_fetch_array($res1);
  //Display records fetched from database.
  echo '<table class="table table-striped contents display tabDesign tableau3" id="tableDesignation" border="1">';
  echo '<thead>
          <tr id="thDesignation">
            <th>Ordre</th>
            <th>Référence</th>
            <th>idFusion</th>
            <th>Catégorie</th>
            <th>Forme</th>
            <th>Tableau</th>
            <th>Prix Session</th>
            <th>Prix Public</th>
            <th>Operations</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  while($design=mysql_fetch_array($res)){ //fetch values
    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$design['idDesignation']."'";
    $res1=mysql_query($sql1);
    echo '<tr>';
    if ($design['idDesignation']==$maxId[0]) {
      echo  '<td>' .$i.'</td>';
      echo  '<td><span style="color:blue;"><form class="form form-inline" role="form"  method="post" >
							<input type="text" size="40" class="form-control" id="designationD-'.$design['idDesignation'].'" min=1 value="'.$design['designation'].'" required=""/></span></td>';
      echo  '<td><span style="color:blue;"><input type="text" size="4" class="form-control"  value="'.strtoupper($design['idFusion']).'" disabled/></span></td>';
      echo  '<td><span style="color:blue;" onclick="selectCategorie_PH('.$design["id"].','.$id.')"><select  size="5" class="form-control" id="categorieD-'.$design['id'].'"  >
      <option selected value= "'.$design['categorie'].'"
       >'.$design['categorie'].'</option></select></span></td>';
      echo  '<td><span onclick="selectForme('.$design["id"].','.$id.')"><select  size="5" class="form-control" id="formeD-'.$design['id'].'"  >
      <option selected value= "'.$design['forme'].'"
       >'.$design['forme'].'</option></select></span></td>';
      echo  '<td><span style="color:blue;"><input type="text" size="4" class="form-control" id="tableauD-'.$design['idDesignation'].'" value="'.strtoupper($design['tableau']).'" required=""/></span></td>';
      echo  '<td><span style="color:blue;"><input type="number" size="6" class="form-control" id="prixSessionD-'.$design['idDesignation'].'" min=0 value="'.strtoupper($design['prixSession']).'" required=""/></span></td>';
      echo  '<td><span style="color:blue;"><input type="number" size="6" class="form-control" id="prixPublicD-'.$design['idDesignation'].'" min=0 value="'.strtoupper($design['prixPublic']).'" required=""/>
      </form></span></td>';
    } else {
      echo  '<td>'.$i.'</td>';
      echo  '<td><form class="form form-inline" role="form"  method="post" >
      <input type="text" size="40" class="form-control" id="designationD-'.$design['idDesignation'].'" min=1 value="'.$design['designation'].'" required=""/></td>';
      echo  '<td><input size="4" type="text" class="form-control"  value="'.strtoupper($design['idFusion']).'" disabled/></td>';
      echo  '<td><span onclick="selectCategorie_PH('.$design["idDesignation"].','.$id.')"><select  size="5" class="form-control" id="categorieD-'.$design['idDesignation'].'"  >
      <option selected value= "'.$design['categorie'].'"
       >'.$design['categorie'].'</option></select></span></td>';
      echo  '<td><span onclick="selectForme('.$design["idDesignation"].','.$id.')"><select  size="5" class="form-control" id="formeD-'.$design['idDesignation'].'"  >
      <option selected value= "'.$design['forme'].'"
       >'.$design['forme'].'</option></select></span></td>';
      echo  '<td><input type="text" size="4" class="form-control" id="tableauD-'.$design['idDesignation'].'" value="'.strtoupper($design['tableau']).'" required=""/></td>';
      echo  '<td><input type="number" size="6" class="form-control" id="prixSessionD-'.$design['idDesignation'].'" min=0 value="'.strtoupper($design['prixSession']).'" required=""/></td>';
      echo  '<td><input type="number" size="6" class="form-control" id="prixPublicD-'.$design['idDesignation'].'" min=0 value="'.strtoupper($design['prixPublic']).'" required=""/>
      </form></td>';
    }
         
        if($design["codeBarreDesignation"]!=null){
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:black" class="glyphicon glyphicon-star"></span></a>&nbsp;
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;
          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation_Ph('.$design["idDesignation"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>'; 
        }
        else{
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:black" class="glyphicon glyphicon-star"></span></a>&nbsp;
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;
          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation_Ph('.$design["idDesignation"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>'; 
        }  
    echo '</tr>';
    $i++;
    $cpt++;
  }
  if ($cpt==0) {
    echo '<tr>';
    echo  '<td colspan="9" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';
  echo '<div class="">';
    echo '<div class="col-md-4">';
      echo '<ul class="pull-left">';
        if ($item_per_page > $total_rows[0]) {
            echo '1 à '.($total_rows[0]).' sur '.$total_rows[0].' articles';        
        } else {
          if ($page_number == 1) {
            echo '1 à '.($item_per_page).' sur '.$total_rows[0].' articles';
          } else {
            if (($total_rows[0]-($item_per_page * $page_number)) < 0) {
              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows[0].' sur '.$total_rows[0].' articles';
            } else {
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
  $nbEntreePh = @$_POST["nbEntreePh"];
  $query = @$_POST["query"];
  $item_per_page 		= $nbEntreePh; //item to display per page
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
    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` ";
    $res=mysql_query($sql);
  } else {
    //get total number of records from database
    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where designation LIKE '%".$query."%'";
    $res=mysql_query($sql);
  }
  $total_rows = mysql_fetch_array($res);
  $total_pages = ceil($total_rows[0]/$item_per_page);
  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  if ($query =="") {
    $sql="select * from `".$nomtableDesignation."` order by codeBarreDesignation ASC LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
  } else {
    //Limit our results within a specified range. 
    $sql="select * from `".$nomtableDesignation."` where designation LIKE '%".$query."%' order by codeBarreDesignation ASC LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
  }
  //Display records fetched from database.
  echo '<table class="table table-striped contents" id="contents" border="1">';
  echo '<thead>
          <tr id="thDesignation">
            <th>Ordre</th>
            <th>Référence</th>
            <th>Catégorie</th>
            <th>Forme</th>
            <th>Tableau</th>
            <th>Prix Session</th>
            <th>Prix Public</th>
            <th>Operations</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  while($design=mysql_fetch_array($res)){ //fetch values
    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$design['idDesignation']."'";
    $res1=mysql_query($sql1);
    echo '<tr>';
    if ($i==1) {
      echo  '<td>' .$i.'</td>';
      echo  '<td><span style="color:blue;">'.strtoupper($design['designation']).'</span></td>';
      echo  '<td><span style="color:blue;">'.strtoupper($design['categorie']).'</span></td>';
      echo  '<td><span style="color:blue;">'.strtoupper($design['forme']).'</span></td>';
      echo  '<td><span style="color:blue;">'.strtoupper($design['tableau']).'</span></td>';
      echo  '<td><span style="color:blue;">'.$design['prixSession'].'</span></td>';
      echo  '<td><span style="color:blue;">'.$design['prixPublic'].'</span></td>';
    } else {
      echo  '<td>'.$i.'</td>';
      echo  '<td>'.strtoupper($design['designation']).'</td>';
      echo  '<td>'.strtoupper($design['categorie']).'</td>';
      echo  '<td>'.strtoupper($design['forme']).'</td>';
      echo  '<td>'.strtoupper($design['tableau']).'</td>';
      echo  '<td>'.$design['prixSession'].'</td>';
      echo  '<td>'.$design['prixPublic'].'</td>';
    }
    if($_SESSION['proprietaire']==1){
      if($design["tva"]==0){
        if($design["codeBarreDesignation"]!=null){
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" id="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:red" class="glyphicon glyphicon-star-empty"></span></a>&nbsp;
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;
          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation_Ph('.$design["idDesignation"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>';   
        }
        else{
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" id="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:red" class="glyphicon glyphicon-star-empty"></span></a>&nbsp;
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;
          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation_Ph('.$design["idDesignation"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>'; 
        }
      }
      else{
        if($design["codeBarreDesignation"]!=null){
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:black" class="glyphicon glyphicon-star"></span></a>&nbsp;
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;
          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation_Ph('.$design["idDesignation"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>'; 
        }
        else{
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:black" class="glyphicon glyphicon-star"></span></a>&nbsp;
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;
          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation_Ph('.$design["idDesignation"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>'; 
        }
      }
    }
    else{
      if($design["tva"]==0){
        if($design["codeBarreDesignation"]!=null){
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" id="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:red" class="glyphicon glyphicon-star-empty"></span></a>&nbsp; 
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;</td>';
        }
        else{
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" id="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:red" class="glyphicon glyphicon-star-empty"></span></a>&nbsp; 
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;</td>';
        }
      }
      else{
        if($design["codeBarreDesignation"]!=null){
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" id="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:black" class="glyphicon glyphicon-star"></span></a>&nbsp;
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;</td>';  
        }
        else{
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" id="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:black" class="glyphicon glyphicon-star"></span></a>&nbsp;
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;</td>'; 
        }
      }
    }
    echo '</tr>';
    $i++;
    $cpt++;
  }
  if ($cpt==0) {
    echo '<tr>';
    echo  '<td colspan="9" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';
  echo '<div class="">';
    echo '<div class="col-md-4">';
      echo '<ul class="pull-left">';
        if ($item_per_page > $total_rows[0]) {
            echo '1 à '.($total_rows[0]).' sur '.$total_rows[0].' articles';        
        } else {
          if ($page_number == 1) {
            echo '1 à '.($item_per_page).' sur '.$total_rows[0].' articles';
          } else {
            if (($total_rows[0]-($item_per_page * $page_number)) < 0) {
              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows[0].' sur '.$total_rows[0].' articles';
            } else {
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
  $nbEntreePh = @$_POST["nbEntreePh"];
  $query = @$_POST["query"];
  $item_per_page 		= $nbEntreePh; //item to display per page
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
    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` ";
    $res=mysql_query($sql);
  } else {
    //get total number of records from database
    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where designation LIKE '%".$query."%'";
    $res=mysql_query($sql);
  }
  $total_rows = mysql_fetch_array($res);
  $total_pages = ceil($total_rows[0]/$item_per_page);
  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  if ($query =="") {
    $sql="select * from `".$nomtableDesignation."` order by idDesignation desc LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
  } else {
    //Limit our results within a specified range. 
    $sql="select * from `".$nomtableDesignation."` where designation LIKE '%".$query."%' order by idDesignation desc LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
  }
  //Display records fetched from database.
  echo '<table class="table table-striped contents" id="contents" border="1">';
  echo '<thead>
          <tr id="thDesignation">
            <th>Ordre</th>
            <th>Référence</th>
            <th>Catégorie</th>
            <th>Forme</th>
            <th>Tableau</th>
            <th>Prix Session</th>
            <th>Prix Public</th>
            <th>Operations</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  while($design=mysql_fetch_array($res)){ //fetch values
    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$design['idDesignation']."'";
    $res1=mysql_query($sql1);
    echo '<tr>';
    if ($i==1) {
      echo  '<td>' .$i.'</td>';
      echo  '<td><span style="color:blue;">'.strtoupper($design['designation']).'</span></td>';
      echo  '<td><span style="color:blue;">'.strtoupper($design['categorie']).'</span></td>';
      echo  '<td><span style="color:blue;">'.strtoupper($design['forme']).'</span></td>';
      echo  '<td><span style="color:blue;">'.strtoupper($design['tableau']).'</span></td>';
      echo  '<td><span style="color:blue;">'.$design['prixSession'].'</span></td>';
      echo  '<td><span style="color:blue;">'.$design['prixPublic'].'</span></td>';
    } else {
      echo  '<td>'.$i.'</td>';
      echo  '<td>'.strtoupper($design['designation']).'</td>';
      echo  '<td>'.strtoupper($design['categorie']).'</td>';
      echo  '<td>'.strtoupper($design['forme']).'</td>';
      echo  '<td>'.strtoupper($design['tableau']).'</td>';
      echo  '<td>'.$design['prixSession'].'</td>';
      echo  '<td>'.$design['prixPublic'].'</td>';
    }
    if($_SESSION['proprietaire']==1){
      if($design["tva"]==0){
        if($design["codeBarreDesignation"]!=null){
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" id="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:red" class="glyphicon glyphicon-star-empty"></span></a>&nbsp;
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;
          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation_Ph('.$design["idDesignation"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>';   
        }
        else{
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" id="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:red" class="glyphicon glyphicon-star-empty"></span></a>&nbsp;
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;
          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation_Ph('.$design["idDesignation"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>'; 
        }
      }
      else{
        if($design["codeBarreDesignation"]!=null){
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:black" class="glyphicon glyphicon-star"></span></a>&nbsp;
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;
          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation_Ph('.$design["idDesignation"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>'; 
        }
        else{
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:black" class="glyphicon glyphicon-star"></span></a>&nbsp;
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;
          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation_Ph('.$design["idDesignation"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a></td>'; 
        }
      }
    }
    else{
      if($design["tva"]==0){
        if($design["codeBarreDesignation"]!=null){
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" id="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:red" class="glyphicon glyphicon-star-empty"></span></a>&nbsp; 
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;</td>';
        }
        else{
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" id="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:red" class="glyphicon glyphicon-star-empty"></span></a>&nbsp; 
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;</td>';
        }
      }
      else{
        if($design["codeBarreDesignation"]!=null){
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" id="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:black" class="glyphicon glyphicon-star"></span></a>&nbsp;
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;</td>';  
        }
        else{
          echo '<td><a onclick="mdf_CodeBarreDesign_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;
          <a onclick="mdf_Tva_Ph('.$design["idDesignation"].','.$i.')" data-toggle="modal" id="#tva'.$design["idDesignation"].'"><span style="font-size:16px;color:black" class="glyphicon glyphicon-star"></span></a>&nbsp;
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_Ph('.$design["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;</td>'; 
        }
      }
    }
    echo '</tr>';
    $i++;
    $cpt++;
  }
  if ($cpt==0) {
    echo '<tr>';
    echo  '<td colspan="9" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';
  echo '<div class="">';
    echo '<div class="col-md-4">';
      echo '<ul class="pull-left">';
        if ($item_per_page > $total_rows[0]) {
            echo '1 à '.($total_rows[0]).' sur '.$total_rows[0].' articles';        
        } else {
          if ($page_number == 1) {
            echo '1 à '.($item_per_page).' sur '.$total_rows[0].' articles';
          } else {
            if (($total_rows[0]-($item_per_page * $page_number)) < 0) {
              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows[0].' sur '.$total_rows[0].' articles';
            } else {
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
            $pagination .= '<li class="page-item disabled"><a data-page="1" class="page-link"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></a>
            </li>';
          } else {
            $pagination .= '<li class="page-item"><a href="#" data-page="'.($current_page - 1).'" class="page-link"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></a></li>';
          }        
          if ($total_pages <= 5) {                                            
            for($page = 1; $page <= $total_pages; $page++){
              if ($current_page == $page) {
                $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
              } else {
                $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
              }
              }
            }else {  
              if ($current_page == 1) {
                $pagination .= '<li class="page-item active"><a href="#" data-page="1" class="page-link">1</a></li>';
              } else {
                $pagination .= '<li class="page-item"><a href="#" data-page="1" class="page-link">1</a></li>';
              }
              if($current_page == 1 || $current_page == 2){ 
                for($page = 2 ; $page <= 3; $page++){
                  if ($current_page == $page) {
                    $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  } else {
                    $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  }                  
                }         
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
                } else if(($current_page > 2) and ($current_page < $total_pages - 2)){  
                  $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
                  for($page =$current_page ; $page <= ($current_page + 1); $page++){ 
                    if ($current_page == $page) {
                      $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                    } else {
                      $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                    }
                }
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
              }else{
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
                for($page = ($total_pages - 2) ; $page <= ($total_pages - 1); $page++){
                  if ($current_page == $page) {
                    $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  } else {
                    $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  }
                }
              }
              if ($current_page == $total_pages) {
                $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$total_pages.'" class="page-link">'.$total_pages.'</a></li>';
              } else {
                $pagination .= '<li class="page-item page"><a href="#" data-page="'.$total_pages.'" class="page-link">'.$total_pages.'</a></li>';
              }
              }
          if ($current_page == $total_pages) {
            $pagination .= '<li class="page-item disabled"><a data-page="'.$total_pages.'" class="page-link"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a></li>';
          } else {
            $pagination .= '<li class="page-item"><a href="#" data-page="'.($current_page + 1).'" class="page-link"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a></li>';
          }
          $pagination .= '</ul>'; 
      }
      return $pagination; //return pagination links
  }
?>



