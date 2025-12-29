<?php
// Connexion PDO
// $host = 'localhost';
// $db   = 'votre_base'; // Remplacez par le nom de votre base
// $user = 'votre_user'; // Remplacez par votre utilisateur
// $pass = 'votre_password'; // Remplacez par votre mot de passe
// $charset = 'utf8mb4';
// $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
// $options = [
//     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//     PDO::ATTR_EMULATE_PREPARES   => false,
// ];
// try {
//     $pdo = new PDO($dsn, $user, $pass, $options);
// } catch (PDOException $e) {
//     die('Connexion échouée : ' . $e->getMessage());
// }

$sql_Bl="CREATE TABLE IF NOT EXISTS `".$nomtableBl."` (
    `idBl` int(11) NOT NULL AUTO_INCREMENT,
    `idFournisseur` int(11) NOT NULL,
    `idVoyage` int(11) NOT NULL,
    `numeroBl` varchar(30) NOT NULL,
    `montantDevise` double NOT NULL,
    `devise` varchar(20) NOT NULL,
    `valeurDevise` double NOT NULL,
    `valeurConversion` double NOT NULL,
    `dateBl` varchar(20) NOT NULL,
    `heureBl` varchar(10) NOT NULL,
    `montantBl` double NOT NULL,
    `iduser` int(11) NOT NULL,
    `description` varchar(250) DEFAULT NULL,
    `image` varchar(50) DEFAULT NULL,
    `dateEcheance` varchar(20) NOT NULL,
    PRIMARY KEY (`idBl`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_Bl);
} catch (PDOException $e) {
    die ("creation table BL impossible -- ". $e->getMessage());
}

$sql_Bon="CREATE TABLE IF NOT EXISTS `".$nomtableBon."` (
    `idBon` int(11) NOT NULL AUTO_INCREMENT,
    `idClient` int(11) NOT NULL,
    `montant` double NOT NULL,
    `date` varchar(15) NOT NULL,
    `heureBon` varchar(10) NOT NULL,
    `iduser` int(11) NOT NULL,
    PRIMARY KEY (`idBon`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_Bon);
} catch (PDOException $e) {
    die ("creation table Bon impossible -- ". $e->getMessage());
}

$sql_Categorie="CREATE TABLE IF NOT EXISTS `".$nomtableCategorie."`  (
    `idcategorie` int(11) NOT NULL AUTO_INCREMENT,
    `nomcategorie` varchar(50) NOT NULL,
    `categorieParent` int(11) DEFAULT NULL,
    PRIMARY KEY (`idcategorie`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_Categorie);
} catch (PDOException $e) {
    die ("creation table Categorie impossible -- ". $e->getMessage());
}

$sql_Client="CREATE TABLE IF NOT EXISTS `".$nomtableClient."` (
    `idClient` int(11) NOT NULL AUTO_INCREMENT,
    `idClientVitrine` int(11) NOT NULL,
    `prenom` varchar(50) NOT NULL,
    `nom` varchar(20) NOT NULL,
    `adresse` varchar(50) NOT NULL,
    `telephone` varchar(50) NOT NULL,
    `solde` double NOT NULL,
    `activer` int(11) NOT NULL,
    `datetime` varchar(25) NOT NULL,
    `iduser` int(11) NOT NULL,
    `personnel` int(11) NOT NULL,
    `archiver` int(11) NOT NULL,
    `avoir` int(11) NOT NULL,
    `montantAvoir` double NOT NULL,
    `matriculePension` varchar(30) NOT NULL,
    `numCarnet` varchar(30) NOT NULL,
    `taux` double NOT NULL,
    `plafond` int(11) NOT NULL,
    PRIMARY KEY (`idClient`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_Client);
} catch (PDOException $e) {
    die ("creation table Client impossible -- ". $e->getMessage());
}

$sql_Designation="CREATE TABLE IF NOT EXISTS `".$nomtableDesignation."` (  
    `idDesignation` int(11) NOT NULL AUTO_INCREMENT,
    `idFusion` int(11) DEFAULT NULL,
    `designation` varchar(50) NOT NULL,
    `description` varchar(100) DEFAULT NULL,
    `categorie` varchar(50) NOT NULL,
    `uniteStock` varchar(50) NOT NULL,
    `uniteDetails` varchar(50) NOT NULL,
    `nbreArticleUniteStock` double NOT NULL,
    `prix` double NOT NULL,
    `seuil` int(11) NOT NULL,
    `prixuniteStock` double NOT NULL,
    `prixcommercant` double NOT NULL,
    `prixachat` double NOT NULL,
    `codeBarreDesignation` varchar(50) NOT NULL,
    `codeBarreuniteStock` varchar(50) NOT NULL,
    `classe` int(11) NOT NULL,
    `tva` int(1) NOT NULL,
    `image` varchar(50) NOT NULL,
    `archiver` int(1) NOT NULL,
    PRIMARY KEY (`idDesignation`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_Designation);
} catch (PDOException $e) {
    die ("creation table Designation impossible -- ". $e->getMessage());
}

$sql_Entrepot="CREATE TABLE IF NOT EXISTS `".$nomtableEntrepot."` (
    `idEntrepot` int(11) NOT NULL AUTO_INCREMENT,
    `nomEntrepot` varchar(50) NOT NULL,
    `adresseEntrepot` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`idEntrepot`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_Entrepot);
} catch (PDOException $e) {
    die ("creation table Entrepot impossible -- ". $e->getMessage());
}

$sql_EntrepotStock="CREATE TABLE IF NOT EXISTS `".$nomtableEntrepotStock."` (
    `idEntrepotStock` int(11) NOT NULL AUTO_INCREMENT,
    `idStock` int(11) NOT NULL,
    `idEntrepot` int(11) NOT NULL,
    `idTransfert` int(11) NOT NULL,
    `idEntrepotTransfert` int(11) NOT NULL,
    `idDesignation` int(11) NOT NULL,
    `designation` varchar(50) NOT NULL,
    `quantiteStockinitial` double NOT NULL,
    `uniteStock` varchar(50) NOT NULL,
    `uniteDetails` varchar(50) NOT NULL,
    `nbreArticleUniteStock` int(11) NOT NULL,
    `totalArticleStock` double NOT NULL,
    `dateStockage` varchar(15) NOT NULL,
    `quantiteStockCourant` double NOT NULL,
    `retirer` double NOT NULL,
    `dateFinStock` varchar(15) NOT NULL,
    `dateExpiration` varchar(15) NOT NULL,
    `prixuniteStock` double NOT NULL,
    `prixunitaire` double NOT NULL,
    `prixDeRevientDuStock` double NOT NULL,
    `pointControleArticle` int(11) NOT NULL DEFAULT 0,
    `pointControleUnite` int(11) NOT NULL DEFAULT 0,
    `iduser` int(11) NOT NULL,
    PRIMARY KEY (`idEntrepotStock`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_EntrepotStock);
} catch (PDOException $e) {
    die ("creation table Entrepot Stock impossible -- ". $e->getMessage());
}

$sql_EntrepotTransfert="CREATE TABLE IF NOT EXISTS `".$nomtableEntrepotTransfert."` (
    `idEntrepotTransfert` int(11) NOT NULL AUTO_INCREMENT,
    `idEntrepot` int(11) NOT NULL,
    `idDesignation` int(11) DEFAULT NULL,
    `designation` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
    `quantite` double DEFAULT NULL,
    `quantiteInitiale` double DEFAULT NULL,
    `quantiteFinale` double DEFAULT NULL,
    `dateTransfert` varchar(15) DEFAULT NULL,
    `etat1` tinyint(1) DEFAULT NULL,
    `etat2` tinyint(1) DEFAULT NULL,
    `iduser` int(11) DEFAULT NULL,
    PRIMARY KEY (`idEntrepotTransfert`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;";
try {
    $bdd->exec($sql_EntrepotTransfert);
} catch (PDOException $e) {
    die ("creation table Entrepot Transfert impossible -- ". $e->getMessage());
}

$sql_Facture="CREATE TABLE IF NOT EXISTS `".$nomtableFacture."` (
    `idFacture` int(11) NOT NULL AUTO_INCREMENT,
    `idPagnet` int(11) NOT NULL,
    `prenom` varchar(50) DEFAULT NULL,
    `nom` varchar(20) DEFAULT NULL,
    `adresse` varchar(50) DEFAULT NULL,
    `telephone` varchar(50) DEFAULT NULL,
    `dateFacture` varchar(10) DEFAULT NULL,
    `heureFacture` varchar(10) DEFAULT NULL,
    `iduser` int(11) DEFAULT NULL,
    PRIMARY KEY (`idFacture`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;";
try {
    $bdd->exec($sql_Facture);
} catch (PDOException $e) {
    die ("creation table Fature impossible -- ". $e->getMessage());
}

$sql_Fournisseur="CREATE TABLE IF NOT EXISTS `".$nomtableFournisseur."` (
    `idFournisseur` int(11) NOT NULL AUTO_INCREMENT,
    `nomFournisseur` varchar(50) NOT NULL,
    `adresseFournisseur` varchar(50) NOT NULL,
    `telephoneFournisseur` varchar(20) NOT NULL,
    `montant` double NOT NULL,
    `banqueFournisseur` varchar(50) NOT NULL,
    `numBanqueFournisseur` varchar(50) NOT NULL,
    PRIMARY KEY (`idFournisseur`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_Fournisseur);
} catch (PDOException $e) {
    die ("creation table Fournisseur impossible -- ". $e->getMessage());
}

$sql_Frais="CREATE TABLE IF NOT EXISTS `".$nomtableFrais."` (
    `idFrais` int(11) NOT NULL AUTO_INCREMENT,
    `idVoyage` int(11) NOT NULL,
    `frais` varchar(50) DEFAULT NULL,
    `montantDevise` double DEFAULT NULL,
    `devise` varchar(20) DEFAULT NULL,
    `valeurDevise` double DEFAULT NULL,
    `valeurConversion` double NOT NULL,
    `montantFrais` double DEFAULT NULL,
    `dateFrais` varchar(20) DEFAULT NULL,
    `heureFrais` varchar(10) DEFAULT NULL,
    `idCompte` int(11) NOT NULL,
    `image` varchar(50) DEFAULT NULL,
    PRIMARY KEY (`idFrais`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;";
try {
    $bdd->exec($sql_Frais);
} catch (PDOException $e) {
    die ("creation table Frais impossible -- ". $e->getMessage());
}

$sql_Inventaire="CREATE TABLE IF NOT EXISTS `".$nomtableInventaire."` (
    `idInventaire` int(11) NOT NULL AUTO_INCREMENT,
    `idStock` int(11) NOT NULL,
    `idEntrepotStock` int(11) NOT NULL,
    `idDesignation` int(11) NOT NULL,
    `quantite` double NOT NULL,
    `quantiteStockCourant` double NOT NULL,
    `nbreArticleUniteStock` int(11) NOT NULL,
    `dateInventaire` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
    `type` int(11) DEFAULT NULL,
    `iduser` int(11) NOT NULL,
    PRIMARY KEY (`idInventaire`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_Inventaire);
} catch (PDOException $e) {
    die ("creation table Inventaire impossible -- ". $e->getMessage());
}

$sql_Journal="CREATE TABLE IF NOT EXISTS `".$nomtableJournal."` (
    `idjournal` int(11) NOT NULL AUTO_INCREMENT,
    `mois` int(11) NOT NULL,
    `annee` int(11) NOT NULL,
    `totalVente` double NOT NULL,
    `totalVersement` double NOT NULL,
    `totalBon` double NOT NULL,
    `totalFrais` double NOT NULL,
    PRIMARY KEY (`idjournal`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_Journal);
} catch (PDOException $e) {
    die ("creation table Journal impossible -- ". $e->getMessage());
}

$sql_Ligne="CREATE TABLE IF NOT EXISTS `".$nomtableLigne."` (
    `numligne` int(11) NOT NULL AUTO_INCREMENT,
    `idPagnet` int(11) NOT NULL,
    `idDesignation` int(11) NOT NULL,
    `idEntrepot` int(11) NOT NULL,
    `designation` varchar(155) NOT NULL,
    `idStock` int(11) NOT NULL,
    `unitevente` varchar(155) NOT NULL,
    `prixunitevente` double NOT NULL,
    `quantite` int(11) NOT NULL,
    `prixtotal` double NOT NULL,
    `classe` int(1) NOT NULL,
    `prixtotalTvaP` double NOT NULL,
    `prixtotalTvaR` double NOT NULL,
    PRIMARY KEY (`numligne`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_Ligne);
} catch (PDOException $e) {
    die ("creation table Ligne impossible -- ". $e->getMessage());
}

$sql_Page="CREATE TABLE IF NOT EXISTS `".$nomtablePage."` (
    `numpage` int(11) NOT NULL AUTO_INCREMENT,
    `datepage` varchar(15) NOT NULL,
    `totalVente` double NOT NULL,
    `totalService` double NOT NULL,
    `totalVersement` double NOT NULL,
    `totalBon` double NOT NULL,
    `totalFrais` double NOT NULL,
    `totalCaisse` double NOT NULL,
    `datetime` varchar(25) NOT NULL,
    `iduser` int(11) NOT NULL,
    PRIMARY KEY (`numpage`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_Page);
} catch (PDOException $e) {
    die ("creation table Page impossible -- ". $e->getMessage());
}

$sql_Pagnet="CREATE TABLE IF NOT EXISTS `".$nomtablePagnet."` (
    `idPagnet` int(11) NOT NULL AUTO_INCREMENT,
    `datepagej` varchar(15) NOT NULL,
    `type` int(11) NOT NULL,
    `heurePagnet` varchar(10) NOT NULL,
    `totalp` double NOT NULL,
    `remise` double NOT NULL,
    `apayerPagnet` double NOT NULL,
    `restourne` double NOT NULL,
    `versement` double NOT NULL,
    `paiement` varchar(250) DEFAULT NULL,
    `livreur` varchar(200) DEFAULT NULL,
    `verrouiller` tinyint(1) NOT NULL,
    `idClient` int(11) NOT NULL,
    `dateecheance` varchar(15) DEFAULT NULL,
    `iduser` int(11) NOT NULL,
    `idCompte` int(11) NOT NULL,
    `avance` double NOT NULL,
    `apayerPagnetTvaP` double NOT NULL,
    `apayerPagnetTvaR` double NOT NULL,
    `dejaTerminer` int(11) NOT NULL,
    `image` varchar(50) DEFAULT NULL,
    `taux` double NOT NULL,
    `ticketCopy` int(11) NOT NULL,
    `nomClient` varchar(100) NOT NULL,
    `validerProforma` int(11) NOT NULL,
    `terminerProforma` int(11) NOT NULL,
    `nbColis` int(11) NOT NULL,
    `numContainer` varchar(30) NOT NULL,
    PRIMARY KEY (`idPagnet`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_Pagnet);
} catch (PDOException $e) {
    die ("creation table Pagnet impossible -- ". $e->getMessage());
}

$sql_Stock="CREATE TABLE IF NOT EXISTS `".$nomtableStock."` (
    `idStock` int(11) NOT NULL AUTO_INCREMENT,
    `idDesignation` int(11) NOT NULL,
    `idBl` int(11) NOT NULL,
    `designation` varchar(50) NOT NULL,
    `quantiteStockinitial` double NOT NULL,
    `uniteStock` varchar(50) NOT NULL,
    `uniteDetails` varchar(50) NOT NULL,
    `nbreArticleUniteStock` int(11) NOT NULL,
    `totalArticleStock` double NOT NULL,
    `dateStockage` varchar(15) NOT NULL,
    `quantiteStockCourant` double NOT NULL,
    `quantiteStockTemp` double NOT NULL,
    `retirer` double NOT NULL,
    `dateFinStock` varchar(15) NOT NULL,
    `dateExpiration` varchar(15) NOT NULL,
    `prixuniteStock` double NOT NULL,
    `prixunitaire` double NOT NULL,
    `prixachat` double NOT NULL,
    `prixDeRevientDuStock` double NOT NULL,
    `pointControleArticle` int(11) NOT NULL DEFAULT 0,
    `pointControleUnite` int(11) NOT NULL DEFAULT 0,
    `iduser` int(11) NOT NULL,
    PRIMARY KEY (`idStock`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_Stock);
} catch (PDOException $e) {
    die ("creation table Stock impossible -- ". $e->getMessage());
}

$sql_TotalStock="CREATE TABLE IF NOT EXISTS `".$nomtableTotalStock."` (
    `designation` varchar(50) NOT NULL,
    `quantiteEnStocke` double NOT NULL,
    PRIMARY KEY (`designation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_TotalStock);
} catch (PDOException $e) {
    die ("creation table Total Stock impossible -- ". $e->getMessage());
}

$sql_Versement="CREATE TABLE IF NOT EXISTS `".$nomtableVersement."` (
    `idVersement` int(11) NOT NULL AUTO_INCREMENT,
    `idClient` int(11) NOT NULL,
    `idFournisseur` int(11) NOT NULL,
    `montant` double NOT NULL,
    `paiement` varchar(250) DEFAULT NULL,
    `dateVersement` varchar(15) NOT NULL,
    `heureVersement` varchar(10) NOT NULL,
    `iduser` int(11) NOT NULL,
    `idCompte` int(11) NOT NULL,
    `idPagnet` int(11) NOT NULL,
    `montantAvoir` double NOT NULL,
    `image` varchar(50) DEFAULT NULL,
    PRIMARY KEY (`idVersement`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_Versement);
} catch (PDOException $e) {
    die ("creation table Versement impossible -- ". $e->getMessage());
}

$sql_Voyage="CREATE TABLE IF NOT EXISTS `".$nomtableVoyage."` (
    `idVoyage` int(11) NOT NULL AUTO_INCREMENT,
    `destination` varchar(50) DEFAULT NULL,
    `motif` varchar(50) DEFAULT NULL,
    `dateVoyage` varchar(20) DEFAULT NULL,
    `tauxRevient` double DEFAULT NULL,
    `tauxVente` double DEFAULT NULL,
    PRIMARY KEY (`idVoyage`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;";
try {
    $bdd->exec($sql_Voyage);
} catch (PDOException $e) {
    die ("creation table Voyage impossible -- ". $e->getMessage());
}
