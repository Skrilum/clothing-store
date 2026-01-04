<?php
require_once 'includes/config.php';
require_once 'classes/Cart.php';
require_once 'classes/Product.php';

$pdo = getDBConnection();
$cart = new Cart();
$productManager = new Product($pdo);

$cartItems = $cart->getItems();
$productsData = [];
$totalPrice = 0;

// Получаем данные о товарах из корзины
foreach ($cartItems as $product_id => $quantity) {
    $product = $productManager->getById($product_id);
    if ($product) {
        $product['cart_quantity'] = $quantity;
        $product['subtotal'] = $product['price'] * $quantity;
        $totalPrice += $product['subtotal'];
        $productsData[] = $product;
    } else {
        // Если товар не найден, удаляем его из корзины
        $cart->remove($product_id);
    }
}

require_once 'templates/header.php';
?>

<div style="max-width: 1000px; margin: 0 auto;">
    <h2>Корзина покупок</h2>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if(empty($productsData)): ?>
        <div style="text-align: center; padding: 50px 0;">
            <p style="font-size: 18px; color: #666;">Ваша корзина пуста</p>
            <a href="index.php" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">
                Вернуться к покупкам
            </a>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <thead>
                    <tr style="background: #f5f5f5;">
                        <th style="padding: 15px; text-align: left; border-bottom: 1px solid #ddd;">Товар</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 1px solid #ddd;">Цена</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 1px solid #ddd;">Количество</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 1px solid #ddd;">Сумма</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 1px solid #ddd;">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($productsData as $product): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <?php if(!empty($product['image_path'])): ?>
                                        <img src="<?php echo getImagePath($product['image_path']); ?>" 
                                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                                             style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                                    <?php endif; ?>
                                    <div>
                                        <a href="product.php?id=<?php echo $product['id']; ?>" style="font-weight: bold; text-decoration: none; color: #333;">
                                            <?php echo htmlspecialchars($product['name']); ?>
                                        </a>
                                        <?php if(!empty($product['size'])): ?>
                                            <div style="color: #666; font-size: 14px; margin-top: 5px;">
                                                Размер: <?php echo htmlspecialchars($product['size']); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if(!empty($product['color'])): ?>
                                            <div style="color: #666; font-size: 14px;">
                                                Цвет: <?php echo htmlspecialchars($product['color']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 15px; font-weight: bold;">
                                <?php echo number_format($product['price'], 0, ',', ' '); ?> руб.
                            </td>
                            <td style="padding: 15px;">
                                <form action="update_cart.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $product['cart_quantity']; ?>" 
                                           min="1" max="<?php echo $product['stock_quantity']; ?>"
                                           style="width: 70px; padding: 5px; border: 1px solid #ddd; border-radius: 4px;">
                                    <button type="submit" style="margin-left: 5px; padding: 5px 10px; background: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                        Обновить
                                    </button>
                                </form>
                            </td>
                            <td style="padding: 15px; font-weight: bold; color: #4CAF50;">
                                <?php echo number_format($product['subtotal'], 0, ',', ' '); ?> руб.
                            </td>
                            <td style="padding: 15px;">
                                <a href="remove_from_cart.php?id=<?php echo $product['id']; ?>" 
                                   style="color: #f44336; text-decoration: none; padding: 5px 10px; border: 1px solid #f44336; border-radius: 4px;"
                                   onclick="return confirm('Удалить товар из корзины?')">
                                    Удалить
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background: #f9f9f9;">
                        <td colspan="3" style="padding: 15px; text-align: right; font-weight: bold;">
                            Итого:
                        </td>
                        <td style="padding: 15px; font-weight: bold; color: #4CAF50; font-size: 18px;">
                            <?php echo number_format($totalPrice, 0, ',', ' '); ?> руб.
                        </td>
                        <td style="padding: 15px;">
                            <a href="clear_cart.php" 
                               style="color: #666; text-decoration: none; padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px;"
                               onclick="return confirm('Очистить всю корзину?')">
                                Очистить корзину
                            </a>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div style="display: flex; justify-content: space-between; margin-top: 30px;">
            <a href="index.php" style="padding: 10px 20px; background: #ddd; color: #333; text-decoration: none; border-radius: 4px;">
                ← Продолжить покупки
            </a>
            
            <?php if(isset($_SESSION['user'])): ?>
                <a href="checkout.php" style="padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
                    Оформить заказ →
                </a>
            <?php else: ?>
                <div>
                    <p style="color: #666; margin-bottom: 10px;">Для оформления заказа необходимо войти в систему</p>
                    <a href="login.php?return=cart" style="padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 4px;">
                        Войти
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script src="/shop/js/cart.js"></script>

<?php require_once 'templates/footer.php'; ?>