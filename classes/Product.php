<?php
class Product {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($name, $description, $price, $category_id, $size, $color, $fabric_type, $image_path, $stock_quantity) {
        $stmt = $this->pdo->prepare("INSERT INTO products (name, description, price, category_id, size, color, fabric_type, image_path, stock_quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $description, $price, $category_id, $size, $color, $fabric_type, $image_path, $stock_quantity]);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll($limit = 100, $offset = 0) {
        $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_active = TRUE ORDER BY p.id DESC LIMIT ? OFFSET ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByCategory($category_id, $limit = 100, $offset = 0) {
        $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? and p.is_active = TRUE ORDER BY p.id DESC LIMIT ? OFFSET ?");
        $stmt->bindValue(1, $category_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function search($keyword, $limit = 100, $offset = 0) {
        $keyword = "%{$keyword}%";
        $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE (p.name LIKE ? OR p.description LIKE ?) and p.is_active = TRUE ORDER BY p.id DESC LIMIT ? OFFSET ?");
        $stmt->bindValue(1, $keyword, PDO::PARAM_STR);
        $stmt->bindValue(2, $keyword, PDO::PARAM_STR);
        $stmt->bindValue(3, $limit, PDO::PARAM_INT);
        $stmt->bindValue(4, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function update($id, $name, $description, $price, $category_id, $size, $color, $fabric_type, $image_path, $stock_quantity) {
        $stmt = $this->pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ?, size = ?, color = ?, fabric_type = ?, image_path = ?, stock_quantity = ? WHERE id = ?");
        return $stmt->execute([$name, $description, $price, $category_id, $size, $color, $fabric_type, $image_path, $stock_quantity, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("UPDATE products SET is_active = FALSE WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function deletePermanent($id) {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getCount() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM products WHERE is_active = TRUE");
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result && isset($result['count'])) {
            return $result['count'];
        }
        return 0;
    }

    public function getCountByCategory($category_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM products WHERE category_id = ? AND is_active = TRUE");
        $stmt->execute([$category_id]);
        $result = $stmt->fetch();

        if ($result && isset($result['count'])) {
            return $result['count'];
        }
        return 0;
    }
}
?>