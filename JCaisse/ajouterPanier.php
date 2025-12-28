<?php
  session_start();

  if ($_POST['operation']=="ajouterPanier") {
        if (isset($_SESSION['panier'])) {
          $panier=$_SESSION['panier'];
        } else {
          $panier= array();
        }
        $index=count($panier);

        //**************************************************
        function find_p_with_position($pns,$des) {
          foreach($pns as $index => $p) {
              if($p['designation'] == $des) return $index;
          }
          return FALSE;
        }
        //**************************************************
        if (find_p_with_position($panier, $_POST['designation']) !==FALSE) {
          $i=find_p_with_position($panier, $_POST['designation']);
          //die('ggg'.$i);
          $qt=$panier[$i]['quantite'];
          $qt++;
          $panier[$i]['quantite']=$qt;
        } else {
          // $panier[$index]['idProduit']=$_POST['id'];
          $panier[$index]['type']=$_POST['type'];
          $panier[$index]['idBoutique']=$_SESSION['idBoutique'];
          $panier[$index]['boutique']=$_POST['boutique'];
          $panier[$index]['image']=$_POST['image'];
          $panier[$index]['quantite']=1;
          $type=$_POST['type'];
          $panier[$index]['idDesignation']=$_POST['idDesignation'];
          $panier[$index]['designation']=$_POST['designation'];
          if ($type=='Pharmacie') {
            $panier[$index]['prixPublic']=$_POST['prixPublic'];
          } else {
            $panier[$index]['prix']=$_POST['prix'];
          }
        }
        $_SESSION['panier']= $panier;
        //var_dump($index);
        $index=count($panier);
        exit(''.$index);
  }
  elseif ($_POST['operation']=="updateQuantite") {
    $index=$_POST['index'];
    $panier=$_SESSION['panier'];
    $panier[$index]['quantite']=$_POST['quantite'];
    $_SESSION['panier']= $panier;

    $totalT=0;
    for ($i=0; $i < count($panier); $i++) {
        $total=0;
        $total=$total+ $panier[$i]['prix']* $panier[$i]['quantite'];
        $totalT=$totalT+ $total;
        }
    exit(''.$totalT);
  }

 ?>
