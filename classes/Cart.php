<?php
class Cart {
    private $items = [];
    
    public function __construct() {
        $this->loadFromSession();
    }
    
    // Загрузка корзины из сессии
    private function loadFromSession() {
        if (isset($_SESSION['cart'])) {
            $this->items = $_SESSION['cart'];
        } else {
            // Пытаемся загрузить из cookie
            $this->loadFromCookie();
        }
    }
    
    // Загрузка корзины из cookie
    private function loadFromCookie() {
        if (isset($_COOKIE['cart'])) {
            $cookieData = json_decode($_COOKIE['cart'], true);
            if (is_array($cookieData)) {
                $this->items = $cookieData;
                $this->saveToSession();
            }
        }
    }
    
    // Сохранение в сессию
    private function saveToSession() {
        $_SESSION['cart'] = $this->items;
    }
    
    // Сохранение в cookie (на 30 дней)
    private function saveToCookie() {
        if (!headers_sent()) {
            setcookie('cart', json_encode($this->items), time() + (30 * 24 * 60 * 60), '/');
        }
    }
    
    // Сохранение корзины
    private function save() {
        $this->saveToSession();
        $this->saveToCookie();
    }
    
    // Добавление товара в корзину
    public function add($product_id, $quantity = 1) {
        $product_id = (int)$product_id;
        $quantity = (int)$quantity;
        
        if ($quantity <= 0) {
            return false;
        }
        
        if (isset($this->items[$product_id])) {
            $this->items[$product_id] += $quantity;
        } else {
            $this->items[$product_id] = $quantity;
        }
        
        $this->save();
        return true;
    }
    
    // Удаление товара из корзины
    public function remove($product_id) {
        $product_id = (int)$product_id;
        
        if (isset($this->items[$product_id])) {
            unset($this->items[$product_id]);
            $this->save();
            return true;
        }
        
        return false;
    }
    
    // Обновление количества товара
    public function update($product_id, $quantity) {
        $product_id = (int)$product_id;
        $quantity = (int)$quantity;
        
        if ($quantity <= 0) {
            // Если количество 0 или меньше, удаляем товар
            return $this->remove($product_id);
        }
        
        if (isset($this->items[$product_id])) {
            $this->items[$product_id] = $quantity;
            $this->save();
            return true;
        }
        
        return false;
    }
    
    // Получение всех товаров в корзине
    public function getItems() {
        return $this->items;
    }
    
    // Получение количества товаров в корзине
    public function getCount() {
        $count = 0;
        foreach ($this->items as $quantity) {
            $count += $quantity;
        }
        return $count;
    }
    
    // Очистка корзины
    public function clear() {
        $this->items = [];
        $this->save();
        // Удаляем cookie, если заголовки ещё не отправлены
        if (!headers_sent()) {
            setcookie('cart', '', time() - 3600, '/');
        }
    }
    
    // Проверка, пуста ли корзина
    public function isEmpty() {
        return empty($this->items);
    }
    
    // Получение количества конкретного товара
    public function getQuantity($product_id) {
        $product_id = (int)$product_id;
        return isset($this->items[$product_id]) ? $this->items[$product_id] : 0;
    }
}
?>