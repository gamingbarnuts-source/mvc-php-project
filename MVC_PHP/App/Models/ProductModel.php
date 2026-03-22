<?php
require_once "../App/Config/Database.php";

class ProductModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT products.*, users.name as seller_name FROM products JOIN users ON products.seller_id = users.id ORDER BY products.id DESC");
        return $stmt->fetchAll();
    }

    public function getBySeller($sellerId) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE seller_id = ? ORDER BY id DESC");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT products.*, users.name as seller_name FROM products JOIN users ON products.seller_id = users.id WHERE products.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO products (seller_id, name, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$data['seller_id'], $data['name'], $data['description'], $data['price'], $data['stock'], $data['image']]);
    }

    public function update($id, $data) {
        $sql = "UPDATE products SET name = ?, description = ?, price = ?, stock = ?";
        $params = [$data['name'], $data['description'], $data['price'], $data['stock']];
        if (!empty($data['image'])) {
            $sql .= ", image = ?";
            $params[] = $data['image'];
        }
        $sql .= " WHERE id = ?";
        $params[] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countAll() {
        return $this->db->query("SELECT COUNT(*) as total FROM products")->fetch()['total'];
    }

    public function reduceStock($id, $qty) {
        $stmt = $this->db->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");
        return $stmt->execute([$qty, $id, $qty]);
    }

    // ADD THIS METHOD TO FIX THE FATAL ERROR
    public function addStock($id, $qty) {
    // Increment the stock by the cancelled quantity
    $stmt = $this->db->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
    return $stmt->execute([$qty, $id]);
}

public function search($term) {
    $searchTerm = "%$term%";
    $stmt = $this->db->prepare("
        SELECT products.*, users.name as seller_name 
        FROM products 
        JOIN users ON products.seller_id = users.id 
        WHERE products.name LIKE ? OR products.description LIKE ?
        ORDER BY products.id DESC
    ");
    $stmt->execute([$searchTerm, $searchTerm]);
    return $stmt->fetchAll();
}
}