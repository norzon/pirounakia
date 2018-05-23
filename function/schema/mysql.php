<?php
    function schemaMysql ($prefix) {
        return <<<EOF

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


-- --------------------------------------------------------

--
-- Table structure for table `${prefix}category`
--

CREATE TABLE `${prefix}category` (
  `id` int(11) NOT NULL,
  `cid` int(11) NOT NULL COMMENT '[FK] `${prefix}company`.`id`',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `catid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `${prefix}company`
--

CREATE TABLE `${prefix}company` (
  `id` int(11) NOT NULL,
  `name` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `${prefix}dish`
--

CREATE TABLE `${prefix}dish` (
  `id` int(11) NOT NULL,
  `catid` int(11) NOT NULL COMMENT '[FK] `${prefix}category`.`id`',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `${prefix}dish_ingredient`
--

CREATE TABLE `${prefix}dish_ingredient` (
  `dish_id` int(11) NOT NULL COMMENT '[FK] `${prefix}dish`.`id`',
  `ingred_id` int(11) NOT NULL COMMENT '[FK] `${prefix}ingredient`.`id`',
  `amount` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `${prefix}employee`
--

CREATE TABLE `${prefix}employee` (
  `id` int(11) NOT NULL,
  `stid` int(11) NOT NULL COMMENT '[FK] `${prefix}store`.`id`',
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `${prefix}ingredient`
--

CREATE TABLE `${prefix}ingredient` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `${prefix}options`
--

CREATE TABLE `${prefix}options` (
  `id` int(11) NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `${prefix}orders`
--

CREATE TABLE `${prefix}orders` (
  `id` int(11) NOT NULL,
  `rid` int(11) NOT NULL COMMENT '[FK] `${prefix}reservation`.`id`',
  `did` int(11) NOT NULL COMMENT '[FK] `${prefix}dish`.`id`',
  `amount` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `${prefix}reservation`
--

CREATE TABLE `${prefix}reservation` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '[FK] `${prefix}user`.`id`',
  `stid` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `people` smallint(11) UNSIGNED NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `${prefix}store`
--

CREATE TABLE `${prefix}store` (
  `id` int(11) NOT NULL,
  `cpid` int(11) NOT NULL COMMENT '[FK] `${prefix}company`.`id`',
  `name` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `location` longtext COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `${prefix}store_dish`
--

CREATE TABLE `${prefix}store_dish` (
  `store_id` int(11) NOT NULL COMMENT '[FK] `${prefix}store`.`id`',
  `dish_id` int(11) NOT NULL COMMENT '[FK] `${prefix}dish`.`id`',
  `price` float NOT NULL,
  `available_from` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `${prefix}tables`
--

CREATE TABLE `${prefix}tables` (
  `id` int(11) NOT NULL,
  `stid` int(11) NOT NULL COMMENT '[FK] `${prefix}store`.`id`',
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seats` tinyint(3) UNSIGNED NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `${prefix}user`
--

CREATE TABLE `${prefix}user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `score` tinyint(4) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Can do nothing if true',
  `is_blocked` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Reservation creation',
  `is_locked` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Locked due to suspicious activity',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `${prefix}category`
--
ALTER TABLE `${prefix}category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stid` (`cid`),
  ADD KEY `catid` (`catid`);

--
-- Indexes for table `${prefix}company`
--
ALTER TABLE `${prefix}company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `${prefix}dish`
--
ALTER TABLE `${prefix}dish`
  ADD PRIMARY KEY (`id`),
  ADD KEY `catid` (`catid`);

--
-- Indexes for table `dish_ingredient`
--
ALTER TABLE `${prefix}dish_ingredient`
  ADD PRIMARY KEY (`dish_id`,`ingred_id`),
  ADD KEY `ingred_id` (`ingred_id`),
  ADD KEY `dish_id` (`dish_id`);

--
-- Indexes for table `${prefix}employee`
--
ALTER TABLE `${prefix}employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `stid` (`stid`);

--
-- Indexes for table `${prefix}ingredient`
--
ALTER TABLE `${prefix}ingredient`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `${prefix}options`
--
ALTER TABLE `${prefix}options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `${prefix}orders`
--
ALTER TABLE `${prefix}orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rid` (`rid`),
  ADD KEY `order_did` (`did`);

--
-- Indexes for table `${prefix}reservation`
--
ALTER TABLE `${prefix}reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `stid` (`stid`);

--
-- Indexes for table `${prefix}store`
--
ALTER TABLE `${prefix}store`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cpid` (`cpid`);

--
-- Indexes for table `store_dish`
--
ALTER TABLE `${prefix}store_dish`
  ADD PRIMARY KEY (`store_id`,`dish_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `dish_id` (`dish_id`);

--
-- Indexes for table `${prefix}tables`
--
ALTER TABLE `${prefix}tables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stid` (`stid`);

--
-- Indexes for table `${prefix}user`
--
ALTER TABLE `${prefix}user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `${prefix}category`
--
ALTER TABLE `${prefix}category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `${prefix}company`
--
ALTER TABLE `${prefix}company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `${prefix}dish`
--
ALTER TABLE `${prefix}dish`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `${prefix}employee`
--
ALTER TABLE `${prefix}employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `${prefix}ingredient`
--
ALTER TABLE `${prefix}ingredient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `${prefix}options`
--
ALTER TABLE `${prefix}options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `${prefix}orders`
--
ALTER TABLE `${prefix}orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `${prefix}reservation`
--
ALTER TABLE `${prefix}reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `${prefix}store`
--
ALTER TABLE `${prefix}store`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `${prefix}tables`
--
ALTER TABLE `${prefix}tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `${prefix}user`
--
ALTER TABLE `${prefix}user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `${prefix}category`
--
ALTER TABLE `${prefix}category`
  ADD CONSTRAINT `category_catid` FOREIGN KEY (`catid`) REFERENCES `${prefix}category` (`id`),
  ADD CONSTRAINT `category_cid` FOREIGN KEY (`cid`) REFERENCES `${prefix}company` (`id`);

--
-- Constraints for table `${prefix}dish`
--
ALTER TABLE `${prefix}dish`
  ADD CONSTRAINT `dish_catid` FOREIGN KEY (`catid`) REFERENCES `${prefix}category` (`id`);

--
-- Constraints for table `dish_ingredient`
--
ALTER TABLE `dish_ingredient`
  ADD CONSTRAINT `dish_ingr_did` FOREIGN KEY (`dish_id`) REFERENCES `${prefix}dish` (`id`),
  ADD CONSTRAINT `dish_ingr_ingid` FOREIGN KEY (`ingred_id`) REFERENCES `${prefix}ingredient` (`id`);

--
-- Constraints for table `${prefix}employee`
--
ALTER TABLE `${prefix}employee`
  ADD CONSTRAINT `employee_stid` FOREIGN KEY (`stid`) REFERENCES `${prefix}store` (`id`);

--
-- Constraints for table `${prefix}orders`
--
ALTER TABLE `${prefix}orders`
  ADD CONSTRAINT `order_did` FOREIGN KEY (`did`) REFERENCES `${prefix}dish` (`id`),
  ADD CONSTRAINT `order_rid` FOREIGN KEY (`rid`) REFERENCES `${prefix}reservation` (`id`);

--
-- Constraints for table `${prefix}reservation`
--
ALTER TABLE `${prefix}reservation`
  ADD CONSTRAINT `reservation_stid` FOREIGN KEY (`stid`) REFERENCES `${prefix}store` (`id`),
  ADD CONSTRAINT `reservation_uid` FOREIGN KEY (`uid`) REFERENCES `${prefix}user` (`id`);

--
-- Constraints for table `${prefix}store`
--
ALTER TABLE `${prefix}store`
  ADD CONSTRAINT `store_cpid` FOREIGN KEY (`cpid`) REFERENCES `${prefix}company` (`id`);

--
-- Constraints for table `store_dish`
--
ALTER TABLE `store_dish`
  ADD CONSTRAINT `store_dish_did` FOREIGN KEY (`dish_id`) REFERENCES `${prefix}dish` (`id`),
  ADD CONSTRAINT `store_dish_stid` FOREIGN KEY (`store_id`) REFERENCES `${prefix}store` (`id`);

--
-- Constraints for table `${prefix}tables`
--
ALTER TABLE `${prefix}tables`
  ADD CONSTRAINT `table_stid` FOREIGN KEY (`stid`) REFERENCES `${prefix}store` (`id`) ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

EOF;
    }
?>