-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-real-estate-rh.alwaysdata.net
-- Generation Time: Jan 11, 2024 at 11:09 AM
-- Server version: 10.6.14-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `real-estate-rh_real_estate`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `user_id`, `role`, `created_at`, `updated_at`) VALUES
(1, 1, 0, '2024-01-09 16:46:42', '2024-01-09 16:46:42');

-- --------------------------------------------------------

--
-- Table structure for table `agencies`
--

CREATE TABLE `agencies` (
  `agency_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `is_displayed` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `agencies`
--

INSERT INTO `agencies` (`agency_id`, `name`, `address`, `city`, `zip`, `email`, `phone`, `is_displayed`, `created_at`, `updated_at`) VALUES
(1, 'Duport Immobilier Bordeaux', '5 Rue du Jeune Marché', 'Bordeaux', '33000', 'duportimmo@manager.com', '0644249585', 1, '2024-01-09 16:55:00', '2024-01-09 16:55:00'),
(2, 'Duport Immobilier Mimizan', '5 Rue du Vieux Marché', 'Mimizan', '40200', 'duportimmo@manager2.com', '0544219289', 1, '2024-01-09 17:39:19', '2024-01-09 17:39:19'),
(3, '72Immobilier', '1 Rue du Polygone', 'Le Mans', '72000', '72immo@immo.fr', '0145788592', 1, '2024-01-09 17:40:40', '2024-01-09 17:40:40');

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `agent_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `role` tinyint(4) DEFAULT 2,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`agent_id`, `user_id`, `agency_id`, `manager_id`, `role`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, 2, '2024-01-09 17:33:47', '2024-01-09 17:33:47'),
(2, 6, 2, 2, 2, '2024-01-09 17:49:13', '2024-01-09 17:49:13'),
(3, 7, 3, 3, 2, '2024-01-09 18:23:16', '2024-01-09 18:23:16');

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `manager_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `role` tinyint(4) DEFAULT 1,
  `has_pending_application` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`manager_id`, `user_id`, `agency_id`, `role`, `has_pending_application`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 0, '2024-01-09 16:55:00', '2024-01-09 16:55:00'),
(2, 4, 2, 1, 0, '2024-01-09 17:39:19', '2024-01-09 17:39:19'),
(3, 5, 3, 1, 0, '2024-01-09 17:40:40', '2024-01-09 17:40:40');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `property_id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `reference` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `offer` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `int_surface` int(11) NOT NULL,
  `ext_surface` int(11) NOT NULL,
  `rooms` int(11) NOT NULL,
  `rented` tinyint(4) DEFAULT 0,
  `rented_for` int(11) DEFAULT NULL,
  `rented_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sold` tinyint(4) DEFAULT 0,
  `sold_for` int(11) DEFAULT NULL,
  `sold_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`property_id`, `agency_id`, `reference`, `address`, `city`, `zip`, `offer`, `type`, `title`, `description`, `price`, `int_surface`, `ext_surface`, `rooms`, `rented`, `rented_for`, `rented_at`, `sold`, `sold_for`, `sold_at`, `created_at`, `updated_at`) VALUES
(1, 1, '82670061', 'Avenue du Général Leclerc', 'Bordeaux', '33000', 'sale', 'house', 'PROPRIÉTÉ VITICOLE', 'Niché sur une propriété de 23 hectares de vignes en un seul tenant, ce château du XIX siècle est la troisième plus grande exploitation du Fronsadais, il offre une occasion unique d&#039;investir dans l&#039;histoire et la beauté de la région. Le château lui-même est à rénover, ce qui vous permet de personnaliser la propriété selon vos goûts et vos besoins. Cela vous donne également la possibilité de restaurer ce joyau architectural à son ancienne gloire et de créer une résidence de prestige qui sera la fierté de la région. En plus le château, la propriété comprend une maison divisée en deux logements d&#039;une surface d&#039;environ 340m². Les bâtiments d&#039;exploitation comprennent de grandes salles prévues pour la dégustation, également un bâtiment pour le stockage, un atelier, un espace pour le personnel et divers cuves et chais. La propriété produit son eau grâce à un puit et une pompe de remontage et filtrage. Ne manquez pas cette occasion unique de posséder une propriété exceptionnelle dans une région viticole de renommée mondiale. Contactez-nous dès maintenant pour organiser une visite et découvrir tout ce que cette propriété a à vous offrir.', 12600000, 366, 230000, 10, 0, NULL, '2024-01-09 17:30:52', 0, NULL, '0000-00-00 00:00:00', '2024-01-09 17:21:58', '2024-01-09 17:21:58'),
(2, 2, '83826037', 'Vieille Route de Saint-Pée', 'Saint-Pée-sur-Nivelle', '64310', 'sale', 'house', 'PROPRIÉTÉ SUR LES HAUTEURS DE SAINT JEAN DE LUZ', 'Située sur les hauteurs de Saint-Pée-sur-Nivelle, dans le pays basque français, au coeur de cette ville, se trouve une magnifique propriété qui vous charmera dès le premier coup d&#039;oeil. Lorsque vous franchissez les portes, vous êtes immédiatement accueilli par un grand jardin bien entretenu, qui offre une vue panoramique sur les montagnes ou le calme et la sérénité règnent en maîtres. Cette propriété, comprend six chambres spacieuses avec entrée indépendante, chacune ayant leur propre identité et décoration. Elles disposent toutes d&#039;une salle de bain privée et un accès direct à la terrasse vous offrant confort et intimité. Au premier étage vous trouverez également une grande cuisine entièrement équipée, séparée d&#039;une magnifique salle à manger baignée de lumière par ses grandes baies vitrées donnant sur une terrasse commune. De plus, la propriété comprend une dépendance disposant de deux chambres supplémentaires, d&#039;une piscine privée, avec un pool house entièrement équipé, d&#039;un spa et d&#039;un carport pouvant accueillir deux voitures. Offrant un cadre de vie très rare entre mer et montagne et à proximité de tous commerces, le charme de cette propriété vous séduira. Un endroit unique et préservé avec une vue époustouflante.', 3360000, 560, 10000, 11, 0, NULL, '2024-01-09 17:50:35', 0, NULL, '0000-00-00 00:00:00', '2024-01-09 17:50:35', '2024-01-09 17:50:35'),
(4, 3, '2227MMNL', '22 rue Jules Verne', 'Le Mans', '72100', 'sale', 'house', 'LE MANS GARE SUD BATIGNOLLES', 'QUARTIER BATIGNOLLES GARE SUD MAISON AVEC GARAGE 2 STATIONNEMENTS ET JARDIN POSEZ VOS VALISES DANS CETTE GRANDE MAISON FAMILIALE DE QUATRE CHAMBRES SALLE DE BAINS SALLE D EAU AINSI QU UN ESPACE DE VIE DE PLUS DE 58 M2 ET UNE VERANDA DE 20 M2 JARDIN PAYSAGE AVEC TERRASSE ET PISCINE SEMI ENTERREE', 370000, 255, 560, 5, 0, NULL, '2024-01-09 18:34:50', 0, NULL, '0000-00-00 00:00:00', '2024-01-09 18:34:50', '2024-01-09 18:34:50'),
(5, 1, '835240105', '22 Rue du Ha', 'Bordeaux', '33000', 'sale', 'house', 'HOTEL 3 ÉTOILES BENIDORM', 'A proximité d&#039;Alicante, découvrez cet hôtel 3 étoiles sur 4 niveaux. Au rez-de-chaussée, un espace réception avec salle à manger, cuisine, salle de bain et dortoir. Le premier et le deuxième étage comptabilisent 18 chambres avec WC privatif.\r\nAu sous-sol une boite de nuit.\r\nEnfin pour l&#039;espace extérieur, un jardin, une piscine et un parking pour accueillir votre clientèle.\r\nRare sur le marché.', 2625000, 500, 15000, 15, 0, NULL, '2024-01-11 08:10:31', 0, NULL, '0000-00-00 00:00:00', '2024-01-10 13:32:47', '2024-01-10 13:32:47');

-- --------------------------------------------------------

--
-- Table structure for table `properties_agents`
--

CREATE TABLE `properties_agents` (
  `property_agent_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `properties_agents`
--

INSERT INTO `properties_agents` (`property_agent_id`, `property_id`, `agent_id`, `created_at`, `updated_at`) VALUES
(3, 2, 2, '2024-01-09 18:00:40', '2024-01-09 18:00:40'),
(11, 1, 1, '2024-01-10 13:34:30', '2024-01-10 13:34:30'),
(16, 5, 1, '2024-01-11 08:28:22', '2024-01-11 08:28:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint(4) DEFAULT 3,
  `has_connected` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fname`, `lname`, `email`, `password`, `role`, `has_connected`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Admin', 'admin@admin.com', '$2y$12$NHd.b..bWGYyY.jKUgpJguiph9oi3ndpMbDjce8U6DhQ90LNOelDK', 0, 1, '2024-01-09 16:46:42', '2024-01-09 16:46:42'),
(2, 'Manager', 'Manager', 'manager@manager.com', '$2y$12$HXNrh3sgRx1wArkbrxzd/.CPBn/ynSli4I/7cI.INWTJB11vz.emS', 1, 1, '2024-01-09 16:54:09', '2024-01-09 16:54:09'),
(3, 'Sir', 'Agent', 'siragent@agent.com', '$2y$12$v9v0gbpTXKLAWDIELxgRROKQxJZE01tnARIf4odEYc60FUvTGf/iO', 2, 1, '2024-01-09 17:33:47', '2024-01-09 17:33:47'),
(4, 'Manager2', 'Manager2', 'manager2@manager.com', '$2y$12$Vgjmv5CHhVzB3/lrW1rnVeda7BbsGYFietySYIn33a7W4qR182AqK', 1, 1, '2024-01-09 17:37:46', '2024-01-09 17:37:46'),
(5, 'Manager3', 'Manager3', 'manager3@manager.com', '$2y$12$pV4qYDrUXU20NtzEIKR/7eoQ8.wET8vFKAYdctK4Az/zeFi0KNpXC', 1, 1, '2024-01-09 17:40:01', '2024-01-09 17:40:01'),
(6, 'Sir', 'Agent2', 'siragent2@agent.com', '$2y$12$WbNpiy/donZlAHhuPe48L.GOuLoeKZpdV/MVqsftubIYEXlWI9u5i', 2, 1, '2024-01-09 17:49:13', '2024-01-09 17:49:13'),
(7, 'Sir', 'Agent3', 'siragent3@agent.com', '$2y$12$qWEvd7jxLnVIrFlaGTlUROPJF0YTHAn3VTTOI5pf2IT6ZmoE.5mDC', 2, 0, '2024-01-09 18:23:16', '2024-01-09 18:23:16'),
(9, 'User', 'User', 'user@user.com', '$2y$12$gX4eeyRc.MmxjPQSGQCh.ugzx.lTEI6jNt5DNdupSn1nKeO.SyJ7W', 3, 1, '2024-01-10 09:41:52', '2024-01-10 09:41:52'),
(10, 'User2', 'User2', 'user2@user.com', '$2y$12$V0GRk0qrHppqIzRtPR5Je.dFmrHMJQTVA7CJVKRUbKco3y0TduEuW', 3, 1, '2024-01-10 09:42:58', '2024-01-10 09:42:58'),
(11, 'User3', 'User3', 'user3@user.com', '$2y$12$XndAwOMAlmMC5nDKMfH0Qez11LqszQ1tlNPnzBfLj02LAOerq3kWe', 3, 1, '2024-01-10 09:44:36', '2024-01-10 09:44:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `agencies`
--
ALTER TABLE `agencies`
  ADD PRIMARY KEY (`agency_id`);

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`agent_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `agency_id` (`agency_id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`manager_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `agency_id` (`agency_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`property_id`),
  ADD KEY `agency_id` (`agency_id`);

--
-- Indexes for table `properties_agents`
--
ALTER TABLE `properties_agents`
  ADD PRIMARY KEY (`property_agent_id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `agent_id` (`agent_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agencies`
--
ALTER TABLE `agencies`
  MODIFY `agency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `agent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `manager_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `properties_agents`
--
ALTER TABLE `properties_agents`
  MODIFY `property_agent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `agents`
--
ALTER TABLE `agents`
  ADD CONSTRAINT `agents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agents_ibfk_2` FOREIGN KEY (`agency_id`) REFERENCES `agencies` (`agency_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agents_ibfk_3` FOREIGN KEY (`manager_id`) REFERENCES `managers` (`manager_id`) ON DELETE CASCADE;

--
-- Constraints for table `managers`
--
ALTER TABLE `managers`
  ADD CONSTRAINT `managers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `managers_ibfk_2` FOREIGN KEY (`agency_id`) REFERENCES `agencies` (`agency_id`) ON DELETE CASCADE;

--
-- Constraints for table `properties`
--
ALTER TABLE `properties`
  ADD CONSTRAINT `properties_ibfk_1` FOREIGN KEY (`agency_id`) REFERENCES `agencies` (`agency_id`) ON DELETE CASCADE;

--
-- Constraints for table `properties_agents`
--
ALTER TABLE `properties_agents`
  ADD CONSTRAINT `properties_agents_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`property_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `properties_agents_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`agent_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
