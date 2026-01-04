<?php
require_once 'header.php';

$productManager = new Product($pdo);
$categoryManager = new Category($pdo);

// Обработка действий
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? 0;

// Удаление товара
if ($action == 'delete' && $id > 0) {
    if ($productManager->delete($id)) {
        $_SESSION['success'] = 'Товар успешно удален';
    } else {
        $_SESSION['error'] = 'Ошибка при удалении товара';
    }
    header('Location: products.php');
    exit;
}

// Получение списка товаров
$products = $productManager->getAll(1000);
$categories = $categoryManager->getAll();
?>

<h1 class="admin-title">Управление товарами</h1>

<?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div style="margin-bottom: 20px;">
    <a href="?action=create" class="btn">Добавить новый товар</a>
</div>

<?php if($action == 'create' || $action == 'edit'): ?>
    <!-- Форма создания/редактирования товара -->
    <?php 
    $product = [];
    if ($action == 'edit' && $id > 0) {
        $product = $productManager->getById($id);
        if (!$product) {
            header('Location: products.php');
            exit;
        }
    }
    ?>
    
    <h2><?php echo $action == 'create' ? 'Добавление товара' : 'Редактирование товара'; ?></h2>
    
    <form action="product_save.php" method="POST" enctype="multipart/form-data" style="max-width: 600px;">
        <input type="hidden" name="id" value="<?php echo $product['id'] ?? 0; ?>">
        
        <div class="form-group">
            <label>Название товара *</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Описание</label>
            <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Цена *</label>
            <input type="number" name="price" class="form-control" step="0.01" min="0" value="<?php echo $product['price'] ?? ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Категория</label>
            <select name="category_id" class="form-control">
                <option value="">Без категории</option>
                <?php foreach($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" 
                        <?php echo ($product['category_id'] ?? 0) == $category['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Размер</label>
            <select name="size" class="form-control">
                <option value="">Не указан</option>
                <option value="S" <?php echo ($product['size'] ?? '') == 'S' ? 'selected' : ''; ?>>S</option>
                <option value="M" <?php echo ($product['size'] ?? '') == 'M' ? 'selected' : ''; ?>>M</option>
                <option value="L" <?php echo ($product['size'] ?? '') == 'L' ? 'selected' : ''; ?>>L</option>
                <option value="XL" <?php echo ($product['size'] ?? '') == 'XL' ? 'selected' : ''; ?>>XL</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Цвет</label>
            <input type="text" name="color" class="form-control" value="<?php echo htmlspecialchars($product['color'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label>Тип ткани</label>
            <input type="text" name="fabric_type" class="form-control" value="<?php echo htmlspecialchars($product['fabric_type'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label>Количество на складе</label>
            <input type="number" name="stock_quantity" class="form-control" min="0" value="<?php echo $product['stock_quantity'] ?? 0; ?>">
        </div>
        
        <div class="form-group">
            <label>Изображение</label>
            <?php if(!empty($product['image_path'])): ?>
                <div style="margin-bottom: 10px;">
                    <img src="<?php echo getImagePath($product['image_path']); ?>" style="max-width: 200px; max-height: 200px;">
                </div>
            <?php endif; ?>
            <input type="file" name="image" class="form-control">
            <small>Оставьте пустым, чтобы не менять текущее изображение</small>
        </div>
        
        <div style="margin-top: 30px;">
            <button type="submit" class="btn">Сохранить</button>
            <a href="products.php" class="btn" style="background: #666; margin-left: 10px;">Отмена</a>
        </div>
    </form>
    
<?php else: ?>
    <!-- Список товаров -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Изображение</th>
                <th>Название</th>
                <th>Категория</th>
                <th>Цена</th>
                <th>На складе</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($products)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">Товары не найдены</td>
                </tr>
            <?php else: ?>
                <?php foreach($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td>
                            <?php if(!empty($product['image_path'])): ?>
                                <img src="<?php echo getImagePath($product['image_path']); ?>" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($product['name']); ?></strong><br>
                            <small style="color: #666;">
                                <?php 
                                if(!empty($product['size'])) echo "Размер: " . $product['size'] . " ";
                                if(!empty($product['color'])) echo "Цвет: " . $product['color'];
                                ?>
                            </small>
                        </td>
                        <td><?php echo htmlspecialchars($product['category_name'] ?? 'Без категории'); ?></td>
                        <td><?php echo number_format($product['price'], 0, ',', ' '); ?> руб.</td>
                        <td><?php echo $product['stock_quantity']; ?> шт.</td>
                        <td>
                            <a href="?action=edit&id=<?php echo $product['id']; ?>" class="btn" style="padding: 5px 10px; font-size: 12px;">Редактировать</a>
                            <a href="?action=delete&id=<?php echo $product['id']; ?>" 
                               class="btn btn-danger" 
                               style="padding: 5px 10px; font-size: 12px;"
                               onclick="return confirm('Удалить товар <?php echo addslashes($product['name']); ?>?')">
                                Удалить
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once 'footer.php'; ?>