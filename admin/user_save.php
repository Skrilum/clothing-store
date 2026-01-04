<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireAdmin();

require_once '../classes/User.php';

$pdo = getDBConnection();
$userManager = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: users.php');
    exit;
}

// Получаем данные из формы
$id = (int)($_POST['id'] ?? 0);
$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';
$role = trim($_POST['role'] ?? 'user');

// Валидация
if (empty($email) || empty($username) || empty($role)) {
    $_SESSION['error'] = 'Заполните обязательные поля';
    header('Location: users.php?action=' . ($id > 0 ? 'edit&id=' . $id : 'create'));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Некорректный email';
    header('Location: users.php?action=' . ($id > 0 ? 'edit&id=' . $id : 'create'));
    exit;
}

// Проверка уникальности email (кроме текущего пользователя)
$existingUser = $userManager->getUserByEmail($email);
if ($existingUser && $existingUser['id'] != $id) {
    $_SESSION['error'] = 'Пользователь с таким email уже существует';
    header('Location: users.php?action=' . ($id > 0 ? 'edit&id=' . $id : 'create'));
    exit;
}

// Если создаем нового пользователя или меняем пароль
if ($id == 0 || !empty($password)) {
    if ($password !== $password_confirm) {
        $_SESSION['error'] = 'Пароли не совпадают';
        header('Location: users.php?action=' . ($id > 0 ? 'edit&id=' . $id : 'create'));
        exit;
    }
    
    if (strlen($password) < 6) {
        $_SESSION['error'] = 'Пароль должен быть не менее 6 символов';
        header('Location: users.php?action=' . ($id > 0 ? 'edit&id=' . $id : 'create'));
        exit;
    }
}

if ($id > 0) {
    // Редактирование существующего пользователя
    if (!empty($password)) {
        // Меняем пароль
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        // Нужно будет добавить метод в User класс
        $stmt = $pdo->prepare("UPDATE users SET email = ?, username = ?, role = ?, password = ? WHERE id = ?");
        $stmt->execute([$email, $username, $role, $passwordHash, $id]);
    } else {
        // Обновляем без пароля
        $userManager->updateUser($id, $email, $username);
        // Обновляем роль
        $userManager->changeRole($id, $role);
    }
    
    $_SESSION['success'] = 'Пользователь успешно обновлен';
} else {
    // Создание нового пользователя
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    $newId = $userManager->register($email, $password, $username);
    
    if ($newId && $role == 'admin') {
        // Устанавливаем роль админа
        $userManager->changeRole($newId, 'admin');
    }
    
    $_SESSION['success'] = 'Пользователь успешно создан';
}

header('Location: users.php');
exit;
?>