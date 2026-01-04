<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireAdmin();

require_once '../classes/Category.php';

$pdo = getDBConnection();
$categoryManager = new Category($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: categories.php');
    exit;
}

// Получаем данные из формы
$id = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;

// Валидация
if (empty($name)) {
    $_SESSION['error'] = 'Название категории обязательно';
    header('Location: categories.php?action=' . ($id > 0 ? 'edit&id=' . $id : 'create'));
    exit;
}

if ($id > 0) {
    // Редактирование существующей категории
    $categoryManager->update($id, $name);
    
    // Обновляем статус
    $categoryManager->setActive($id, $is_active);
    
    $_SESSION['success'] = 'Категория успешно обновлена';
} else {
    // Создание новой категории
    $categoryManager->create($name);
    
    // Если нужно сразу сделать неактивной
    $newId = $pdo->lastInsertId();
    if ($is_active == 0) {
        $categoryManager->setActive($newId, 0);
    }
    
    $_SESSION['success'] = 'Категория успешно создана';
}

header('Location: categories.php');
exit;
?>