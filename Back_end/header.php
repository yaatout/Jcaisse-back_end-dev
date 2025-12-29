<?php
/*
R�sum? :
Commentaire :
Version : 2.1
see also :
Auteur : Ibrahima DIOP
Date de cr?ation : 5-08-2019
Date derni?re modification :  17-10-2019
*/

// $date = new DateTime('25-02-2011');
$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');

$dateString=$jour.'/'.$mois.'/'.$annee; ?>

<header class="noImpr" >

<table border="0" width="95%" align="center">
    <tr>
        <td><?php echo '<p align="LEFT"><h2>STARTUP JCAISSE</h2></p>'; ?></td>
        <td align="RIGHT"><?php echo '<p ><h2>BACK-END</h2></p>'; ?></td>

</tr></table>
<nav class="navbar navbar-inverse" id="nav_bar">
  <ul class="nav navbar-nav">
 <?php
 if(($_SESSION['profil']=="SuperAdmin")||($_SESSION['profil']=="Admin")){
		echo '<li><a href="admin-map.php"><b>ACCUEIL</b></a></li>';
		echo '<li><a href="rapport.php"><b>RAPPORT</b></a></li>'; }
	else if ($_SESSION['profil']=="Accompagnateur")
		echo '<li><a href="user-map.php"><b>ACCUEIL</b></a></li>';
  else if ($_SESSION['profil']=="Assistant")
    echo '<li><a href="admin-map.php"><b>ACCUEIL</b></a></li>';
   ?>
    <!--li>
      <a href="vitrine.php"><b>VITRINE</b></a>
    </li-->
      <?php
           /* if (($_SESSION['profil']=="SuperAdmin")||($_SESSION['profil']=="Editeur catalogue")){
    		      echo '<li>
                      <a href="catalogueTotal.php"><b>CATALOGUE TOTAL</b></a>
                    </li>';
           }*/
        if (($_SESSION['profil']=="SuperAdmin")||($_SESSION['profil']=="Assistant")){
          echo '<li>
                      <a href="catalogueTotal.php"><b>CATALOGUE TOTAL</b></a>
                    </li>';
           }
        ?>
    <!-- <li><a  href="boutiques.php"><b>BOUTIQUES</b><span ></span></a></li> -->
    <li  class="dropdown">
      <a href="#" data-toggle="dropdown" href="#"><b>BOUTIQUES</b><span class="caret"></span></a>
      <ul class="dropdown-menu">
          <li><a href="boutiques.php"><b>LISTES DES BOUTIQUES</b></a></li>
          <!-- <li><a href="crm.php?p=home"><b>CRM</b></a></li> -->
           <li><a href="position.php"><b>POSITIONS</b></a></li> 
      </ul>
    </li>
    
    <?php  //if (($_SESSION['gerant']==1) || ($_SESSION['proprietaire']==1)){

      //  echo '<li><a href="#"><b>STATISTIQUES</b></a></li>';

    //} ?>



 <?php  if(($_SESSION['profil']=="SuperAdmin")||($_SESSION['profil']=="Admin")) {

           echo ' <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>PAYEMENTS</b><span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="paiement/?p=paiement"><b>MOIS EN COUR...</b></a></li>';
            echo '<li><a href="paiement/?p=ret"><b>RETARDS</b></a></li>';
			echo '<li><a href="paiement/?p=hist"><b>HISTORIQUES</b></a></li>';
            if($_SESSION['profil']=="SuperAdmin"){
				echo '<li><a href="paiement/?p=refT"><b>REFERENCES TRANSFERTS</b></a></li>';
			echo '<li><a href="paiement/?p=param"><b>PARAMETTRES PAYEMENTS</b></a></li>';
			}

                echo '     </ul>
              </li>';
		}
		// else if(($_SESSION['profil']=="SuperAdmin") || ($_SESSION['profil']=="Admin") ){

		// echo ' <li class="dropdown">
    //             <a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>PAYEMENT</b><span class="caret"></span></a>
    //             <ul class="dropdown-menu">
    //               <li><a href="payementMesBoutiques.php"><b>MOIS EN COUR...</b></a></li>';
    //           echo '<li><a href="#"><b>RETARDS</b></a></li>';
  	// 		     echo '<li><a href="#"><b>HISTORIQUES</b></a></li>';
    //        echo '</ul>
    //           </li>
    //           ';

		// } ?>

        
       <?php if(($_SESSION['profil']=="SuperAdmin") || ($_SESSION['profil']=="Admin") ) {
      echo ' <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>SALAIRES</b><span class="caret"></span></a>
                <ul class="dropdown-menu">';
                echo  '<li><a href="salaire/?p=salaire"><b>MOIS EN COUR...</b></a></li>
                  <li><a href="salaire/?p=latSal"><b>RETARDS</b></a></li>
                  <li><a href="salaire/?p=hist"><b>HISTORIQUES</b></a></li>';
                  if($_SESSION['profil']=="SuperAdmin"){
                    echo  ' <li><a href="salaire/?p=paramet"><b>PARAMETTRES SALAIRES</b></a></li>';
                  }
      //           echo '<li><a href="salaireAccompagnateurs.php"><b>MOIS EN COUR...</b></a></li>';
      //       echo '<li><a href="payementSalairesRetards.php"><b>RETARDS</b></a></li>';
			// echo '<li><a href="#"><b>HISTORIQUES</b></a></li>';
            if($_SESSION['profil']=="SuperAdmin"){
                //echo '<li><a href="paramettreSalaire.php"><b>PARAMETTRES SALAIRES</b></a></li>   ';
            }
                echo '     </ul>
              </li>';
		}
    /* else if(($_SESSION['profil']=="SuperAdmin") || ($_SESSION['profil']=="Admin") ){
			echo ' <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>SALAIRE</b><span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="monSalaireAccompagnateur.php"><b>MOIS EN COUR...</b></a></li>';
            echo '<li><a href="#"><b>RETARDS</b></a></li>';
			echo '<li><a href="#"><b>HISTORIQUES</b></a></li>';
                echo '     </ul>
              </li>';
		} */ ?>

    <?php  
	if($_SESSION['profil']=="SuperAdmin" ){
        echo ' <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>TRESORERIES</b><span class="caret"></span></a>
                <ul class="dropdown-menu">
                <li><a href="compte/?p=ops"><b>LISTES OPERATIONS</b></a></li>
                <li><a href="compte/?p=compte"><b>LISTES COMPTES</b></a></li>
                
                ';
                  // <li><a href="opComtes.php"><b>LISTES OPERATIONS</b></a></li>';
            // echo '<li><a href="compte.php"><b>LISTES COMPTES</b></a></li>';
            // echo '<li><a href="materiel.php"><b>MATERIELS</b></a></li>';
            echo '</ul> </li>';
            }
  if ($_SESSION['profil']=="SuperAdmin") {
	         echo'
            <li><a href="compte.php"><b></b></a></li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>PARAMETRAGES</b><span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="insertionType.php"><b>TYPES DE CAISSES</b></a></li>';
                echo '<li><a href="insertionCategorie.php"><b>CATEGORIES DE CAISSES</b></a></li>';
          			echo '<li><a href="insertionUnite.php"><b>UNITES DE STOCKAGE</b></a></li>';
          			echo '<li><a href="insertionUniteDetail.php"><b>UNITES DE DETAIL</b></a></li>';
          			echo '<li><a href="insertionService.php"><b>SERVICES</b></a></li>';
                
          			echo '<li><a href="insertionTransfert.php"><b>TRANSFERT ARGENT</b></a></li>';
          			echo '<li><a href="insertionCredit.php"><b>CREDITS</b></a></li>';
          			echo '<li><a href="insertionDepense.php"><b>DEPENSES</b></a></li>';
          			echo '<li><a href="insertionDevise.php"><b>DEVISES</b></a></li>';
          			echo '<li><a href="banque.php"><b>BANQUE</b></a></li>';
                echo '     </ul>
              </li>';
      }
    //}?>



  </ul>

  <ul class="nav navbar-nav navbar-right">
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>MON COMPTE</b><span class="caret"></span></a>
			<ul class="dropdown-menu">

            <?php if(($_SESSION['profil']=="SuperAdmin")||($_SESSION['profil']=="Admin")) {
              echo '<li ><a href="personnel.php"><span class="fa fa-group" style="font-size:16px"></span> <b>PERSONNEL</b></a></li>';
             // echo '<li ><a href="boutique.php">BOUTIQUE</a></li>';
           } ?>
            <?php if($_SESSION['profil']=="SuperAdmin") {
              echo '<li ><a href="vitrine.php"><span class="fa " style="font-size:16px"></span> <b>VITRINE</b></a></li>';
             // echo '<li ><a href="boutique.php">BOUTIQUE</a></li>';
           } ?>

	<?php echo '<li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> <b>PROFIL ('.$_SESSION["prenom"].' '.$_SESSION["nomU"].')</b></a></li>'; ?>
    <li><a href="deconnexion.php"><span class="glyphicon glyphicon-log-out"></span> <b>SORTIE</b></a></li>
  </ul>
  </ul>
</nav>
</header>
