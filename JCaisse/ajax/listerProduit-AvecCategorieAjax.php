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

$sql0="select * from `aaa-utilisateur` where idutilisateur = ".$_SESSION['iduser'];
$res0=mysql_query($sql0);
$user=mysql_fetch_array($res0);

$sql="select * from `".$nomtableDesignation."` where classe=0 and (categorie!='sans categorie' && categorie!='') order by idDesignation";
$res=mysql_query($sql);

$data=array();
$i=1;
while($design=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableCategorie."` where nomcategorie='".$design['categorie']."' ";
  $res1=mysql_query($sql1);
  $categorie=mysql_fetch_array($res1);

  $sql2="SELECT * FROM `".$nomtableCategorie."` where idcategorie='".$categorie['categorieParent']."' ";
  $res2=mysql_query($sql2);
  $parent=mysql_fetch_array($res2);

  $rows = array();
    $rows[] = $i;
    // $rows[] = '<input class="form-control" id="designation-'.$design['idDesignation'].'" value= "'.$design['designation'].'" /></input>';
    // $rows[] = '<input class="form-control categorieSearch" id="categorie-'.$design['idDesignation'].'" autocomplete="off" placeholder="Recherche Catégories..."/></input>';
    $rows[] = '<span id="designation-'.$design['idDesignation'].'">'.$design['designation'].'</span>';
    // $rows[] = '<span id="categorieActu-'.$design['idDesignation'].'">'.$design['categorie'].'</span>';
    $rows[] = '<span id="categorieActu2-'.$design['idDesignation'].'">'.$design['categorie'].'</span>';
    
    if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) {
      
      $sql3="select * from `aaa-utilisateur` where idutilisateur = ".$design['idUser'];
      $res3=mysql_query($sql3);
      $user=mysql_fetch_array($res3);
      // var_dump($user);

      if (empty($user)) {
        # code...
      
        $rows[] = '<span id="username-'.$design['idDesignation'].'">Utilisateur de test</span>';

        $rows[] = '<span id="usertel-'.$design['idDesignation'].'">--------</span>';
      } else {
        # code...
      
        $rows[] = '<span id="username-'.$design['idDesignation'].'">'.$user['prenom'].' - '.$user['nom'].'</span>';

        $rows[] = '<span id="usertel-'.$design['idDesignation'].'">'.$user['telPortable'].'</span>';
      }

    }
    // $rows[] = '<input class="form-control" id="codeBarre-'.$design['idDesignation'].'" value= "'.$design['codeBarreDesignation'].'" /></input>';
    // $rows[] = '<select class="form-control categorieSelection" onmouseover="selectionCategorie('.$design['idDesignation'].')"  onchange="choisirCategorie('.$design['idDesignation'].')" id="categorie-'.$design['idDesignation'].'"   >
    //             <option selected value= "'.$parent["idcategorie"].'">'.$parent["nomcategorie"].'</option>
    //           </select> ';
    // $rows[] = '<select class="form-control" id="sousCategorie-'.$design['idDesignation'].'" >
    //         <option selected value= "'.$categorie["idcategorie"].'">'.$categorie["nomcategorie"].'</option>
    //       </select> ';
    // $rows[] = '<span>'.strtoupper($design['uniteStock']).'</span>';
    // $rows[] = '<span>'.$design['nbreArticleUniteStock'].'</span>';
    // $rows[] = '<input class="form-control" id="prixUS-'.$design['idDesignation'].'" value= "'.$design["prixuniteStock"].'" /></input>';	
    // $rows[] = '<input class="form-control" id="prixUN-'.$design['idDesignation'].'" value= "'.$design["prix"].'" /></input>';	
    // $rows[] = '<input class="form-control" id="prixAC-'.$design['idDesignation'].'" value= "'.$design["prixachat"].'" /></input>';

  if($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1 || $_SESSION['gestionnaire']==1){
    $rows[] = '<button type="button" id="btn_ChgCateg-'.$design['idDesignation'].'" class="btn btn-success btn-sm disabled terminer_categorie">
      <i class="glyphicon glyphicon-ok"></i></button>
      <button type="button" id="btn_MdfCateg-'.$design['idDesignation'].'" class="btn btn-primary btn-sm mdf_categorie">
      <i class="glyphicon glyphicon-edit"></i> Modifier</button>
      <button type="button" id="btn_RefreshCateg-'.$design['idDesignation'].'" class="btn btn-danger btn-sm refaire_categorie">
      <i class="glyphicon glyphicon-refresh"></i> Refaire</button>';
  } else {
    $rows[] = '<button type="button" id="btn_ChgCateg-'.$design['idDesignation'].'" class="btn btn-success btn-sm disabled terminer_categorie">
      <i class="glyphicon glyphicon-ok"></i></button>
      <button type="button" id="btn_MdfCateg-'.$design['idDesignation'].'" class="btn btn-primary btn-sm mdf_categorie">
      <i class="glyphicon glyphicon-edit"></i> Modifier</button>';
  }
  

  $data[] = $rows;
  $i=$i + 1;
}

 
$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);



?>
