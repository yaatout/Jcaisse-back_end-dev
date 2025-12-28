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
  $sql13="SELECT * from  `".$formeTypeCategPharmacie."` ";
  //var_dump($sql13);
  $res13 = mysql_query($sql13) or die ("persoonel requête 3".mysql_error());
  $design = mysql_fetch_assoc($res13);

  $result=$design['id'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['prixuniteStock'].'<>'.$design['prix'].'<>'.$design['image'];
  exit($result);
   
}
if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeFoPh = @$_POST["nbEntreeFoPh"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= $nbEntreeFoPh; //item to display per page
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
    $sql="select COUNT(*) as total_row from `".$formeTypeCategPharmacie."`  ";
    $res=mysql_query($sql);
  } else {
    //get total number of records from database
    if ($cb==1) {
     /* $sql="select COUNT(*) as total_row from `".$formeTypeCategPharmacie."`  where codeBarreCategorie='".$query."'";
      $res=mysql_query($sql);*/
    }else {
      $sql="select COUNT(*) as total_row from `".$formeTypeCategPharmacie."` where nomForme LIKE '%".$query."%' ";
      $res=mysql_query($sql);
    }
  }
  $total_rows = mysql_fetch_array($res);
  $total_pages = ceil($total_rows[0]/$item_per_page);
  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  if ($query =="") {
    $sql="select * from `".$formeTypeCategPharmacie."`  order by nomForme ASC LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
  } else {
    //Limit our results within a specified range. 
    if ($cb==1) {
      //$sql="select * from `".$formeTypeCategPharmacie."` where codeBarredesignation='".$query."' order by nomCategorie desc LIMIT $page_position, $item_per_page";
      //$res=mysql_query($sql);
    } else {
      //$sql="select * from `".$formeTypeCategPharmacie."` where nomCategorie LIKE '%".$query."%' or codeBarreCategorie LIKE '%".$query."%' order by nomCategorie desc LIMIT $page_position, $item_per_page";
      $sql="select * from `".$formeTypeCategPharmacie."` where nomForme LIKE '%".$query."%'  order by nomForme ASC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    }
  }


  //Display records fetched from database.
  echo '<table class="table table-striped contents display tabDesign " id="tableForme" border="1">';
  echo '<thead>
          <tr id="thDesignation">
		  <th>NOM FORME</th>
		  <th>OPERATION</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  while($design=mysql_fetch_array($res)){ //fetch values
    $sql1="SELECT * FROM `". $formeTypeCategPharmacie."` ";
    $res1=mysql_query($sql1);
    echo '<tr>';
      # code...
      echo  '<td><span style="color:blue;"><form class="form form-inline" role="form"  method="post" >
      <input type="text"  class="form-control" id="formeD-'.$design['id'].'" size="15" value="'.$design['nomForme'].'" required=""/></span>
      <input type="hidden"  class="form-control" id="forme_oldD-'.$design['id'].'" size="15" value="'.$design['nomForme'].'" required=""/>
      </td>';
      
     
      
        /*echo '<td><a id="pencilmoD-'.$design['id'].'" onclick="mdf_CategorieB('.$design["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
        <a onclick="spm_CategorieB('.$design["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
        <a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  onclick="imgN_CategorieC('.$design["id"].','.$id.','.$i.')" /></a></td>';*/
        echo '<td><a id="pencilmoDF-'.$design['id'].'" onclick="mdf_FormePh('.$design["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
        <a onclick="spm_FormePh('.$design["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>';
      
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

?>





