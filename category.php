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

$category_id = (int)$_GET['id'];
$category = $categoryManager->getById($category_id);

if (!$category) {
    header('Location: index.php');
    exit;
}

// Получаем товары этой категории
$products = $productManager->getByCategory($category_id);

// Получаем все категории для сайдбара
$allCategories = $categoryManager->getAll();

require_once 'templates/header.php';
?>

<div style="display: flex; gap: 30px;">
    <div style="flex: 3;">
        <h2>Категория: <?php echo htmlspecialchars($category['name']); ?></h2>
        
        <?php if(empty($products)): ?>
            <p>В этой категории пока нет товаров.</p>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach($products as $product): ?>
                    <div class="product-card">
                        <a href="product.php?id=<?php echo $product['id']; ?>">
                            <?php if(!empty($product['image_path'])): ?>
                                <img src="<?php echo getImagePath($product['image_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="product-image">
                            <?php else: ?>
                                <div style="width: 100%; height: 200px; background: #eee; display: flex; align-items: center; justify-content: center;">
                                    <span>Нет изображения</span>
                                </div>
                            <?php endif; ?>
                        </a>
                        <div class="product-info">
                            <a href="product.php?id=<?php echo $product['id']; ?>" style="text-decoration: none; color: inherit;">
                                <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                            </a>
                            <div class="product-price"><?php echo number_format($product['price'], 0, ',', ' '); ?> руб.</div>
                            
                            <?php if(!empty($product['size'])): ?>
                                <div>Размер: <?php echo htmlspecialchars($product['size']); ?></div>
                            <?php endif; ?>
                            
                            <?php if(!empty($product['color'])): ?>
                                <div>Цвет: <?php echo htmlspecialchars($product['color']); ?></div>
                            <?php endif; ?>
                            
                            <form action="add_to_cart.php" method="POST" style="margin-top: 10px;">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" 
                                       style="width: 60px; padding: 5px; margin-right: 10px;">
                                <button type="submit" style="background: #4CAF50; color: white; padding: 5px 15px; border: none; cursor: pointer;">
                                    В корзину
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div style="flex: 1; background: white; padding: 20px; border-radius: 5px; height: fit-content;">
        <h3>Категории</h3>
        <ul style="list-style: none; padding: 0;">
            <li><a href="index.php">Все товары</a></li>
            <?php foreach($allCategories as $cat): ?>
                <li style="margin: 5px 0;">
                    <a href="category.php?id=<?php echo $cat['id']; ?>" 
                       <?php if($cat['id'] == $category_id) echo 'style="color: #4CAF50; font-weight: bold;"'; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>