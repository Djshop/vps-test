-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 12 mai 2022 à 15:34
-- Version du serveur : 10.4.21-MariaDB
-- Version de PHP : 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `djshop`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220426072745', '2022-04-26 09:29:16', 61),
('DoctrineMigrations\\Version20220510102136', '2022-05-10 12:21:39', 57),
('DoctrineMigrations\\Version20220510112926', '2022-05-10 13:29:30', 63),
('DoctrineMigrations\\Version20220510115428', '2022-05-10 13:54:32', 54);

-- --------------------------------------------------------

--
-- Structure de la table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `order_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `products` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `products_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `order`
--

INSERT INTO `order` (`id`, `order_name`, `buyer_id`, `products`, `total`, `shipping_status`, `products_id`) VALUES
(1, 'MHCZ8DVU', 1, 'Xbox 360 Slim RGH - Edition Limité Star Wars', '65', 'refund', '4'),
(6, '5PD4881E', 1, 'Xbox 360 Slim RGH - Edition Limité Star Wars', '60', 'waiting', '2'),
(7, '3IC41IK9', 1, 'Xbox 360 Slim Hell\'s Fire Red/Xbox 360 Slim RGH - Edition Limité Star Wars', '210', 'waiting', '11'),
(8, 'IYOGLRFA', 1, 'Xbox 360 Slim RGH - Edition Limité Star Wars', '150', 'cancel', '3'),
(9, 'UG7DPYIQ', 4, 'Xbox 360 Fat Red Galaxy', '170', 'waiting', '10'),
(10, 'H4XH5YL2', 1, 'Xbox 360 Slim RGH - Edition Limité Star Wars', '150', 'waiting', '6'),
(11, '4TFS8DM7', 1, 'Xbox 360 Slim RGH - Edition Limité Star Wars', '150', 'waiting', '7'),
(12, 'DYAJ8RF5', 1, 'Xbox 360 Slim RGH - Edition Limité Star Wars', '150', 'waiting', '8');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`) VALUES
(1, 'testfinal@mail.com', '[\"ROLE_USER\"]', '$2y$13$nth7yRkv73pwdb6.QXLGJ.FW7Xx4ZiJxDgTuccps/XsE3U3PP9VOm', 0),
(4, 'testfinal2@mail.com', '[\"ROLE_ADMIN\"]', '$2y$13$nth7yRkv73pwdb6.QXLGJ.FW7Xx4ZiJxDgTuccps/XsE3U3PP9VOm', 0);

-- --------------------------------------------------------

--
-- Structure de la table `xbox`
--

CREATE TABLE `xbox` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `generation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture` varchar(2000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sold` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `xbox`
--

INSERT INTO `xbox` (`id`, `title`, `description`, `price`, `generation`, `color`, `picture`, `sold`) VALUES
(1, 'Xbox 360 Slim RGH - Edition Limité Star Wars', 'descriptif de lma xbox Xbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star Wars', '200', 'xboxFat', '#ffffff#070ff3', 'uploads/626be68dbe137.png_uploads/626be68dbe6cb.png_uploads/626be68dbed89.png_uploads/626be68dbf547.png_uploads/626be85d123a9.png_uploads/626be85d1296d.png', 'SOLD'),
(2, 'Xbox 360 Slim RGH - Edition Limité Star Wars', 'descriptif de lma xbox Xbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star Wars', '60', 'xboxFat', '#ffffff#070ff3', 'uploads/626be6cc9274e.png_uploads/626be6cc93efa.png_uploads/626be6cc946f3.png_uploads/626be6d2ac3ee.png', 'SOLD'),
(3, 'Xbox 360 Slim RGH - Edition Limité Star Wars', 'descriptif de lma xbox Xbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star Wars', '150', 'xboxSlim', '#ffffff#070ff3', 'uploads/626be7249b32a.png_uploads/626be7249b8c9.png_uploads/626be72ca271c.png_uploads/626be72ca2ceb.png', 'SOLD'),
(4, 'Xbox 360 Slim RGH - Edition Limité Star Wars', 'descriptif de lma xbox Xbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star Wars', '65', 'xboxFat', '#ffffff#070ff3', 'uploads/626be74095b74.png_uploads/626be7486d05b.png_uploads/626be7486d6e2.png_uploads/626be7486dd68.png', 'SOLD'),
(6, 'Xbox 360 Slim RGH - Edition Limité Star Wars', 'descriptif de lma xbox Xbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star Wars', '150', 'xboxSlim', '#f70202/#000000/#000000', 'uploads/626be7803e848.png_uploads/626be7803f098.png_uploads/626be7803f6c9.png_uploads/626be7803fc32.png', 'SOLD'),
(7, 'Xbox 360 Slim RGH - Edition Limité Star Wars', 'descriptif de lma xbox Xbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star Wars', '150', 'xboxSlim', '#e90101#fd0d0d', 'uploads/626be7a773c4d.png_uploads/626be7a774231.png_uploads/626be7a7747ac.png_uploads/626be7ae072af.png', 'SOLD'),
(8, 'Xbox 360 Slim RGH - Edition Limité Star Wars', 'descriptif de lma xbox Xbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star Wars', '150', 'xboxSlim', '#e90101#fd0d0d', 'uploads/626be7d511956.png_uploads/626be7d511f19.png_uploads/626be7dd66760.png_uploads/626be7dd66d1d.png', 'SOLD'),
(10, 'Xbox 360 Fat Red Galaxy', 'Xbox 360 Fat Red Galaxy', '170', 'xboxSlim', '#a30000#ffffff', 'uploads/6278e1458d68c.jpg_uploads/6278e1458dc90.jpg_uploads/6278e1458e2ea.jpg_uploads/6278e1458e903.jpg', 'SOLD'),
(11, 'Xbox 360 Slim Hell\'s Fire Red', 'Xbox 360 Slim Hell\'s Fire Red', '210', 'xboxSlim', '#8f0000#ffffff', 'uploads/6278e33f8904b.jpg_uploads/6278e33f8964e.jpg_uploads/6278e33f89d67.jpg_uploads/6278e33f8a3f7.jpg', 'SOLD');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Index pour la table `xbox`
--
ALTER TABLE `xbox`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `xbox`
--
ALTER TABLE `xbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
