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


require('../connection.php');
require('../connectionPDO.php');
require('../declarationVariables.php');

$onglet=@htmlspecialchars($_POST["onglet"]);
$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);
$cpt=0;
if ($operation=="chercheBoutique") {
  $id = @$_POST["i"];
  $req = $bdd->prepare("SELECT * FROM `aaa-boutique` WHERE idBoutique=:i"); 
  $req->execute(array('i' =>$id))  or die(print_r($req->errorInfo())); 
  $boutique=$req->fetch();

  $result=$boutique['nomBoutique'].'<>'.$boutique['montantFixeHorsParametre'].'<>'.$boutique['adresseBoutique'].
        '<>'.$boutique['type'].'<>'.$boutique['categorie'].'<>'.$boutique['labelB'];
  exit($result);
   
}
if ($onglet=='listeUser') {
    if ($operation=="1" || $operation=="3" || $operation=="4") {  

      $nbEntreeUB = @$_POST["nbEntreeUB"];
      $query = @$_POST["query"];
      $cb = @$_POST["cb"];
      $item_per_page 		= intval($nbEntreeUB); //item to display per page
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
        $req = $bdd->prepare("SELECT  COUNT(DISTINCT u.idutilisateur) as total_row from `aaa-utilisateur` u 
            INNER JOIN `aaa-acces` a ON a.idutilisateur =u.idutilisateur
            where a.idBoutique != :ib  and (proprietaire=:p or gerant=:g or caissier=:c or vendeur=:v)"); 
        $req->execute(array('ib' =>0, 'p' =>1, 'g' =>1, 'c' =>1, 'v' =>1 ))  or die(print_r($req->errorInfo())); 
        
      } else {
        //get total number of records from database
        if ($cb==1) {
          $req = $bdd->prepare("SELECT  COUNT(DISTINCT u.idutilisateur) as total_row from `aaa-utilisateur` u 
            INNER JOIN `aaa-acces` a ON a.idutilisateur =u.idutilisateur
            where ( u.prenom LIKE '%".$query."%' OR u.email LIKE '%".$query."%') and (a.idBoutique != :ib) and (proprietaire=:p or gerant=:g or caissier=:c or vendeur=:v)"); 
          $req->execute(array('ib' =>0, 'p' =>1, 'g' =>1, 'c' =>1, 'v' =>1 ))  or die(print_r($req->errorInfo()));     
        }else {
            $req = $bdd->prepare("SELECT  COUNT(DISTINCT u.idutilisateur) as total_row from `aaa-utilisateur` u 
            INNER JOIN `aaa-acces` a ON a.idutilisateur =u.idutilisateur
            where ( u.prenom LIKE '%".$query."%' OR u.email LIKE '%".$query."%') and (a.idBoutique != :ib ) and (proprietaire=:p or gerant=:g or caissier=:c or vendeur=:v)"); 
            $req->execute(array('ib' =>0, 'p' =>1, 'g' =>1, 'c' =>1, 'v' =>1 ))  or die(print_r($req->errorInfo())); 
            
        }
      }
      //var_dump($sql);
      $total_rows = $req->fetch();
      $total_pages = ceil($total_rows[0]/$item_per_page);
      
      //position of records
      $page_position = (($page_number-1) * $item_per_page);
      
      if ($query =="") {
        $req = $bdd->prepare("SELECT  DISTINCT u.idutilisateur from `aaa-utilisateur` u 
            INNER JOIN `aaa-acces` a ON a.idutilisateur =u.idutilisateur where a.idBoutique != :ib  and (proprietaire=:p or gerant=:g or caissier=:c or vendeur=:v)
              LIMIT $page_position ,$item_per_page"); 
        $req->execute(array('ib' =>0, 'p' =>1, 'g' =>1, 'c' =>1, 'v' =>1))  or die(print_r($req->errorInfo())); 
    
      } else {
        //Limit our results within a specified range. 
        if ($cb==1) {
        $req = $bdd->prepare("SELECT DISTINCT u.idutilisateur from `aaa-utilisateur` u 
            INNER JOIN `aaa-acces` a ON a.idutilisateur =u.idutilisateur
            where ( u.prenom LIKE '%".$query."%' OR u.email LIKE '%".$query."%') and (a.idBoutique != :ib) and (proprietaire=:p or gerant=:g or caissier=:c or vendeur=:v)  LIMIT $page_position ,$item_per_page"); 
        $req->execute(array('ib' =>0, 'p' =>1, 'g' =>1, 'c' =>1, 'v' =>1))  or die(print_r($req->errorInfo()));     
    
        } else {
            $req = $bdd->prepare("SELECT DISTINCT u.idutilisateur from `aaa-utilisateur` u 
            INNER JOIN `aaa-acces` a ON a.idutilisateur =u.idutilisateur
            where  ( u.prenom LIKE '%".$query."%' OR u.email LIKE '%".$query."%') and (a.idBoutique != :ib) and (proprietaire=:p or gerant=:g or caissier=:c or vendeur=:v)  LIMIT $page_position ,$item_per_page"); 
            $req->execute(array('ib' =>0, 'p' =>1, 'g' =>1, 'c' =>1, 'v' =>1))  or die(print_r($req->errorInfo())); 
            
        }
      }
      //Display records fetched from database.
      echo '<table class="table table-bordered table-striped table-condensed " id="exemple5" border="1" >';
      echo '<thead>
              <tr id="">
              <th>Prénom</th>
              <th>Nom</th>
              <th>Email</th>
              <th> </th>
              </tr>
            </thead>';
      $i = ($page_number - 1) * $item_per_page +1;
      $cpt = 0;
      while($utilisateur=$req->fetch()){ 
        $req1 = $bdd->prepare("SELECT * from `aaa-utilisateur` where idutilisateur =:u");                                                
        $req1->execute(array('u' =>$utilisateur['idutilisateur'])) or die(print_r($req1->errorInfo()));      
        while ($user = $req1->fetch()) {
          echo '<tr>';
            echo  '<td> '.$user['prenom']. '</td>';
            echo  '<td> '.$user['nom']. '</td>';
            echo  '<td> '.$user['email']. '</td>';
            echo  '<td>
                      <button class="btn btn-primary" data-toggle="modal" data-target="#detailAllU'.$user['idutilisateur'].'" >
                                  Detail 
                              </button>
                              <button class="btn btn-warning" data-toggle="modal" data-target="#reitAllU'.$user['idutilisateur'].'" >Password</button>
                              
                              <div class="modal fade bd-example-modal-lg" id="detailAllU'.$user['idutilisateur'].'" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title" id="myModalLabel">Details User </h4>
                                    </div>
                                    <div class="modal-body">
                                      <div class="form-group">
                                        <h2>Listes des boutiques pour l\'utilisateurs '.$user['prenom'] .'" </h2>																
                                        
                                              <form action="" method="post" id="formulaireInfo'.$user['idutilisateur'] .'">
                                                <div class="form-group col-sm-5">
                                                  <label>Boutique à lier</label>
                                                  <input type="text"  class="form-control" name="query" id="searchBoutAcces'.$user['idutilisateur'].'" onkeyup="autocompB('.$user['idutilisateur'].')" class="form-control" placeholder="Nom de la boutique à ajouter" autocomplet="off" >
                                                  <div id="reponseS'.$user['idutilisateur'].'"></div>
                                                </div>
                                                <br><br>
                                                  
                                              </form>	
                                            
                                          <table  class="table  table-bordered table-striped table-condensed" border="1" class="table table-bordered table-striped" id="tabBoutiqueByUser'.$user['idutilisateur'].'">
                                                <tr>
                                                  <th>Nom boutique</th>
                                                  <th>Propriétaire</th>
                                                  <th>Gérant</th>
                                                  <th>Caissier</th>
                                                  <th>Vendeur</th>
                                                  <th></th>
                                                  <th></th>
                                                </tr>';
                                                  $req2 = $bdd->prepare("SELECT * from `aaa-boutique` b INNER JOIN `aaa-acces` a ON a.idBoutique =b.idBoutique where a.idutilisateur=:i");                                                
                                                  $req2->execute(array('i' =>$user['idutilisateur'])) or die(print_r($req2->errorInfo()));  
                                                    while ($boutique = $req2->fetch()) { 
                                                      echo '<tr>  <td> '. $boutique['labelB'].'</td> ';
                                                        if ($boutique['proprietaire']) echo '<td><b>OUI</b></td>'; else echo '<td> NON  </td>'; 
                                                        if ($boutique['gerant']) echo '<td><b>OUI</b></td>'; else echo '<td> NON </td>';
                                                        if ($boutique['caissier']) echo '<td><b>OUI</b></td>'; else echo '<td> NON </td>';
                                                        if ($boutique['vendeur']) echo '<td><b>OUI</b></td>'; else echo '<td> NON </td>';
                                                        echo	'<td> </td>
                                                          <td>	
                                                              <button class="btn btn-danger" id="deleterowBtn'.$boutique['idBoutique'].'
                                                                onclick="supAccesUser('.$boutique["idBoutique"].' ,'.$user["idutilisateur"].' )">Retirer</button>
                                                          </td>
                                                      </tr>';
                                                    }
                                        echo	'</table>
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="modal fade " id="reitAllU'.$user['idutilisateur'].'"  tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title" id="myModalLabel">Reinitialiser mot de passe </h4>
                                    </div>
                                    <div class="modal-body">
                                      <div class="form-group">
                                        <h2>Voulez-vous vraiment reinitialiser le mot de passe de "'.$user['prenom'] .'" </h2>																
                                          <form action="boutiques.php" method="POST">
                                            <div id="formReini"'.$user['idutilisateur'].'" >
                                              <input type="hidden" name="id" value="'.$user['idutilisateur'].'">
                                              <input type="submit" name="btnReinit"  class="btn btn-success"  value="Ré-initialiser mots de passe">
                                            </div>
                                          </form>     	
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                    </td>
            </tr>';    
          $i++;
          $cpt++;
      }
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
 
}elseif ($onglet=='parametre') {
    
}
if (isset($_POST["details"])) {
  $idBoutique=$_POST["details"];
  if ($operation=="1" || $operation=="3" || $operation=="4") {  

    $nbEntreeDet = @$_POST["nbEntreeDet"];
    $query = @$_POST["query"];
    $cb = @$_POST["cb"];
    $item_per_page 		= intval($nbEntreeDet); //item to display per page
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
      $req = $bdd->prepare("SELECT  COUNT(DISTINCT u.idutilisateur) as total_row from `aaa-utilisateur` u 
          INNER JOIN `aaa-acces` a ON a.idutilisateur =u.idutilisateur
          where a.idBoutique != :ib  and (proprietaire=:p or gerant=:g or caissier=:c or vendeur=:v)"); 
      $req->execute(array('ib' =>0, 'p' =>1, 'g' =>1, 'c' =>1, 'v' =>1 ))  or die(print_r($req->errorInfo())); 
      
    } else {
      //get total number of records from database
      if ($cb==1) {
        $req = $bdd->prepare("SELECT  COUNT(DISTINCT u.idutilisateur) as total_row from `aaa-utilisateur` u 
          INNER JOIN `aaa-acces` a ON a.idutilisateur =u.idutilisateur
          where (a.idBoutique != :ib and u.prenom=:q) and (proprietaire=:p or gerant=:g or caissier=:c or vendeur=:v)"); 
        $req->execute(array('ib' =>0, 'q' =>$query, 'p' =>1, 'g' =>1, 'c' =>1, 'v' =>1 ))  or die(print_r($req->errorInfo()));     
      }else {
          $req = $bdd->prepare("SELECT  COUNT(DISTINCT u.idutilisateur) as total_row from `aaa-utilisateur` u 
          INNER JOIN `aaa-acces` a ON a.idutilisateur =u.idutilisateur
          where (a.idBoutique != :ib and u.prenom=:q) and (proprietaire=:p or gerant=:g or caissier=:c or vendeur=:v)"); 
          $req->execute(array('ib' =>0, 'q' =>$query, 'p' =>1, 'g' =>1, 'c' =>1, 'v' =>1 ))  or die(print_r($req->errorInfo())); 
          
      }
    }
    //var_dump($sql);
    $total_rows = $req->fetch();
    $total_pages = ceil($total_rows[0]/$item_per_page);
    
    //position of records
    $page_position = (($page_number-1) * $item_per_page);
    
    if ($query =="") {
      $req = $bdd->prepare("SELECT  DISTINCT u.idutilisateur from `aaa-utilisateur` u 
          INNER JOIN `aaa-acces` a ON a.idutilisateur =u.idutilisateur where a.idBoutique != :ib  and (proprietaire=:p or gerant=:g or caissier=:c or vendeur=:v)
            LIMIT $page_position ,$item_per_page"); 
      $req->execute(array('ib' =>0, 'p' =>1, 'g' =>1, 'c' =>1, 'v' =>1))  or die(print_r($req->errorInfo())); 
  
    } else {
      //Limit our results within a specified range. 
      if ($cb==1) {
      $req = $bdd->prepare("SELECT DISTINCT u.idutilisateur from `aaa-utilisateur` u 
          INNER JOIN `aaa-acces` a ON a.idutilisateur =u.idutilisateur
          where (a.idBoutique != :ib and u.prenom=:q) and (proprietaire=:p or gerant=:g or caissier=:c or vendeur=:v)  LIMIT $page_position ,$item_per_page"); 
      $req->execute(array('ib' =>0, 'q' =>$query, 'p' =>1, 'g' =>1, 'c' =>1, 'v' =>1))  or die(print_r($req->errorInfo()));     
  
      } else {
          $req = $bdd->prepare("SELECT DISTINCT u.idutilisateur from `aaa-utilisateur` u 
          INNER JOIN `aaa-acces` a ON a.idutilisateur =u.idutilisateur
          where (a.idBoutique != :ib and u.prenom=:q) and (proprietaire=:p or gerant=:g or caissier=:c or vendeur=:v)  LIMIT $page_position ,$item_per_page"); 
          $req->execute(array('ib' =>0, 'q' =>$query, 'p' =>1, 'g' =>1, 'c' =>1, 'v' =>1))  or die(print_r($req->errorInfo())); 
          
      }
    }
    //Display records fetched from database.
    echo '<table class="table table-striped contents display tabDesign tableau3"  border="1">';
    echo '<thead>
            <tr id="">
            <th>Prénom </th>
            <th>Nom</th>
            <th>Email</th>
            <th> </th>
            </tr>
          </thead>';
    $i = ($page_number - 1) * $item_per_page +1;
    $cpt = 0;
    while($utilisateur=$req->fetch()){ 
      $req1 = $bdd->prepare("SELECT * from `aaa-utilisateur` where idutilisateur =:u");                                                
      $req1->execute(array('u' =>$utilisateur['idutilisateur'])) or die(print_r($req1->errorInfo()));      
      while ($user = $req1->fetch()) {
        echo '<tr>';
          echo  '<td> '.$user['prenom']. '</td>';
          echo  '<td> '.$user['nom']. '</td>';
          echo  '<td> '.$user['email']. '</td>';
          $td1=  '<td>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#detailU"'.$user['idutilisateur'].'" >
                                Detail
                            </button>
                            <button class="btn btn-warning" data-toggle="modal" data-target="#reitU"'.$user['idutilisateur'].'" >Password</button>
                            
                            <div class="modal fade bd-example-modal-lg" id="detailU"'.$user['idutilisateur'].'" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Details User </h4>
                                  </div>
                                  <div class="modal-body">
                                    <div class="form-group">
                                      <h2>Listes des boutiques pour l\'utilisateurs "'.$user['prenom'] .'" </h2>																
                                      
                                            <form action="" method="post" id="formulaireInfo"'.$user['idutilisateur'] .'">
                                              <div class="form-group col-sm-5">
                                                <label>Boutique à lier</label>
                                                <input type="text"  class="form-control" name="query" id="searchBoutAcces"'.$user['idutilisateur'].'" onkeyup="autocompB("'.$user['idutilisateur'].'")" class="form-control" placeholder="Nom de la boutique à ajouter" autocomplet="off" >
                                                <div id="reponseS"'.$user['idutilisateur'].'"></div>
                                              </div>
                                              <br><br>
                                                
                                            </form>	
                                          
                                        <table  class="table  table-bordered table-striped table-condensed" border="1" class="table table-bordered table-striped" id="tabBoutiqueByUser"'.$user['idutilisateur'].'">
                                              <tr>
                                                <th>Nom boutique</th>
                                                <th>Propriétaire</th>
                                                <th>Gérant</th>
                                                <th>Caissier</th>
                                                <th>Vendeur</th>
                                                <th></th>
                                                <th></th>
                                              </tr>';
                                                $req2 = $bdd->prepare("SELECT * from `aaa-boutique` b INNER JOIN `aaa-acces` a ON a.idBoutique =b.idBoutique where a.idutilisateur=:i");                                                
                                                $req2->execute(array('i' =>$user['idutilisateur'])) or die(print_r($req2->errorInfo()));  
                                                  while ($boutique = $req2->fetch()) { 
                                                    $td1=$td1.'<tr>  <td> "'. $boutique['labelB'].'"</td> ';
                                                      if ($boutique['proprietaire']) $td1=$td1. '<td><b>OUI</b></td>'; else $td1=$td1. '<td> NON  </td>'; 
                                                      if ($boutique['gerant']) $td1=$td1. '<td><b>OUI</b></td>'; else $td1=$td1. '<td> NON </td>';
                                                      if ($boutique['caissier']) $td1=$td1. '<td><b>OUI</b></td>'; else $td1=$td1. '<td> NON </td>';
                                                      if ($boutique['vendeur']) $td1=$td1. '<td><b>OUI</b></td>'; else $td1=$td1. '<td> NON </td>';
                                                      echo $td1;
                                                      echo	'<td> </td>
                                                        <td>	
                                                            <button class="btn btn-danger" id="deleterowBtn"'.$boutique['idBoutique'].'" 
                                                              onclick="supAccesUser("'.$boutique["idBoutique"].'" ,"'.$user["idutilisateur"].'" )">Retirer</button>
                                                        </td>
                                                    </tr>';
                                                  }
                                      echo	'</table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="modal fade " id="reitU"'.$user['idutilisateur'].'"  tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Reinitialiser mot de passe </h4>
                                  </div>
                                  <div class="modal-body">
                                    <div class="form-group">
                                      <h2>Voulez-vous vraiment reinitialiser le mot de passe de "'.$user['prenom'] .'" </h2>																
                                        <form action="boutiques.php" method="POST">
                                          <div id="formReini"'.$user['idutilisateur'].'" >
                                            <input type="hidden" name="id" value="'.$user['idutilisateur'].'">
                                            <input type="submit" name="btnReinit"  class="btn btn-success"  value="Ré-initialiser mots de passe">
                                          </div>
                                        </form>     	
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                  </td>
          </tr>';    
        echo '<tr>';
        $i++;
        $cpt++;
    }
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