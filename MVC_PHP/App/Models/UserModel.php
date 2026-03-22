<?php
require_once "../App/Config/Database.php";

class UserModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function register($name, $email, $password, $role_id = 3) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role_id) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $hash, $role_id]);
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT users.*, roles.role_name FROM users JOIN roles ON users.role_id = roles.id WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT users.*, roles.role_name FROM users JOIN roles ON users.role_id = roles.id WHERE users.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT users.*, roles.role_name FROM users JOIN roles ON users.role_id = roles.id ORDER BY users.id DESC");
        return $stmt->fetchAll();
    }

    public function update($id, $name, $email, $role_id) {
        $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, role_id = ? WHERE id = ?");
        return $stmt->execute([$name, $email, $role_id, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countAll() {
        return $this->db->query("SELECT COUNT(*) as total FROM users")->fetch()['total'];
    }

    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) as c FROM users WHERE email = ?";
        $params = [$email];
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['c'] > 0;
    }
}
