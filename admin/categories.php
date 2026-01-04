<?php
require_once 'header.php';

$categoryManager = new Category($pdo);

// Обработка действий
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? 0;

// Удаление категории
if ($action == 'delete' && $id > 0) {
    if ($categoryManager->delete($id)) {
        $_SESSION['success'] = 'Категория успешно удалена';
    } else {
        $_SESSION['error'] = 'Ошибка при удалении категории. Возможно, в ней есть товары.';
    }
    header('Location: categories.php');
    exit;
}

// Получение списка категорий
$categories = $categoryManager->getAll(false); // Получаем все, включая неактивные
?>

<h1 class="admin-title">Управление категориями</h1>

<?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div style="margin-bottom: 20px;">
    <a href="?action=create" class="btn">Добавить новую категорию</a>
</div>

<?php if($action == 'create' || $action == 'edit'): ?>
    <!-- Форма создания/редактирования категории -->
    <?php 
    $category = [];
    if ($action == 'edit' && $id > 0) {
        $category = $categoryManager->getById($id);
        if (!$category) {
            header('Location: categories.php');
            exit;
        }
    }
    ?>
    
    <h2><?php echo $action == 'create' ? 'Добавление категории' : 'Редактирование категории'; ?></h2>
    
    <form action="category_save.php" method="POST" style="max-width: 500px;">
        <input type="hidden" name="id" value="<?php echo $category['id'] ?? 0; ?>">
        
        <div class="form-group">
            <label>Название категории *</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($category['name'] ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Статус</label>
            <div>
                <label style="margin-right: 20px;">
                    <input type="radio" name="is_active" value="1" 
                        <?php echo ($category['is_active'] ?? 1) == 1 ? 'checked' : ''; ?>> Активна
                </label>
                <label>
                    <input type="radio" name="is_active" value="0"
                        <?php echo isset($category['is_active']) && $category['is_active'] == 0 ? 'checked' : ''; ?>> Неактивна
                </label>
            </div>
        </div>
        
        <div style="margin-top: 30px;">
            <button type="submit" class="btn">Сохранить</button>
            <a href="categories.php" class="btn" style="background: #666; margin-left: 10px;">Отмена</a>
        </div>
    </form>
    
<?php else: ?>
    <!-- Список категорий -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($categories)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px;">Категории не найдены</td>
                </tr>
            <?php else: ?>
                <?php foreach($categories as $cat): ?>
                    <tr>
                        <td><?php echo $cat['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($cat['name']); ?></strong>
                        </td>
                        <td>
                            <?php if($cat['is_active']): ?>
                                <span style="color: green; font-weight: bold;">Активна</span>
                            <?php else: ?>
                                <span style="color: red;">Неактивна</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?action=edit&id=<?php echo $cat['id']; ?>" class="btn" style="padding: 5px 10px; font-size: 12px;">Редактировать</a>
                            <a href="?action=delete&id=<?php echo $cat['id']; ?>" 
                               class="btn btn-danger" 
                               style="padding: 5px 10px; font-size: 12px;"
                               onclick="return confirm('Удалить категорию "<?php echo addslashes($cat['name']); ?>"?')">
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