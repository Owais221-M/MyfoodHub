-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 18, 2025 at 06:03 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food_order`
--

-- --------------------------------------------------------

--
-- Table structure for table `MenuItems`
--

CREATE TABLE `MenuItems` (
  `MenuItemID` int(11) NOT NULL,
  `RestaurantID` int(11) NOT NULL,
  `ItemName` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `ImageURL` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `MenuItems`
--

INSERT INTO `MenuItems` (`MenuItemID`, `RestaurantID`, `ItemName`, `Description`, `Price`, `ImageURL`) VALUES
(50, 9, 'Butter Chicken', 'Creamy tomato-based chicken curry.', 15.99, NULL),
(51, 9, 'Palak Paneer', 'Spinach and cottage cheese curry.', 13.99, NULL),
(52, 9, 'Naan Bread', 'Soft and fluffy Indian flatbread.', 3.50, NULL),
(53, 10, 'Quinoa Salad', 'Healthy quinoa with mixed vegetables.', 9.99, NULL),
(54, 10, 'Veggie Burger', 'Plant-based patty with fresh toppings.', 11.99, NULL),
(55, 10, 'Grilled Vegetable Panini', 'Grilled seasonal vegetables with pesto.', 8.50, NULL),
(56, 11, 'Margherita Pizza', 'Classic pizza with tomatoes, mozzarella, and basil.', 12.99, NULL),
(57, 11, 'Pepperoni Pizza', 'Pepperoni slices with mozzarella and tomato sauce.', 14.99, NULL),
(58, 11, 'Veggie Supreme Pizza', 'Loaded with bell peppers, olives, onions, and mushrooms.', 13.99, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `RestaurantID` int(11) NOT NULL,
  `OrderDate` datetime NOT NULL DEFAULT current_timestamp(),
  `OrderStatus` enum('Pending','Processing','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `TotalPrice` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `UserID`, `RestaurantID`, `OrderDate`, `OrderStatus`, `TotalPrice`) VALUES
(7, 4, 9, '2025-02-11 01:42:51', 'Completed', 15.99),
(8, 4, 9, '2025-02-11 01:44:05', 'Completed', 29.98),
(9, 4, 10, '2025-02-12 00:52:29', 'Pending', 8.50),
(10, 4, 10, '2025-02-12 00:56:27', 'Pending', 11.99),
(11, 4, 10, '2025-02-12 01:23:54', 'Pending', 11.99),
(12, 4, 10, '2025-02-12 01:46:22', 'Pending', 11.99),
(13, 4, 10, '2025-02-12 09:41:05', 'Pending', 11.99),
(14, 4, 11, '2025-02-12 15:29:09', 'Pending', 14.99),
(15, 8, 11, '2025-02-12 16:37:55', 'Pending', 12.99),
(16, 4, 9, '2025-02-16 17:35:04', 'Pending', 15.99);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `OrderItemID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `MenuItemID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 1,
  `Price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`OrderItemID`, `OrderID`, `MenuItemID`, `Quantity`, `Price`) VALUES
(5, 8, 50, 1, 15.99),
(6, 8, 51, 1, 13.99),
(7, 9, 55, 1, 8.50),
(8, 10, 54, 1, 11.99),
(9, 11, 54, 1, 11.99),
(10, 12, 54, 1, 11.99),
(11, 13, 54, 1, 11.99),
(12, 14, 57, 1, 14.99),
(13, 15, 56, 1, 12.99),
(14, 16, 50, 1, 15.99);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `PaymentID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `PaymentMethod` enum('COD','Credit Card','PayPal') DEFAULT 'COD',
  `PaymentStatus` enum('Pending','Completed','Failed') DEFAULT 'Pending',
  `PaymentDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`PaymentID`, `OrderID`, `UserID`, `Amount`, `PaymentMethod`, `PaymentStatus`, `PaymentDate`) VALUES
(1, 8, 4, 29.98, 'COD', 'Completed', '2025-02-11 00:44:05'),
(2, 9, 4, 8.50, 'COD', 'Completed', '2025-02-11 23:52:29'),
(3, 10, 4, 11.99, 'COD', 'Completed', '2025-02-11 23:56:27'),
(4, 11, 4, 11.99, 'COD', 'Completed', '2025-02-12 00:23:54'),
(5, 12, 4, 11.99, 'COD', 'Completed', '2025-02-12 00:46:22'),
(6, 13, 4, 11.99, 'COD', 'Completed', '2025-02-12 08:41:05'),
(7, 14, 4, 14.99, 'COD', 'Completed', '2025-02-12 14:29:09'),
(8, 15, 8, 12.99, 'COD', 'Pending', '2025-02-12 15:37:55'),
(9, 16, 4, 15.99, 'COD', 'Pending', '2025-02-16 16:35:04');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `PromotionID` int(11) NOT NULL,
  `RestaurantID` int(11) NOT NULL,
  `PromoCode` varchar(50) NOT NULL,
  `DiscountPercentage` decimal(5,2) NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`PromotionID`, `RestaurantID`, `PromoCode`, `DiscountPercentage`, `StartDate`, `EndDate`) VALUES
(5, 9, 'DISCOUNT10', 10.00, '2025-02-01', '2025-12-31');

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `RestaurantID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Location` varchar(100) NOT NULL,
  `CuisineType` varchar(50) NOT NULL,
  `Description` text DEFAULT NULL,
  `ImageURL` varchar(255) DEFAULT NULL,
  `OwnerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`RestaurantID`, `Name`, `Location`, `CuisineType`, `Description`, `ImageURL`, `OwnerID`) VALUES
(9, 'Taj Mahal', 'Via Roma 100, Rome, Italy', 'Indian', 'Authentic Indian cuisine with a royal touch.', 'images/taj_mahal.jpg', 3),
(10, 'Veggie Delight', 'Via Milano 200, Milan, Italy', 'Vegetarian', 'Delicious vegetarian dishes made from fresh, local ingredients.', 'images/veggie_delight.jpg', 3),
(11, 'Pizza Planet', 'Corso Venezia 300, Venice, Italy', 'Italian', 'Classic and innovative pizzas inspired by Italian traditions.', 'images/pizza_planet.jpg', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `role` enum('customer','restaurant_owner') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Email`, `Password`, `role`) VALUES
(3, 'owais1', '2@gmail.com', '$2y$10$BlesDlYYD/rIaVevWYUpYOKmDIYkXxvFZX6ICG6Sp0luw0hqDu6aK', 'restaurant_owner'),
(4, 'Monti', '1@gmail.com', '$2y$10$EOCI3iix1Pjd.W8yfweB8eKsHZXRHYx.OE.L.gYMmB4nkXbnMx7YK', 'customer'),
(8, 'acelesti', 'acelesti@unime.it', '$2y$10$BwqiJIE7DAhNiR0EzGEXLepK2zHHKp1SiThFfRSDq2h5bj7as1zN2', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `MenuItems`
--
ALTER TABLE `MenuItems`
  ADD PRIMARY KEY (`MenuItemID`),
  ADD KEY `RestaurantID` (`RestaurantID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `RestaurantID` (`RestaurantID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`OrderItemID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `MenuItemID` (`MenuItemID`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `fk_order` (`OrderID`),
  ADD KEY `fk_user` (`UserID`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`PromotionID`),
  ADD UNIQUE KEY `promo_code` (`PromoCode`),
  ADD KEY `restaurant_id` (`RestaurantID`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`RestaurantID`),
  ADD KEY `OwnerID` (`OwnerID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `MenuItems`
--
ALTER TABLE `MenuItems`
  MODIFY `MenuItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `OrderItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `PromotionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `RestaurantID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `MenuItems`
--
ALTER TABLE `MenuItems`
  ADD CONSTRAINT `menuitems_ibfk_1` FOREIGN KEY (`RestaurantID`) REFERENCES `restaurants` (`RestaurantID`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`RestaurantID`) REFERENCES `restaurants` (`RestaurantID`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`MenuItemID`) REFERENCES `MenuItems` (`MenuItemID`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `promotions`
--
ALTER TABLE `promotions`
  ADD CONSTRAINT `promotions_ibfk_1` FOREIGN KEY (`RestaurantID`) REFERENCES `restaurants` (`RestaurantID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD CONSTRAINT `restaurants_ibfk_1` FOREIGN KEY (`OwnerID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
