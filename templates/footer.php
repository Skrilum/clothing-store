    </div>
    
    <footer style="background: #333; color: white; padding: 30px 0; margin-top: 50px;">
        <div class="container" style="display: flex; justify-content: space-between; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px; margin: 10px;">
                <h3>Магазин одежды</h3>
                <p>Лучшая одежда по доступным ценам</p>
            </div>
            <div style="flex: 1; min-width: 250px; margin: 10px;">
                <h3>Категории</h3>
                <?php
                if(isset($categories) && !empty($categories)) {
                    echo '<ul style="list-style: none; padding: 0;">';
                    foreach($categories as $category) {
                        echo '<li><a href="category.php?id=' . $category['id'] . '" style="color: #ddd;">' . 
                             htmlspecialchars($category['name']) . '</a></li>';
                    }
                    echo '</ul>';
                }
                ?>
            </div>
            <div style="flex: 1; min-width: 250px; margin: 10px;">
                <h3>Контакты</h3>
                <p>Email: info@clothing-store.ru</p>
                <p>Телефон: +375 (29) 123-45-67</p>
            </div>
        </div>
        <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #444;">
            <p>Магазин одежды &copy; <?php echo date('Y'); ?>. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>