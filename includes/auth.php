<?php
require_once 'config.php';

function requireLogin($redirect = 'login.php') {
    if (!isset($_SESSION['user'])) {
        $return_url = $_SERVER['REQUEST_URI'];
        header("Location: $redirect?return=" . urlencode($return_url));
        exit;
    }
}

function requireAdmin() {
    requireLogin();
    if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = 'Доступ запрещен. Требуются права администратора.';
        header('Location: index.php');
        exit;
    }
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function isAdmin() {
    return isLoggedIn() && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}
?>