-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 29 mai 2022 à 09:42
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
-- Structure de la table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `products_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adress` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer_firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer_lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('DoctrineMigrations\\Version20220510115428', '2022-05-10 13:54:32', 54),
('DoctrineMigrations\\Version20220516091252', '2022-05-16 11:12:55', 84),
('DoctrineMigrations\\Version20220517112614', '2022-05-17 13:26:19', 257),
('DoctrineMigrations\\Version20220517133950', '2022-05-17 15:39:54', 53),
('DoctrineMigrations\\Version20220520074703', '2022-05-20 09:47:13', 706),
('DoctrineMigrations\\Version20220524134114', '2022-05-24 15:41:19', 233),
('DoctrineMigrations\\Version20220524134835', '2022-05-24 15:48:38', 50),
('DoctrineMigrations\\Version20220524140759', '2022-05-24 16:08:02', 53),
('DoctrineMigrations\\Version20220525090955', '2022-05-25 11:10:48', 127);

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
  `products_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adress` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refund_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `order`
--

INSERT INTO `order` (`id`, `order_name`, `buyer_id`, `products`, `total`, `shipping_status`, `products_id`, `adress`, `postal`, `city`, `phone`, `refund_id`, `firstname`, `lastname`) VALUES
(31, '3HMN1TIA', 7, 'Xbox 360 Slim RGH - Edition Limité Star Wars', '150', 'gegege21', '6', '194 rued de la street 307', '31200', 'Toulouse', '0623285647', NULL, 'Yanis', 'LastYanis'),
(32, '6LCVW9LZ', 7, 'Xbox 360 Fat RGH Red Galaxy', '170', 'cancel', '25', '194 rue Edmond Rostandde ', '31201', 'Toulousa', '0623285649', 'fe654ef64e5f', 'Yanisde', 'Mengouchide'),
(33, 'TPV3JGK5', 8, 'Xbox 360 Slim RGH - Edition Limité Star Wars', '150', 'refund', '23', '194 rue Edmond Rostand', '31200', 'Toulouse', '0623285647', NULL, 'gg', 'gg');

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reset_password_request`
--

INSERT INTO `reset_password_request` (`id`, `user_id`, `selector`, `hashed_token`, `requested_at`, `expires_at`) VALUES
(2, 7, 'eZvhJ7UDRXiJ66RQIzKm', 'l7AGbhHz8fUXIUJuGWObZcHY7FYuMjuwNo5/GTou8zw=', '2022-05-25 11:38:20', '2022-05-25 12:38:20'),
(5, 5, '2LGdvWnDdjgLyIhSWYzf', 'MzEL05T9DFJ3dfOxE4eRklHcrmRPTCHaIo5Tcx7KkwU=', '2022-05-25 14:22:13', '2022-05-25 15:22:13'),
(6, 5, '5IkecZeZnfznk63DFTdL', 'iuxhrOLfX7ScjVvsy1+JjjzosJ9ZQUKXcllzxVk4kVI=', '2022-05-25 16:43:23', '2022-05-25 17:43:23'),
(7, 7, 'AoCLQiP7TBT5yXb9a8hT', '8/ZCmggsmMOQiPdVpRP0LhX5LKjNDbpY5TypkbAczwU=', '2022-05-25 16:45:38', '2022-05-25 17:45:38');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `cart` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adress` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `cart`, `adress`, `postal`, `city`, `phone`, `firstname`, `lastname`) VALUES
(1, 'testfinal@mail.com', '[\"ROLE_USER\"]', '$2y$13$nth7yRkv73pwdb6.QXLGJ.FW7Xx4ZiJxDgTuccps/XsE3U3PP9VOm', 0, 'empty', '7 avenue du lycée', '31650', 'Toulouse', '0623252458', 'Yanis', 'LastYanis'),
(4, 'testfinal2@mail.com', '[\"ROLE_ADMIN\"]', '$2y$13$HBdUZa73CT5KMCP.EN7S0OUy/2pR/vbnz3X9jFwW/CcbRI3r.DqLi', 0, 'empty', '194 rue Edmond Rostand', '31200', 'Toulouse', '0623285647', 'Yanis', 'LastYanis'),
(5, 'yanis.mengouchi1@gmail.com', '[\"ROLE_USER\"]', '$2y$13$Z/thtRAWUEtbOvoUY9rMduFE2DH6bbl8.JFzfuYY8Rz8wu34JyFrC', 0, 'empty', '194 rue Edmond Rostand', '31100', 'Lyon', '0623285649', 'Yanis', 'LastYanis'),
(7, 'yanis.mengouchi2@gmail.com', '[\"ROLE_USER\"]', '$2y$13$QdWJ6E3TWtU1QYqT2vzEiORo6zoik4m2dOcJnrt7PgaP5g3bRx7qi', 0, 'empty', '194 rued de la street 307', '31200', 'Toulouse', '0623285647', 'Yanis', 'Mengouchi'),
(8, 'sarahmaze2@gmail.com', '[\"ROLE_USER\"]', '$2y$13$NnBTe.g/AaJECyXMaHf5F.4kbO.DSLMF9yvuQ2d.TagaZ7RQeSFka', 0, 'empty', '194 rue Edmond Rostand', '31200', 'Toulouse', '0623285647', 'gg', 'gg');

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
  `picture` varchar(2000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sold` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `xbox`
--

INSERT INTO `xbox` (`id`, `title`, `description`, `price`, `generation`, `picture`, `sold`) VALUES
(1, 'Xbox 360 Slim RGH - Edition Limité Star Wars', 'descriptif de lma xbox Xbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star Wars', '200', 'xboxFat', 'uploads/626be68dbe137.png_uploads/626be68dbe6cb.png_uploads/626be68dbed89.png_uploads/626be68dbf547.png_uploads/626be85d123a9.png_uploads/626be85d1296d.png', 'SOLD'),
(3, 'Xbox 360 Fat RGH Red Galaxy', 'descriptif de lma xbox Xbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star Wars', '150', 'xboxSlim', 'uploads/626be7249b32a.png_uploads/626be7249b8c9.png_uploads/626be72ca271c.png_uploads/626be72ca2ceb.png', 'SOLD'),
(6, 'Xbox 360 Slim RGH - Edition Limité Star Wars', 'descriptif de lma xbox Xbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star Wars', '150', 'xboxSlim', 'uploads/626be7803e848.png_uploads/626be7803f098.png_uploads/626be7803f6c9.png_uploads/626be7803fc32.png', 'SOLD'),
(8, 'Xbox 360 Slim RGH - Edition Limité Star Wars', 'descriptif de lma xbox Xbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star Wars', '150', 'xboxSlim', 'uploads/626be7d511956.png_uploads/626be7d511f19.png_uploads/626be7dd66760.png_uploads/626be7dd66d1d.png', 'SOLD'),
(10, 'Xbox 360 Fat RGH Red Galaxy', 'Xbox 360 Fat Red Galaxy', '170', 'xboxSlim', 'uploads/6278e1458d68c.jpg_uploads/6278e1458dc90.jpg_uploads/6278e1458e2ea.jpg_uploads/6278e1458e903.jpg', 'SOLD'),
(23, 'Xbox 360 Slim RGH - Edition Limité Star Wars', 'descriptif de lma xbox Xbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star Wars', '150', 'xboxSlim', 'uploads/626be7803e848.png_uploads/626be7803f098.png_uploads/626be7803f6c9.png_uploads/626be7803fc32.png', 'SOLD'),
(24, 'Xbox 360 Slim RGH - Edition Limité Star Wars', 'descriptif de lma xbox Xbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star WarsXbox 360 Slim RGH - Edition Limité Star Wars', '150', 'xboxSlim', 'uploads/626be7d511956.png_uploads/626be7d511f19.png_uploads/626be7dd66760.png_uploads/626be7dd66d1d.png', 'to sell'),
(25, 'Xbox 360 Fat RGH Red Galaxy', 'Xbox 360 Fat Red Galaxy', '170', 'xboxSlim', 'uploads/6278e1458d68c.jpg_uploads/6278e1458dc90.jpg_uploads/6278e1458e2ea.jpg_uploads/6278e1458e903.jpg', 'SOLD');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

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
-- Index pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

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
-- AUTO_INCREMENT pour la table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT pour la table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `xbox`
--
ALTER TABLE `xbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
