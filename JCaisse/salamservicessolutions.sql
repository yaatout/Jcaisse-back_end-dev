-- phpMyAdmin SQL Dump
-- version 3.1.3
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mer 24 Août 2016 à 17:46
-- Version du serveur: 5.1.32
-- Version de PHP: 5.2.9-1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `bdjournalcaisse`
--

-- --------------------------------------------------------

--
-- Structure de la table `boutique`
--

CREATE TABLE IF NOT EXISTS `boutique` (
  `idBoutique` int(11) NOT NULL AUTO_INCREMENT,
  `nomBoutique` varchar(100) NOT NULL,
  `adresseBoutique` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `datecreation` varchar(50) NOT NULL,
  PRIMARY KEY (`idBoutique`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `boutique`
--

INSERT INTO `boutique` (`idBoutique`, `nomBoutique`, `adresseBoutique`, `type`, `datecreation`) VALUES
(1, 'dioplemaire', 'saint-louis', 'detaillant', '17-04-2016'),
(8, 'SalamServicesSolutions', 'Santhiaba', 'multiservices', '06-08-2016');

-- --------------------------------------------------------

--
-- Structure de la table `dioplemaire-designation`
--

CREATE TABLE IF NOT EXISTS `dioplemaire-designation` (
  `idDesignation` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(155) NOT NULL,
  `prix` double NOT NULL,
  `uniteStock` varchar(100) NOT NULL,
  `nbreArticleUniteStock` double NOT NULL,
  `prixuniteStock` double NOT NULL,
  `classe` int(11) NOT NULL,
  PRIMARY KEY (`idDesignation`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Contenu de la table `dioplemaire-designation`
--

INSERT INTO `dioplemaire-designation` (`idDesignation`, `designation`, `prix`, `uniteStock`, `nbreArticleUniteStock`, `prixuniteStock`, `classe`) VALUES
(1, 'Ardoise', 200, 'paquet', 20, 5000, 0),
(2, 'Cahier 100 pages', 250, 'paquet', 10, 2000, 0),
(3, 'Bic bleu', 100, 'paquet', 50, 1500, 0),
(5, 'Bic crayon', 150, 'paquet', 50, 2000, 0),
(8, 'Depense gerant', 2050, '', 1, 440000, 2),
(9, 'Photocopie', 100, '', 1, 200000, 1),
(10, 'Cahier 200 pages', 250, 'paquet', 5, 3000, 0),
(11, 'Impression couleur', 250, '', 1, 20000, 1),
(12, 'Ordinateur', 150000, 'dizaine', 1, 1400000, 0),
(13, 'Television', 250000, 'article', 1, 250000, 0),
(15, 'Telephone ', 25000, 'article', 1, 25000, 0),
(16, 'Tablette', 15000, 'article', 1, 15000, 0),
(18, 'Gomme', 50, 'paquet', 100, 3000, 0);

-- --------------------------------------------------------

--
-- Structure de la table `dioplemaire-journal`
--

CREATE TABLE IF NOT EXISTS `dioplemaire-journal` (
  `idjournal` int(11) NOT NULL AUTO_INCREMENT,
  `mois` int(11) NOT NULL,
  `annee` int(11) NOT NULL,
  `tentrees` double NOT NULL,
  `tsorties` double NOT NULL,
  PRIMARY KEY (`idjournal`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `dioplemaire-journal`
--

INSERT INTO `dioplemaire-journal` (`idjournal`, `mois`, `annee`, `tentrees`, `tsorties`) VALUES
(1, 5, 2016, 0, 0),
(2, 7, 2016, 0, 0),
(3, 8, 2016, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `dioplemaire-lignepj`
--

CREATE TABLE IF NOT EXISTS `dioplemaire-lignepj` (
  `numligne` int(11) NOT NULL AUTO_INCREMENT,
  `datepage` varchar(100) NOT NULL,
  `designation` varchar(155) NOT NULL,
  `unitevente` varchar(100) NOT NULL,
  `prixunitevente` double NOT NULL,
  `quantite` int(11) NOT NULL,
  `remise` double NOT NULL,
  `prixtotal` double NOT NULL,
  `typeligne` varchar(15) NOT NULL,
  PRIMARY KEY (`numligne`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

--
-- Contenu de la table `dioplemaire-lignepj`
--

INSERT INTO `dioplemaire-lignepj` (`numligne`, `datepage`, `designation`, `unitevente`, `prixunitevente`, `quantite`, `remise`, `prixtotal`, `typeligne`) VALUES
(15, '14-05-2016', 'bic crayon', 'article', 150, 30, 0, 4500, 'Entree'),
(11, '14-05-2016', 'bic bleu', 'paquet', 1500, 5, 0, 7500, 'Entree'),
(12, '14-05-2016', 'bic bleu', 'paquet', 1500, 4, 0, 6000, 'Entree'),
(13, '14-05-2016', 'bic bleu', 'paquet', 1500, 16, 0, 24000, 'Entree'),
(14, '14-05-2016', 'bic crayon', 'paquet', 2000, 10, 0, 20400, 'Entree'),
(16, '13-05-2016', 'bic bleu', 'paquet', 1500, 2, 0, 3000, 'Entree'),
(17, '15-05-2016', 'bic crayon', 'paquet', 2000, 15, 0, 30000, 'Entree'),
(18, '15-05-2016', 'bic crayon', 'paquet', 2000, 5, 0, 10000, 'Entree'),
(19, '09-07-2016', 'Depense gerant', 'article', 2000, 1, 0, 2000, 'Sortie'),
(20, '09-07-2016', 'bic crayon', 'paquet', 2000, 5, 500, 9500, 'Entree'),
(21, '19-07-2016', 'Depense gerant', 'article', 2000, 1, 0, 2000, 'Sortie'),
(22, '29-07-2016', 'Depense gerant', 'article', 2000, 1, 0, 2000, 'Entree'),
(23, '29-07-2016', 'bic crayon', 'article', 150, 20, 0, 3000, 'Entree'),
(24, '29-07-2016', 'Depense gerant', 'article', 2050, 1, 0, 2050, 'Sortie'),
(25, '29-07-2016', 'bic crayon', 'paquet', 2000, 2, 0, 4000, 'Entree'),
(26, '29-07-2016', 'Photocopie', 'article', 100, 250, 1500, 23500, 'Entree'),
(27, '29-07-2016', 'bic bleu', 'article', 100, 2, 0, 200, 'Entree'),
(28, '29-07-2016', 'bic bleu', 'paquet', 1500, 1, 0, 1500, 'Entree'),
(29, '29-07-2016', 'bic bleu', 'paquet', 1500, 2, 0, 3000, 'Entree'),
(30, '31-07-2016', 'bic bleu', 'paquet', 1500, 80, 0, 120000, 'Entree'),
(31, '31-07-2016', 'bic bleu', 'article', 100, 100, 0, 10000, 'Entree'),
(49, '01-08-2016', 'bic bleu', 'article', 100, 2, 0, 0, 'Entree'),
(33, '31-07-2016', 'bic bleu', 'article', 100, 27, 0, 2700, 'Entree'),
(48, '01-08-2016', 'Gomme', 'paquet', 3000, 2, 0, 6000, 'Entree'),
(50, '02-08-2016', 'bic bleu', 'paquet', 1500, 3, 0, 4500, 'Entree'),
(51, '02-08-2016', 'Depense gerant', 'article', 2050, 1, 0, 2050, 'Entree'),
(52, '02-08-2016', 'bic crayon', 'article', 150, 10, 0, 1500, 'Entree'),
(53, '04-08-2016', 'bic bleu', 'paquet', 1500, 2, 0, 3000, 'Entree'),
(54, '04-08-2016', 'Depense gerant', 'article', 2050, 1, 0, 2050, 'Sortie'),
(55, '06-08-2016', 'Ardoise', 'paquet', 200, 2, 0, 400, 'Entree');

-- --------------------------------------------------------

--
-- Structure de la table `dioplemaire-pagej`
--

CREATE TABLE IF NOT EXISTS `dioplemaire-pagej` (
  `numpage` int(11) NOT NULL AUTO_INCREMENT,
  `datepage` varchar(100) NOT NULL,
  `tentreesp` double NOT NULL,
  `tsortiesp` double NOT NULL,
  PRIMARY KEY (`numpage`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `dioplemaire-pagej`
--

INSERT INTO `dioplemaire-pagej` (`numpage`, `datepage`, `tentreesp`, `tsortiesp`) VALUES
(1, '14-05-2016', 62400, 0),
(2, '13-05-2016', 3000, 0),
(3, '15-05-2016', 40000, 0),
(4, '09-07-2016', 9500, 2000),
(5, '19-07-2016', 0, 2000),
(6, '21-07-2016', 0, 0),
(7, '29-07-2016', 37200, 2050),
(8, '30-07-2016', 0, 0),
(9, '31-07-2016', 132700, 0),
(10, '01-08-2016', 6000, 0),
(11, '02-08-2016', 8050, 0),
(12, '04-08-2016', 3000, 2050),
(13, '06-08-2016', 400, 0);

-- --------------------------------------------------------

--
-- Structure de la table `dioplemaire-pagnet`
--

CREATE TABLE IF NOT EXISTS `dioplemaire-pagnet` (
  `idpagnet` int(11) NOT NULL AUTO_INCREMENT,
  `datepagej` int(11) NOT NULL,
  `totalp` double NOT NULL,
  PRIMARY KEY (`idpagnet`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `dioplemaire-pagnet`
--


-- --------------------------------------------------------

--
-- Structure de la table `dioplemaire-stock`
--

CREATE TABLE IF NOT EXISTS `dioplemaire-stock` (
  `idStock` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(155) NOT NULL,
  `quantiteStockinitial` double NOT NULL,
  `uniteStock` varchar(100) NOT NULL,
  `nbreArticleUniteStock` int(11) NOT NULL,
  `totalArticleStock` double NOT NULL,
  `dateStockage` varchar(100) NOT NULL,
  `quantiteStockCourant` double NOT NULL,
  `dateFinStock` varchar(100) NOT NULL,
  `dateExpiration` varchar(100) NOT NULL,
  PRIMARY KEY (`idStock`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

--
-- Contenu de la table `dioplemaire-stock`
--

INSERT INTO `dioplemaire-stock` (`idStock`, `designation`, `quantiteStockinitial`, `uniteStock`, `nbreArticleUniteStock`, `totalArticleStock`, `dateStockage`, `quantiteStockCourant`, `dateFinStock`, `dateExpiration`) VALUES
(1, 'bic bleu', 10, 'article', 1, 10, '15-05-2016', 0, '04-08-2016', ''),
(2, 'bic bleu', 15, 'paquet', 10, 150, '15-05-2016', 0, '04-08-2016', ''),
(3, 'bic crayon', 15, 'paquet', 50, 750, '15-05-2016', 0, '02-08-2016', ''),
(4, 'bic crayon', 15, 'article', 1, 15, '15-05-2016', 0, '02-08-2016', ''),
(5, 'bic crayon', 5, 'paquet', 10, 0, '15-05-2016', 0, '02-08-2016', ''),
(6, 'bic bleu', 15, 'paquet', 50, 750, '15-05-2016', 277, '', ''),
(7, 'Ordinateur', 2, 'article', 1, 0, '09-07-2016', 0, '', ''),
(8, 'Ordinateur', 5, 'article', 1, 0, '09-07-2016', 0, '', ''),
(16, 'Gomme', 2, 'paquet', 100, 200, '31-07-2016', 0, '01-08-2016', ''),
(10, 'Gomme', 5, 'paquet', 100, 500, '30-07-2016', 0, '01-08-2016', ''),
(11, 'cahier 100 pages', 2, 'paquet', 10, 20, '31-07-2016', 20, '', ''),
(12, 'telephone ', 25, 'article', 1, 25, '31-07-2016', 25, '', ''),
(13, 'television', 5, 'article', 1, 5, '31-07-2016', 1, '', ''),
(14, 'Ordinateur', 10, 'article', 1, 10, '31-07-2016', 10, '', ''),
(15, 'Ordinateur', 100, 'article', 1, 100, '31-07-2016', 100, '', ''),
(17, 'Gomme', 2, 'paquet', 100, 200, '31-07-2016', 0, '01-08-2016', ''),
(36, 'Gomme', 2, 'paquet', 100, 200, 'Retour', 200, '', ''),
(35, 'television', 10, 'article', 1, 10, '01-08-2016', 10, '', ''),
(34, 'Gomme', 2, 'paquet', 100, 200, 'Retour', 200, '', ''),
(33, 'Gomme', 2, 'paquet', 100, 200, 'Retour', 200, '', ''),
(32, 'Gomme', 2, 'paquet', 100, 200, 'Retour', 200, '', ''),
(37, 'Gomme', 2, 'paquet', 100, 200, 'Retour', 200, '', ''),
(38, 'bic bleu', 2, 'article', 1, 2, 'Retour', 2, '', ''),
(39, 'bic bleu', 2, 'article', 1, 2, 'Retour', 2, '', ''),
(40, 'bic crayon', 10, 'article', 50, 500, 'Retour', 500, '', ''),
(41, 'Ardoise', 10, 'article', 20, 200, '02-08-2016', 160, '', ''),
(42, 'Ardoise', 5, 'article', 20, 100, '02-08-2016', 100, '', ''),
(43, 'telephone ', 1, 'article', 1, 1, '02-08-2016', 1, '', ''),
(44, 'Cahier 200 pages', 5, 'article', 5, 5, '02-08-2016', 5, '', ''),
(45, 'Ardoise', 5, 'article', 20, 5, '02-08-2016', 5, '', ''),
(46, 'bic bleu', 2, 'article', 50, 2, '04-08-2016', 2, '', ''),
(47, 'Ardoise', 2, 'paquet', 20, 40, 'Retour', 40, '', '');

-- --------------------------------------------------------

--
-- Structure de la table `dioplemaire-totalstock`
--

CREATE TABLE IF NOT EXISTS `dioplemaire-totalstock` (
  `idStock` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(155) NOT NULL,
  `quantiteEnStocke` double NOT NULL,
  `dateExpiration` varchar(50) NOT NULL,
  PRIMARY KEY (`idStock`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `dioplemaire-totalstock`
--

INSERT INTO `dioplemaire-totalstock` (`idStock`, `designation`, `quantiteEnStocke`, `dateExpiration`) VALUES
(4, 'bic crayon', 619, '15-05-2016'),
(3, 'bic bleu', 356, '15-05-2016'),
(5, 'Ordinateur', 110, '09-07-2016'),
(6, 'television', 11, '31-07-2016'),
(7, 'Gomme', 298, '31-07-2016'),
(8, 'cahier 100 pages', 20, ''),
(9, 'telephone ', 26, '02-08-2016'),
(10, 'Ardoise', 265, '02-08-2016'),
(11, 'Cahier 200 pages', 5, '');

-- --------------------------------------------------------

--
-- Structure de la table `gerant`
--

CREATE TABLE IF NOT EXISTS `gerant` (
  `idutilisateur` int(11) NOT NULL,
  `idBoutique` int(11) NOT NULL,
  `profil` varchar(50) NOT NULL,
  PRIMARY KEY (`idutilisateur`,`idBoutique`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `gerant`
--

INSERT INTO `gerant` (`idutilisateur`, `idBoutique`, `profil`) VALUES
(1, 1, 'Admin'),
(6, 8, 'Admin');

-- --------------------------------------------------------

--
-- Structure de la table `salamservicessolutions-designation`
--

CREATE TABLE IF NOT EXISTS `salamservicessolutions-designation` (
  `idDesignation` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(155) NOT NULL,
  `uniteStock` varchar(155) NOT NULL,
  `prixuniteStock` double NOT NULL,
  `nbreArticleUniteStock` double NOT NULL,
  `prix` double NOT NULL,
  `classe` int(11) NOT NULL,
  PRIMARY KEY (`idDesignation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `salamservicessolutions-designation`
--


-- --------------------------------------------------------

--
-- Structure de la table `salamservicessolutions-journal`
--

CREATE TABLE IF NOT EXISTS `salamservicessolutions-journal` (
  `idjournal` int(11) NOT NULL AUTO_INCREMENT,
  `mois` int(11) NOT NULL,
  `annee` int(11) NOT NULL,
  `tentrees` double NOT NULL,
  `tsorties` double NOT NULL,
  PRIMARY KEY (`idjournal`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `salamservicessolutions-journal`
--


-- --------------------------------------------------------

--
-- Structure de la table `salamservicessolutions-lignepj`
--

CREATE TABLE IF NOT EXISTS `salamservicessolutions-lignepj` (
  `numligne` int(11) NOT NULL AUTO_INCREMENT,
  `datepage` varchar(100) NOT NULL,
  `designation` varchar(155) NOT NULL,
  `unitevente` double NOT NULL,
  `prixunitevente` double NOT NULL,
  `quantite` int(11) NOT NULL,
  `remise` double NOT NULL,
  `prixtotal` double NOT NULL,
  `typeligne` varchar(15) NOT NULL,
  PRIMARY KEY (`numligne`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `salamservicessolutions-lignepj`
--


-- --------------------------------------------------------

--
-- Structure de la table `salamservicessolutions-pagej`
--

CREATE TABLE IF NOT EXISTS `salamservicessolutions-pagej` (
  `numpage` int(11) NOT NULL AUTO_INCREMENT,
  `datepage` varchar(100) NOT NULL,
  `tentreesp` double NOT NULL,
  `tsortiesp` double NOT NULL,
  PRIMARY KEY (`numpage`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `salamservicessolutions-pagej`
--


-- --------------------------------------------------------

--
-- Structure de la table `salamservicessolutions-pagnet`
--

CREATE TABLE IF NOT EXISTS `salamservicessolutions-pagnet` (
  `idjournal` int(11) NOT NULL AUTO_INCREMENT,
  `mois` int(11) NOT NULL,
  `annee` int(11) NOT NULL,
  `tentrees` double NOT NULL,
  `tsorties` double NOT NULL,
  PRIMARY KEY (`idjournal`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `salamservicessolutions-pagnet`
--


-- --------------------------------------------------------

--
-- Structure de la table `salamservicessolutions-stock`
--

CREATE TABLE IF NOT EXISTS `salamservicessolutions-stock` (
  `idStock` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(155) NOT NULL,
  `quantiteStockinitial` double NOT NULL,
  `uniteStock` varchar(100) NOT NULL,
  `nbreArticleUniteStock` int(11) NOT NULL,
  `totalArticleStock` double NOT NULL,
  `dateStockage` varchar(50) NOT NULL,
  `quantiteStockCourant` double NOT NULL,
  `dateFinStock` varchar(50) NOT NULL,
  `dateExpiration` varchar(50) NOT NULL,
  PRIMARY KEY (`idStock`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `salamservicessolutions-stock`
--


-- --------------------------------------------------------

--
-- Structure de la table `salamservicessolutions-totalstock`
--

CREATE TABLE IF NOT EXISTS `salamservicessolutions-totalstock` (
  `idStock` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(155) NOT NULL,
  `quantiteEnStocke` double NOT NULL,
  `dateExpiration` varchar(50) NOT NULL,
  PRIMARY KEY (`idStock`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `salamservicessolutions-totalstock`
--


-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idutilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `motdepasse` varchar(50) NOT NULL,
  `profil` varchar(50) NOT NULL,
  `dateinscription` varchar(100) NOT NULL,
  PRIMARY KEY (`idutilisateur`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idutilisateur`, `nom`, `prenom`, `adresse`, `email`, `motdepasse`, `profil`, `dateinscription`) VALUES
(1, 'DIOP', 'Ibrahima', 'Goumel', 'ibrahima.diop@univ-zig.sn', 'jaimeDieu', 'Admin', '13-04-2016'),
(6, 'Goudiaby', 'Mouhamadou Samsidy', 'Scale ziguinchor', 'msgoudiaby@univ-zig.sn', 'jaimepro', 'Admin', '06-08-2016');
