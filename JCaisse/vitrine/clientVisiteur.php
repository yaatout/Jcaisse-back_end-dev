<?php
session_start();

if(!$_SESSION['iduser']){
  header('Location:../../index.php');
}

if($_SESSION['vitrine']==0){
	header('Location:../accueil.php');
}

require('../connection.php');
require('../connectionVitrine.php');

require('../declarationVariables.php');
$doublons=NULL;
/**********************/


    require('../entetehtmlVitrine.php');
    echo'
       <body >';
       require('../header.php'); ?>

   <div class="container" align="center">
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#LISTECLIENTS">LISTE DES CLIENTS </a></li>
              <li  ><a data-toggle="tab" href="#LISTEINVITES">LISTE DES INVITES </a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="LISTECLIENTS">
                    <table id="exemple" class="display" border="1" class="table table-bordered table-striped table-condensed">
                      <thead>
                        <tr>
                          <th>Prénom</th>
                          <th>Nom</th>
                          <th>Ville</th>
                          <th>Adresse</th>
                          <th>Numéro téléphone</th>
                          <th>Email</th>
                          <th>Opération</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>Prénom</th>
                          <th>Nom</th>
                          <th>Ville</th>
                          <th>Adresse</th>
                          <th>Numéro téléphone</th>
                          <th>Email</th>
                          <th>Opération</th>
                        </tr>
                      </tfoot>
                      <tbody>
                          <?php
                                $req1 = $bddV->prepare("SELECT * FROM panier p INNER JOIN ligne l ON l.idPanier = p.idPanier
                                                        WHERE l.idBoutique = :idBoutique ORDER BY p.idPanier ");
                                $req1->execute(array('idBoutique' =>$_SESSION['idBoutique'])) or die(print_r($req1->errorInfo()));

                                while ($client=$req1->fetch()) {
                                    $doublons[] =$client['idClient']; }
                                    $tab=array_unique ($doublons);
                                    //var_dump($tab);
                                    foreach($tab as $c)
                                      {
                                        $req2 = $bddV->prepare("SELECT * FROM client
                                                        WHERE idClient = :idClient ");
                                        $req2->execute(array('idClient' =>$c)) or die(print_r($req2->errorInfo()));

                                        while ($client=$req2->fetch()) { ?>
                                           <tr>
                                              <td> <?php echo  $client['prenom']; ?>  </td>
                                              <td> <?php echo  $client['nom']; ?>  </td>
                                              <td> <?php echo  $client['ville']; ?>  </td>
                                              <td> <?php echo  $client['adresse']; ?>  </td>
                                              <td> <?php echo  $client['telephone']; ?>  </td>
                                              <td> <?php echo  $client['email']; ?>  </td>
                                              <td> <a   <?php echo  "href=vitrine/commandeDetail.php?c=".$client['idClient'] ; ?> > Details</a> </td>
                                          </tr>
                                          <?php }
                                      }

                                  ?>


                      </tbody>
                    </table>
                </div>
                <div class="tab-pane fade " id="LISTEINVITES">
                    <table id="exemple2" class="display" border="1" class="table table-bordered table-striped table-condensed">
                      <thead>
                        <tr>
                          <th>Nom complet</th>
                          <th>Numéro téléphone</th>
                          <th>Opération</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>Nom complet</th>
                          <th>Numéro téléphone</th>
                          <th>Opération</th>
                        </tr>
                      </tfoot>
                      <tbody>
                          <?php
                                $req3 = $bddV->prepare("SELECT * FROM panier p INNER JOIN ligne l ON l.idPanier = p.idPanier
                                                        WHERE l.idBoutique = :idBoutique ORDER BY p.idPanier ");
                                $req3->execute(array('idBoutique' =>$_SESSION['idBoutique'])) or die(print_r($req3->errorInfo()));

                                while ($guest=$req3->fetch()) {
                                    $doublons[] =$guest['idGuest']; }
                                    $tab=array_unique ($doublons);
                                    //var_dump($tab);
                                    foreach($tab as $g)
                                      {
                                        $req4 = $bddV->prepare("SELECT * FROM guest
                                                        WHERE idGuest = :idGuest ");
                                        $req4->execute(array('idGuest' =>$g)) or die(print_r($req4->errorInfo()));

                                        while ($guest=$req4->fetch()) { ?>
                                           <tr>
                                              <td> <?php echo  $guest['nomComplet']; ?>  </td>
                                              <td> <?php echo  $guest['telephone']; ?>  </td>
                                              <td> <a   <?php echo  "href=vitrine/commandeDetail.php?g=".$guest['idGuest'] ; ?> > Details</a> </td>
                                          </tr>
                                          <?php }
                                      }

                                  ?>


                      </tbody>
                    </table>
                </div>
          </div>
        </div>
  </div>


<?php
    echo'</body></html>';

?>
