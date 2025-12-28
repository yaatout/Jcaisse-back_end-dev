<?php
session_start();

//require('connection.php');
require('connectionVitrine.php');
//$_SESSION['panier']=NULL;
$boutique=null;
$nomtableDesignation=null;
$type=null;
$currentBoutique=null;
// var_dump($_SESSION['nomB']);

if (isset($_GET['i']) and !$_SESSION['nomB']) {
    $i=$_GET['i'];
    $explod_i = explode('_', $i);
    //   $nomtableDesignation=$_SESSION['nomB']."-designation";

    $id = $explod_i[0];
    $idBoutique = $explod_i[1];
    $_SESSION['idBoutique'] = $idBoutique;

    $sqlCurrentBoutique = $bddV->prepare("SELECT nomBoutique, type FROM  `boutique` WHERE idBoutique = ".$idBoutique);
    $sqlCurrentBoutique->execute() or
    die(print_r($sqlCurrentBoutique->errorInfo()));
    
    $sqlCurrentBoutiqueFetching = $sqlCurrentBoutique->fetch();
    $currentBoutique = $sqlCurrentBoutiqueFetching['nomBoutique'];
    $currentType = $sqlCurrentBoutiqueFetching['type'];
    // var_dump($currentBoutique);
    $nomtableDesignation = $currentBoutique."-designation";
    // var_dump($nomtableDesignation);
    // $_SESSION['nomB'] = $currentBoutique;

}else if (isset($_GET['i']) and $_SESSION['nomB']) {
    
    $i=$_GET['i'];
    $explod_i = explode('_', $i);
    //   $nomtableDesignation=$_SESSION['nomB']."-designation";

    $id = $explod_i[0];
    $idBoutique = $explod_i[1];
    $nomtableDesignation=$_SESSION['nomB']."-designation";
    $_SESSION['idBoutique'] = $idBoutique;

}else {
  header('Location:index.php');
}
 ?>
<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <?php require_once('page/head.php'); ?>
</head>

<body>
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <!-- Body main wrapper start -->
    <div class="wrapper">

        <?php require_once('page/header.php'); ?>



        <!-- Start Bradcaump area -->

        <!-- End Bradcaump area -->
        <!-- Start Product Details Area -->
        <section class="htc__product__details bg__white ptb--100">
            <!-- Start Product Details Top -->
            <div class="htc__product__details__top">
                <div class="container">
                    <div class="row">
                        <?php
                            $req = $bddV->prepare("SELECT * FROM  `".$nomtableDesignation."` WHERE 
                            id =  ".$id." and idBoutique = ".$idBoutique);
                            $req->execute() or
                            die(print_r($req->errorInfo()));

                            // $sqlGetShopName = $bddV->prepare("SELECT * FROM  `boutique` WHERE idBoutique = ".$idBoutique);
                            // $sqlGetShopName->execute() or
                            // die(print_r($sqlGetShopName->errorInfo()));
                            
                            // $sqlGetShopNameFetching = $sqlGetShopName->fetch();
                            // $shopName = $sqlGetShopNameFetching
                            // var_dump($req);
                            if ($req) {
                               while ($produit=$req->fetch()) { ?>
                                 <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                     <div class="row htc__product__details__tab__content">
                                         <!-- Start Product Big Images -->
                                         <div class="product__big__images col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                             <div class="portfolio-full-image tab-content">
                                                 <div role="tabpanel" class="tab-pane fade in active" id="img-tab-1">
                                                     <!-- <img src="images/product-2/big-img/1.jpg" alt="full-image"> -->
                                                     <?php echo '<img src="uploads/'.$produit["image"].'" />'; ?>
                                                 </div>
                                                 <div role="tabpanel" class="tab-pane fade" id="img-tab-2">
                                                     <!-- <img src="uploads/marseille_1.jpg" alt="full-image"> -->
                                                     <?php echo '<img src=".uploads/'.$produit["image"].'" />'; ?>
                                                 </div>
                                                 <div role="tabpanel" class="tab-pane fade" id="img-tab-3">
                                                     <!-- <img src="uploads/marseille_3.jpg" alt="full-image"> -->
                                                     <?php echo '<img src="uploads/'.$produit["image"].'" />'; ?>
                                                 </div>
                                             </div>
                                         </div>
                                         <!-- End Product Big Images -->
                                         <!-- Start Small images -->
                                         <ul class="product__small__images col-md-5 col-lg-5 col-sm-12 col-xs-12" role="tablist">
                                             <li role="presentation" class="pot-small-img active">
                                                 <a href="#img-tab-1" role="tab" data-toggle="tab">
                                                    <img src="<?php echo 'uploads/'.$produit["image"]; ?>" alt="small-image">
                                                 </a>
                                             </li>
                                             <li role="presentation" class="pot-small-img">
                                                 <a href="#img-tab-2" role="tab" data-toggle="tab">
                                                    <img src="<?php echo 'uploads/'.$produit["image"]; ?>" alt="small-image">
                                                 </a>
                                             </li>
                                             <li role="presentation" class="pot-small-img">
                                                 <a href="#img-tab-3" role="tab" data-toggle="tab">
                                                    <img src="<?php echo 'uploads/'.$produit["image"]; ?>" alt="small-image">
                                                 </a>
                                             </li>
                                         </ul>
                                         <!-- End Small images -->
                                     </div>
                                 </div>
                                 <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 smt-40 xmt-40">
                                     <div class="ht__product__dtl">
                                         <h2><?php echo $produit['designation']; ?></h2>
                                         <!-- <h6>Model: <span>MNG001</span></h6> -->
                                         <ul class="rating">
                                             <li><i class="icon-star icons"></i></li>
                                             <li><i class="icon-star icons"></i></li>
                                             <li><i class="icon-star icons"></i></li>
                                             <li class="old"><i class="icon-star icons"></i></li>
                                             <li class="old"><i class="icon-star icons"></i></li>
                                         </ul>
                                         <ul  class="pro__prize">
                                             <li><?php echo $produit['prix']." FCFA"; ?></li>
                                         </ul>
                                         <p class="pro__info"><?php echo $produit['description']; ?></p>
                                         <div class="ht__pro__desc">
                                            <div class="sin__desc">
                                                <?php if ($_SESSION['nomB']) { ?>
                                                    <p><span>Boutique:</span> <?= ' '.$_SESSION['nomB']; ?></p>
                                                <?php }else if($currentBoutique){ ?>
                                                    <p><span>Boutique:<?= ' '.$currentBoutique; ?></span></p>
                                                <?php } ?>
                                            </div>
                                            <div class="sin__desc product__share__link">
                                                <p><span>Disponibilté: En stock</span></p>
                                            </div>
                                             <!-- <div class="sin__desc align--left">
                                                 <p><span>color:</span></p>
                                                 <ul class="pro__color">
                                                     <li class="red"><a href="#">red</a></li>
                                                     <li class="green"><a href="#">green</a></li>
                                                     <li class="balck"><a href="#">balck</a></li>
                                                 </ul>
                                                 <div class="pro__more__btn">
                                                     <a href="#">more</a>
                                                 </div>
                                             </div>
                                             <div class="sin__desc align--left">
                                                 <p><span>size</span></p>
                                                 <select class="select__size">
                                                     <option>s</option>
                                                     <option>l</option>
                                                     <option>xs</option>
                                                     <option>xl</option>
                                                     <option>m</option>
                                                     <option>s</option>
                                                 </select>
                                             </div>
                                             <div class="sin__desc align--left">
                                                 <p><span>Categories:</span></p>
                                                 <ul class="pro__cat__list">
                                                     <li><a href="#">Fashion,</a></li>
                                                     <li><a href="#">Accessories,</a></li>
                                                     <li><a href="#">Women,</a></li>
                                                     <li><a href="#">Men,</a></li>
                                                     <li><a href="#">Kid,</a></li>
                                                     <li><a href="#">Mobile,</a></li>
                                                     <li><a href="#">Computer,</a></li>
                                                     <li><a href="#">Hair,</a></li>
                                                     <li><a href="#">Clothing,</a></li>
                                                 </ul>
                                             </div>
                                             <div class="sin__desc align--left">
                                                 <p><span>Product tags:</span></p>
                                                 <ul class="pro__cat__list">
                                                     <li><a href="#">Fashion,</a></li>
                                                     <li><a href="#">Accessories,</a></li>
                                                     <li><a href="#">Women,</a></li>
                                                     <li><a href="#">Men,</a></li>
                                                     <li><a href="#">Kid,</a></li>
                                                 </ul>
                                             </div> -->

                                             <div class="sin__desc product__share__link">
                                                 <p><span>Partager ceci:</span></p>
                                                 <ul class="pro__share">
                                                     <li><a href="#" target="_blank"><i class="icon-social-twitter icons"></i></a></li>

                                                     <li><a href="#" target="_blank"><i class="icon-social-instagram icons"></i></a></li>

                                                     <li><a href="https://www.facebook.com/Furny/?ref=bookmarks" target="_blank"><i class="icon-social-facebook icons"></i></a></li>

                                                     <li><a href="#" target="_blank"><i class="icon-social-google icons"></i></a></li>

                                                     <li><a href="#" target="_blank"><i class="icon-social-linkedin icons"></i></a></li>

                                                     <li><a href="#" target="_blank"><i class="icon-social-pinterest icons"></i></a></li>
                                                 </ul>
                                             </div>
                                         </div>

                                         <ul class="payment__btn">

                                             <form  >
                                               <?php
                                                    if ($_SESSION['nomB']) {
                                                        echo '
                                                        <input type="hidden" name="idDesignation"  value="'.$produit['id'].'" >
                                                        <input type="hidden" name="image" id="image-'.$produit['id'].'" value="'.$produit['image'].'" >
                                                        <input type="hidden" name="type" id="type-'.$produit['id'].'" value="'.$_SESSION['type'].'">
                                                        <input type="hidden" name="designation" id="designation-'.$produit['id'].'" value="'.$produit['designation'].'" >
                                                        <input type="hidden" name="boutique" id="boutique-'.$produit['id'].'" value="'.$_SESSION['nomB'].'" >
                                                        <input type="hidden" name="operation" id="operation" value="ajouterPanier">';
                                                    }else{
                                                        

                                                        echo '
                                                        <input type="hidden" name="idDesignation"  value="'.$produit['id'].'" >
                                                        <input type="hidden" name="image" id="image-'.$produit['id'].'" value="'.$produit['image'].'" >
                                                        <input type="hidden" name="type" id="type-'.$produit['id'].'" value="'.$currentType.'">
                                                        <input type="hidden" name="designation" id="designation-'.$produit['id'].'" value="'.$produit['designation'].'" >
                                                        <input type="hidden" name="boutique" id="boutique-'.$produit['id'].'" value="'.$currentBoutique.'" >
                                                        <input type="hidden" name="operation" id="operation" value="ajouterPanier">';
                                                    }
                                               ?>

                                               <?php if ($type=='Pharmacie') {
                                                   echo ' <input type="hidden" name="prixPublic" id="prixPublic-'.$produit['id'].'" value="'.$produit['prixPublic'].'" >';
                                               } else {
                                                   echo ' <input type="hidden" name="prix" id="prix-'.$produit['id'].'" value="'.$produit['prix'].'" >';
                                               }
                                            echo ' <li class="active"><a href="#"   onclick="ajouterPanier('.$produit["id"].')"  data-toggle="modal" data-target="#conf" >Ajouter</a></li>';
                                              ?>
                                             </form>
                                          </ul>
                                          <div class="modal fade" id="conf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                              <div class="modal-dialog" role="document">
                                                  <div class="modal-content">
                                                      <div class="modal-header">
                                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                              <h4 class="modal-title" id="myModalLabel">Ajouté au panier</h4>
                                                      </div>
                                                    <div class="modal-body">
                                                      <div class="">
                                                        <h2><?php echo $produit['designation']." a été ajouté à votre panier"; ?></h2>
                                                      </div>
                                                        <div class="buttons-cart checkout--btn">
                                                            <?php if ($currentBoutique) {
                                                                echo '<a href="index.php" >Poursuivre vos achats</a>';
                                                            }else if($_SESSION['nomB']){
                                                                echo '<a href="shop__'.$_SESSION['nomB'].'" >Poursuivre vos achats</a>';
                                                            }else if($_SESSION['categorie']){
                                                                echo '<a href="categorie__'.$_SESSION['categorie'].'" >Poursuivre vos achats</a>';
                                                            }else if($_SESSION['produit']){
                                                                echo '<a href="index.php?produit='.$_SESSION['produit'].'" >Poursuivre vos achats</a>';
                                                            }
                                                            ?>
                                                            <a href="cart.php" class="orange-btn">Finaliser Commande</a>
                                                        </div>
                                                    </div>
                                                  </div>
                                              </div>
                                          </div>
                                     </div>
                                 </div>
                          <?php
                               }
                             }else{?>
<p>gfhgjklmùmlkjhgfdsfghjk</p>
                            <?php  }
                               $req->closeCursor();
                         ?>

                    </div>
                </div>
            </div>
            <!-- End Product Details Top -->

        </section>
        <!-- End Product Details Area -->

        <!-- Start Product Area -->

        <!-- End Product Area -->
        <!-- Start Banner Area -->
        <!-- <div class="htc__brand__area bg__cat--4">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="ht__brand__inner">
                            <ul class="brand__list owl-carousel clearfix">
                                <li><a href="#"><img src="images/brand/1.png" alt="brand images"></a></li>
                                <li><a href="#"><img src="images/brand/2.png" alt="brand images"></a></li>
                                <li><a href="#"><img src="images/brand/3.png" alt="brand images"></a></li>
                                <li><a href="#"><img src="images/brand/4.png" alt="brand images"></a></li>
                                <li><a href="#"><img src="images/brand/5.png" alt="brand images"></a></li>
                                <li><a href="#"><img src="images/brand/5.png" alt="brand images"></a></li>
                                <li><a href="#"><img src="images/brand/1.png" alt="brand images"></a></li>
                                <li><a href="#"><img src="images/brand/2.png" alt="brand images"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
        <!-- End Banner Area --> 
        <!-- End Banner Area -->
        <!-- Start Footer Area -->
        <?php require_once('page/footer.php'); ?>
      <!-- End Footer Style -->
  </div>
  <!-- Body main wrapper end -->

  <!-- Placed js at the end of the document so the pages load faster -->
    <?php require_once('page/js.php'); ?>


</body>

</html>
