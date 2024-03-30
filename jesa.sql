-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 12 juin 2023 à 13:20
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `jesa`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `Id_admin` int(11) NOT NULL,
  `Nom_admin` longtext NOT NULL,
  `Date_admin` date DEFAULT NULL,
  `Tele_admin` varchar(20) DEFAULT NULL,
  `Email_admin` longtext NOT NULL,
  `Mdps_admin` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`Id_admin`, `Nom_admin`, `Date_admin`, `Tele_admin`, `Email_admin`, `Mdps_admin`) VALUES
(1, 'admin', '2002-09-13', '0609658998', 'admin', '1234');

-- --------------------------------------------------------

--
-- Structure de la table `discipline`
--

CREATE TABLE `discipline` (
  `Id_discipline` int(11) NOT NULL,
  `Id_phase` int(11) NOT NULL,
  `Titre_discipline` longtext NOT NULL,
  `numero_discipline` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `discipline`
--

INSERT INTO `discipline` (`Id_discipline`, `Id_phase`, `Titre_discipline`, `numero_discipline`) VALUES
(1, 1, 'E&D', 1),
(2, 1, 'Mechanical', 2),
(3, 1, 'Electrical', 3),
(4, 1, 'I&C', 4),
(5, 1, 'Process', 5),
(6, 1, 'Piping', 6),
(7, 1, 'Civil', 7),
(8, 2, 'E&D', 1),
(9, 2, 'Mechanical', 2),
(10, 2, 'Electrical', 3),
(11, 2, 'I&C', 4),
(12, 2, 'Process', 5),
(13, 2, 'Piping', 6),
(14, 2, 'Civil', 7),
(15, 3, 'E&D', 1),
(16, 3, 'Mechanical', 2),
(17, 3, 'Electrical', 3),
(18, 3, 'I&C', 4),
(19, 3, 'Process', 5),
(20, 3, 'Piping', 6),
(21, 3, 'Civil', 7),
(22, 4, 'E&D', 1),
(23, 4, 'Mechanical', 2),
(24, 4, 'Electrical', 3),
(25, 4, 'I&C', 4),
(26, 4, 'Process', 5),
(27, 4, 'Piping', 6),
(28, 4, 'Civil', 7),
(29, 5, 'E&D', 1),
(30, 5, 'Mechanical', 2),
(31, 5, 'Electrical', 3),
(32, 5, 'I&C', 4),
(33, 5, 'Process', 5),
(34, 5, 'Piping', 6),
(35, 5, 'Civil', 7),
(36, 6, 'E&D', 1),
(37, 6, 'Mechanical', 2),
(38, 6, 'Electrical', 3),
(39, 6, 'I&C', 4),
(40, 6, 'Process', 5),
(41, 6, 'Piping', 6),
(42, 6, 'Civil', 7);

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE `historique` (
  `Id_his` int(11) NOT NULL,
  `Id_user` int(11) NOT NULL,
  `Type_user` varchar(255) DEFAULT NULL,
  `Action_his` varchar(255) NOT NULL,
  `Date_his` datetime NOT NULL,
  `Details_his` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `historique`
--

INSERT INTO `historique` (`Id_his`, `Id_user`, `Type_user`, `Action_his`, `Date_his`, `Details_his`) VALUES
(2, 1, 'admin', 'Ajout manager ', '2023-06-06 15:09:44', 'manager '),
(3, 2, 'manager', 'Ajout projet ', '2023-06-06 15:46:11', ''),
(4, 1, 'admin', 'Ajout projet 25', '2023-06-08 15:44:54', ''),
(5, 1, 'admin', 'Suppression projet 8', '2023-06-08 15:44:57', ''),
(6, 1, 'admin', 'Suppression projet 25', '2023-06-08 15:45:02', ''),
(7, 1, 'admin', 'Suppression projet 24', '2023-06-08 15:45:07', ''),
(8, 2, 'manager', 'Suppression projet 6', '2023-06-08 15:46:50', ''),
(9, 2, 'manager', 'Ajout projet 26', '2023-06-08 15:46:53', ''),
(10, 1, 'admin', 'Connexion', '2023-06-08 17:29:53', 'admin s\'est connecté.'),
(11, 2, 'manager', 'Connexion', '2023-06-08 17:34:34', 'imadjakhrouti@gmail.com s\'est connecté depuis l\'appareil . DESKTOP-9I8ESUH'),
(12, 1, 'admin', 'Connexion', '2023-06-08 17:35:34', 'admin s\'est connecté depuis l\'appareil  . DESKTOP-9I8ESUH'),
(13, 1, 'admin', 'Connexion', '2023-06-08 20:00:51', 'admin s\'est connecté depuis l\'appareil DESKTOP-9I8ESUH'),
(14, 1, 'admin', 'Modification projet 5', '2023-06-08 21:04:12', ''),
(15, 1, 'admin', 'Modification projet 1', '2023-06-08 21:26:28', ''),
(16, 1, 'admin', 'Modification projet 1', '2023-06-08 21:28:34', ''),
(17, 1, 'admin', 'Modification projet 1', '2023-06-08 22:08:23', ''),
(18, 1, 'admin', 'Modification projet 1', '2023-06-08 22:41:27', ''),
(19, 1, 'admin', 'Modification projet 1', '2023-06-08 22:53:24', ''),
(20, 1, 'admin', 'Modification projet 1', '2023-06-08 22:54:57', ''),
(21, 1, 'admin', 'Modification projet 1', '2023-06-08 22:55:44', ''),
(22, 1, 'admin', 'Modification projet 10', '2023-06-09 15:27:13', ''),
(23, 1, 'admin', 'Suppression projet 11', '2023-06-09 15:31:07', ''),
(24, 1, 'admin', 'Modification projet 7', '2023-06-09 15:35:44', ''),
(25, 1, 'admin', 'Modification projet 5', '2023-06-09 15:46:27', ''),
(26, 1, 'admin', 'Ajout manager 27', '2023-06-09 15:48:12', 'manager '),
(27, 1, 'admin', 'Modification projet 5', '2023-06-09 16:20:26', ''),
(28, 1, 'admin', 'Connexion', '2023-06-09 19:26:41', 'admin s\'est connecté depuis l\'appareil DESKTOP-9I8ESUH'),
(29, 1, 'admin', 'Déconnexion', '2023-06-10 21:57:48', ' s\'est déconnecté depuis l\'appareil DESKTOP-9I8ESUH'),
(30, 1, 'admin', 'Connexion', '2023-06-10 21:57:56', 'admin s\'est connecté depuis l\'appareil DESKTOP-9I8ESUH'),
(31, 1, 'admin', 'Modification projet 7', '2023-06-10 20:58:59', ''),
(32, 1, 'admin', 'Déconnexion', '2023-06-10 22:01:24', ' s\'est déconnecté depuis l\'appareil DESKTOP-9I8ESUH'),
(33, 1, 'admin', 'Connexion', '2023-06-10 22:01:35', 'admin s\'est connecté depuis l\'appareil DESKTOP-9I8ESUH'),
(34, 1, 'admin', 'Connexion', '2023-06-10 23:47:02', 'admin s\'est connecté depuis l\'appareil DESKTOP-9I8ESUH'),
(35, 1, 'admin', 'Déconnexion', '2023-06-12 13:04:11', ' s\'est déconnecté depuis l\'appareil DESKTOP-9I8ESUH'),
(36, 1, 'admin', 'Connexion', '2023-06-12 13:04:18', 'admin s\'est connecté depuis l\'appareil DESKTOP-9I8ESUH');

-- --------------------------------------------------------

--
-- Structure de la table `manager`
--

CREATE TABLE `manager` (
  `Id_manager` int(11) NOT NULL,
  `Nom_manager` longtext NOT NULL,
  `Date_manager` date DEFAULT NULL,
  `Tele_manager` varchar(20) DEFAULT NULL,
  `Email_manager` longtext NOT NULL,
  `Mdps_manager` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `manager`
--

INSERT INTO `manager` (`Id_manager`, `Nom_manager`, `Date_manager`, `Tele_manager`, `Email_manager`, `Mdps_manager`) VALUES
(1, 'manager', NULL, NULL, 'manager', '1234'),
(2, 'imad', '2002-09-13', '0609658998', 'imadjakhrouti@gmail.com', '1234'),
(19, 'adam', NULL, NULL, 'adam@gmail.com', '1234'),
(26, 'mimoun', NULL, NULL, 'mimoun@gmail.com', '1234'),
(27, 'gf', NULL, NULL, 'gf', 'hgfg');

-- --------------------------------------------------------

--
-- Structure de la table `phase`
--

CREATE TABLE `phase` (
  `Id_phase` int(11) NOT NULL,
  `Titre_phase` longtext NOT NULL,
  `numero_phase` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `phase`
--

INSERT INTO `phase` (`Id_phase`, `Titre_phase`, `numero_phase`) VALUES
(1, 'Faisabilité', 1),
(2, 'Conceptuel', 2),
(3, 'Basic', 3),
(4, 'Details', 4),
(5, 'Construction', 5),
(6, 'Comisionning', 6);

-- --------------------------------------------------------

--
-- Structure de la table `projet`
--

CREATE TABLE `projet` (
  `Id_projet` int(11) NOT NULL,
  `Id_manager` int(11) NOT NULL,
  `Code_projet` int(11) NOT NULL,
  `Nom_projet` longtext NOT NULL,
  `Date_projet` date NOT NULL,
  `Id_phase` int(11) NOT NULL,
  `Id_discipline` int(11) NOT NULL,
  `Estimated` float NOT NULL,
  `Burned` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `projet`
--

INSERT INTO `projet` (`Id_projet`, `Id_manager`, `Code_projet`, `Nom_projet`, `Date_projet`, `Id_phase`, `Id_discipline`, `Estimated`, `Burned`) VALUES
(5, 2, 0, 'JESAq', '0000-00-00', 6, 42, 0, 0),
(7, 1, 0, 'lky', '0000-00-00', 1, 4, 0, 0),
(9, 1, 0, '', '0000-00-00', 1, 3, 0, 0),
(10, 1, 0, '', '0000-00-00', 1, 1, 0, 344),
(12, 1, 0, '', '0000-00-00', 1, 1, 0, 0),
(13, 1, 0, '', '0000-00-00', 1, 1, 0, 0),
(14, 1, 0, '', '0000-00-00', 1, 1, 0, 0),
(15, 1, 0, '', '0000-00-00', 1, 1, 0, 0),
(16, 1, 0, '', '0000-00-00', 1, 1, 0, 0),
(17, 1, 0, 'err', '0000-00-00', 1, 1, 0, 0),
(19, 1, 34, 'erf', '0000-00-00', 1, 1, 344, 344),
(20, 1, 0, 'imad', '0000-00-00', 1, 1, 444, 444),
(21, 2, 12323, 'JESA2', '2023-06-15', 1, 1, 34.34, 62.09),
(22, 19, 34, 'eer', '0000-00-00', 1, 1, 122, 2212),
(23, 1, 0, '', '0000-00-00', 1, 1, 0, 0),
(26, 2, 0, '', '0000-00-00', 1, 1, 0, 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Id_admin`);

--
-- Index pour la table `discipline`
--
ALTER TABLE `discipline`
  ADD PRIMARY KEY (`Id_discipline`),
  ADD KEY `FK_NECESSITE` (`Id_phase`);

--
-- Index pour la table `historique`
--
ALTER TABLE `historique`
  ADD PRIMARY KEY (`Id_his`);

--
-- Index pour la table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`Id_manager`);

--
-- Index pour la table `phase`
--
ALTER TABLE `phase`
  ADD PRIMARY KEY (`Id_phase`);

--
-- Index pour la table `projet`
--
ALTER TABLE `projet`
  ADD PRIMARY KEY (`Id_projet`),
  ADD KEY `fk_manager` (`Id_manager`),
  ADD KEY `Id_phase` (`Id_phase`),
  ADD KEY `Id_discipline` (`Id_discipline`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `Id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `discipline`
--
ALTER TABLE `discipline`
  MODIFY `Id_discipline` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `historique`
--
ALTER TABLE `historique`
  MODIFY `Id_his` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `manager`
--
ALTER TABLE `manager`
  MODIFY `Id_manager` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `phase`
--
ALTER TABLE `phase`
  MODIFY `Id_phase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `projet`
--
ALTER TABLE `projet`
  MODIFY `Id_projet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `discipline`
--
ALTER TABLE `discipline`
  ADD CONSTRAINT `FK_NECESSITE` FOREIGN KEY (`Id_phase`) REFERENCES `phase` (`Id_phase`);

--
-- Contraintes pour la table `projet`
--
ALTER TABLE `projet`
  ADD CONSTRAINT `fk_manager` FOREIGN KEY (`Id_manager`) REFERENCES `manager` (`Id_manager`),
  ADD CONSTRAINT `projet_ibfk_1` FOREIGN KEY (`Id_phase`) REFERENCES `phase` (`Id_phase`),
  ADD CONSTRAINT `projet_ibfk_2` FOREIGN KEY (`Id_discipline`) REFERENCES `discipline` (`Id_discipline`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
