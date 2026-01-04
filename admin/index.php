<?php
require_once 'header.php';

// Создаем объекты для статистики
$productManager = new Product($pdo);
$categoryManager = new Category($pdo);
$userManager = new User($pdo);

// Получаем данные с проверкой
$productCount = $productManager->getCount();
$categoryCount = $categoryManager->getCount();
$users = $userManager->getAllUsers();
$userCount = is_array($users) ? count($users) : 0;
?>

<h1 class="admin-title">Панель управления</h1>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 8px;">
        <h3 style="margin-bottom: 10px;">Товары</h3>
        <p style="font-size: 32px; font-weight: bold; margin: 10px 0;"><?php echo $productCount; ?></p>
        <a href="products.php" style="color: white; text-decoration: underline;">Управление товарами</a>
    </div>
    
    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 25px; border-radius: 8px;">
        <h3 style="margin-bottom: 10px;">Категории</h3>
        <p style="font-size: 32px; font-weight: bold; margin: 10px 0;"><?php echo $categoryCount; ?></p>
        <a href="categories.php" style="color: white; text-decoration: underline;">Управление категориями</a>
    </div>
    
    <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 25px; border-radius: 8px;">
        <h3 style="margin-bottom: 10px;">Пользователи</h3>
        <p style="font-size: 32px; font-weight: bold; margin: 10px 0;"><?php echo $userCount; ?></p>
        <a href="users.php" style="color: white; text-decoration: underline;">Управление пользователями</a>
    </div>
    
    <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 25px; border-radius: 8px;">
        <h3 style="margin-bottom: 10px;">Заказы</h3>
        <p style="font-size: 32px; font-weight: bold; margin: 10px 0;">0</p>
        <a href="orders.php" style="color: white; text-decoration: underline;">Управление заказами</a>
    </div>
</div>

<div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-top: 30px;">
    <h3>Быстрые действия</h3>
    <div style="margin-top: 15px;">
        <a href="products.php?action=create" class="btn" style="margin-right: 10px;">Добавить товар</a>
        <a href="categories.php?action=create" class="btn" style="margin-right: 10px;">Добавить категорию</a>
        <a href="users.php?action=create" class="btn">Добавить пользователя</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>