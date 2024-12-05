-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 05 déc. 2024 à 18:25
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `wattsup`
--
CREATE DATABASE IF NOT EXISTS `wattsup` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `wattsup`;

-- --------------------------------------------------------

--
-- Structure de la table `commission`
--

DROP TABLE IF EXISTS `commission`;
CREATE TABLE IF NOT EXISTS `commission` (
  `id` int NOT NULL AUTO_INCREMENT,
  `author_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `closed_at` datetime DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL,
  `is_temp` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1C650158F675F31B` (`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commission`
--

INSERT INTO `commission` (`id`, `author_id`, `name`, `created_at`, `closed_at`, `is_closed`, `is_temp`) VALUES
(1, NULL, 'global', '2024-09-26 14:50:44', NULL, 0, 0),
(2, NULL, 'animation', '2024-09-26 00:00:00', NULL, 0, 0),
(3, NULL, 'communication', '2024-09-26 14:53:49', NULL, 0, 0),
(4, NULL, 'finances', '2024-09-26 14:53:49', NULL, 0, 0),
(5, NULL, 'restauration', '2024-09-26 00:00:00', NULL, 0, 0),
(10, 13, 'fête de village', '2024-12-05 00:00:00', '2024-12-05 00:00:00', 0, 1),
(11, 14, 'test', '2024-12-05 17:56:03', '2024-12-13 18:56:00', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240611132547', '2024-06-11 13:26:06', 154),
('DoctrineMigrations\\Version20240611132757', '2024-06-11 13:28:03', 28),
('DoctrineMigrations\\Version20240710133146', '2024-07-10 13:31:57', 46),
('DoctrineMigrations\\Version20240710135641', '2024-07-10 13:56:55', 74),
('DoctrineMigrations\\Version20240712092819', '2024-07-12 09:28:34', 89),
('DoctrineMigrations\\Version20240712093358', '2024-07-12 09:34:11', 95),
('DoctrineMigrations\\Version20240712094508', '2024-07-12 09:45:16', 278),
('DoctrineMigrations\\Version20240712094958', '2024-07-12 09:50:02', 416),
('DoctrineMigrations\\Version20240712095801', '2024-07-12 09:58:06', 160),
('DoctrineMigrations\\Version20240712100021', '2024-07-12 10:00:25', 176),
('DoctrineMigrations\\Version20240712104922', '2024-07-12 10:49:26', 179),
('DoctrineMigrations\\Version20240712105657', '2024-07-12 10:57:04', 103),
('DoctrineMigrations\\Version20240712111555', '2024-07-12 11:16:07', 968);

-- --------------------------------------------------------

--
-- Structure de la table `forget_password`
--

DROP TABLE IF EXISTS `forget_password`;
CREATE TABLE IF NOT EXISTS `forget_password` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `date` datetime DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C816EDE2A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `forget_password`
--

INSERT INTO `forget_password` (`id`, `user_id`, `date`, `token`) VALUES
(13, 13, '2024-12-05 17:37:09', 'e540b435-8c91-422b-b9e8-8b37cab3708e');

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `commission_id` int NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_B6BD307FA76ED395` (`user_id`),
  KEY `IDX_B6BD307F202D1EB2` (`commission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `user_id`, `commission_id`, `text`, `created_at`, `is_read`) VALUES
(104, 15, 1, 'ça va vous ?', '2024-12-05 17:40:10', 0),
(105, 13, 1, 'très bien et toi?', '2024-12-05 17:42:48', 0),
(106, 13, 10, 'bonjour !', '2024-12-05 17:46:21', 0),
(107, 15, 10, 'ça va vous ?', '2024-12-05 17:46:46', 0),
(108, 14, 11, 'ça va vous ?', '2024-12-05 17:56:15', 0);

-- --------------------------------------------------------

--
-- Structure de la table `message_read_status`
--

DROP TABLE IF EXISTS `message_read_status`;
CREATE TABLE IF NOT EXISTS `message_read_status` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message_id` int NOT NULL,
  `user_id` int NOT NULL,
  `commission_id` int NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`),
  KEY `user_id` (`user_id`),
  KEY `commission_id` (`commission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

DROP TABLE IF EXISTS `notification`;
CREATE TABLE IF NOT EXISTS `notification` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `commission_id` int NOT NULL,
  `is_notify` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BF5476CAA76ED395` (`user_id`),
  KEY `IDX_BF5476CA202D1EB2` (`commission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `nom`, `prenom`) VALUES
(13, 'agust.quintero@gmail.com', '[\"ROLE_ADMINISTRATEUR\"]', '$2y$13$jAb0hi3IbhhfwLRLZt.QT.6Twq6Qi8jj8v4T6M.T0YKFwLLB9UWmG', 'QUINTERO', 'Agustin'),
(14, 'clement.torija@limayrac.fr', '[\"ROLE_MODERATEUR\"]', '$2y$13$ym9ZaNMEeXnhT2lfuIQ9JeixU0csqn3LXJNUN.F9wCHNbBujoPjZ2', 'TORIJA', 'Clement'),
(15, 'o.cazemajou@limayrac.fr', '[\"ROLE_ADMINISTRATEUR\"]', '$2y$13$EJhXN3fo2XhMAkit.hQFourbk4h2Rj.wLqaf/nZs.CI93Hon5NOku', 'CAZEMAJOU', 'Olivier');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commission`
--
ALTER TABLE `commission`
  ADD CONSTRAINT `FK_1C650158F675F31B` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `forget_password`
--
ALTER TABLE `forget_password`
  ADD CONSTRAINT `FK_C816EDE2A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_B6BD307F202D1EB2` FOREIGN KEY (`commission_id`) REFERENCES `commission` (`id`),
  ADD CONSTRAINT `FK_B6BD307FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `message_read_status`
--
ALTER TABLE `message_read_status`
  ADD CONSTRAINT `message_read_status_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `message` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_read_status_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_read_status_ibfk_3` FOREIGN KEY (`commission_id`) REFERENCES `commission` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `FK_BF5476CA202D1EB2` FOREIGN KEY (`commission_id`) REFERENCES `commission` (`id`),
  ADD CONSTRAINT `FK_BF5476CAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
