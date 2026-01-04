<?php
class Category {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($name) {
        $stmt = $this->pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll($active_only = true) {
        if ($active_only) {
           $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE is_active = TRUE ORDER BY name"); 
        } else {
            $stmt = $this->pdo->prepare("SELECT * FROM categories ORDER BY name");
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getWithProducts($id) {
        $category = $this->getById($id);
        if (!$category) return null;

        // Получаем товары категории
        $product = new Product($this->pdo);
        $category['products'] = $product->getByCategory($id);

        return $category;
    }

    public function update($id, $name) {
        $stmt = $this->pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("UPDATE categories SET is_active = FALSE WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function deletePermanent($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM products WHERE category_id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();

        if ($result && isset($result['count']) && $result['count'] > 0) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function setActive($id, $active) {
        $stmt = $this->pdo->prepare("UPDATE categories SET is_active = ? WHERE id = ?");
        return $stmt->execute([$active, $id]);
    }

    public function getCount() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM categories WHERE is_active = TRUE");
        $stmt->execute();
        $result = $stmt->fetch();
        
        if ($result && isset($result['count'])) {
            return $result['count'];
        }
        return 0;
    }
}
?>