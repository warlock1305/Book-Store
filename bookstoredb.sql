-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Апр 07 2025 г., 01:42
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `bookstoredb`
--

-- --------------------------------------------------------

--
-- Структура таблицы `books`
--

CREATE TABLE `books` (
  `bookID` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `price` float NOT NULL,
  `description` text DEFAULT NULL,
  `publishDate` date DEFAULT NULL,
  `genreID` varchar(50) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `books`
--

INSERT INTO `books` (`bookID`, `title`, `author`, `price`, `description`, `publishDate`, `genreID`, `stock`, `image`) VALUES
('B001', 'Test Book Title', 'Test Author', 29.99, 'This is a test description.', '2025-04-01', 'G001', 15, 'uploads/test-book.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `genres`
--

CREATE TABLE `genres` (
  `genreID` varchar(50) NOT NULL,
  `genreName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `genres`
--

INSERT INTO `genres` (`genreID`, `genreName`) VALUES
('G001', 'Testing Genre');

-- --------------------------------------------------------

--
-- Структура таблицы `orderdetails`
--

CREATE TABLE `orderdetails` (
  `orderDetailID` varchar(50) NOT NULL,
  `orderID` varchar(50) NOT NULL,
  `bookID` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `orderID` varchar(50) NOT NULL,
  `userID` varchar(50) NOT NULL,
  `orderDate` date DEFAULT NULL,
  `shippingAddress` varchar(255) DEFAULT NULL,
  `totalPrice` float DEFAULT NULL,
  `orderStatus` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `reviewID` varchar(50) NOT NULL,
  `bookID` varchar(50) NOT NULL,
  `userID` varchar(50) NOT NULL,
  `reviewDate` date DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `userID` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `firstName` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) DEFAULT NULL,
  `dateJoined` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`bookID`),
  ADD KEY `genreID` (`genreID`);

--
-- Индексы таблицы `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genreID`);

--
-- Индексы таблицы `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`orderDetailID`),
  ADD KEY `orderID` (`orderID`),
  ADD KEY `bookID` (`bookID`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `userID` (`userID`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`reviewID`),
  ADD KEY `bookID` (`bookID`),
  ADD KEY `userID` (`userID`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`genreID`) REFERENCES `genres` (`genreID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`bookID`) REFERENCES `books` (`bookID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`bookID`) REFERENCES `books` (`bookID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
