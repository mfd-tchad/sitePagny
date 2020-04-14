-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  ven. 10 avr. 2020 à 13:51
-- Version du serveur :  10.1.38-MariaDB
-- Version de PHP :  7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `sitepagny`
--
CREATE DATABASE IF NOT EXISTS sitepagny CHARACTER SET utf8mb4;
GRANT ALL ON sitepagny TO 'mfd'@localhost IDENTIFIED BY 'mfd5345';
FLUSH PRIVILEGES;
-- --------------------------------------------------------
USE sitepagny;
--
-- Structure de la table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `nom` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse_mail` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

CREATE TABLE `evenement` (
  `id` int(11) NOT NULL,
  `type` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `evenement`
--

INSERT INTO `evenement` (`id`, `type`, `date`, `description`, `created_at`, `titre`, `image`) VALUES
(1, '1', '2019-04-11 13:30:00', 'Mme Bienaimé était connue en tant que jeune fille sous le nom de Dewulf. Elle a grandi à Pagny, s\'y est mariée et y a passé le reste de sa vie. Nous adressons nos condoléances à ses enfants et tous ceux qui l\'ont côtoyée.', '2019-04-12 17:26:35', 'Mme Monique Bienaimé', NULL),
(2, '4', '2019-08-06 06:00:00', 'Les inscriptions pour le vide-greniers sont ouvertes auprès de Mme Marie-José Prud\'homme.', '2019-04-12 17:28:09', 'Inscriptions', NULL),
(3, '1', '2020-01-01 00:00:00', 'Monsieur Marcel Jacquotte, compagnon de Madame Féron-Féral est décédé des suites d\'une longue maladie. A sa compagne, ses enfants et amis marqués par ce départ, nous adressons nos sincères condoléances.', '2020-02-18 11:01:53', 'M. Marcel Jacquotte', NULL),
(4, '1', '2019-08-01 00:00:00', 'Dominique Beck est natif du village et a été renommé Patrick par ses parents au décès de son jeune frère qui s\'appelait Patrick et qui est décédé dans son jeune âge. Dominique est décédé dans l\'Ouest de la France des suites d\'une longue maladie et ses cendres ont été ramenées au village. A sa famille éprouvée, nous adressons nos sincères condoléances.', '2020-02-18 11:07:01', 'M. Patrick Beck', NULL),
(5, '3', '2020-05-01 00:00:00', 'La fête annuelle aura lieu selon la coutume, début Mai. Un office sera célébré en l\'église St Grégoire.', '2020-02-18 11:10:05', 'Fête annuelle du village', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20190404103319', '2019-04-12 15:07:40'),
('20190409054707', '2019-04-12 15:07:41'),
('20190410054244', '2019-04-12 15:07:41'),
('20190410071407', '2019-04-12 15:07:42'),
('20190410110522', '2019-04-12 15:07:42'),
('20190410115232', '2019-04-12 15:07:43');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`) VALUES
(1, 'mairie', '$2y$12$KmechLRnFjRScSOUYnhYq.KSOw5818Q3XBjdtA3XEKtQ2CsVa2rgq'),
(2, 'mfd', 'mfd');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `evenement`
--
ALTER TABLE `evenement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
