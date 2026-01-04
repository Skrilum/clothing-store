<?php
require_once 'includes/config.php';
require_once 'classes/Cart.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    $_SESSION['error'] = 'Не указан товар или количество';
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

$product_id = (int)$_POST['product_id'];
$quantity = (int)$_POST['quantity'];

if ($product_id <= 0 || $quantity <= 0) {
    $_SESSION['error'] = 'Некорректные данные';
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

// Создаем объект корзины
$cart = new Cart();
$cart->add($product_id, $quantity);

$_SESSION['success'] = 'Товар добавлен в корзину';
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit;
?>