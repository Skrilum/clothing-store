<?php
require_once 'header.php';

// TODO: Создать класс Order для работы с заказами

?>

<h1 class="admin-title">Управление заказами</h1>

<div class="alert alert-info">
    <p>Функционал управления заказами в разработке.</p>
    <p>Здесь будут отображаться все заказы пользователей с возможностью изменения статуса заказа.</p>
</div>

<div style="background: white; padding: 20px; border-radius: 8px; margin-top: 20px;">
    <h3>Пример будущей таблицы заказов:</h3>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID заказа</th>
                <th>Пользователь</th>
                <th>Дата</th>
                <th>Сумма</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>#00001</td>
                <td>Иван (ivan@example.com)</td>
                <td>10.11.2024 14:30</td>
                <td>54 руб.</td>
                <td><span style="color: orange;">В обработке</span></td>
                <td>
                    <a href="#" class="btn" style="padding: 5px 10px; font-size: 12px;">Просмотреть</a>
                    <a href="#" class="btn btn-info" style="padding: 5px 10px; font-size: 12px;">Изменить статус</a>
                </td>
            </tr>
            <tr>
                <td>#00002</td>
                <td>Анна (anna@example.com)</td>
                <td>09.11.2024 11:15</td>
                <td>82 руб.</td>
                <td><span style="color: green;">Выполнен</span></td>
                <td>
                    <a href="#" class="btn" style="padding: 5px 10px; font-size: 12px;">Просмотреть</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php require_once 'footer.php'; ?>