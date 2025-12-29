<?php

$sql_Bl="CREATE TABLE IF NOT EXISTS `".$nomtableBl."` (
    `idBl` int(11) NOT NULL AUTO_INCREMENT,
    `idFournisseur` int(11) NOT NULL,
    `numeroBl` varchar(30) NOT NULL,
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
    `forme` varchar(50) NOT NULL,
    `tableau` varchar(50) NOT NULL,
    `seuil` int(11) NOT NULL,
    `prixSession` double NOT NULL DEFAULT 0,
    `prixPublic` double NOT NULL,
    `codeBarreDesignation` varchar(50) NOT NULL,
    `codeBarreuniteStock` varchar(50) NOT NULL,
    `classe` int(11) NOT NULL,
    `tva` int(11) NOT NULL,
    `image` varchar(50) NOT NULL,
    `archiver` int(1) NOT NULL,
    PRIMARY KEY (`idDesignation`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_Designation);
} catch (PDOException $e) {
    die ("creation table Designation impossible -- ". $e->getMessage());
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

$sql_Inventaire="CREATE TABLE IF NOT EXISTS `".$nomtableInventaire."` (
    `idInventaire` int(11) NOT NULL AUTO_INCREMENT,
    `idStock` int(11) NOT NULL,
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

$sql_Mutuelle="CREATE TABLE IF NOT EXISTS `".$nomtableMutuelle."` (
    `idMutuelle` int(11) NOT NULL AUTO_INCREMENT,
    `nomMutuelle` varchar(50) DEFAULT NULL,
    `tauxMutuelle` int(11) NOT NULL,
    `adresseMutuelle` varchar(50) DEFAULT NULL,
    `telephoneMutuelle` varchar(20) DEFAULT NULL,
    PRIMARY KEY (`idMutuelle`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;";
try {
    $bdd->exec($sql_Mutuelle);
} catch (PDOException $e) {
    die ("creation table Mutuelle impossible -- ". $e->getMessage());
}

$sql_MutuellePagnet="CREATE TABLE IF NOT EXISTS `".$nomtableMutuellePagnet."` (
    `idMutuellePagnet` int(11) NOT NULL AUTO_INCREMENT,
    `datepagej` varchar(15) DEFAULT NULL,
    `type` int(11) DEFAULT NULL,
    `heurePagnet` varchar(10) DEFAULT NULL,
    `totalp` double DEFAULT NULL,
    `remise` double DEFAULT NULL,
    `taux` double DEFAULT NULL,
    `apayerPagnet` double DEFAULT NULL,
    `apayerMutuelle` double DEFAULT NULL,
    `verrouiller` tinyint(1) DEFAULT NULL,
    `idClient` int(11) DEFAULT NULL,
    `idMutuelle` int(11) DEFAULT NULL,
    `adherant` varchar(250) DEFAULT NULL,
    `codeAdherant` varchar(20) DEFAULT NULL,
    `codeBeneficiaire` varchar(20) DEFAULT NULL,
    `numeroRecu` varchar(15) DEFAULT NULL,
    `dateRecu` varchar(15) DEFAULT NULL,
    `iduser` int(11) DEFAULT NULL,
    `image` varchar(50) DEFAULT NULL,
    `restourne` double NOT NULL,
    `versement` double NOT NULL,
    PRIMARY KEY (`idMutuellePagnet`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;";
try {
    $bdd->exec($sql_MutuellePagnet);
} catch (PDOException $e) {
    die ("creation table Mutuelle Pagnet impossible -- ". $e->getMessage());
}

$sql_Ligne="CREATE TABLE IF NOT EXISTS `".$nomtableLigne."` (
    `numligne` int(11) NOT NULL AUTO_INCREMENT,
    `idPagnet` int(11) NOT NULL,
    `idMutuellePagnet` int(11) NOT NULL,
    `idDesignation` int(11) NOT NULL,
    `designation` varchar(155) NOT NULL,
    `idStock` int(11) NOT NULL,
    `forme` varchar(50) NOT NULL,
    `prixPublic` double NOT NULL,
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
    `verrouiller` tinyint(1) NOT NULL,
    `idClient` int(11) NOT NULL,
    `iduser` int(11) NOT NULL,
    `apayerPagnetTvaP` double NOT NULL,
    `apayerPagnetTva` double NOT NULL,
    `dejaTerminer` int(11) NOT NULL,
    `image` varchar(50) DEFAULT NULL,
    `taux` double NOT NULL,
    `ticketCopy` int(11) NOT NULL,
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
    `forme` varchar(50) NOT NULL,
    `nbreArticleUniteStock` int(11) NOT NULL,
    `totalArticleStock` double NOT NULL,
    `dateStockage` varchar(15) NOT NULL,
    `quantiteStockCourant` double NOT NULL,
    `retirer` double NOT NULL,
    `dateFinStock` varchar(15) NOT NULL,
    `dateExpiration` varchar(15) NOT NULL,
    `prixSession` double NOT NULL DEFAULT 0,
    `prixPublic` double NOT NULL,
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
    `idMutuelle` int(11) NOT NULL,
    `montant` double NOT NULL,
    `paiement` varchar(250) DEFAULT NULL,
    `dateVersement` varchar(15) NOT NULL,
    `heureVersement` varchar(10) NOT NULL,
    `iduser` int(11) NOT NULL,
    `montantAvoir` double NOT NULL,
    `image` varchar(50) DEFAULT NULL,
    PRIMARY KEY (`idVersement`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
try {
    $bdd->exec($sql_Versement);
} catch (PDOException $e) {
    die ("creation table Versement impossible -- ". $e->getMessage());
}
