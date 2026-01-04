<?php
require_once 'includes/config.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';
require_once 'classes/Cart.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$pdo = getDBConnection();
$productManager = new Product($pdo);
$categoryManager = new Category($pdo);

$product_id = (int)$_GET['id'];
$product = $productManager->getById($product_id);

if (!$product) {
    header('Location: index.php');
    exit;
}

// Получаем категории для сайдбара
$categories = $categoryManager->getAll();

require_once 'templates/header.php';
?>

<div style="display: flex; gap: 30px;">
    <div style="flex: 3;">
        <div class="product-detail">
            <div style="display: flex; gap: 30px; margin-bottom: 20px;">
                <div style="flex: 1;">
                    <?php if(!empty($product['image_path'])): ?>
                        <img src="<?php echo getImagePath($product['image_path']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             class="product-detail-image">
                    <?php else: ?>
                        <div style="width: 100%; max-width: 400px; height: 400px; background: #eee; display: flex; align-items: center; justify-content: center;">
                            <span>Нет изображения</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div style="flex: 2;">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    <div style="font-size: 24px; color: #4CAF50; font-weight: bold; margin: 15px 0;">
                        <?php echo number_format($product['price'], 0, ',', ' '); ?> руб.
                    </div>
                    
                    <div style="margin: 15px 0;">
                        <strong>Категория:</strong> 
                        <?php if(!empty($product['category_name'])): ?>
                            <a href="category.php?id=<?php echo $product['category_id']; ?>">
                                <?php echo htmlspecialchars($product['category_name']); ?>
                            </a>
                        <?php else: ?>
                            Без категории
                        <?php endif; ?>
                    </div>
                    
                    <?php if(!empty($product['size'])): ?>
                        <div style="margin: 10px 0;">
                            <strong>Размер:</strong> <?php echo htmlspecialchars($product['size']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($product['color'])): ?>
                        <div style="margin: 10px 0;">
                            <strong>Цвет:</strong> <?php echo htmlspecialchars($product['color']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($product['fabric_type'])): ?>
                        <div style="margin: 10px 0;">
                            <strong>Тип ткани:</strong> <?php echo htmlspecialchars($product['fabric_type']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div style="margin: 10px 0;">
                        <strong>В наличии:</strong> <?php echo $product['stock_quantity']; ?> шт.
                    </div>
                    
                    <?php if($product['stock_quantity'] > 0): ?>
                        <form action="add_to_cart.php" method="POST" style="margin: 20px 0;">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <label>Количество:</label>
                                <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" 
                                       style="width: 80px; padding: 8px;">
                                <button type="submit" style="background: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; font-size: 16px;">
                                    Добавить в корзину
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div style="color: red; margin: 20px 0; padding: 10px; background: #ffe6e6;">
                            Товар временно отсутствует
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if(!empty($product['description'])): ?>
                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                    <h3>Описание товара</h3>
                    <p style="line-height: 1.6;"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div style="flex: 1; background: white; padding: 20px; border-radius: 5px; height: fit-content;">
        <h3>Категории</h3>
        <ul style="list-style: none; padding: 0;">
            <li><a href="index.php">Все товары</a></li>
            <?php foreach($categories as $category): ?>
                <li style="margin: 5px 0;">
                    <a href="category.php?id=<?php echo $category['id']; ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>