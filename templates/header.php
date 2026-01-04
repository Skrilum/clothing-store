<?php
require_once 'includes/config.php';
require_once 'classes/Category.php';
require_once 'classes/Cart.php';

$cart = new Cart();
$cartCount = $cart->getCount();

$pdo = getDBConnection();
$categoryManager = new Category($pdo);
$categories = $categoryManager->getAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин одежды</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        
        .header { background: #333; color: white; padding: 15px 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 15px; }
        
        .top-menu { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .logo a { color: white; text-decoration: none; font-size: 24px; font-weight: bold; }
        .nav a { color: white; margin: 0 10px; text-decoration: none; }
        .nav a:hover { text-decoration: underline; }
        .user-info { color: #4CAF50; margin-right: 15px; }
        .cart-info { background: #4CAF50; padding: 5px 10px; border-radius: 3px; color: white; }
        
        .categories { background: #444; padding: 10px 0; }
        .categories-nav { display: flex; gap: 20px; }
        .categories-nav a { color: #ddd; text-decoration: none; }
        .categories-nav a:hover { color: white; }
        
        .main-content { padding: 20px 0; }
        
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .product-card { background: white; border-radius: 5px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .product-image { width: 100%; height: 200px; object-fit: cover; }
        .product-info { padding: 15px; }
        .product-title { font-size: 16px; margin-bottom: 5px; }
        .product-price { color: #4CAF50; font-weight: bold; font-size: 18px; }
        .product-category { color: #777; font-size: 14px; }
        
        .product-detail { background: white; padding: 30px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .product-detail-image { max-width: 400px; width: 100%; height: auto; }
        
        .error { color: red; padding: 10px; background: #ffe6e6; margin: 10px 0; }
        .success { color: green; padding: 10px; background: #e6ffe6; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="top-menu">
                <div class="logo">
                    <a href="index.php">Магазин одежды</a>
                </div>
                <div class="nav">
                    <?php if(isset($_SESSION['user'])): ?>
                        <span class="user-info">Привет, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</span>
                        <a href="logout.php">Выйти</a>
                    <?php else: ?>
                        <a href="login.php">Войти</a>
                        <a href="register.php">Регистрация</a>
                    <?php endif; ?>
                    <a href="cart.php" class="cart-info">Корзина (<?php echo $cartCount; ?>)</a>
                </div>
            </div>
            
            <?php if(!empty($categories)): ?>
            <div class="categories">
                <div class="categories-nav">
                    <a href="index.php">Все товары</a>
                    <?php foreach($categories as $category): ?>
                        <a href="category.php?id=<?php echo $category['id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="container main-content">