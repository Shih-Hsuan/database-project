-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2024-01-07 05:58:28
-- 伺服器版本： 10.4.28-MariaDB
-- PHP 版本： 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `class`
--

DELIMITER $$
--
-- 函式
--
CREATE DEFINER=`root`@`localhost` FUNCTION `add_product_review` (`prod_id` INT, `username` VARCHAR(50), `rtg` INT) RETURNS INT(11)  BEGIN
    INSERT INTO product_reviews (product_id, username, rating)
    VALUES (prod_id, username, rtg);
    RETURN LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_product_average_rating` (`prod_id` INT) RETURNS FLOAT  BEGIN
    DECLARE avg_rating FLOAT;
    SELECT AVG(rating) INTO avg_rating
    FROM product_reviews
    WHERE product_id = prod_id;
    IF avg_rating IS NULL THEN
        SET avg_rating = 0; -- 設定預設值
    END IF;
    RETURN avg_rating;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- 資料表結構 `admin`
--

CREATE TABLE `admin` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `admin`
--

INSERT INTO `admin` (`username`, `password`) VALUES
('01057006', '$2y$10$9CZhh3arN1RZYDWNlykwquXcXUOwkva8XFZmL.GItxobmv3bB.4cG'),
('01057007', '$2y$10$kUljeg0HSRqTxEKn7n1Uj.noLoIDr/E3gKSl0sLWsAGGjeHm83nKG'),
('01057016', '$2y$10$SjaQqi/l1J.6fUT4fqYtMug53jdmfzzTabAIEwb1/sGMVXLDevt3y'),
('admin', '$2y$10$8N5xtmrqbOobKeakNEP9OeQ0XIufDNqQVIrytnnn3w/kmWrbIo0W2');

-- --------------------------------------------------------

--
-- 資料表結構 `category`
--

CREATE TABLE `category` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, '零食點心'),
(2, '常溫/冷藏飲料'),
(3, '三明治'),
(4, '御飯糰/鮮食便當'),
(5, '杯裝泡麵/湯品'),
(6, '生理用品');

-- --------------------------------------------------------

--
-- 資料表結構 `fixboard`
--

CREATE TABLE `fixboard` (
  `fixboard_id` int(10) NOT NULL,
  `place_id` int(10) UNSIGNED NOT NULL,
  `product_counter` int(10) UNSIGNED DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `fixboard_subject` varchar(100) DEFAULT NULL,
  `fixboard_time` datetime DEFAULT NULL,
  `fixboard_content` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `fixboard`
--

INSERT INTO `fixboard` (`fixboard_id`, `place_id`, `product_counter`, `username`, `fixboard_subject`, `fixboard_time`, `fixboard_content`) VALUES
(38, 1, 36, '01057006', '', '0000-00-00 00:00:00', ''),
(39, 1, 23, '01057006', '123', '0000-00-00 00:00:00', '雞雞雞');

--
-- 觸發器 `fixboard`
--
DELIMITER $$
CREATE TRIGGER `check_admin_username` BEFORE INSERT ON `fixboard` FOR EACH ROW BEGIN
    DECLARE username_count INT;
    SET username_count = (
        SELECT COUNT(*) FROM admin WHERE username = NEW.username
    );
    IF username_count = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'username 不存在！請提供有效的 username。';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_admin_username_fixboardUpdate` BEFORE UPDATE ON `fixboard` FOR EACH ROW BEGIN
    DECLARE username_count INT;
    SET username_count = (
        SELECT COUNT(*) FROM admin WHERE username = NEW.username
    );
    IF username_count = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'username 不存在！請提供有效的 username。';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_product_counter` BEFORE INSERT ON `fixboard` FOR EACH ROW BEGIN
    DECLARE product_count INT;
    SET product_count = (
        SELECT COUNT(*) FROM product WHERE product_counter = NEW.product_counter
    );

    IF product_count = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'product_counter 不存在！請提供有效的 product_counter。';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_product_counter_fixboardUpdate` BEFORE UPDATE ON `fixboard` FOR EACH ROW BEGIN
    DECLARE product_count INT;
    SET product_count = (
        SELECT COUNT(*) FROM product WHERE product_counter = NEW.product_counter
    );
    IF product_count = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'product_counter 不存在！請提供有效的 product_counter。';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- 資料表結構 `place`
--

CREATE TABLE `place` (
  `place_id` int(10) UNSIGNED NOT NULL,
  `place_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `place`
--

INSERT INTO `place` (`place_id`, `place_name`) VALUES
(1, '海大電資大樓一樓'),
(2, '第三餐廳門口');

-- --------------------------------------------------------

--
-- 資料表結構 `product`
--

CREATE TABLE `product` (
  `product_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `place_id` int(10) UNSIGNED NOT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `product_price` int(10) UNSIGNED DEFAULT NULL,
  `product_images` varchar(100) DEFAULT NULL,
  `product_counter` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `product`
--

INSERT INTO `product` (`product_id`, `category_id`, `place_id`, `product_name`, `product_price`, `product_images`, `product_counter`) VALUES
(2, 4, 1, '鮪魚御飯糰', 30, '鮪魚御飯糰', 10),
(3, 4, 1, '石安牧場溏心蛋飯糰', 39, '石安牧場溏心蛋飯糰', 25),
(4, 3, 1, '鮪魚洋芋溏心蛋三明治', 49, '鮪魚洋芋溏心蛋三明治', 15),
(7, 4, 1, '涵碧美饌-紹興東坡肉', 59, 'https://www.7-11.com.tw/freshfoods/1_Ricerolls/images/ricerolls_396.png', 28),
(8, 4, 1, '日式佛蒙特咖哩雞肉飯', 89, 'https://www.7-11.com.tw/freshfoods/5_ForeignDishes/images/ForeignDishes_394.png', 39),
(9, 1, 2, '涵碧美饌-御膳美齡粥', 69, 'https://www.7-11.com.tw/freshfoods/5_ForeignDishes/images/ForeignDishes_399.png', 23),
(10, 1, 2, '蠟筆小新鹽燒鯖魚', 109, 'https://www.7-11.com.tw/freshfoods/4_Snacks/images/Snacks_229.png', 30),
(17, 1, 2, '厚燒玉子', 49, 'https://www.7-11.com.tw/freshfoods/4_Snacks/images/Snacks_230.png', 36),
(19, 3, 2, '茄汁薯餅起司三明治', 59, 'https://www.7-11.com.tw/freshfoods/16_sandwich/images/sandwich_61.png', 37);

-- --------------------------------------------------------

--
-- 資料表結構 `product_reviews`
--

CREATE TABLE `product_reviews` (
  `review_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `rating` int(1) UNSIGNED DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `wishboard`
--

CREATE TABLE `wishboard` (
  `wishboard_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `wishboard_subject` varchar(100) DEFAULT NULL,
  `wishboard_time` date DEFAULT NULL,
  `wishboard_content` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `wishboard`
--

INSERT INTO `wishboard` (`wishboard_id`, `username`, `wishboard_subject`, `wishboard_time`, `wishboard_content`) VALUES
(17, 'admin', '123', '2024-01-06', 'test'),
(22, 'admin', '01057006', '2024-01-01', '01057006'),
(24, 'admin', '01057006', '2024-01-01', '01057006'),
(30, 'admin', '01057006', '2024-01-27', '01057006');

--
-- 觸發器 `wishboard`
--
DELIMITER $$
CREATE TRIGGER `check_admin_username_wishboardInsert` BEFORE INSERT ON `wishboard` FOR EACH ROW BEGIN
    DECLARE username_count INT;
    SET username_count = (
        SELECT COUNT(*) FROM admin WHERE username = NEW.username
    );
    IF username_count = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'username 不存在！請提供有效的 username。';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_admin_username_wishboardUpdate` BEFORE UPDATE ON `wishboard` FOR EACH ROW BEGIN
    DECLARE username_count INT;
    SET username_count = (
        SELECT COUNT(*) FROM admin WHERE username = NEW.username
    );
    IF username_count = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'username 不存在！請提供有效的 username。';
    END IF;
END
$$
DELIMITER ;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`username`);

--
-- 資料表索引 `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- 資料表索引 `fixboard`
--
ALTER TABLE `fixboard`
  ADD PRIMARY KEY (`fixboard_id`),
  ADD KEY `product_counter` (`product_counter`),
  ADD KEY `place_id` (`place_id`),
  ADD KEY `username` (`username`);

--
-- 資料表索引 `place`
--
ALTER TABLE `place`
  ADD PRIMARY KEY (`place_id`);

--
-- 資料表索引 `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `place_id` (`place_id`),
  ADD KEY `product_counter` (`product_counter`);

--
-- 資料表索引 `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `username` (`username`);

--
-- 資料表索引 `wishboard`
--
ALTER TABLE `wishboard`
  ADD PRIMARY KEY (`wishboard_id`),
  ADD KEY `username` (`username`) USING BTREE;

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `fixboard`
--
ALTER TABLE `fixboard`
  MODIFY `fixboard_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `place`
--
ALTER TABLE `place`
  MODIFY `place_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `review_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `wishboard`
--
ALTER TABLE `wishboard`
  MODIFY `wishboard_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `fixboard`
--
ALTER TABLE `fixboard`
  ADD CONSTRAINT `fixboard_ibfk_1` FOREIGN KEY (`product_counter`) REFERENCES `product` (`product_counter`),
  ADD CONSTRAINT `fixboard_ibfk_2` FOREIGN KEY (`place_id`) REFERENCES `place` (`place_id`),
  ADD CONSTRAINT `fixboard_ibfk_3` FOREIGN KEY (`username`) REFERENCES `admin` (`username`);

--
-- 資料表的限制式 `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`place_id`) REFERENCES `place` (`place_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的限制式 `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`username`) REFERENCES `admin` (`username`);

--
-- 資料表的限制式 `wishboard`
--
ALTER TABLE `wishboard`
  ADD CONSTRAINT `wishboard_ibfk_1` FOREIGN KEY (`username`) REFERENCES `admin` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
