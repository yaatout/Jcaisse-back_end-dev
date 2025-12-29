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

if (isset($_POST['materiel'])) {
            
      if ($operation=="1" || $operation=="3" || $operation=="4") {  

          $nbEntreeMat = @$_POST["nbEntreeMat"];
          $query = @$_POST["query"];
          $cb = @$_POST["cb"];
          $item_per_page 		= intval($nbEntreeMat); //item to display per page
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
            $req = $bdd->prepare("SELECT  COUNT(idMateriel) as total_row from `aaa-bien-materiel` "); 
            $req->execute()  or die(print_r($req->errorInfo())); 
            
          } else {
            //get total number of records from database
            if ($cb==1) {
                $req = $bdd->prepare("SELECT  COUNT(idMateriel) as total_row from `aaa-bien-materiel` "); 
                $req->execute()  or die(print_r($req->errorInfo()));    
            }else {
                
                $req = $bdd->prepare("SELECT  COUNT(idMateriel) as total_row from `aaa-bien-materiel`where nomMateriel LIKE '%".$query."%' "); 
                $req->execute()  or die(print_r($req->errorInfo()));
            }
          }
          //var_dump($sql);
          $total_rows = $req->fetch();
          $total_pages = ceil($total_rows[0]/$item_per_page);
          
          //position of records
            $page_position = (($page_number-1) * $item_per_page);
            
            if ($query =="") {
              $req = $bdd->prepare("SELECT  * from `aaa-bien-materiel` "); 
              $req->execute()  or die(print_r($req->errorInfo())); 
              
            } else {
              //get total number of records from database
              if ($cb==1) {
                  $req = $bdd->prepare("SELECT * from `aaa-bien-materiel` "); 
                  $req->execute()  or die(print_r($req->errorInfo()));    
              }else {
                  
                  $req = $bdd->prepare("SELECT * from `aaa-bien-materiel` where nomMateriel LIKE '%".$query."%' "); 
                  $req->execute()  or die(print_r($req->errorInfo()));
              }
            }

            $matos=$req->fetchAll();
          //Display records fetched from database.
            echo '<table class="table table-bordered table-striped table-condensed " id="exemple5" border="1" >';
              echo '<thead>
                      <tr id="">
                      <th>NOM MATERIEL</th>
                      <th>CATEGORIE </th>
                      <th>PRIX ACHAT </th>
                      <th>QUANTITE</th>
                      <th>DATE</th>
                      <th> </th>
                      </tr>
                    </thead>';
                    $i = ($page_number - 1) * $item_per_page +1;
                    $cpt = 0;
                    foreach ($matos as $mat ) {
                      //var_dump($mat);
                        echo '<tr>';
                          echo  '<td> '.$mat['nomMateriel']. '</td>';
                            $req6 = $bdd->prepare("SELECT * from `aaa-bien-categorie` where idCategorie =:i "); 
                            $req6->execute(array('i'=>$mat['idCategorie']))  or die(print_r($req6->errorInfo()));
                            $categ=$req6->fetch();
                          echo  '<td> '.$categ['nomCategorie']. '</td>';
                          echo  '<td> '.$mat['prix']. '</td>';
                          echo  '<td> '.$mat['quantite'].'</td>';
                          echo  '<td> '.$mat['dateMateriel']. '</td>';
                          echo  "<td>  <a class='btn btn-success' href='materielDetails.php?m=".$mat["idMateriel"]."'> Details </a></td>";

                          /* echo  '<td>';
                    
                            echo '<div class="col-sm-8"">
                                      <input class="form-control" type="text" id="searchWhoAtt'.$mat["idMateriel"].'" onkeyup="autocompWhoAtt('.$mat["idMateriel"].')" />
                                      <input type="hidden" id="matWhoAtt'.$mat["idMateriel"].'" />
                                      <div id="reponseWhoAtt'.$mat["idMateriel"].'"></div>
                                  </div> 
                                  <button  class="btn btn-success" id="btnValiderWhoAtt'.$mat["idMateriel"].'"  onclick="validerWhoAtt('.$mat["idMateriel"].')"  >
                                      Attribuer<i class="glyphicon glyphicon-plus"></i>
                                  </button>
                                  ' ;
                            echo  ' </td>'; */
                          echo  '<td>';
                          echo '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Materiel('.$mat["idMateriel"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$mat["idMateriel"].'" /></a>&nbsp;
                    
                          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="sup_Materiel('.$mat["idMateriel"].')"  data-toggle="modal" data-target="#imgsup'.$mat["idMateriel"].'" /></a>';
                          echo  ' </td>
                          </tr>';    
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
      elseif($operation=="addMateriel"){
        $nom=$_POST['nomMateriel'];
        $codeMateriel=$_POST['code'];
        $idCategorie=$_POST['iCategorie'];
        $prixAchat=$_POST['prixAchat'];
        $quantite=$_POST['quantite'];
        $dateM=$_POST['dateM'];
    
        $req1 = $bdd->prepare("insert into `aaa-bien-materiel` (nomMateriel,idCategorie,prix,dateMateriel,quantite) values(:n, :c,:pr,:d,:q)");
        $req1->execute(array(
                                'n' =>$nom, 
                                'c'=>$idCategorie,
                                'pr' =>$prixAchat,
                                'd'=>$dateM,
                                'q'=>$quantite
                                ))  or die(print_r($req1->errorInfo()));
 
        $idM =$bdd->lastInsertId();
        $codeMateriel=$codeMateriel.$idM;
        $req3 = $bdd->prepare("UPDATE `aaa-bien-materiel` SET codeMateriel=:c WHERE idMateriel=:i ");
        $req3->execute(array( 'c' => $codeMateriel,  'i' => $idM ))  or die(print_r($req3->errorInfo()));


      }
      elseif($operation=="recherche"){
          $idM=$_POST["i"];
          $req = $bdd->prepare("SELECT  * from `aaa-bien-materiel` where idMateriel=:i "); 
              $req->execute(array('i'=>$idM))  or die(print_r($req->errorInfo())); 
              
          $mat = $req->fetch();
          $resultat= $mat['nomMateriel']."<>".$mat['idCategorie']."<>".$mat['prix']."<>".$mat['dateMateriel'];
          exit ($resultat);
      }elseif ($operation=="modeCBMateriel") {

            $idM=$_POST["i"];
            $codeBarreMateriel=$_POST["codeBarreMateriel"];

            $req3 = $bdd->prepare("UPDATE `aaa-materiel` SET codeBarreMateriel=:c WHERE idMateriel=:i ");
            $req3->execute(array( 'c' => $codeBarreMateriel,  'i' => $idM ))  or die(print_r($req3->errorInfo()));

      }elseif ($operation=="modeCRFIDMateriel") {

          $idM=$_POST["i"];
          $codeRFID=$_POST["codeRFID"];

          $req3 = $bdd->prepare("UPDATE `aaa-materiel` SET codeRFID=:c WHERE idMateriel=:i ");
          $req3->execute(array( 'c' => $codeRFID,  'i' => $idM ))  or die(print_r($req3->errorInfo()));

      }elseif ($operation=="modeMateriel") {

          $idM=$_POST["i"];
          $nomMatMod=$_POST["nomMatMod"];
          $prixAchatMatMod=$_POST["prixAchatMatMod"];
          $dateMatMod=$_POST["dateMatMod"];

          $req3 = $bdd->prepare("UPDATE `aaa-bien-materiel` SET nomMateriel=:n,prix =:p,dateMateriel=:d WHERE idMateriel=:i ");
          $req3->execute(array( 'n' => $nomMatMod,
                              'p' => $prixAchatMatMod,
                              'd' => $dateMatMod,
                              'i' => $idM ))  or die(print_r($req3->errorInfo()));
      }elseif ($operation=="supMateriel") {
          $idM=$_POST["i"];
          $req = $bdd->prepare("DELETE FROM `aaa-bien-materiel` WHERE idMateriel=:i ");
          $req->execute(array('i' => $idM )) or die(print_r($req->errorInfo()));
      }elseif ($operation=="attrubMatPer") {
        $idPersonnel=$_POST["i"];
        $m=$_POST["m"];
        $req = $bdd->prepare("INSERT INTO `aaa-materiel-attribution`(`idMateriel`,`idPersonnel`)
                                                             VALUES (:m,:p)");
        $req->execute(array(
                            'p' => $idPersonnel ,
                            'm' => $m 
                            )) or die(print_r($req->errorInfo()));
                            
                            
        $req3 = $bdd->prepare("UPDATE `aaa-materiel` SET estAttribuer=:e WHERE idMateriel=:i ");
        $req3->execute(array( 'e' => 1,
                              'i' => $m ))  or die(print_r($req3->errorInfo()));
    }

} 
/////PERSNNELS*********************
else if(isset($_POST['personnel'])) {

  if ($operation=="1" || $operation=="3" || $operation=="4") {  

    $nbEntreePer = @$_POST["nbEntreePer"];
    $query = @$_POST["query"];
    $cb = @$_POST["cb"];
    $item_per_page 		= intval($nbEntreePer); //item to display per page
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
      $req = $bdd->prepare("SELECT  COUNT(idPersonnel) as total_row from `aaa-personnel` "); 
      $req->execute()  or die(print_r($req->errorInfo())); 
      
    } else {
      //get total number of records from database
      if ($cb==1) {
          $req = $bdd->prepare("SELECT  COUNT(idPersonnel) as total_row from `aaa-personnel` "); 
          $req->execute()  or die(print_r($req->errorInfo()));    
      }else {
          
          $req = $bdd->prepare("SELECT  COUNT(idPersonnel) as total_row from `aaa-personnel`where nomPersonnel LIKE '%".$query."%' "); 
          $req->execute()  or die(print_r($req->errorInfo()));
      }
    }
    //var_dump($sql);
    $total_rows = $req->fetch();
    $total_pages = ceil($total_rows[0]/$item_per_page);
    
    //position of records
      $page_position = (($page_number-1) * $item_per_page);
      
      if ($query =="") {
        $req = $bdd->prepare("SELECT  * from `aaa-personnel` "); 
        $req->execute()  or die(print_r($req->errorInfo())); 
        
      } else {
        //get total number of records from database
        if ($cb==1) {
            $req = $bdd->prepare("SELECT * from `aaa-personnel` "); 
            $req->execute()  or die(print_r($req->errorInfo()));    
        }else {
            
            $req = $bdd->prepare("SELECT * from `aaa-personnel`where nomPersonnel LIKE '%".$query."%' "); 
            $req->execute()  or die(print_r($req->errorInfo()));
        }
      }
    //Display records fetched from database.
      echo '<table class="table table-bordered table-striped table-condensed "   border="1" >';
        echo '<thead>
                <tr id="">
                <th>NOM MATERIEL</th>
                <th>DATE</th>
                <th>ATTRIBUER</th>
                <th>A </th>
                <th> </th>

                </tr>
              </thead>';
              $i = ($page_number - 1) * $item_per_page +1;
              $cpt = 0;
              while ($per = $req->fetch()) {
                  echo '<tr>';
                    echo  '<td> '.$per['nomPersonnel']. '</td>';
                    echo  '<td> '.$per['nomPersonnel']. '</td>';
                    echo  '<td> '.$per['nomPersonnel']. '</td>';
                    echo  '<td>';
                   
                    echo '<div class="col-sm-8"">
                              <input class="form-control" type="text" id="searchMatToPer'.$per['idPersonnel'].'" onkeyup="autocompMatToPer('.$per['idPersonnel'].')" />
                              <input type="hidden" id="matMatToPer'.$per['idPersonnel'].'" />
                              <div id="reponseMatPer'.$per['idPersonnel'].'"></div>
                          </div> 
                          <button  class="btn btn-success" id="btnValiderAtMatToPer'.$per['idPersonnel'].'"  onclick="validerAtMatToPer('.$per['idPersonnel'].')"  >
                          <i class="glyphicon glyphicon-plus"></i>Attribuer
                          </button>
                          ' ;
                    echo  ' </td>
                           <td id="detMatToPer'.$per['idPersonnel'].'"> DETAILS BUTTON</td>
                    </tr>';    
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
}else if(isset($_POST['salle'])) {
    if ($operation=="1" || $operation=="3" || $operation=="4") {  

      $nbEntreeSal = @$_POST["nbEntreeSal"];
      $query = @$_POST["query"];
      $cb = @$_POST["cb"];
      $item_per_page 		= intval($nbEntreeSal); //item to display per page
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
        $req = $bdd->prepare("SELECT  COUNT(idSalle) as total_row from `aaa-materiel-salle` "); 
        $req->execute()  or die(print_r($req->errorInfo())); 
        
      } else {
        //get total number of records from database
        if ($cb==1) {
            $req = $bdd->prepare("SELECT  COUNT(idSalle) as total_row from `aaa-materiel-salle` "); 
            $req->execute()  or die(print_r($req->errorInfo()));    
        }else {
            
            $req = $bdd->prepare("SELECT  COUNT(idSalle) as total_row from `aaa-materiel-salle`where nomSalle LIKE '%".$query."%' "); 
            $req->execute()  or die(print_r($req->errorInfo()));
        }
      }
      //var_dump($sql);
      $total_rows = $req->fetch();
      $total_pages = ceil($total_rows[0]/$item_per_page);
      
      //position of records
        $page_position = (($page_number-1) * $item_per_page);
        
        if ($query =="") {
          $req = $bdd->prepare("SELECT  * from `aaa-materiel-salle` "); 
          $req->execute()  or die(print_r($req->errorInfo())); 
          
        } else {
          //get total number of records from database
          if ($cb==1) {
              $req = $bdd->prepare("SELECT * from `aaa-materiel-salle` "); 
              $req->execute()  or die(print_r($req->errorInfo()));    
          }else {
              
              $req = $bdd->prepare("SELECT * from `aaa-materiel-salle`where nomSalle LIKE '%".$query."%' "); 
              $req->execute()  or die(print_r($req->errorInfo()));
          }
        }
      //Display records fetched from database.
        
        echo '<table class="table table-bordered table-striped table-condensed "   border="1" >';
          echo '<thead>
                  <tr id="">
                  <th>NOM SALL</th>
                  <th>CODE salle</th>
                  <th>A </th>
                  <th> </th>

                  </tr>
                </thead>';
                $i = ($page_number - 1) * $item_per_page +1;
                $cpt = 0;
                while ($salle = $req->fetch()) {
                    echo '<tr>';
                      echo  '<td> '.$salle['nomSalle']. '</td>';
                      echo  '<td> '.$salle['codeSalle']. '</td>'; 
                      echo  '<td>';
                    
                      echo '<div class="col-sm-8"">
                                <input class="form-control" type="text" id="searchMatToSal'.$salle['idSalle'].'" onkeyup="autocompMatToSal('.$salle['idSalle'].')" />
                                <input type="hidden" id="matMatToSal'.$salle['idSalle'].'" />
                                <div id="reponseMatSal'.$salle['idSalle'].'"></div>
                            </div> 
                            <button  class="btn btn-success" id="btnValiderAtMatToSal'.$salle['idSalle'].'"  onclick="validerAtMatToSal('.$salle['idSalle'].')"  >
                            <i class="glyphicon glyphicon-plus"></i>Attribuer
                            </button>
                            ' ;
                      echo  ' </td>
                            <td id="detMatToSal'.$salle['idSalle'].'"> DETAILS BUTTON</td>';
                            echo  '<td>';
                          echo '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Salle('.$salle["idSalle"].')" id="" data-toggle="modal" data-target="#imgmodifierSall'.$salle["idSalle"].'" /></a>&nbsp;
                    
                          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="sup_Salle('.$salle["idSalle"].')"  data-toggle="modal" data-target="#imgsupSall'.$salle["idSalle"].'" /></a>';
                          echo  ' </td>
                      </tr>';    
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
    elseif($operation=="addSalle"){
      $nom=$_POST['nomSalle'];
      $codeSalle=$_POST['codeSalle'];
      $description=$_POST['description'];
    
      $req1 = $bdd->prepare("insert into `aaa-materiel-salle` (nomSalle,codeSalle,description) values(:n,:c,:d)");
      $req1->execute(array(
                                'n' =>$nom,
                                'c' =>$codeSalle ,
                                'd' =>$description
                                ))  or die(print_r($req1->errorInfo()));
    }
     elseif($operation=="recherche"){
        $idS=$_POST["s"];
        $req = $bdd->prepare("SELECT  * from `aaa-materiel-salle` where idSalle=:s "); 
        $req->execute(array('s'=>$idS))  or die(print_r($req->errorInfo())); 
            
        $sat = $req->fetch();
        $resultat= $sat['nomSalle']."<>".$sat['codeSalle']."<>".$sat['description'];
        exit ($resultat);
    }/*elseif ($operation=="modeCBMateriel") {

          $idM=$_POST["i"];
          $codeBarreMateriel=$_POST["codeBarreMateriel"];

          $req3 = $bdd->prepare("UPDATE `aaa-materiel` SET codeBarreMateriel=:c WHERE idMateriel=:i ");
          $req3->execute(array( 'c' => $codeBarreMateriel,  'i' => $idM ))  or die(print_r($req3->errorInfo()));

    }elseif ($operation=="modeCRFIDMateriel") {

        $idM=$_POST["i"];
        $codeRFID=$_POST["codeRFID"];

        $req3 = $bdd->prepare("UPDATE `aaa-materiel` SET codeRFID=:c WHERE idMateriel=:i ");
        $req3->execute(array( 'c' => $codeRFID,  'i' => $idM ))  or die(print_r($req3->errorInfo()));

    }*/elseif ($operation=="modeSalle") {

        $s=$_POST["s"];
        $nomSalleMod=$_POST["nomSalleMod"];
        $codeSalleMod=$_POST["codeSalleMod"];
        $descriptionSalleMod=$_POST["descriptionSalleMod"];

        $req3 = $bdd->prepare("UPDATE `aaa-materiel-salle` SET nomSalle=:n,codeSalle=:c,description=:d WHERE idSalle=:s ");
        $req3->execute(array( 'n' => $nomSalleMod,
                            'c' => $codeSalleMod,
                            'd' => $descriptionSalleMod,
                            's' => $s ))  or die(print_r($req3->errorInfo()));
    }elseif ($operation=="supSalle") {
        $idS=$_POST["s"];
        $req = $bdd->prepare("DELETE FROM `aaa-materiel-salle` WHERE idSalle=:i ");
        $req->execute(array('i' => $idS )) or die(print_r($req->errorInfo()));
    } 
    elseif ($operation=="attrubMatSal") {
      //exit("OK");
      $idSalle=$_POST["s"];
      $m=$_POST["m"];
      $req = $bdd->prepare("INSERT INTO `aaa-materiel-attribution`(`idMateriel`,`idSalle`)
                                                          VALUES (:m,:s)");
      $req->execute(array(
                          'm' => $m ,
                          's' => $idSalle 
                          )) or die(print_r($req->errorInfo()));
                          
                          
      $req3 = $bdd->prepare("UPDATE `aaa-materiel` SET estAttribuer=:e WHERE idMateriel=:m ");
      $req3->execute(array( 'e' => 1,
                            'm' => $m ))  or die(print_r($req3->errorInfo()));      
    }
}else if(isset($_POST['categorie'])) {
  if ($operation=="1" || $operation=="3" || $operation=="4") {  

    $nbEntreeCat = @$_POST["nbEntreeCat"];
    $query = @$_POST["query"];
    $cb = @$_POST["cb"];
    $item_per_page 		= intval($nbEntreeCat); //item to display per page
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
      $req = $bdd->prepare("SELECT  COUNT(idCategorie) as total_row from `aaa-bien-categorie` "); 
      $req->execute()  or die(print_r($req->errorInfo())); 
      
    } else {
      //get total number of records from database
      if ($cb==1) {
          $req = $bdd->prepare("SELECT  COUNT(idCategorie) as total_row from `aaa-bien-categorie` "); 
          $req->execute()  or die(print_r($req->errorInfo()));    
      }else {
          
          $req = $bdd->prepare("SELECT  COUNT(idCategorie) as total_row from `aaa-bien-categorie`where nomSalle LIKE '%".$query."%' "); 
          $req->execute()  or die(print_r($req->errorInfo()));
      }
    }
    //var_dump($sql);
    $total_rows = $req->fetch();
    $total_pages = ceil($total_rows[0]/$item_per_page);
    
    //position of records
      $page_position = (($page_number-1) * $item_per_page);
      
      if ($query =="") {
        $req = $bdd->prepare("SELECT  * from `aaa-bien-categorie` "); 
        $req->execute()  or die(print_r($req->errorInfo())); 
        
      } else {
        //get total number of records from database
        if ($cb==1) {
            $req = $bdd->prepare("SELECT * from `aaa-bien-categorie` "); 
            $req->execute()  or die(print_r($req->errorInfo()));    
        }else {
            
            $req = $bdd->prepare("SELECT * from `bien-categorie`where nomSalle LIKE '%".$query."%' "); 
            $req->execute()  or die(print_r($req->errorInfo()));
        }
      }
    //Display records fetched from database.
      
      echo '<table class="table table-bordered table-striped table-condensed "   border="1" >';
        echo '<thead>
                <tr id="">
                <th>NOM SALL</th>
                <th>CODE salle</th>
                <th>A </th>
                <th> </th>

                </tr>
              </thead>';
              $i = ($page_number - 1) * $item_per_page +1;
              $cpt = 0;
              while ($cat = $req->fetch()) {
                  echo '<tr>';
                    echo  '<td> '.$cat['nomCategorie']. '</td>';
                    echo  '<td> '.$cat['codeCategorie']. '</td>';  
                    echo  '<td>';
                        echo '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_cat('.$cat["idCategorie"].')" id="" data-toggle="modal" data-target="#imgmodifierSall'.$cat["idCategorie"].'" /></a>&nbsp;
                  
                        <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="sup_cat('.$cat["idCategorie"].')"  data-toggle="modal" data-target="#imgsupSall'.$cat["idCategorie"].'" /></a>';
                        echo  ' </td>
                    </tr>';    
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
  }elseif($operation=="addCategorie"){
    $nom=$_POST['nomCategorie'];
    $codeCategorie=$_POST['codeCategorie'];
  
    $req1 = $bdd->prepare("insert into `aaa-bien-categorie` (nomCategorie,codeCategorie) values(:n,:c)");
    $req1->execute(array(
                              'n' =>$nom,
                              'c' =>$codeCategorie 
                              ))  or die(print_r($req1->errorInfo()));
  }elseif($operation=="recherche"){
    $idC=$_POST["i"];
    $req = $bdd->prepare("SELECT  * from `aaa-bien-categorie` where idCategorie=:c "); 
    $req->execute(array('c'=>$idC))  or die(print_r($req->errorInfo())); 
        
    $sat = $req->fetch();
    $resultat= $sat['nomCategorie']."<>".$sat['codeCategorie'];
    exit ($resultat);
  }elseif ($operation=="modeCategorie") { 
    $i=$_POST["i"];
    $nomCategorieMod=$_POST["nomCategorieMod"];
    $codeCategorieMod=$_POST["codeCategorieMod"]; 

    $req3 = $bdd->prepare("UPDATE `aaa-bien-categorie` SET nomCategorie=:n,codeCategorie=:c WHERE idCategorie=:i ");
    $req3->execute(array( 'n' => $nomCategorieMod,
                        'c' => $codeCategorieMod, 
                        'i' => $i ))  or die(print_r($req3->errorInfo()));
  }elseif ($operation=="supCategorie") {
      $id=$_POST["i"];
      $req = $bdd->prepare("DELETE FROM `aaa-bien-categorie` WHERE idCategorie=:i ");
      $req->execute(array('i' => $id )) or die(print_r($req->errorInfo()));
  } 
}elseif (isset($_POST['instance'])) {
  if ($operation=="addInstance") {
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
}




?>