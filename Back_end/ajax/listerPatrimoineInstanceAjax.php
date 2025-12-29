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
	header('Location:index.php');
}


require('../connectionPDO.php');

$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');


$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateString2=$jour.'-'.$mois.'-'.$annee;
$dateHeures=$dateString.' '.$heureString;

if (!isset($_POST['operation'])) {
  $reponse="<ul><li>Materiel inexistant</li></ul>";
  //$data = json_decode(file_get_contents('php://input'), true);
  //$query=$data["query"];
  $query=$_POST["query"];
  //si c'est un personnel
  if (isset($_POST["i"])) {
      $idPersonnel=$_POST["i"];
      //$reqS="SELECT * from `aaa-boutique` where enTest=1 and activer=1 and labelB LIKE '%$query%'";
      
      $req = $bdd->prepare("SELECT * from `aaa-materiel`where estAttribuer=:e and nomMateriel LIKE '%".$query."%' "); 
      $req->execute(array('e'=>0))  or die(print_r($req->errorInfo()));
      
      $dataAll=$req->fetchAll();
    
      if ($dataAll) {
          $html="<ul class='ulcB' id='ulAMP".$idPersonnel."'>";
          foreach($dataAll as $data) {
              $html.="<li class='licRB' id='liAMP".$data['idMateriel']."' onclick=\"choisirMatPer(".$data['idMateriel'].",". $idPersonnel.",'".$data['nomMateriel']."')\">".$data['nomMateriel']."</li>";
          }
          $html.="</ul>";
      }else {
          $html="<ul><li>erreur requette</li></ul>";
      }        
      exit($html);
  }// si c'est une salle 
  elseif (isset($_POST["s"]))  {
      $idSalle=$_POST["s"];
      //$reqS="SELECT * from `aaa-boutique` where enTest=1 and activer=1 and labelB LIKE '%$query%'";
      
      $req = $bdd->prepare("SELECT * from `aaa-materiel`where estAttribuer=:e and nomMateriel LIKE '%".$query."%' "); 
      $req->execute(array('e'=>0))  or die(print_r($req->errorInfo()));
      $dataAll=$req->fetchAll();
    
      if ($dataAll) {
          $html="<ul class='ulcB' id='ulAMS".$idSalle."'>";
          foreach ($data as $data) {
              $html.="<li class='licRB' id='liAMS".$data['idMateriel']."' onclick=\"choisirMatSal(".$data['idMateriel'].",". $idSalle.",'".$data['nomMateriel']."')\">".$data['nomMateriel']."</li>";
          }
          $html.="</ul>";
      }else {
          $html="<ul><li>erreur requette</li></ul>";
      }        
      exit($html);
  }
}

$operation=@htmlspecialchars($_POST["operation"]);

function paginate_function($item_per_page, $current_page, $total_records, $total_pages){

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

/////*********************
if ($operation=="1" || $operation=="3" || $operation=="4") {  

    $nbEntreeInst = @$_POST["nbEntreeInst"];
    $query = @$_POST["query"];
    $cb = @$_POST["cb"];
    $item_per_page 		= intval($nbEntreeInst); //item to display per page
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
      $req = $bdd->prepare("SELECT  COUNT(idInstanceMat) as total_row from `aaa-bien-instancemat` "); 
      $req->execute()  or die(print_r($req->errorInfo())); 
      
    } else {
      //get total number of records from database
      if ($cb==1) {
          $req = $bdd->prepare("SELECT  COUNT(idInstanceMat) as total_row from `aaa-bien-instancemat` "); 
          $req->execute()  or die(print_r($req->errorInfo()));    
      }else {
          
          $req = $bdd->prepare("SELECT  COUNT(idInstanceMat) as total_row from `aaa-bien-instancemat` where codeBarreInstMat LIKE '%".$query."%' "); 
          $req->execute()  or die(print_r($req->errorInfo()));
      }
    }
    //var_dump($sql);
    $total_rows = $req->fetch();
    $total_pages = ceil($total_rows[0]/$item_per_page);
    
    //position of records
      $page_position = (($page_number-1) * $item_per_page);
      
      if ($query =="") {
        $req = $bdd->prepare("SELECT  * from `aaa-bien-instancemat` "); 
        $req->execute()  or die(print_r($req->errorInfo())); 
        
      } else {
        //get total number of records from database
        if ($cb==1) {
            $req = $bdd->prepare("SELECT * from `aaa-bien-instancemat` "); 
            $req->execute()  or die(print_r($req->errorInfo()));    
        }else {
            
            $req = $bdd->prepare("SELECT * from `bien-instancemat`where codeBarreInstMat LIKE '%".$query."%' "); 
            $req->execute()  or die(print_r($req->errorInfo()));
        }
      }
    //Display records fetched from database.
      
      echo '<table class="table table-bordered table-striped table-condensed "   border="1" >';
        echo '<thead>
                <tr id="">
                  <th>Code materiel</th>
                  <th>Code barre</th>
                  <th>Code RFID</th>
                  <th>Bénéficiaire</th> 
                  <th>Description</th> 
                  <th>Attribuer</th> 
                  <th>Details</th> 
                </tr>
              </thead>';
              $i = ($page_number - 1) * $item_per_page +1;
              $cpt = 0;
              while ($inst = $req->fetch()) {
                  echo '<tr>';
                      echo  '<td>'.$inst['codeInstMat'].'</td>';
                      echo  '<td><input class="form-control" type="text" value="'.$inst['codeBarreInstMat'].'" ></td>';
                      echo  '<td><input class="form-control" type="text" value="'.$inst['codeInstRFID'].'" ></td>'; 
                      echo  '<td>'.$inst['estAttribuer'].'</td>'; 
                      echo  '<td><input class="form-control" type="text" value="'.$inst['description'].'" ></td> </td>';
                      echo  '<td>';
                      echo  '<input class="form-control" type="text" id="searchWhoAtt'.$inst["idInstanceMat"].'" onkeyup="autocompWhoAtt('.$inst["idInstanceMat"].')" />
                            <button  class="btn btn-success" id="btnValiderWhoAtt'.$inst["idInstanceMat"].'"  onclick="validerWhoAtt('.$inst["idInstanceMat"].')"  >
                              <i class="glyphicon glyphicon-plus"></i>Attribuer
                            </button>  
                            <input type="hidden" id="instWhoAtt'.$inst["idInstanceMat"].'" />
                            <div id="reponseWhoAtt'.$inst["idInstanceMat"].'"></div>';
                      
                      //echo '<input class="form-control"type="text" value="'.$inst['etat'].'" ></td>';
                      echo '</td>';
                      echo  '<td><a onclick="" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a><a>Details</a>';
                      echo '</td>';
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
}elseif ($operation=="addInstance") {
      $idM=$_POST['idM'];
    
      $req1 = $bdd->prepare("insert into `aaa-bien-instancemat` (idMateriel) values(:i)");
      $req1->execute(array( 
                              'i' =>$idM
                              ))  or die(print_r($req1->errorInfo()));
                              
      $idISNT =$bdd->lastInsertId();

      $req = $bdd->prepare("SELECT  * from `aaa-bien-materiel` where idMateriel=:i "); 
      $req->execute(array('i'=>$idM))  or die(print_r($req->errorInfo()));                       
      $mat = $req->fetch(); 

      $codeInst=$mat['codeMateriel'].'-'.$idISNT;
      $req3 = $bdd->prepare("UPDATE `aaa-bien-instancemat` SET codeInstMat=:c WHERE idInstanceMat=:i ");
      $req3->execute(array( 'c' => $codeInst,  'i' => $idISNT ))  or die(print_r($req3->errorInfo()));

      //TESTER si NBR INSTANCE et QUANTITE DANS MATERIEL

      $req4 = $bdd->prepare("SELECT   COUNT(idInstanceMat) as total_row  from `aaa-bien-instancemat` where idMateriel=:i "); 
      $req4->execute(array('i'=>$idM))  or die(print_r($req4->errorInfo()));                       
      $total_rows = $req4->fetch();
      $nomb=$total_rows[0];
      $afficherBoutton=1;

      if (intval($nomb)>= intval($mat['quantite'])) {
        $afficherBoutton=0;
      }  
      $resultat=$codeInst.'<>'.$afficherBoutton;
      exit($resultat);       
}
?>