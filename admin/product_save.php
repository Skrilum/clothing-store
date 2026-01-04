<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireAdmin();

require_once '../classes/Product.php';

$pdo = getDBConnection();
$productManager = new Product($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: products.php');
    exit;
}

// Получаем данные из формы
$id = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = (float)($_POST['price'] ?? 0);
$category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
$size = trim($_POST['size'] ?? '');
$color = trim($_POST['color'] ?? '');
$fabric_type = trim($_POST['fabric_type'] ?? '');
$stock_quantity = (int)($_POST['stock_quantity'] ?? 0);

// Валидация
if (empty($name) || $price <= 0) {
    $_SESSION['error'] = 'Заполните обязательные поля (название и цена)';
    header('Location: products.php?action=' . ($id > 0 ? 'edit&id=' . $id : 'create'));
    exit;
}

// Обработка загрузки изображения
$image_path = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../uploads/';
    $file_name = time() . '_' . basename($_FILES['image']['name']);
    $file_path = $upload_dir . $file_name;
    
    // Создаем папку если нет
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Проверяем тип файла
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jfif', 'image/avif'];
    $file_type = mime_content_type($_FILES['image']['tmp_name']);
    
    if (in_array($file_type, $allowed_types)) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
            $image_path = '/uploads/' . $file_name;
        }
    }
}

if ($id > 0) {
    // Редактирование существующего товара
    if (!empty($image_path)) {
        // Обновляем с новым изображением
        $productManager->update($id, $name, $description, $price, $category_id, $size, $color, $fabric_type, $image_path, $stock_quantity);
    } else {
        // Получаем текущий товар чтобы сохранить старое изображение
        $current_product = $productManager->getById($id);
        $current_image = $current_product['image_path'] ?? '';
        $productManager->update($id, $name, $description, $price, $category_id, $size, $color, $fabric_type, $current_image, $stock_quantity);
    }
    $_SESSION['success'] = 'Товар успешно обновлен';
} else {
    // Создание нового товара
    if (empty($image_path)) {
        $image_path = '/images/no-image.jpg'; // Заглушка
    }
    $productManager->create($name, $description, $price, $category_id, $size, $color, $fabric_type, $image_path, $stock_quantity);
    $_SESSION['success'] = 'Товар успешно создан';
}

header('Location: products.php');
exit;
?>