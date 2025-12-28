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
require('../connectionPDO.php');

require('../declarationVariables.php');

$idCategorie=@htmlspecialchars($_POST["idCategorie"]);
$nomCategorie=@htmlspecialchars($_POST["nomCategorie"]);


  $sql="SELECT * FROM `".$nomtableDesignation."` WHERE (classe=1 || classe=0) and categorie='".$nomCategorie."' ORDER BY designation";
    
  $statement = $bdd->prepare($sql);
  $statement->execute();
  $produits = $statement->fetchAll(PDO::FETCH_ASSOC);
  

function find_p_with_position0($pns,$des) {
  foreach($pns as $index => $p) {
      if(($p['idDesignation'] == $des)){
          return $index;
      }
  } 
  return FALSE;
}
?>
<br>
    <br>
      <div class="container">
        <?php
          foreach ($produits as $key) {
            // if ($key['idDesignation']==156) {
            //   var_dump($key['image']);
            //   if (file_exists("../uploads/".$key['image'])) {
            //      var_dump('true');
            //   }else {
            //      var_dump('false');
            //   }
            // }
            $qty=0;
            if (@find_p_with_position0(@$_SESSION['panier'], $key['idDesignation']) !==FALSE) {
        
              $i=@find_p_with_position0(@$_SESSION['panier'], $key['idDesignation']);

              $qty=@$_SESSION['panier'][$i]['quantite'];
            }
        ?>
          <div class="col-sm-6 col-md-3 col-lg-3">
          
            <div class="cardProd">
              <span <?= ($qty==0) ? 'style="display:none"' : '' ; ?> class="badge badge-light notify-badge nbByArticle" id="nbByArticle<?= $key['idDesignation'] ?>"><?= $qty ?></span>

              <div class="image">

                <?php  if (file_exists("../uploads/".$key['image'])) { ?> 
                    <img class="card-img-prod" src="./uploads/<?= ($key['image']) ? $key['image'] : 'defaultImg.png' ; ?>" width="90%" height="150" alt="<?= $key['designation'] ?>" onClick="addProductInCart(<?= $key['idDesignation'] ?>, 1)">

                <?php } else { ?>

                    <img class="card-img-prod" src="./uploads/defaultImg.png" width="90%" height="150" onClick="addProductInCart(<?= $key['idDesignation'] ?>, 1)">

                <?php } ?>
              </div>
              <div class="card-inner">
                <div class="header">
                  <h5 style="margin-bottom:5px;" title="<?= strtoupper($key['designation']) ?>"><?= strtoupper($key['designation']) ?></h5>
                </div>
              </div>
            </div>
          </div>
        <?php
          }
        ?>
      </div>
      
