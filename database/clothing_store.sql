-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Янв 04 2026 г., 13:00
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `clothing_store`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`, `is_active`) VALUES
(1, 'Футболки', 1),
(2, 'Джинсы', 1),
(3, 'Куртки', 1),
(4, 'Обувь', 1),
(5, 'Аксессуары', 1),
(6, 'Свитшоты', 1),
(7, 'Рубашки', 1),
(8, 'Шорты', 1),
(9, 'Платья', 1),
(10, 'Юбки', 1),
(11, 'Платья1', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `size` enum('S','M','L','XL') DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `fabric_type` varchar(100) DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `size`, `color`, `fabric_type`, `image_path`, `stock_quantity`, `is_active`) VALUES
(1, 'Футболка белая', 'Белая хлопковая футболка', 42.00, 1, 'M', 'Белый', 'Хлопок', '/uploads/shirts/white.webp', 25, 1),
(2, 'Футболка черная', 'Черная базовая футболка', 38.50, 1, 'L', 'Черный', 'Хлопок', '/uploads/shirts/black.jpg', 30, 1),
(3, 'Футболка серая', 'Серая футболка oversize', 45.50, 1, 'XL', 'Серый', 'Хлопок', '/uploads/shirts/gray.jpg', 15, 1),
(4, 'Футболка с принтом', 'Футболка с графическим принтом', 52.50, 1, 'M', 'Разноцветный', 'Хлопок', '/uploads/shirts/graphic.avif', 20, 1),
(5, 'Джинсы черные', 'Черные скинни джинсы', 140.00, 2, 'L', 'Черный', 'Деним', '/uploads/jeans/black.webp', 12, 1),
(6, 'Джинсы синие', 'Синие джинсы прямого кроя', 122.50, 2, 'M', 'Синий', 'Деним', '/uploads/jeans/blue.jpg', 18, 1),
(7, 'Джинсы рваные', 'Джинсы с потертостями', 157.50, 2, 'S', 'Голубой', 'Деним', '/uploads/jeans/ripped.jpg', 8, 1),
(8, 'Джинсы mom-fit', 'Джинсы завышенная талия', 129.50, 2, 'M', 'Темно-синий', 'Деним', '/uploads/jeans/mom.avif', 10, 1),
(9, 'Куртка кожаная', 'Кожаная куртка черного цвета', 420.00, 3, 'XL', 'Черный', 'Кожа', '/uploads/jackets/leather.jpg', 5, 1),
(10, 'Куртка джинсовая', 'Джинсовая куртка', 210.00, 3, 'L', 'Синий', 'Деним', '/uploads/jackets/denim.jfif', 7, 1),
(11, 'Куртка ветровка', 'Ветровка спортивная', 140.00, 3, 'M', 'Красный', 'Полиэстер', '/uploads/jackets/windbreaker.jpg', 15, 1),
(12, 'Куртка пуховик', 'Теплый пуховик', 350.00, 3, 'XL', 'Черный', 'Нейлон', '/uploads/jackets/puffer.jpeg', 6, 1),
(13, 'Кроссовки белые', 'Белые кожаные кроссовки', 210.00, 4, NULL, 'Белый', 'Кожа', '/uploads/shoes/sneakers_white.jfif', 14, 1),
(14, 'Туфли черные', 'Черные кожаные туфли', 245.00, 4, NULL, 'Черный', 'Кожа', '/uploads/shoes/shoes_black.jpg', 9, 1),
(15, 'Ботинки осенние', 'Осенние ботинки', 280.00, 4, NULL, 'Коричневый', 'Замша', '/uploads/shoes/boots.jfif', 7, 1),
(16, 'Кеды красные', 'Красные текстильные кеды', 105.00, 4, NULL, 'Красный', 'Текстиль', '/uploads/shoes/sneakers_red.jfif', 20, 1),
(17, 'Кепка черная', 'Бейсболка черного цвета', 31.50, 5, NULL, 'Черный', 'Хлопок', '/uploads/accessories/cap.jpg', 35, 1),
(18, 'Ремень кожаный', 'Кожаный ремень', 52.50, 5, NULL, 'Коричневый', 'Кожа', '/uploads/accessories/belt.webp', 22, 1),
(19, 'Сумка спортивная', 'Спортивная сумка', 87.50, 5, NULL, 'Черный', 'Полиэстер', '/uploads/accessories/bag.jpg', 18, 1),
(20, 'Очки солнцезащитные', 'Солнцезащитные очки', 70.00, 5, NULL, 'Черный', 'Пластик', '/uploads/accessories/glasses.jfif', 25, 1),
(21, 'Свитшот серый', 'Серый свитшот oversize', 105.00, 6, 'L', 'Серый', 'Хлопок', '/uploads/sweatshirts/gray.webp', 16, 1),
(22, 'Свитшот с капюшоном', 'Худи черного цвета', 122.50, 6, 'M', 'Черный', 'Хлопок', '/uploads/sweatshirts/hoodie.webp', 12, 1),
(23, 'Свитшот оверсайз', 'Свободный свитшот', 98.00, 6, 'XL', 'Бежевый', 'Хлопок', '/uploads/sweatshirts/beige.jfif', 14, 1),
(24, 'Рубашка белая', 'Белая классическая рубашка', 87.50, 7, 'M', 'Белый', 'Хлопок', '/uploads/shirts/classic_white.jfif', 18, 1),
(25, 'Рубашка в клетку', 'Рубашка в красную клетку', 73.50, 7, 'L', 'Красный', 'Хлопок', '/uploads/shirts/checkered.webp', 15, 1),
(26, 'Шорты джинсовые', 'Джинсовые шорты', 91.00, 8, 'M', 'Синий', 'Деним', '/uploads/shorts/denim.webp', 20, 1),
(27, 'Шорты спортивные', 'Спортивные шорты', 52.50, 8, 'L', 'Черный', 'Полиэстер', '/uploads/shorts/sport.jpeg', 25, 1),
(28, 'Платье черное', 'Черное платье миди', 175.00, 9, 'S', 'Черный', 'Вискоза', '/uploads/dresses/black_midi.jpg', 8, 1),
(29, 'Платье летнее', 'Летнее платье с цветами', 140.00, 9, 'M', 'Цветочный', 'Хлопок', '/uploads/dresses/summer.jpg', 12, 1),
(30, 'Юбка черная', 'Черная юбка карандаш', 122.50, 10, 'S', 'Черный', 'Полиэстер', '/uploads/skirts/pencil.jpg', 10, 1),
(31, 'Юбка джинсовая', 'Джинсовая юбка миди', 105.00, 10, 'M', 'Синий', 'Деним', '/uploads/skirts/denim.jpg', 15, 1),
(32, 'Трусы', 'Мужские', 25.00, NULL, '', 'Серый', 'Хлопок', '/images/no-image.jpg', 3, 0),
(33, 'Трусы', 'Серые', 25.00, NULL, '', 'Серый', 'Хлопок', '/uploads/gray.jpg', 5, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `role`, `created_at`) VALUES
(1, 'ivan@mail.ru', '$2y$12$VFA8Mx.UMR6g9HcbnWm4Y.qqVY8vs.WC/u7zclMFFmyxB7bhjC2R.', 'Иван', 'user', '2025-11-17 22:42:15'),
(2, 'admin@mail.ru', '$2y$10$b7qPzLrKOYQgUCOCyHe2UeDPuFNCRpwa0kN12webBRZfsNv2IQe5u', 'Admin', 'admin', '2025-12-04 21:33:54'),
(3, 'ivan3@mail.ru', '$2y$10$DxvqKHoP/YqfCNFpRezaXeOW/IX8RJwqDsiR19MC8XhL/1Hgrwo0W', 'Иван3', 'user', '2025-12-05 11:05:23'),
(5, 'admin@example.com', '$2y$12$ZvCeEyfnMn6wwS6haSp8qOgXmXr8me/3nk4YGCDZKqU7BIOx8QkbC', 'Администратор', 'admin', '2025-12-18 17:43:41');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
