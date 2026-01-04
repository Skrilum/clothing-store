<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Базовый путь для проекта
define('BASE_URL', '/shop');
define('BASE_PATH', __DIR__); // Полный путь к корневой папке

function getDBConnection(){
    $host = 'localhost';
    $dbname = 'yourdbname';
    $username = 'yourusername';
    $password = 'yourpassword';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        // echo "Подключение установлено\n\n";
        // $stmt = $pdo->query("SELECT * FROM categories");
        // echo "Категории:\n";
        // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //     print_r($row);
        // }
        return $pdo;
    } catch (PDOException $e) {
        die("Ошибка подключения: " . $e->getMessage());
    }
}

function getImagePath($image_path) {
    if (empty($image_path)) {
        return BASE_URL . '/uploads/no-image.jpg'; // Заглушка
    }
    return BASE_URL . $image_path;
}
?>