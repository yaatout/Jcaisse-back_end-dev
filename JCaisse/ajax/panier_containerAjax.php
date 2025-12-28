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


  $sql="SELECT * FROM `".$nomtableDesignation."` WHERE (classe=1 || classe=0) and categorie='".$nomCategorie."'";
    
  $statement = $bdd->prepare($sql);
  $statement->execute();
  $services = $statement->fetchAll(PDO::FETCH_ASSOC);
  

function find_p_with_position0($pns,$des) {
  foreach($pns as $index => $p) {
      if(($p['idDesignation'] == $des)){
          return $index;
      }
  } 
  return FALSE;
}


  $sqlCpt="SELECT * FROM `".$nomtableCompte."` where idCompte<>2 and idCompte<>3";
    
  $statementCpt = $bdd->prepare($sqlCpt);
  $statementCpt->execute();
  $comptes = $statementCpt->fetchAll(PDO::FETCH_ASSOC);
?>

  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">Produit</th>
        <th scope="col">Quantité</th>
        <th scope="col">Unité</th>
        <th scope="col">Prix (FCFA)</th>
        <th scope="col">Sous-total (FCFA)</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      <?php 
        $total = 0;
        foreach ($_SESSION['panier'] as $ligne) {

          $total += $ligne['prix']*$ligne['quantite'];

      ?>
        <tr>
          <input type="hidden" id="prixInput-<?= $ligne['idDesignation'] ?>" value="<?= $ligne['prix'] ?>">
          <input type="hidden" id="qtyInput-<?= $ligne['idDesignation'] ?>" value="<?= $ligne['quantite'] ?>">

          <th scope="row"><?= $ligne['designation'] ?></th>
          <td id="qtyList-<?= $ligne['idDesignation'] ?>"><?= $ligne['quantite'] ?></td>
          <td><?= $ligne['unite'] ?></td>
          <td><?= number_format($ligne['prix'], 0, '.', ' ') ?></td>
          <td id="soustotal-<?= $ligne['idDesignation'] ?>"><?= number_format($ligne['quantite']*$ligne['prix'], 0, '.', ' ') ?></td>
          <td>
            <button type="button" class="btn btn-default" id="minus-<?= $ligne['idDesignation'] ?>" onClick="addProductInCart(<?= $ligne['idDesignation'] ?>, -1)"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
            <button type="button" class="btn btn-default" id="plus-<?= $ligne['idDesignation'] ?>" onClick="addProductInCart(<?= $ligne['idDesignation'] ?>, 1)"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
            <button type="button" class="btn btn-danger" id="delete-<?= $ligne['idDesignation'] ?>" onClick="deleteProductInCart(<?= $ligne['idDesignation'] ?>)"><i class="fa fa-trash" aria-hidden="true"></i></button>
          </td>
        </tr>
      <?php
        }
      ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="6"></td>
      </tr>
      <?php
        if ($_SESSION['compte'] == 1) {
          
      ?>
      <tr>
        <td colspan="6">
          <label for="moyenDePaiement"> Moyen de paiement : </label>
          <select style="width: 15%;" class="form-control" name="" id="moyenDePaiement">
            <?php 
              foreach ($comptes as $cpt) {
            ?>                      
              <option value="<?= $cpt['idCompte'] ?>"><?= $cpt['nomCompte'] ?></option>
            <?php 
              }
            ?>
          </select>
        </td>
      </tr>
      <?php
        }
      ?>
      <tr style="background-color:lightgray">
        <th colspan="4"> Total panier</th>
        <input type="hidden" id="totalPanierInput" value="<?= $total ?>">
        <th colspan="4"> <span id="totalPanier"><?= number_format($total, 0, '.', ' ') ?></span> FCFA</th>
      </tr>                
    </tfoot>
  </table>
