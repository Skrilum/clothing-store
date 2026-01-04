<?php
require_once 'includes/config.php';
require_once 'classes/User.php';

// Если пользвователь авторизован, то отправлем на главную
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        header('Location: admin/index.php');
    } else {
        header('Location: index.php');
    }
    exit;
}

$pdo = getDBConnection();
$userManager = new User($pdo);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Введите email и пароль';
    } else {
        $user = $userManager->login($email, $password);

        if ($user) {
            // Сохраняем пользователя в сессии
            $_SESSION['user'] = $user;
            
            if ($user['role'] === 'admin') {
                header('Location: admin/index.php');
            } else {
                $return_url = $_GET['return'] ?? 'index.php';
                header("Location: $return_url");
            }
            exit;
        } else {
            $error = 'Неверный email или пароль';
        }
    }
}

require_once 'templates/header.php';
?>

<div style="max-width: 400px; margin: 50px auto;">
    <h2>Вход в аккаунт</h2>

    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div style="margin-bottom: 15px;">
            <label>Email:</label>
            <input type="email" name="email" required style="width: 100%; padding: 8px;" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Пароль:</label>
            <input type="password" name="password" required style="width: 100%; padding: 8px;">
        </div>

        <button type="submit" style="background: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer;">
            Войти
        </button>

        <p style="margin-top: 15px;">
            Нет аккаунта? <a href="register.php">Зарегистрироваться</a>
        </p>
    </form>
</div>

<?php require_once 'templates/footer.php' ?>