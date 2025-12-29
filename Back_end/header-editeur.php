<?php
/*
R�sum? :
Commentaire :
Version : 2.1
see also :
Auteur : korka DIALLO
Date de cr?ation : 28-03-2020
Date derni?re modification :
*/
 ?>

<header class="noImpr" >

<table border="0" width="95%" align="center">
    <tr>
        <td><?php echo '<p align="LEFT"><h2>STARTUP JCAISSE</h2></p>'; ?></td>
        <td align="RIGHT"><?php echo '<p ><h2>BACK-END</h2></p>'; ?></td>

</tr></table>
<nav class="navbar  " >
  <ul class="nav navbar-nav">
 <?php
		echo '<li><a href="accueil-editeur.php"><b>ACCUEIL</b></a></li>';
   ?>
    <li>
      <a href="vitrine.php"><b>VITRINE</b></a>
    </li>
    <li>
      <a href="catalogueTotal.php"><b>CATALOGUE TOTAL</b></a>
    </li>
  </ul>

  <ul class="nav navbar-nav navbar-right">
	<?php echo '<li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> <b>PROFIL ('.$_SESSION["prenom"].' '.$_SESSION["nomU"].')</b></a></li>'; ?>
    <li><a href="deconnexion.php"><span class="glyphicon glyphicon-log-out"></span> <b>SORTIE</b></a></li>
  </ul>
</nav>
</header>
