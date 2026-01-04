-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 04 يناير 2026 الساعة 11:25
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spiderman`
--

-- --------------------------------------------------------

--
-- بنية الجدول `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `activity_type` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_type` varchar(50) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `username`, `activity_type`, `description`, `item_id`, `item_type`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 2, 'maram', 'LOGIN', 'User logged in successfully', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 00:31:49'),
(2, 2, 'maram', 'LOGIN', 'User logged in successfully', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-03 01:06:08');

-- --------------------------------------------------------

--
-- بنية الجدول `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `message`, `submission_date`) VALUES
(79, 'lolo', 'admin@spiderman.com', 'the 100 test of this box', '2025-12-28 11:43:30'),
(82, 'Luna', 'Lulu@gmail.com', 'Trying for the last time before submiting the project', '2026-01-04 09:38:17');

-- --------------------------------------------------------

--
-- بنية الجدول `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `title` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `gallery`
--

INSERT INTO `gallery` (`id`, `image_path`, `title`, `category`, `description`, `uploaded_at`) VALUES
(1, 'images\\spider-man-no-way-home-2021-movies-marvel-comics-3840x2160-6831.jpg', 'Spider man No Way Home ', 'spiderman', NULL, '2025-12-25 13:35:16'),
(2, 'images\\marvels-spider-man-remastered-2021-games-playstation-5-5k-3840x2160-4451.jpg', 'spiderman is Swinging', 'spiderman', '', '2025-12-25 13:35:16'),
(3, 'images\\marvels-spider-man-playstation-4-pro-gameplay-marvel-3840x2160-3564.jpg', 'Marvel Spider-Man', 'spiderman', NULL, '2025-12-25 13:37:35'),
(4, 'images\\marvels-spider-man-playstation-4-playstation-5-pc-games-3840x2160-8937.jpg', 'marvels-spider-man-playstation', 'spiderman', NULL, '2025-12-25 13:37:35'),
(5, 'images\\marvels-spider-man-3840x2160-9850.jpg', 'spider-man-3840x2160', 'spiderman', NULL, '2025-12-25 13:41:31'),
(6, 'images\\ss.jpg', 'Spider-Man 3', 'spiderman', NULL, '2025-12-25 13:41:31'),
(7, 'images\\spider2.jpg', 'Amazing Spider-Man', 'spiderman', NULL, '2025-12-25 13:43:23'),
(8, 'images\\marvels-spider-man-3840x2160-12906.jpeg', 'marvels-spider-man-3840x2160', 'spiderman', NULL, '2025-12-25 13:43:23'),
(9, 'images\\marvels-spider-man-3840x2160-11990.jpeg', 'marvels-spider-man-3840x2160', 'spiderman', NULL, '2025-12-25 13:44:51'),
(10, 'images\\jj.jpg', 'Spider-man ', 'spiderman', NULL, '2025-12-25 13:44:51'),
(11, 'images\\download (13).jpg', 'Spidy', 'spiderman', NULL, '2025-12-25 13:47:16'),
(12, 'images\\spider 11.jpg', 'Rainy day spiderman', 'spiderman', NULL, '2025-12-25 13:53:25'),
(13, 'images\\marvels-spider-man-3840x2160-12891.jpg', 'Spider-Man', 'spiderman', NULL, '2025-12-25 13:53:25'),
(14, 'images\\download (35).jpg', 'Spiderman and iron-man reunion', 'spiderman', NULL, '2025-12-25 13:57:30'),
(15, 'images\\Tony Stark and Peter Parker Avengers infinity war.jpg', 'Avengers infinity war', 'spiderman', NULL, '2025-12-25 13:57:30'),
(21, 'images/download (36).jpg', 'Spider-Man Action Pose', 'spiderman', NULL, '2025-12-25 14:08:06'),
(22, 'images/_3.jpg', 'Spider-Man Wall Crawling', 'spiderman', NULL, '2025-12-25 14:08:06'),
(23, 'images/download (39).jpg', 'Spider-Man multi-univers', 'spiderman', NULL, '2025-12-25 14:08:06'),
(24, 'images/download (37).jpg', 'Spider-Man in Rain', 'spiderman', NULL, '2025-12-25 14:08:06'),
(25, 'images/download (38).jpg', 'Spider-Man Reflection', 'spiderman', NULL, '2025-12-25 14:08:06');

-- --------------------------------------------------------

--
-- بنية الجدول `mcu`
--

CREATE TABLE `mcu` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `release_year` year(4) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `rating` decimal(3,1) NOT NULL,
  `stars` varchar(1) NOT NULL DEFAULT '⭐'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `mcu`
--

INSERT INTO `mcu` (`id`, `title`, `release_year`, `image_path`, `rating`, `stars`) VALUES
(1, 'Captain America: Civil War', '2016', 'images/download (6).jpg', 7.8, '⭐'),
(2, 'Avengers: Infinity War', '2018', 'images/download (5).jpg', 8.4, '⭐'),
(3, 'Avengers: Endgame', '2019', 'images/download (7).jpg', 8.4, '⭐');

-- --------------------------------------------------------

--
-- بنية الجدول `movies`
--

CREATE TABLE `movies` (
  `ID` int(11) NOT NULL,
  `Title` varchar(225) NOT NULL,
  `release_year` year(4) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `rating` decimal(3,1) DEFAULT 0.0,
  `stars` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `movies`
--

INSERT INTO `movies` (`ID`, `Title`, `release_year`, `Image`, `Description`, `rating`, `stars`) VALUES
(2, 'Spider-Man 2', '2004', 'images/image.png', 'Peter faces a personal crisis as his powers begin to fade. He struggles to balance his secret identity with his love for Mary Jane while battling the tragic Doctor Octopus', 8.0, '⭐'),
(3, 'Spider-Man 3', '2007', 'images/Spider-man 3 (2007).jpg', 'Peter’s suit turns black and enhances his darkest impulses when an alien symbiote attaches to him. He must fight his inner demons while facing three new villains: Venom, Sandman, and a new Goblin', 6.2, '⭐'),
(4, 'The Amazing Spider-Man', '2012', 'images/The Amazing Spider-Man.jpg', 'A modern retelling of Peter’s origin. Peter searches for clues about his parents\' disappearance, leading him to a confrontation with his father\'s former partner, The Lizard.', 7.0, '⭐'),
(5, ' The Amazing Spider-Man', '2014', 'images\\The amezing spyderman 2 hd wallpaper (1).jpg', 'As Peter uncovers more about his past, he faces his greatest challenge yet when the powerful Electro threatens New York City and his childhood friend Harry Osborn returns.', 6.8, '⭐'),
(6, 'Sider-man: Homecoming', '2017', 'images/_Spider-man_ homecoming_ (2017)_.jpg', 'Under the mentorship of Tony Stark, Peter tries to prove he is more than just a \"friendly neighborhood Spider-Man\" by taking down the illegal arms dealer known as The Vulture.', 7.9, '⭐'),
(7, 'Spider-man: Far From Home ', '2019', 'images/download (4).jpg', 'While on a school trip to Europe, Peter is recruited by Nick Fury to stop elemental monsters, only to realize the hero Mysterio isn\'t who he claims to be.', 7.5, '⭐'),
(8, 'Spider-man: No Way Home', '2021', 'images/SPIDER-MAN_ NO WAY HOME (2021).jpg', 'When a spell goes wrong, the multiverse opens, bringing in villains from other worlds. Peter must team up with familiar faces to fix the timeline and save his reality.', 8.5, '⭐'),
(19, 'm', '2023', 'images\\_3.jpg', '', 4.0, '');

-- --------------------------------------------------------

--
-- بنية الجدول `profile`
--

CREATE TABLE `profile` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `avatar` varchar(255) DEFAULT 'images/avatars/default.png',
  `bio` text DEFAULT NULL,
  `join_date` date DEFAULT curdate(),
  `last_login` datetime DEFAULT NULL,
  `password_id` int(11) DEFAULT NULL,
  `role` enum('Admin') DEFAULT 'Admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `profile`
--

INSERT INTO `profile` (`id`, `username`, `full_name`, `email`, `avatar`, `bio`, `join_date`, `last_login`, `password_id`, `role`) VALUES
(1, 'alaa', 'Alaa', 'admin@spiderman.com', 'images\\(6).jpg', 'Web-slinger administrator with full control over the Spider-Man website.', '2025-12-17', '2026-01-01 20:05:16', 1, 'Admin'),
(2, 'maram', 'loli', 'maram@example.com', 'images\\(6).jpg', 'Content editor and Spider-Man fan.', '2025-12-17', '2026-01-01 20:05:16', 2, 'Admin'),
(3, 'test', 'Toi', 'test@example.com', 'images/avatars/avatar_Test_1767389474.png', 'Test account for website testing.', '2025-12-17', '2026-01-02 21:54:59', 3, 'Admin'),
(4, 'LU', 'Luna', 'Luna@spiderman.com', 'images\\download (28)xx.jpg', 'New administrator', '2026-01-02', '2026-01-02 16:52:52', 4, 'Admin');

-- --------------------------------------------------------

--
-- بنية الجدول `spiderman_cast`
--

CREATE TABLE `spiderman_cast` (
  `id` int(11) NOT NULL,
  `actor_name` varchar(100) NOT NULL,
  `character_name` varchar(100) NOT NULL,
  `films` varchar(255) NOT NULL,
  `release_years` varchar(50) DEFAULT NULL,
  `universe` enum('MCU','Raimi','Amazing','SSU') NOT NULL,
  `role_type` enum('Spider-Man','Love Interest','Supporting','Cameo') NOT NULL,
  `image_path` varchar(255) DEFAULT 'images/default.jpg',
  `is_main_cast` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `spiderman_cast`
--

INSERT INTO `spiderman_cast` (`id`, `actor_name`, `character_name`, `films`, `release_years`, `universe`, `role_type`, `image_path`, `is_main_cast`, `created_at`, `updated_at`) VALUES
(1, 'Tom Holland', 'Peter Parker / Spider-Man', 'Spider-Man: Homecoming, Far From Home, No Way Home', '2017-2021', 'MCU', 'Spider-Man', 'images\\Tom Holland wallpaper - Copy.jpg', 1, '2025-12-23 19:44:58', '2025-12-23 19:50:09'),
(2, 'Tobey Maguire', 'Peter Parker / Spider-Man', 'Spider-Man (2002), Spider-Man 2, Spider-Man 3', '2002-2007', 'Raimi', 'Spider-Man', 'images\\What Happened to Tobey Maguire and Why Hollywood___.jpg', 1, '2025-12-23 19:44:58', '2025-12-23 19:50:31'),
(3, 'Andrew Garfield', 'Peter Parker / Spider-Man', 'The Amazing Spider-Man 1 & 2', '2012-2014', 'Amazing', 'Spider-Man', ' images\\download (21).jpg ', 1, '2025-12-23 19:44:58', '2025-12-23 19:50:51'),
(4, 'Zendaya', 'Michelle \"MJ\" Jones-Watson', 'Spider-Man: Homecoming, Far From Home, No Way Home', '2017-2021', 'MCU', 'Love Interest', 'images\\zendaya.jpg', 1, '2025-12-23 19:44:58', '2025-12-23 19:51:07'),
(5, 'Kirsten Dunst', 'Mary Jane Watson', 'Spider-Man (2002), Spider-Man 2, Spider-Man 3', '2002-2007', 'Raimi', 'Love Interest', 'images\\Kirsten Dunst hated nickname she was given on___.jpg', 1, '2025-12-23 19:44:58', '2025-12-23 20:31:36'),
(6, 'Emma Stone', 'Gwen Stacy', 'The Amazing Spider-Man 1 & 2', '2012-2014', 'Amazing', 'Love Interest', 'images\\download (22).jpg', 1, '2025-12-23 19:44:58', '2025-12-23 19:53:34'),
(7, 'Marisa Tomei', 'May Parker', 'Spider-Man: Homecoming, Far From Home, No Way Home', '2017-2021', 'MCU', 'Supporting', 'images\\Marisa Tomei.jpg', 1, '2025-12-23 19:44:58', '2025-12-23 19:54:24'),
(8, 'Rosemary Harris', 'May Parker', 'Spider-Man (2002), Spider-Man 2, Spider-Man 3', '2002-2007', 'Raimi', 'Supporting', 'images\\Spider-Man 3 Picture 19.jpg', 1, '2025-12-23 19:44:58', '2025-12-23 19:57:57'),
(9, 'Sally Field', 'May Parker', 'The Amazing Spider-Man 1 & 2', '2012-2014', 'Amazing', 'Supporting', 'images\\sally.jpg', 1, '2025-12-23 19:44:58', '2025-12-23 20:58:45'),
(10, 'J.K. Simmons', 'J. Jonah Jameson', 'Spider-Man trilogy, Spider-Man: Far From Home, No Way Home', '2002-2021', 'Raimi', 'Supporting', 'images\\The Oscars 2025 _ 97th Academy Awards.jpg', 1, '2025-12-23 19:44:58', '2025-12-23 20:03:15'),
(11, 'Jacob Batalon', 'Ned Leeds', 'Spider-Man: Homecoming, Far From Home, No Way Home', '2017-2021', 'MCU', 'Supporting', 'images\\HAPPY 25th BIRTHDAY to JACOB BATALON!! 10_9_21 Born Jacob Batalon, American actor, best known for his role as Ned Leeds in the Marvel Cinematic Universe (MCU) films Spider-Man_ Homecoming (2017), Spider-Man_ Far Fr.jpg', 1, '2025-12-23 19:44:58', '2025-12-23 20:02:28'),
(12, 'Robert Downey Jr.', 'Tony Stark / Iron Man', 'Spider-Man: Homecoming, Avengers: Infinity War', '2017-2019', 'MCU', 'Supporting', 'images\\robert-downey-jr.jpg', 1, '2025-12-23 19:44:58', '2025-12-23 20:00:55'),
(13, 'Tom Hardy', 'Eddie Brock / Venom', 'Venom, Venom: Let There Be Carnage', '2018-2021', 'SSU', 'Supporting', 'images\\Here Are 150 Photos Of Tom Hardy Because Why Not.jpg', 1, '2025-12-23 19:44:58', '2025-12-23 20:01:56');

-- --------------------------------------------------------

--
-- بنية الجدول `spider_passwords`
--

CREATE TABLE `spider_passwords` (
  `id` int(11) NOT NULL,
  `spider_name` varchar(50) NOT NULL,
  `current_password` varchar(255) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reset_token` varchar(100) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `spider_passwords`
--

INSERT INTO `spider_passwords` (`id`, `spider_name`, `current_password`, `last_updated`, `reset_token`, `token_expiry`) VALUES
(1, 'Alaa', '$2y$10$wD5TC8pzdfv1Gsc96vOttOHQlZwnjFaoEgaty/YFUmkVBC5fPQ.gy', '2026-01-01 18:05:16', NULL, NULL),
(2, 'Maram', '$2y$10$W5R6DqswJ7MhQEXomFJvKusdurr5MDay2VJT.Lhkbw9dw1/6lYjk2', '2026-01-01 18:05:16', NULL, NULL),
(3, 'Test', '$2y$10$RiAbtGINsYbfcGjnp0LCQOwG7DDPJQwEHZO/Fpxb9wh5X6.LMlRWG', '2026-01-02 19:54:59', NULL, NULL),
(4, 'LU', '$2y$10$soWHUSHVpzjCrYIMsWvlourwE756mrCltGjlBAyG8pT2mxYfFUCV2', '2026-01-02 14:52:52', NULL, NULL),
(5, 'dabi', '$2y$10$c2VYE5hkot1uwx5dCu0qG.bFNssOgruJxERbyjFJ3LpcCV6F8aymm', '2026-01-02 15:20:36', NULL, NULL),
(6, 'ghj', '$2y$10$AZdEQmDEhCi3O95BUOvWIO2MeA2jaPQ/w7npC3Jslc0.b3ttpx3q.', '2026-01-02 15:48:31', NULL, NULL),
(7, 'A', '$2y$10$GAsB..UjB64dEF.SoD.Eiuv5v.sOf9ImA8Q0h92LehoqV6GQvP8eu', '2026-01-02 19:53:53', NULL, NULL),
(8, 'cielo', '$2y$10$nDuIEKZ328hCR4eYfTnsLOQ/plk5E.j08kgLcyMqEmGUFo6o/cC8S', '2026-01-02 20:07:05', NULL, NULL),
(9, 'jk', '$2y$10$uGI.Yegd9CrwvvotXunnje3M/JX8t1cBsilCqHYBZ20hcrmkix9ve', '2026-01-02 20:26:11', NULL, NULL),
(10, 'kk', '$2y$10$hsFKpH8vZilzB0ogE1Mxae9K3g7BedCLeuScJwwrqhGplZJliQs9W', '2026-01-02 20:28:24', NULL, NULL),
(11, 'aaa', '$2y$10$DvgjUEXmdWtoz5Rds78OuOC1Q/NwAdESlkgiqKZZgSpkQ6RhSImQG', '2026-01-02 20:29:21', NULL, NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `villains`
--

CREATE TABLE `villains` (
  `id` int(11) NOT NULL,
  `villain_name` varchar(100) NOT NULL,
  `real_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `first_appearance` varchar(100) DEFAULT NULL,
  `powers` text DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `villains`
--

INSERT INTO `villains` (`id`, `villain_name`, `real_name`, `description`, `first_appearance`, `powers`, `image_path`, `created_at`) VALUES
(1, 'Green Goblin', 'Norman Osborn', 'A billionaire industrialist turned insane supervillain', 'The Amazing Spider-Man', 'Super strength, genius intellect, goblin glider, pumpkin bombs', 'images/Spider man 1.jpg', '2025-12-23 07:54:47'),
(2, 'Electro', 'Max Dillon', 'A living electrical capacitor with high-voltage powers', 'The Amazing Spider-Man', 'Electricity manipulation, flight through power lines, energy absorption', 'images/Electro_ The amazing spiderman.jpg', '2025-12-23 07:54:47'),
(3, 'Mysterio', 'Quentin Beck', 'Special effects wizard who creates elaborate illusions', 'The Amazing Spider-Man #13 (1964)', 'Illusion creation, hypnotism, special effects technology', 'images/download (17).jpg', '2025-12-23 07:54:47'),
(4, 'Hydro-Man', 'Morris Bench', 'Can manipulate and transform into water', 'The Amazing Spider-Man #212 (1981)', 'Hydrokinesis, water form, size manipulation', 'images/Hydro Man.jpg', '2025-12-23 07:54:47'),
(5, 'Doctor Octopus', 'Otto Octavius', 'Brilliant scientist with four mechanical tentacles', 'The Amazing Spider-Man', 'Genius intellect, mechanical tentacle control, super strength', 'images/download (19).jpg', '2025-12-23 07:54:47'),
(6, 'Sandman', 'Flint Marko', 'Can transform his body into sand and reshape it', 'The Amazing Spider-Man #4 (1963)', 'Sand manipulation, shape-shifting, size alteration', 'images/Spider-Man 3 [2007].jpg', '2025-12-23 07:54:47'),
(7, 'Venom', 'Eddie Brock', 'Alien symbiote that bonds with a host', 'The Amazing Spider-Man #300 (1988)', 'Symbiote suit, super strength, web generation, shape-shifting', 'images/Venom (2018).jpg', '2025-12-23 07:54:47'),
(8, 'The Vulture', 'Adrian Toomes', 'Elderly inventor with a winged harness', 'The Amazing Spider-Man #2 (1963)', 'Flight, enhanced strength, razor-sharp wings', 'images/The vulture contests of champions.jpg', '2025-12-23 07:54:47'),
(9, 'Carnage', 'Cletus Kasady', 'Red symbiote more violent than Venom', 'The Amazing Spider-Man', 'All Venom powers plus weapon creation, madness induction', 'images/Carnage Statuel.jpg', '2025-12-23 07:54:47'),
(10, 'The Scorpion', 'Mac Gargan', 'Bounty hunter with a mechanical scorpion tail', 'The Amazing Spider-Man #19 (1964)', 'Super strength, armored suit, mechanical tail, wall-crawling', 'images/Scorpion.jpg', '2025-12-23 07:54:47'),
(11, 'Kraven the Hunter', 'Sergei Kravinoff', 'Big game hunter seeking to defeat Spider-Man', 'The Amazing Spider-Man #15 (1964)', 'Peak human condition, hunting skills, special weapons', 'images/Kraven the hunter.jpg', '2025-12-23 07:54:47'),
(13, 'The Scorpio', 'Sergei Kravinoff', '', 'The Amazing Spider-Man #19 (1964)', 'Peak human condition, hunting skills, special weapons', 'images/Scorpion.jpg', '2026-01-04 10:17:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mcu`
--
ALTER TABLE `mcu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD KEY `fk_profile_password` (`password_id`);

--
-- Indexes for table `spiderman_cast`
--
ALTER TABLE `spiderman_cast`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `spider_passwords`
--
ALTER TABLE `spider_passwords`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `spider_name` (`spider_name`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `villains`
--
ALTER TABLE `villains`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `mcu`
--
ALTER TABLE `mcu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `spiderman_cast`
--
ALTER TABLE `spiderman_cast`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `spider_passwords`
--
ALTER TABLE `spider_passwords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `villains`
--
ALTER TABLE `villains`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `fk_password_id` FOREIGN KEY (`password_id`) REFERENCES `spider_passwords` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_profile_password` FOREIGN KEY (`password_id`) REFERENCES `spider_passwords` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
