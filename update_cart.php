<?php
require_once 'includes/config.php';
require_once 'classes/Cart.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}

if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    $_SESSION['error'] = 'Не указаны данные';
    header('Location: cart.php');
    exit;
}

$product_id = (int)$_POST['product_id'];
$quantity = (int)$_POST['quantity'];

$cart = new Cart();
$cart->update($product_id, $quantity);

$_SESSION['success'] = 'Корзина обновлена';
header('Location: cart.php');
exit;
?>