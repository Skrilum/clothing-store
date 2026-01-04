<?php
class User {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function register($email, $password, $username) {
        // Проверка существования пользователя
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            return false;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // INSERT в БД
        $stmt = $this->pdo->prepare("INSERT INTO users (email, password, username) VALUES (?, ?, ?)");
        $stmt->execute([$email, $passwordHash, $username]);

        return $this->pdo->lastInsertId();
    }
    
    public function login($email, $password) {
        // Проверка пароля
        $user = $this->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function updateUser($id, $email, $username) {
        $stmt = $this->pdo->prepare("UPDATE users SET email = ?, username = ? WHERE id = ?");
        return $stmt->execute([$email, $username, $id]);
    }

    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function changeRole($id, $role) {
        $stmt = $this->pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        return $stmt->execute([$role, $id]);
    }
    
    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        return $stmt->fetch();
    }

    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT id, email, username, role, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function getAllUsers() {
        $stmt = $this->pdo->prepare("SELECT id, email, username, role, created_at FROM users ORDER BY created_at DESC");
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
?>