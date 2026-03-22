<?php
require_once "../App/Config/Database.php";

class OrderModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    /**
     * Creates a new order and returns the ID
     */
    public function create($customerId, $totalAmount) {
        $stmt = $this->db->prepare("INSERT INTO orders (customer_id, total_amount, status, created_at) VALUES (?, ?, 'Pending', NOW())");
        $stmt->execute([$customerId, $totalAmount]);
        return $this->db->lastInsertId();
    }

    /**
     * Adds an item to the order_items table
     */
    public function addItem($orderId, $productId, $quantity, $price) {
        $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$orderId, $productId, $quantity, $price]);
    }

    /**
     * Admin: Get all orders in the system
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT orders.*, users.name as customer_name FROM orders JOIN users ON orders.customer_id = users.id ORDER BY orders.created_at DESC");
        return $stmt->fetchAll();
    }

    /**
     * Customer: Get only their personal orders
     */
    public function getByCustomer($customerId) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll();
    }

    /**
     * Seller: Get orders that contain their products
     */
    public function getBySeller($sellerId) {
        $stmt = $this->db->prepare("
            SELECT DISTINCT orders.*, users.name as customer_name
            FROM orders
            JOIN order_items ON orders.id = order_items.order_id
            JOIN products ON order_items.product_id = products.id
            JOIN users ON orders.customer_id = users.id
            WHERE products.seller_id = ?
            ORDER BY orders.created_at DESC
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll();
    }

    /**
     * Find a single order by its ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT orders.*, users.name as customer_name FROM orders JOIN users ON orders.customer_id = users.id WHERE orders.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Get all products inside a specific order
     */
    public function getItems($orderId) {
        $stmt = $this->db->prepare("SELECT order_items.*, products.name as product_name, products.image FROM order_items JOIN products ON order_items.product_id = products.id WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    /**
     * FIXED: Updates status using proper PDO prepared statements
     */
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    /**
     * Dashboard: Counts active orders (Excludes Cancelled)
     */
    public function countAll() {
        // Only count orders that are NOT cancelled to keep dashboard totals accurate
        return $this->db->query("SELECT COUNT(*) as total FROM orders WHERE status != 'Cancelled'")->fetch()['total'];
    }

    /**
     * Dashboard: Fetches the most recent active orders
     */
    public function getRecent($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT orders.*, users.name as customer_name 
            FROM orders 
            JOIN users ON orders.customer_id = users.id 
            WHERE orders.status != 'Cancelled'
            ORDER BY orders.created_at DESC 
            LIMIT ?
        ");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}