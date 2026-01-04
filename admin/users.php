<?php
require_once 'header.php';

$userManager = new User($pdo);

// Обработка действий
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? 0;

// Удаление пользователя
if ($action == 'delete' && $id > 0) {
    // Нельзя удалить самого себя
    if ($id == $_SESSION['user']['id']) {
        $_SESSION['error'] = 'Нельзя удалить свой собственный аккаунт';
    } else {
        if ($userManager->deleteUser($id)) {
            $_SESSION['success'] = 'Пользователь успешно удален';
        } else {
            $_SESSION['error'] = 'Ошибка при удалении пользователя';
        }
    }
    header('Location: users.php');
    exit;
}

// Получение списка пользователей
$users = $userManager->getAllUsers();
?>

<h1 class="admin-title">Управление пользователями</h1>

<?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div style="margin-bottom: 20px;">
    <a href="?action=create" class="btn">Добавить нового пользователя</a>
</div>

<?php if($action == 'create' || $action == 'edit'): ?>
    <!-- Форма создания/редактирования пользователя -->
    <?php 
    $user = [];
    if ($action == 'edit' && $id > 0) {
        $user = $userManager->getUserById($id);
        if (!$user) {
            header('Location: users.php');
            exit;
        }
    }
    ?>
    
    <h2><?php echo $action == 'create' ? 'Добавление пользователя' : 'Редактирование пользователя'; ?></h2>
    
    <form action="user_save.php" method="POST" style="max-width: 500px;">
        <input type="hidden" name="id" value="<?php echo $user['id'] ?? 0; ?>">
        
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Имя пользователя *</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
        </div>
        
        <?php if($action == 'create'): ?>
        <div class="form-group">
            <label>Пароль *</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label>Подтверждение пароля *</label>
            <input type="password" name="password_confirm" class="form-control" required>
        </div>
        <?php else: ?>
        <div class="form-group">
            <label>Пароль (оставьте пустым, чтобы не менять)</label>
            <input type="password" name="password" class="form-control">
            <small>Заполните только если хотите изменить пароль</small>
        </div>
        <?php endif; ?>
        
        <div class="form-group">
            <label>Роль</label>
            <select name="role" class="form-control" required>
                <option value="user" <?php echo ($user['role'] ?? '') == 'user' ? 'selected' : ''; ?>>Пользователь</option>
                <option value="admin" <?php echo ($user['role'] ?? '') == 'admin' ? 'selected' : ''; ?>>Администратор</option>
            </select>
        </div>
        
        <div style="margin-top: 30px;">
            <button type="submit" class="btn">Сохранить</button>
            <a href="users.php" class="btn" style="background: #666; margin-left: 10px;">Отмена</a>
        </div>
    </form>
    
<?php else: ?>
    <!-- Список пользователей -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Имя</th>
                <th>Роль</th>
                <th>Дата регистрации</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($users)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">Пользователи не найдены</td>
                </tr>
            <?php else: ?>
                <?php foreach($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td>
                            <?php if($user['role'] == 'admin'): ?>
                                <span style="color: red; font-weight: bold;">Администратор</span>
                            <?php else: ?>
                                <span style="color: green;">Пользователь</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d.m.Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <a href="?action=edit&id=<?php echo $user['id']; ?>" class="btn" style="padding: 5px 10px; font-size: 12px;">Редактировать</a>
                            <?php if($user['id'] != $_SESSION['user']['id']): ?>
                            <a href="?action=delete&id=<?php echo $user['id']; ?>" 
                               class="btn btn-danger" 
                               style="padding: 5px 10px; font-size: 12px;"
                               onclick="return confirm('Удалить пользователя <?php echo addslashes($user['username']); ?>?')">
                                Удалить
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once 'footer.php'; ?>