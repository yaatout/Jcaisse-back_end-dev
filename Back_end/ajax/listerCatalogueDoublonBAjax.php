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




require('../connection.php');



require('../declarationVariables.php');

$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);
$id = @$_POST["id"];
//var_dump($id);
            $sql0="SELECT * from  `aaa-catalogueTotal`  where id=".$id;
            $res0=mysql_query($sql0);
            if ($res0) {
                // code...
                $tab0=mysql_fetch_array($res0);
                $catalogueTotal='aaa-catalogueTotal';
                $type=$tab0['type'];
                $categorie=$tab0['categorie'];
                $typeCategorie=$tab0['type']."-".$tab0['categorie'];
                $catalogueTypeCateg='aaa-catalogue-'.$typeCategorie;
                $categorieTypeCateg='aaa-categorie-'.$typeCategorie;
                $formeTypeCategPharmacie='aaa-forme-'.$typeCategorie;
                $tableauTypeCategPharmacie='aaa-tableau-'.$typeCategorie;
            } else {
                // code...
                $tab0=0;
            }
            $typeCategorie=$tab0['type']."-".$tab0['categorie'];
            $catalogueTypeCateg='aaa-catalogue-'.$typeCategorie;
            $sql3="SELECT * from  `".$catalogueTypeCateg."` ORDER BY designation ASC ";
            $res3=mysql_query($sql3);
            
if ($operation=="image") {
  $idD = @$_POST["idD"];
  $sql13="SELECT * from  `".$catalogueTypeCateg."` where id='".$idD."'";
  //var_dump($sql13);
  $res13 = mysql_query($sql13) or die ("persoonel requête 3".mysql_error());
  $design = mysql_fetch_assoc($res13);

  $result=$design['id'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['prixuniteStock'].'<>'.$design['prix'].'<>'.$design['image'];
  exit($result);
   
}
if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeBD = @$_POST["nbEntreeBD"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= $nbEntreeBD; //item to display per page
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
    $sql="SELECT COUNT(id) AS total_row from (SELECT *
      FROM `".$catalogueTypeCateg."`
      GROUP BY designation, categorie, uniteStock,nbreArticleUniteStock,prixuniteStock,prix,codeBarreDesignation HAVING COUNT(*) > 1) TableReponse";
    $res=mysql_query($sql);
  } else {
    //get total number of records from database
    if ($cb==1) {
        $sql="SELECT COUNT(id) AS total_row from (SELECT *
        FROM `".$catalogueTypeCateg."`  where codeBarredesignation='".$query."'
        GROUP BY designation, categorie, uniteStock,nbreArticleUniteStock,prixuniteStock,prix,codeBarreDesignation HAVING COUNT(*) > 1) TableReponse";
        $res=mysql_query($sql);
    }else {
        $sql="SELECT COUNT(id) AS total_row from (SELECT *
        FROM `".$catalogueTypeCateg."`  where designation LIKE '%".$query."%' or codeBarredesignation='".$query."'
        GROUP BY designation, categorie, uniteStock,nbreArticleUniteStock,prixuniteStock,prix,codeBarreDesignation HAVING COUNT(*) > 1) TableReponse";
        $res=mysql_query($sql);
    }
  }
  $total_rows = mysql_fetch_array($res);
  $total_pages = ceil($total_rows[0]/$item_per_page);
  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  if ($query =="") {
    $sql="SELECT *
    FROM `".$catalogueTypeCateg."`
    GROUP BY designation, categorie, uniteStock,nbreArticleUniteStock,prixuniteStock,prix,codeBarreDesignation HAVING COUNT(*) > 1    order by id desc LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
  } else {
    //Limit our results within a specified range. 
    if ($cb==1) {
        $sql="SELECT *
        FROM `".$catalogueTypeCateg."`  where codeBarredesignation='".$query."'
        GROUP BY designation, categorie, uniteStock,nbreArticleUniteStock,prixuniteStock,prix,codeBarreDesignation HAVING COUNT(*) > 1 order by id desc LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    } else {
        $sql="SELECT *
        FROM `".$catalogueTypeCateg."`  where designation LIKE '%".$query."%' or codeBarredesignation='".$query."'
        GROUP BY designation, categorie, uniteStock,nbreArticleUniteStock,prixuniteStock,prix,codeBarreDesignation HAVING COUNT(*) > 1 order by id desc LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    }
  }


  //Display records fetched from database.
  echo '<table class="table table-striped contents display tabDesign tableau3" id="tableDesignation" border="1">';
  echo '<thead>
          <tr id="thDesignation">
          <th>ORDRE</th>
          <th>DESIGNATION</th>
          <th>CATEGORIE</th>
          <th>UNITE STOCK</th>
          <th>ARTICLE PAR UNITE DE STOCK</th>
          <th>PRIX</th>
          <th>PRIX UNITE STOCK</th>
          <th>PRIX ACHAT</th>
          <th>OPERATION</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  while($design=mysql_fetch_array($res)){ //fetch values
    $sql1="SELECT * FROM `". $catalogueTypeCateg."` ";
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

      echo  '<td><span style="color:blue;">'.$design['prixAchat'].'</span></td>';
      } else {
      # code...
      echo  '<td>'.$i.'</td>';

      echo  '<td>'.strtoupper($design['designation']).'</td>';

      echo  '<td>'.strtoupper($design['codeBarreDesignation']).'</td>';

      echo  '<td>'.strtoupper($design['uniteStock']).'</td>';

      echo  '<td>'.$design['nbreArticleUniteStock'].'</td>';

      echo  '<td>'.$design['prixuniteStock'].'</td>';

      echo  '<td>'.$design['prix'].'</td>';

      echo  '<td>'.$design['prixAchat'].'</td>';
     
    } 
        echo '<td><a><button type="button"  name="button" onclick="dbl_Designation_B('.$design["id"].','.$id.','.$i.')">Doublon</button></a></td>';
      
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
    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where classe=0";
    $res=mysql_query($sql);
  } else {
    # code...
    //get total number of records from database
    $sql="select COUNT(*) as total_row from `".$nomtableDesignation."` where classe=0 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%')";
    $res=mysql_query($sql);
  }
  $total_rows = mysql_fetch_array($res);
  $total_pages = ceil($total_rows[0]/$item_per_page);
  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  if ($tri==0) {
    if ($query =="") {
      # code...
      $sql="select * from `".$nomtableDesignation."` where classe=0 order by designation ASC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");
      // $results->execute(); //Execute prepared Query
      $sql="select * from `".$nomtableDesignation."` where classe=0 order by idDesignation desc LIMIT 1";
      $res1=mysql_query($sql);
    } else {
      # code...
      //Limit our results within a specified range. 
      $sql="select * from `".$nomtableDesignation."` where classe=0 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by designation ASC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
      // $results->execute(); //Execute prepared Query
      $sql="select * from `".$nomtableDesignation."` where classe=0 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by idDesignation desc LIMIT 1";
      $res1=mysql_query($sql);
    }
  } else {
    if ($query =="") {
      # code...
      $sql="select * from `".$nomtableDesignation."` where classe=0 order by designation DESC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");
      // $results->execute(); //Execute prepared Query
      $sql="select * from `".$nomtableDesignation."` where classe=0 order by idDesignation desc LIMIT 1";
      $res1=mysql_query($sql);
    } else {
      # code...
      //Limit our results within a specified range. 
      $sql="select * from `".$nomtableDesignation."` where classe=0 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by designation DESC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
      // $results->execute(); //Execute prepared Query
      $sql="select * from `".$nomtableDesignation."` where classe=0 and (designation LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by idDesignation desc LIMIT 1";
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
    if ($design['idDesignation']==$maxId[0]) {
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

?>

