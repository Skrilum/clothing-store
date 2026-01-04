<?php
require_once 'includes/config.php';
require_once 'classes/User.php';

$pdo = getDBConnection();
$userManager = new User($pdo);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $passwordConfirm = trim($_POST['passwordConfirm'] ?? '');

    //Валадация
    if (empty($email) || empty($password) || empty($username) || empty($passwordConfirm)) {
        $error = 'Все поля обязательны для заполнения';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Некорректный email';
    } elseif (strlen($password) < 6) {
        $error = 'Пароль должен быть не менее 6 символов';
    } elseif ($password !== $passwordConfirm) {
        $error = 'Пароли не совпадают';
    } else {
        $result = $userManager->register($email,$password, $username);

        if ($result) {
            $success = 'Регистрация успешна! Теперь вы можете войти.';
            
        } else {
            $error = 'Пользователь с таким email уже существует';
        }
    }
}

require_once 'templates/header.php';
?>

<div style="max-width: 400px; margin: 50px auto;">
    <h2>Регистрация</h2>

    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <p><a href="login.php">Войти в аккаунт</a></p>
        <?php else: ?>
        
        <form method="POST" action="">
            <div style="margin-bottom: 15px;">
                <label>Email:</label>
                <input type="email" name="email" required style="width: 100%; padding: 8px;" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>

            <div style="margin-bottom: 15px;">
                <label>Имя:</label>
                <input type="text" name="username" required style="width: 100%; padding: 8px;" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>

            <div style="margin-bottom: 15px;">
                <label>Пароль:</label>
                <input type="password" name="password" required style="width: 100%; padding: 8px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label>Подтвердите пароль:</label>
                <input type="password" name="passwordConfirm" required style="width: 100%; padding: 8px;">
            </div>
            
            <button type="submit" style="background: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer;">
                Зарегистрироваться
            </button>

            <p style="margin-top: 15px;">
                Уже есть аккаунт? <a href="login.php">Войти</a>
            </p>
        </form>

        <?php endif; ?>
</div>

<?php require_once 'templates/footer.php' ?>