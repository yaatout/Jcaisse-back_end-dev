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
///////////

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

 
if ($operation=="image") {
  $idD = @$_POST["idD"];
  $sql13="SELECT * from  `".$categorieTypeCateg."` ";
  //var_dump($sql13);
  $res13 = mysql_query($sql13) or die ("persoonel requête 3".mysql_error());
  $design = mysql_fetch_assoc($res13);

  $result=$design['id'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['prixuniteStock'].'<>'.$design['prix'].'<>'.$design['image'];
  exit($result);
   
}
if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeCatPh = @$_POST["nbEntreeCatPh"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= $nbEntreeCatPh; //item to display per page
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
    $sql="select COUNT(*) as total_row from `".$categorieTypeCateg."`  ";
    $res=mysql_query($sql);
  } else {
    //get total number of records from database
    if ($cb==1) {
     /* $sql="select COUNT(*) as total_row from `".$categorieTypeCateg."`  where codeBarreCategorie='".$query."'";
      $res=mysql_query($sql);*/
    }else {
      //$sql="select COUNT(*) as total_row from `".$categorieTypeCateg."` where nomCategorie LIKE '%".$query."%' or codeBarreCategorie LIKE '%".$query."%'";
      $sql="select COUNT(*) as total_row from `".$categorieTypeCateg."` where nomCategorie LIKE '%".$query."%' ";
      $res=mysql_query($sql);
    }
  }
  $total_rows = mysql_fetch_array($res);
  $total_pages = ceil($total_rows[0]/$item_per_page);
  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  if ($query =="") {
    $sql="select * from `".$categorieTypeCateg."`  order by nomCategorie ASC LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
  } else {
    //Limit our results within a specified range. 
    if ($cb==1) {
      //$sql="select * from `".$categorieTypeCateg."` where codeBarredesignation='".$query."' order by nomCategorie desc LIMIT $page_position, $item_per_page";
      //$res=mysql_query($sql);
    } else {
      //$sql="select * from `".$categorieTypeCateg."` where nomCategorie LIKE '%".$query."%' or codeBarreCategorie LIKE '%".$query."%' order by nomCategorie desc LIMIT $page_position, $item_per_page";
      $sql="select * from `".$categorieTypeCateg."` where nomCategorie LIKE '%".$query."%'  order by nomCategorie ASC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    }
  }


  //Display records fetched from database.
  echo '<table class="table table-striped contents display tabDesign " id="tableCategorie" border="1">';
  echo '<thead>
          <tr id="thDesignation">
		  <th>CATEGORIE</th>
		  <th>CATEGORIE PARENT</th>
		  <th>OPERATION</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  while($design=mysql_fetch_array($res)){ //fetch values

    $selC='<td><span style="color:blue;" >
    <select class="form-control" id="categorieP-'.$design['id'].'" >
        <option  selected value="'.$design["categorieParent"].'" >'.$design['categorieParent'].'</option>
        <option   value= "" ></option>';
        $sql111="SELECT * FROM `". $categorieTypeCateg."` ORDER BY `nomCategorie` ASC ";
        $res111=mysql_query($sql111);
        while($op=mysql_fetch_array($res111)){
            $selC=$selC."<option  value='".$op["nomCategorie"]."' > ".$op["nomCategorie"].'</option>';
        }
    $selC=$selC.'</select></span></td>';

    $sql1="SELECT * FROM `". $categorieTypeCateg."` ";
    $res1=mysql_query($sql1);
    echo '<tr>';
    if ($i==1) {
      # code...
      echo  '<td><span style="color:blue;"><form class="form form-inline" role="form"  method="post" >
      <input type="text"  class="form-control" id="categorieD-'.$design['id'].'" min=1 value="'.$design['nomCategorie'].'" required=""/></span></td>';
      echo  $selC;
     
      } else {
      # code...
      echo  '<td><span ><form class="form form-inline" role="form"  method="post" >
      <input type="text" class="form-control" id="categorieD-'.$design['id'].'" min=1 value="'.$design['nomCategorie'].'" required=""/></span></td>';
      echo  $selC;
    } if ($design["image"]) {
      echo '<td><a id="pencilmoD-'.$design['id'].'" onclick="mdf_CategorieB('.$design["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
        <a   onclick="spm_CategorieB('.$design["id"].','.$id.','.$i.')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
        <a><img src="images/iconfinder11.png" align="middle" alt="apperçu"  onclick="imgE_CategorieC('.$design["id"].','.$id.','.$i.')" /></a></td>';
      }
      else{
        echo '<td><a id="pencilmoD-'.$design['id'].'" onclick="mdf_CategorieB('.$design["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
        <a onclick="spm_CategorieB('.$design["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
        <a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  onclick="imgN_CategorieC('.$design["id"].','.$id.','.$i.')" /></a></td>';
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


/*
  $sql15="SELECT * from  `".$categorieTypeCateg."` ";
  $res15=mysql_query($sql15);

$data=array();
$i=1;

while($tab5=mysql_fetch_array($res15)){

  $rows = array();
	$rows[] = $i;
  $rows[] = strtoupper($tab5['nomCategorie']);
  $rows[] = strtoupper($tab5['categorieParent']);
  if ($tab5["image"]) {
	$rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation()" /></a>
	  <a><img src="images/drop.png" align="middle" alt="supprimer"  onclick="spm_Designation()" /></a>
	  <a onclick="codeBR_Designation()" ><span class="glyphicon glyphicon-barcode"></span></a>
	  <a><img src="images/iconfinder11.png" align="middle" alt="apperçu"  onclick="imgE_DesignationC()" /></a>';
	}
	else{
		$rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation()" /></a>
	  <a><img src="images/drop.png" align="middle" alt="supprimer"  onclick="spm_Designation()" /></a>
	  <a onclick="codeBR_Designation()" ><span class="glyphicon glyphicon-barcode"></span></a>
	  <a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  onclick="imgN_DesignationC()" /></a>';
	}
 $data[] = $rows;
$i= $i + 1;
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);
*/

?>





