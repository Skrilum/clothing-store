<?php
require_once 'includes/config.php';
require_once 'classes/Cart.php';
require_once 'includes/auth.php';

// Проверка авторизации
if (!isset($_SESSION['user'])) {
    header('Location: login.php?return=checkout');
    exit;
}

$cart = new Cart();

if ($cart->isEmpty()) {
    header('Location: cart.php');
    exit;
}

// Получаем содержимое корзины ДО очистки
$cartItems = $cart->getItems();

$cart->clear();

require_once 'templates/header.php';
?>

<div style="max-width: 600px; margin: 50px auto; text-align: center;">
    <h2>Заказ оформлен!</h2>
    <div style="padding: 30px; background: #e6ffe6; border-radius: 8px; margin: 30px 0;">
        <p style="font-size: 18px; margin-bottom: 20px;">Ваш заказ успешно оформлен.</p>
        <p><strong>Номер заказа:</strong> #<?php echo time(); ?></p>
        <p><strong>Дата:</strong> <?php echo date('d.m.Y H:i'); ?></p>
        <p>Спасибо за покупку!</p>
    </div>
    
    <?php if(!empty($cartItems)): ?>
    <div style="text-align: left; background: white; padding: 20px; border-radius: 8px; margin: 20px 0;">
        <h3 style="text-align: center;">Состав заказа:</h3>
        <ul>
        <?php 
        // Подключаем менеджер товаров для получения названий
        require_once 'classes/Product.php';
        $pdo = getDBConnection();
        $productManager = new Product($pdo);
        $total = 0;
        
        foreach($cartItems as $product_id => $quantity):
            $product = $productManager->getById($product_id);
            if($product):
                $subtotal = $product['price'] * $quantity;
                $total += $subtotal;
        ?>
            <li style="margin: 10px 0; padding: 10px; border-bottom: 1px solid #eee;">
                <strong><?php echo htmlspecialchars($product['name']); ?></strong><br>
                Количество: <?php echo $quantity; ?> × 
                <?php echo number_format($product['price'], 0, ',', ' '); ?> руб. = 
                <strong><?php echo number_format($subtotal, 0, ',', ' '); ?> руб.</strong>
            </li>
        <?php 
            endif;
        endforeach; 
        ?>
        </ul>
        <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 2px solid #4CAF50;">
            <h3>Итого: <?php echo number_format($total, 0, ',', ' '); ?> руб.</h3>
        </div>
    </div>
    <?php endif; ?>
    
    <p>На ваш email <strong><?php echo htmlspecialchars($_SESSION['user']['email']); ?></strong> отправлено подтверждение заказа.</p>
    
    <div style="margin-top: 30px;">
        <a href="index.php" style="display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">
            Вернуться в магазин
        </a>
        <a href="orders.php" style="display: inline-block; margin-left: 10px; padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 4px;">
            Мои заказы
        </a>
    </div>
</div>

<?php 
require_once 'templates/footer.php'; 
?>