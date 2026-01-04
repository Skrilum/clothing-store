<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireAdmin(); // Проверка прав администратора

require_once '../classes/Product.php';
require_once '../classes/Category.php';
require_once '../classes/User.php';

$pdo = getDBConnection();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель - Магазин одежды</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f0f2f5; }
        
        .admin-header { background: #1a1a1a; color: white; padding: 15px 0; }
        .admin-container { max-width: 1400px; margin: 0 auto; padding: 0 20px; }
        
        .admin-nav { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav a { color: white; text-decoration: none; margin: 0 10px; }
        .admin-nav a:hover { color: #4CAF50; }
        
        .admin-main { display: flex; margin-top: 20px; min-height: 80vh; }
        .admin-sidebar { width: 250px; background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .admin-content { flex: 1; margin-left: 20px; background: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        
        .admin-menu { list-style: none; }
        .admin-menu li { margin: 10px 0; }
        .admin-menu a { display: block; padding: 10px 15px; color: #333; text-decoration: none; border-radius: 4px; }
        .admin-menu a:hover { background: #f5f5f5; }
        .admin-menu a.active { background: #4CAF50; color: white; }
        
        .admin-title { margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #f0f2f5; }
        
        .btn { display: inline-block; padding: 8px 16px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        .btn-danger { background: #f44336; }
        .btn-warning { background: #ff9800; }
        .btn-info { background: #2196F3; }
        
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        .table th { background: #f5f5f5; font-weight: bold; }
        .table tr:hover { background: #f9f9f9; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        
        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="admin-container">
            <div class="admin-nav">
                <div>
                    <span style="font-weight: bold; font-size: 18px;">Админ-панель</span>
                    <span style="margin-left: 20px; color: #ccc;">
                        Привет, <?php echo htmlspecialchars($_SESSION['user']['username'] ?? 'Админ'); ?>
                    </span>
                </div>
                <div>
                    <a href="../index.php">В магазин</a>
                    <a href="../logout.php">Выйти</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="admin-container">
        <div class="admin-main">
            <div class="admin-sidebar">
                <ul class="admin-menu">
                    <li><a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : ''; ?>>Главная</a></li>
                    <li><a href="products.php" <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'class="active"' : ''; ?>>Товары</a></li>
                    <li><a href="categories.php" <?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'class="active"' : ''; ?>>Категории</a></li>
                    <li><a href="users.php" <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'class="active"' : ''; ?>>Пользователи</a></li>
                    <li><a href="orders.php" <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'class="active"' : ''; ?>>Заказы</a></li>
                </ul>
            </div>
            <div class="admin-content">