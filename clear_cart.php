<?php
require_once 'includes/config.php';
require_once 'classes/Cart.php';

$cart = new Cart();
$cart->clear();

$_SESSION['success'] = 'Корзина очищена';
header('Location: cart.php');
exit;
?>