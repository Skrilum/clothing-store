<?php
require_once 'includes/config.php';
require_once 'classes/Cart.php';

if (!isset($_GET['id'])) {
    header('Location: cart.php');
    exit;
}

$product_id = (int)$_GET['id'];

$cart = new Cart();
$cart->remove($product_id);

$_SESSION['success'] = 'Товар удален из корзины';
header('Location: cart.php');
exit;
?>