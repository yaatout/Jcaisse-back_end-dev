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
      <li>
          <a href="accueil-editeur.php"><b>ACCUEIL</b></a>
      </li>
    <li>
        <a href="catalogueTotal.php"><b>CATALOGUE TOTAL</b></a>
    </li>

  </ul>

  <ul class="nav navbar-nav navbar-right">
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>MON COMPTE</b><span class="caret"></span></a>
			<ul class="dropdown-menu">
	<?php echo '<li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> <b>PROFIL ('.$_SESSION["prenom"].' '.$_SESSION["nomU"].')</b></a></li>'; ?>
    <li><a href="deconnexion.php"><span class="glyphicon glyphicon-log-out"></span> <b>SORTIE</b></a></li>
  </ul>
  </ul>
</nav>
</header>
