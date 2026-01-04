<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
requireLogin(); // Только для авторизованных пользователей

require_once 'templates/header.php';
?>

<div style="max-width: 1000px; margin: 0 auto;">
    <h2>Мои заказы</h2>
    
    <?php if(isset($_SESSION['user'])): ?>
        <p>Здравствуйте, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</p>
    <?php endif; ?>
    
    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-top: 20px;">
        <p style="text-align: center; color: #666; font-size: 18px;">
            У вас пока нет заказов
        </p>
        
        <div style="text-align: center; margin-top: 30px;">
            <p>После оформления заказов они появятся на этой странице.</p>
            
            <div style="margin-top: 30px;">
                <a href="index.php" style="display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px;">
                    Перейти к покупкам
                </a>
                
                <a href="cart.php" style="display: inline-block; padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 4px;">
                    Перейти в корзину
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>