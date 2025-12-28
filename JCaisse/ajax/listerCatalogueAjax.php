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


// $_SESSION['catalogue']=$_SESSION['type']."-".$_SESSION['categorie'];
$typeCatalogue=@htmlspecialchars($_POST["nom"]);

if($typeCatalogue!=null){

  $_SESSION['catalogue']=@htmlspecialchars($_POST["nom"]);
  // echo $_SESSION['catalogue']." /// ".$_POST["nom"]." /// ".$catalogueTypeCateg;

}else {

  $_SESSION['catalogue']=$_SESSION['type'].'-'.$_SESSION['categorie'];
  // echo $_SESSION['catalogue']." // ".$catalogueTypeCateg;

}

$catalogueTypeCateg='aaa-catalogue-'.$_SESSION['catalogue'];

// $typeCatalogue=@htmlspecialchars($_POST["nom"]);
// if($typeCatalogue!=null){
//   $_SESSION['catalogue']=@htmlspecialchars($_POST["nom"]);
// }
// $catalogueTypeCateg='aaa-catalogue-'.$_SESSION['catalogue'];


if($_SESSION['catalogue']!='Pharmacie-Detaillant'){
  $sql='SELECT * from  `'.$catalogueTypeCateg.'` ';
  $res=mysql_query($sql);
}
else{
  //$sql='SELECT * from  `'.$catalogueTypeCateg.'`  where categorie="Parapharmacie" ';
  $sql='SELECT * from  `'.$catalogueTypeCateg.'` ';
  $res=mysql_query($sql);
}

// $data=array();
// $i=1;
// while($catalogue=mysql_fetch_array($res)){

  
//   $sql1='SELECT * from  `'.$nomtableDesignation.'` where designation="'.$catalogue["designation"].'"';
//   $res1=mysql_query($sql1);

//   $rows = array();		
//     if(!mysql_num_rows($res1)){
//       $rows[] = '<input type="text" style="color: #b64970;border-color: #b64970;"class="form-control" id="categorie-'.$catalogue['id'].'" min=1 value="'.$catalogue["categorie"].'" />';		
//       $rows[] = strtoupper($catalogue['designation']).' <input type="hidden" id="designation-'.$catalogue['id'].'" min=1 value="'.$catalogue["designation"].'<>'.$catalogue["idFusion"].'" />';	
//       $rows[] = strtoupper($catalogue['codeBarreDesignation']).' <input type="text" style="color: #b64970;border-color: #b64970;" class="form-control" id="codeBarre-'.$catalogue['id'].'" min=1 value="'.$catalogue["codeBarreDesignation"].'" />';	
//       if($_SESSION['catalogue']!='Pharmacie-Detaillant'){
//         $rows[] = '<input type="text" style="color: #b64970;border-color: #b64970;" class="form-control" name="uniteStock-'.$catalogue['id'].'" id="uniteStock-'.$catalogue['id'].'" min=1 value="'.$catalogue["uniteStock"].'" />';
//         $rows[] = '<input type="number" style="color: #b64970;border-color: #b64970;" class="form-control" name="nbreArticleUniteStock-'.$catalogue['id'].'" id="nbreArticleUniteStock-'.$catalogue['id'].'" value="'.$catalogue["nbreArticleUniteStock"].'" />';
//         $rows[] = '<input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prixuniteStock-'.$catalogue['id'].'" id="prixuniteStock-'.$catalogue['id'].'" value="'.$catalogue["prixuniteStock"].'"/>';
//         $rows[] = '<input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prix-'.$catalogue['id'].'" id="prix-'.$catalogue['id'].'" value="'.$catalogue["prix"].'"  />';
//         $rows[] = '<input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prixachat-'.$catalogue['id'].'" id="prixachat-'.$catalogue['id'].'" value="0"  />';
  
//       }	
//       else{
//         $rows[] = '<input type="text" style="color: #b64970;border-color: #b64970;" class="form-control" name="uniteStock-'.$catalogue['id'].'" id="uniteStock-'.$catalogue['id'].'" min=1 value="Article" />';
//         $rows[] = '<input type="text" style="color: #b64970;border-color: #b64970;" class="form-control" name="nbreArticleUniteStock-'.$catalogue['id'].'" id="nbreArticleUniteStock-'.$catalogue['id'].'" value="1" />';
//         $rows[] = '<input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prixuniteStock-'.$catalogue['id'].'" id="prixuniteStock-'.$catalogue['id'].'" value="'.$catalogue["prixPublic"].'"/>';
//         $rows[] = '<input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prix-'.$catalogue['id'].'" id="prix-'.$catalogue['id'].'" value="'.$catalogue["prixPublic"].'"  />';
//         $rows[] = '<input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prixachat-'.$catalogue['id'].'" id="prixachat-'.$catalogue['id'].'" value="'.$catalogue["prixSession"].'" />';
  
//       }
//       $rows[] = '<input type="number" style="color: #0090ff;border-color: #0090ff;" class="form-control"  id="quantite-'.$catalogue['id'].'" min=1 value="" />';
//       $rows[] = '<input type="date" style="color: #0090ff;border-color: #0090ff;" class="form-control" name="dateExpiration-'.$catalogue['id'].'" id="dateExpiration-'.$catalogue['id'].'" value="" />';
//       $rows[] = '<button type="button" onclick="init_Produit('.$catalogue["id"].')" id="btn_init_Produit-'.$catalogue['id'].'" class="btn btn-success">
//         <i class="glyphicon glyphicon-plus"></i>
//       </button>';
//     }
//     else{
//         $rows[] = '<input disabled="true" type="text"  class="form-control" id="categorie-'.$catalogue['id'].'" min=1 value="'.$catalogue["categorie"].'" />';		
//         $rows[] = strtoupper($catalogue['designation']).' <input type="hidden" id="designation-'.$catalogue['id'].'" min=1 value="'.$catalogue["designation"].'" />';
//         $rows[] = '<input disabled="true" type="text"  class="form-control" id="codeBarre-'.$catalogue['id'].'" min=1 value="'.$catalogue["codeBarreDesignation"].'" />';
//         if($_SESSION['catalogue']!='Pharmacie-Detaillant'){
//           $rows[] = '<input disabled="true" type="text"  class="form-control" name="uniteStock-'.$catalogue['id'].'" id="uniteStock-'.$catalogue['id'].'" min=1 value="'.$catalogue["uniteStock"].'" />';
//           $rows[] = '<input disabled="true" type="number"  class="form-control" name="nbreArticleUniteStock-'.$catalogue['id'].'" id="nbreArticleUniteStock-'.$catalogue['id'].'" value="'.$catalogue["nbreArticleUniteStock"].'" />';
//           $rows[] = '<input disabled="true" type="number"  class="form-control" name="prixuniteStock-'.$catalogue['id'].'" id="prixuniteStock-'.$catalogue['id'].'" value="'.$catalogue["prixuniteStock"].'"/>';
//           $rows[] = '<input disabled="true" type="number"  class="form-control" name="prix-'.$catalogue['id'].'" id="prix-'.$catalogue['id'].'" value="'.$catalogue["prix"].'"  />';
//           $rows[] = '<input disabled="true" type="number"  class="form-control" name="prixachat-'.$catalogue['id'].'" id="prixachat-'.$catalogue['id'].'" value="0"  />';
//         }	
//         else{
//           $rows[] = '<input disabled="true" type="text"  class="form-control" name="uniteStock-'.$catalogue['id'].'" id="uniteStock-'.$catalogue['id'].'" min=1 value="Article" />';
//           $rows[] = '<input disabled="true" type="text" class="form-control" name="nbreArticleUniteStock-'.$catalogue['id'].'" id="nbreArticleUniteStock-'.$catalogue['id'].'" value="1" />';
//           $rows[] = '<input disabled="true" type="number" class="form-control" name="prixuniteStock-'.$catalogue['id'].'" id="prixuniteStock-'.$catalogue['id'].'" value="'.$catalogue["prixPublic"].'"/>';
//           $rows[] = '<input disabled="true" type="number" class="form-control" name="prix-'.$catalogue['id'].'" id="prix-'.$catalogue['id'].'" value="'.$catalogue["prixPublic"].'" />';
//           $rows[] = '<input disabled="true" type="number" class="form-control" name="prixachat-'.$catalogue['id'].'" id="prixachat-'.$catalogue['id'].'" value="'.$catalogue["prixSession"].'" />';
//         }
//         $rows[] = '<input disabled="true" type="number" class="form-control"  id="quantite-'.$catalogue['id'].'" min=1 value="" />';
//         $rows[] = '<input disabled="true" type="date" class="form-control" name="dateExpiration-'.$catalogue['id'].'" id="dateExpiration-'.$catalogue['id'].'" value="" />';
//         $rows[] = '<button disabled="true" type="button" onclick="init_Produit('.$catalogue["id"].')" id="btn_init_Produit-'.$catalogue['id'].'" class="btn btn-success">
//           <i class="glyphicon glyphicon-plus"></i>
//         </button>';
//     }
//   $data[] = $rows;

// }


// $results = ["sEcho" => 1,
//           "iTotalRecords" => count($data),
//           "iTotalDisplayRecords" => count($data),
//           "aaData" => $data ];

// echo json_encode($results);




/****************************************************/

if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeBInit = @$_POST["nbEntreeBInit"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= $nbEntreeBInit; //item to display per page
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

    $sql='SELECT COUNT(*) as total_row from  `'.$catalogueTypeCateg.'`';
    $res=mysql_query($sql);

  } else {
    # code...
    //get total number of records from database
    if ($cb==1) {
      # code...
      $sql="SELECT COUNT(*) as total_row from `".$catalogueTypeCateg."` where codeBarredesignation='".$query."'";
      $res=mysql_query($sql);
      
    }else {
      # code...
      $sql="SELECT COUNT(*) as total_row from `".$catalogueTypeCateg."` where (designation LIKE '%".$query."%' or codeBarredesignation LIKE '%".$query."%')";
      $res=mysql_query($sql);
    }
  }
  $total_rows = mysql_fetch_array($res);

  $total_pages = ceil($total_rows[0]/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  
  if ($query =="") {
    # code...    
    $sql="SELECT * from  `".$catalogueTypeCateg."` LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
    // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");
    // $results->execute(); //Execute prepared Query
  } else {
    # code...
    //Limit our results within a specified range. 
    if ($cb==1) {
      # code...
      $sql="SELECT * from  `".$catalogueTypeCateg."` where codeBarredesignation='".$query."' LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    } else {
      # code...
      $sql="SELECT * from  `".$catalogueTypeCateg."` where (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    }
    
    // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
    // $results->execute(); //Execute prepared Query
  }
  //Display records fetched from database.
  echo '<table id="tableCatalogue" class="table table-striped display tabCatalogue"  class="tableau3" align="left" border="1">';
  if($_SESSION['catalogue']!='Pharmacie-Detaillant'){
    echo '<thead>
      <tr id="thStock">
          <th>CATEGORIE</th>
          <th>REFERENCE</th>
          <th>CODEBARRE </th>
          <th>UNITE STOCK (US)</th>
          <th>NOMBRE ARTICLE (US)</th>
          <th>PRIX(US)</th>
          <th>PRIX UNITAIRE</th>
          <th>PRIX COMRCT</th>
          <th>PRIX REVIENS</th>
          <th>QANTITE</th>
          <th>EXPIRATION</th>
          <th></th>
      </tr>
    </thead>';
  }
  else {
    echo '<thead>
    <tr id="thStock">
          <th>CATEGORIE</th>
          <th>REFERENCE</th>
          <th>CODEBARRE </th>
          <th>UNITE STOCK (US)</th>
          <th>NOMBRE ARTICLE (US)</th>
          <th>PRIX(US)</th>
          <th>PRIX UNITAIRE</th>
          <th>PRIX REVIENS</th>
          <th>QANTITE</th>
          <th>EXPIRATION</th>
          <th></th>
      </tr>
    </thead>';
  }

  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  while($catalogue=mysql_fetch_array($res)){
    
    $sql1='SELECT * from  `'.$nomtableDesignation.'` where designation="'.$catalogue["designation"].'"';
    $res1=mysql_query($sql1);
        
    echo '<tr>';
    			
    if(!mysql_num_rows($res1)){
      echo  '<td><input type="text" style="color: #b64970;border-color: #b64970;"class="form-control" id="categorie-'.$catalogue['id'].'" min=1 value="'.$catalogue["categorie"].'" /></td>';		
      echo  '<td>'.strtoupper($catalogue['designation']).' <textarea class="form-control" id="designation-'.$catalogue['id'].'">'.$catalogue["designation"].'</textarea></td>';	
      echo  '<td>'.strtoupper($catalogue['codeBarreDesignation']).' <input type="text" style="color: #b64970;border-color: #b64970;" class="form-control" id="codeBarre-'.$catalogue['id'].'" min=1 value="'.$catalogue["codeBarreDesignation"].'" /></td>';	
      if($_SESSION['catalogue']!='Pharmacie-Detaillant'){
        echo  '<td><input type="text" style="color: #b64970;border-color: #b64970;" class="form-control" name="uniteStock-'.$catalogue['id'].'" id="uniteStock-'.$catalogue['id'].'" min=1 value="'.$catalogue["uniteStock"].'" /></td>';
        echo  '<td><input type="number" style="color: #b64970;border-color: #b64970;" class="form-control" name="nbreArticleUniteStock-'.$catalogue['id'].'" id="nbreArticleUniteStock-'.$catalogue['id'].'" value="'.$catalogue["nbreArticleUniteStock"].'" /></td>';
        echo  '<td><input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prixuniteStock-'.$catalogue['id'].'" id="prixuniteStock-'.$catalogue['id'].'" value="'.$catalogue["prixuniteStock"].'"/></td>';
        echo  '<td><input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prix-'.$catalogue['id'].'" id="prix-'.$catalogue['id'].'" value="'.$catalogue["prix"].'"  /></td>';
        echo  '<td><input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prixcommercant-'.$catalogue['id'].'" id="prixcommercant-'.$catalogue['id'].'" value="0"  /></td>';
        echo  '<td><input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prixachat-'.$catalogue['id'].'" id="prixachat-'.$catalogue['id'].'" value="'.$catalogue["prixAchat"].'"  /></td>';
  
      }	
      else{
        echo  '<td><input type="text" style="color: #b64970;border-color: #b64970;" class="form-control" name="uniteStock-'.$catalogue['id'].'" id="uniteStock-'.$catalogue['id'].'" min=1 value="Article" /></td>';
        echo  '<td><input type="text" style="color: #b64970;border-color: #b64970;" class="form-control" name="nbreArticleUniteStock-'.$catalogue['id'].'" id="nbreArticleUniteStock-'.$catalogue['id'].'" value="1" /></td>';
        echo  '<td><input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prixuniteStock-'.$catalogue['id'].'" id="prixuniteStock-'.$catalogue['id'].'" value="'.$catalogue["prixPublic"].'"/></td>';
        echo  '<td><input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prix-'.$catalogue['id'].'" id="prix-'.$catalogue['id'].'" value="'.$catalogue["prixPublic"].'"  />';
        echo  '<td><input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prixachat-'.$catalogue['id'].'" id="prixachat-'.$catalogue['id'].'" value="'.$catalogue["prixSession"].'" /></td>';
  
      }
      echo  '<td><input type="number" style="color: #0090ff;border-color: #0090ff;" class="form-control"  id="quantite-'.$catalogue['id'].'" min=1 value="" /></td>';
      echo  '<td><input type="date" style="color: #0090ff;border-color: #0090ff;" class="form-control" name="dateExpiration-'.$catalogue['id'].'" id="dateExpiration-'.$catalogue['id'].'" value="" /></td>';
      echo  '<td><button type="button" onclick="init_Produit('.$catalogue["id"].')" id="btn_init_Produit-'.$catalogue['id'].'" class="btn btn-success">
        <i class="glyphicon glyphicon-plus"></i>
      </button></td>';
    }
    else{
      $produit=mysql_fetch_array($res1);
      echo  '<td><input disabled="true" type="text"  class="form-control" id="categorie-'.$catalogue['id'].'" min=1 value="'.$produit["categorie"].'" /></td>';		
      echo  '<td>'.strtoupper($catalogue['designation']).' <input type="hidden" id="designation-'.$catalogue['id'].'" min=1 value="'.$produit["designation"].'" /></td>';
      echo  '<td><input disabled="true" type="text"  class="form-control" id="codeBarre-'.$catalogue['id'].'" min=1 value="'.$produit["codeBarreDesignation"].'" /></td>';
        if($_SESSION['catalogue']!='Pharmacie-Detaillant'){
          echo  '<td><input disabled="true" type="text"  class="form-control" name="uniteStock-'.$catalogue['id'].'" id="uniteStock-'.$catalogue['id'].'" min=1 value="'.$produit["uniteStock"].'" /></td>';
          echo  '<td><input disabled="true" type="number"  class="form-control" name="nbreArticleUniteStock-'.$catalogue['id'].'" id="nbreArticleUniteStock-'.$catalogue['id'].'" value="'.$produit["nbreArticleUniteStock"].'" /></td>';
          echo  '<td><input disabled="true" type="number"  class="form-control" name="prixuniteStock-'.$catalogue['id'].'" id="prixuniteStock-'.$catalogue['id'].'" value="'.$produit["prixuniteStock"].'"/></td>';
          echo  '<td><input disabled="true" type="number"  class="form-control" name="prix-'.$catalogue['id'].'" id="prix-'.$catalogue['id'].'" value="'.$produit["prix"].'"  /></td>';
          echo  '<td><input disabled="true" type="number"  class="form-control" name="prixcommercant-'.$catalogue['id'].'" id="prixcommercant-'.$catalogue['id'].'" value="'.$produit["prixcommercant"].'"  /></td>';
          echo  '<td><input disabled="true" type="number"  class="form-control" name="prixachat-'.$catalogue['id'].'" id="prixachat-'.$catalogue['id'].'" value="'.$produit["prixachat"].'" /></td>';
        }	
        else{
          echo  '<td><input disabled="true" type="text"  class="form-control" name="uniteStock-'.$catalogue['id'].'" id="uniteStock-'.$catalogue['id'].'" min=1 value="Article" /></td>';
          echo  '<td><input disabled="true" type="text" class="form-control" name="nbreArticleUniteStock-'.$catalogue['id'].'" id="nbreArticleUniteStock-'.$produit['id'].'" value="1" /></td>';
          echo  '<td><input disabled="true" type="number" class="form-control" name="prixuniteStock-'.$catalogue['id'].'" id="prixuniteStock-'.$catalogue['id'].'" value="'.$produit["prixPublic"].'"/></td>';
          echo  '<td><input disabled="true" type="number" class="form-control" name="prix-'.$catalogue['id'].'" id="prix-'.$catalogue['id'].'" value="'.$produit["prixPublic"].'" />';
          echo  '<td><input disabled="true" type="number" class="form-control" name="prixachat-'.$catalogue['id'].'" id="prixachat-'.$catalogue['id'].'" value="'.$produit["prixSession"].'" /></td>';
        }
        echo  '<td><input disabled="true" type="number" class="form-control"  id="quantite-'.$catalogue['id'].'" min=1 value="" />';
        echo  '<td><input disabled="true" type="date" class="form-control" name="dateExpiration-'.$catalogue['id'].'" id="dateExpiration-'.$catalogue['id'].'" value="" /></td>';
        echo  '<td><button disabled="true" type="button" onclick="init_Produit('.$catalogue["id"].')" id="btn_init_Produit-'.$catalogue['id'].'" class="btn btn-success">
          <i class="glyphicon glyphicon-plus"></i>
        </button></td>';
    }

    echo '</tr>';

    $i++;
    $cpt++;
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="11" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';

  if ($cpt!=0) {
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
  }
    echo '<div class="col-md-8">';
    // To generate links, we call the pagination function here. 
    echo paginate_function($item_per_page, $page_number, $total_rows[0], $total_pages);
    echo '</div>';
  echo '</div>';

}

/*****  Tri **/
if ($operation=="2") {
  # code...
  $nbEntreeBInit = @$_POST["nbEntreeBInit"];
  $query = @$_POST["query"];
  $item_per_page 		= $nbEntreeBInit; //item to display per page
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

    $sql='SELECT COUNT(*) as total_row from  `'.$catalogueTypeCateg.'`';
    $res=mysql_query($sql);

  } else {
    # code...
    //get total number of records from database
    // if ($cb==1) {
    //   # code...
    //   $sql="SELECT COUNT(*) as total_row from `".$catalogueTypeCateg."` where codeBarredesignation='".$query."'";
    //   $res=mysql_query($sql);
      
    // }else {
      # code...
      $sql="SELECT COUNT(*) as total_row from `".$catalogueTypeCateg."` where (designation LIKE '%".$query."%' or codeBarredesignation LIKE '%".$query."%')";
      $res=mysql_query($sql);
    // }
  }
  $total_rows = mysql_fetch_array($res);

  $total_pages = ceil($total_rows[0]/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  
  if ($tri==0) {
    if ($query =="") {
    # code...    
    $sql="SELECT * from  `".$catalogueTypeCateg."` ORDER BY designation ASC LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
    // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");
    // $results->execute(); //Execute prepared Query
    } else {
      # code...
      //Limit our results within a specified range. 
      // if ($cb==1) {
      //   # code...
      //   $sql="SELECT * from  `".$catalogueTypeCateg."` where codeBarredesignation='".$query."' LIMIT $page_position, $item_per_page";
      //   $res=mysql_query($sql);
      // } else {
        # code...
        $sql="SELECT * from  `".$catalogueTypeCateg."` where (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') ORDER BY designation ASC LIMIT $page_position, $item_per_page";
        $res=mysql_query($sql);
      // }
      
      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
      // $results->execute(); //Execute prepared Query
    }
    
  } else {
    if ($query =="") {
    # code...    
      $sql="SELECT * from  `".$catalogueTypeCateg."` ORDER BY designation DESC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");
    // $results->execute(); //Execute prepared Query
    } else {
      # code...
      //Limit our results within a specified range. 
      // if ($cb==1) {
      //   # code...
      //   $sql="SELECT * from  `".$catalogueTypeCateg."` where codeBarredesignation='".$query."' LIMIT $page_position, $item_per_page";
      //   $res=mysql_query($sql);
      // } else {
        # code...
        $sql="SELECT * from  `".$catalogueTypeCateg."` where (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') ORDER BY designation DESC LIMIT $page_position, $item_per_page";
        $res=mysql_query($sql);
      // }
      
      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
      // $results->execute(); //Execute prepared Query
    }
  }
  
  // var_dump($tabIdDesigantion);
  // $results->bind_result($id, $name, $message); //bind variables to prepared statement

  //Display records fetched from database.
  echo '<table id="tableCatalogue" class="table table-striped display tabCatalogue"  class="tableau3" align="left" border="1">';
  if($_SESSION['catalogue']!='Pharmacie-Detaillant'){
    echo '<thead>
      <tr id="thStock">
          <th>CATEGORIE</th>
          <th>REFERENCE</th>
          <th>CODEBARRE </th>
          <th>UNITE STOCK (US)</th>
          <th>NOMBRE ARTICLE (US)</th>
          <th>PRIX(US)</th>
          <th>PRIX UNITAIRE</th>
          <th>PRIX COMRCT</th>
          <th>PRIX REVIENS</th>
          <th>QANTITE</th>
          <th>EXPIRATION</th>
          <th></th>
      </tr>
    </thead>';
  }
  else {
    echo '<thead>
    <tr id="thStock">
          <th>CATEGORIE</th>
          <th>REFERENCE</th>
          <th>CODEBARRE </th>
          <th>UNITE STOCK (US)</th>
          <th>NOMBRE ARTICLE (US)</th>
          <th>PRIX(US)</th>
          <th>PRIX UNITAIRE</th>
          <th>PRIX REVIENS</th>
          <th>QANTITE</th>
          <th>EXPIRATION</th>
          <th></th>
      </tr>
    </thead>';
  }
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  while($catalogue=mysql_fetch_array($res)){
    
    $sql1='SELECT * from  `'.$nomtableDesignation.'` where designation="'.$catalogue["designation"].'"';
    $res1=mysql_query($sql1);
        
    echo '<tr>';
    			
    if(!mysql_num_rows($res1)){
      echo  '<td><input type="text" style="color: #b64970;border-color: #b64970;"class="form-control" id="categorie-'.$catalogue['id'].'" min=1 value="'.$catalogue["categorie"].'" /></td>';		
      echo  '<td>'.strtoupper($catalogue['designation']).' <textarea class="form-control" id="designation-'.$catalogue['id'].'">'.$catalogue["designation"].'</textarea></td>';	
      echo  '<td>'.strtoupper($catalogue['codeBarreDesignation']).' <input type="text" style="color: #b64970;border-color: #b64970;" class="form-control" id="codeBarre-'.$catalogue['id'].'" min=1 value="'.$catalogue["codeBarreDesignation"].'" /></td>';	
      if($_SESSION['catalogue']!='Pharmacie-Detaillant'){
        echo  '<td><input type="text" style="color: #b64970;border-color: #b64970;" class="form-control" name="uniteStock-'.$catalogue['id'].'" id="uniteStock-'.$catalogue['id'].'" min=1 value="'.$catalogue["uniteStock"].'" /></td>';
        echo  '<td><input type="number" style="color: #b64970;border-color: #b64970;" class="form-control" name="nbreArticleUniteStock-'.$catalogue['id'].'" id="nbreArticleUniteStock-'.$catalogue['id'].'" value="'.$catalogue["nbreArticleUniteStock"].'" /></td>';
        echo  '<td><input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prixuniteStock-'.$catalogue['id'].'" id="prixuniteStock-'.$catalogue['id'].'" value="'.$catalogue["prixuniteStock"].'"/></td>';
        echo  '<td><input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prix-'.$catalogue['id'].'" id="prix-'.$catalogue['id'].'" value="'.$catalogue["prix"].'"  /></td>';
        echo  '<td><input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prixcommercant-'.$catalogue['id'].'" id="prixcommercant-'.$catalogue['id'].'" value="0"  /></td>';
        echo  '<td><input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prixachat-'.$catalogue['id'].'" id="prixachat-'.$catalogue['id'].'" value="'.$catalogue["prixAchat"].'"  /></td>';
  
      }	
      else{
        echo  '<td><input type="text" style="color: #b64970;border-color: #b64970;" class="form-control" name="uniteStock-'.$catalogue['id'].'" id="uniteStock-'.$catalogue['id'].'" min=1 value="Article" /></td>';
        echo  '<td><input type="text" style="color: #b64970;border-color: #b64970;" class="form-control" name="nbreArticleUniteStock-'.$catalogue['id'].'" id="nbreArticleUniteStock-'.$catalogue['id'].'" value="1" /></td>';
        echo  '<td><input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prixuniteStock-'.$catalogue['id'].'" id="prixuniteStock-'.$catalogue['id'].'" value="'.$catalogue["prixPublic"].'"/></td>';
        echo  '<td><input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prix-'.$catalogue['id'].'" id="prix-'.$catalogue['id'].'" value="'.$catalogue["prixPublic"].'"  />';
        echo  '<td><input type="number" style="color: #ff5959;border-color: #ff5959;" class="form-control" name="prixachat-'.$catalogue['id'].'" id="prixachat-'.$catalogue['id'].'" value="'.$catalogue["prixSession"].'" /></td>';
  
      }
      echo  '<td><input type="number" style="color: #0090ff;border-color: #0090ff;" class="form-control"  id="quantite-'.$catalogue['id'].'" min=1 value="" /></td>';
      echo  '<td><input type="date" style="color: #0090ff;border-color: #0090ff;" class="form-control" name="dateExpiration-'.$catalogue['id'].'" id="dateExpiration-'.$catalogue['id'].'" value="" /></td>';
      echo  '<td><button type="button" onclick="init_Produit('.$catalogue["id"].')" id="btn_init_Produit-'.$catalogue['id'].'" class="btn btn-success">
        <i class="glyphicon glyphicon-plus"></i>
      </button></td>';
    }
    else{
      $produit=mysql_fetch_array($res1);
      echo  '<td><input disabled="true" type="text"  class="form-control" id="categorie-'.$catalogue['id'].'" min=1 value="'.$produit["categorie"].'" /></td>';		
      echo  '<td>'.strtoupper($catalogue['designation']).' <input type="hidden" id="designation-'.$catalogue['id'].'" min=1 value="'.$produit["designation"].'" /></td>';
      echo  '<td><input disabled="true" type="text"  class="form-control" id="codeBarre-'.$catalogue['id'].'" min=1 value="'.$produit["codeBarreDesignation"].'" /></td>';
        if($_SESSION['catalogue']!='Pharmacie-Detaillant'){
          echo  '<td><input disabled="true" type="text"  class="form-control" name="uniteStock-'.$catalogue['id'].'" id="uniteStock-'.$catalogue['id'].'" min=1 value="'.$produit["uniteStock"].'" /></td>';
          echo  '<td><input disabled="true" type="number"  class="form-control" name="nbreArticleUniteStock-'.$catalogue['id'].'" id="nbreArticleUniteStock-'.$catalogue['id'].'" value="'.$produit["nbreArticleUniteStock"].'" /></td>';
          echo  '<td><input disabled="true" type="number"  class="form-control" name="prixuniteStock-'.$catalogue['id'].'" id="prixuniteStock-'.$catalogue['id'].'" value="'.$produit["prixuniteStock"].'"/></td>';
          echo  '<td><input disabled="true" type="number"  class="form-control" name="prix-'.$catalogue['id'].'" id="prix-'.$catalogue['id'].'" value="'.$produit["prix"].'"  /></td>';
          echo  '<td><input disabled="true" type="number"  class="form-control" name="prixcommercant-'.$catalogue['id'].'" id="prixcommercant-'.$catalogue['id'].'" value="'.$produit["prixcommercant"].'"  /></td>';
          echo  '<td><input disabled="true" type="number"  class="form-control" name="prixachat-'.$catalogue['id'].'" id="prixachat-'.$catalogue['id'].'" value="'.$produit["prixachat"].'" /></td>';
        }	
        else{
          echo  '<td><input disabled="true" type="text"  class="form-control" name="uniteStock-'.$catalogue['id'].'" id="uniteStock-'.$catalogue['id'].'" min=1 value="Article" /></td>';
          echo  '<td><input disabled="true" type="text" class="form-control" name="nbreArticleUniteStock-'.$catalogue['id'].'" id="nbreArticleUniteStock-'.$produit['id'].'" value="1" /></td>';
          echo  '<td><input disabled="true" type="number" class="form-control" name="prixuniteStock-'.$catalogue['id'].'" id="prixuniteStock-'.$catalogue['id'].'" value="'.$produit["prixPublic"].'"/></td>';
          echo  '<td><input disabled="true" type="number" class="form-control" name="prix-'.$catalogue['id'].'" id="prix-'.$catalogue['id'].'" value="'.$produit["prixPublic"].'" />';
          echo  '<td><input disabled="true" type="number" class="form-control" name="prixachat-'.$catalogue['id'].'" id="prixachat-'.$catalogue['id'].'" value="'.$produit["prixSession"].'" /></td>';
        }
        echo  '<td><input disabled="true" type="number" class="form-control"  id="quantite-'.$catalogue['id'].'" min=1 value="" />';
        echo  '<td><input disabled="true" type="date" class="form-control" name="dateExpiration-'.$catalogue['id'].'" id="dateExpiration-'.$catalogue['id'].'" value="" /></td>';
        echo  '<td><button disabled="true" type="button" onclick="init_Produit('.$catalogue["id"].')" id="btn_init_Produit-'.$catalogue['id'].'" class="btn btn-success">
          <i class="glyphicon glyphicon-plus"></i>
        </button></td>';
    }

    echo '</tr>';

    $i++;
    $cpt++;
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="11" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';

if ($cpt!=0) {
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
  }
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

?>