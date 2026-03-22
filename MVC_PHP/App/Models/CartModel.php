<?php
require_once "../App/Config/Database.php";

class CartModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    /**
     * Gets all items in a user's cart with product details
     */
    public function getByUser($userId) {
        $stmt = $this->db->prepare("
            SELECT cart.*, products.name, products.price, products.image, products.stock
            FROM cart
            JOIN products ON cart.product_id = products.id
            WHERE cart.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Used by the Controller to check if an item is already in the cart
     * before adding/updating to enforce stock limits.
     */
    public function getSpecificItem($userId, $productId) {
        $stmt = $this->db->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        return $stmt->fetch();
    }

    /**
     * Adds an item or increments quantity
     */
    public function add($userId, $productId, $quantity = 1) {
        $existing = $this->getSpecificItem($userId, $productId);

        if ($existing) {
            $stmt = $this->db->prepare("UPDATE cart SET quantity = quantity + ? WHERE id = ?");
            return $stmt->execute([$quantity, $existing['id']]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            return $stmt->execute([$userId, $productId, $quantity]);
        }
    }

    /**
     * Updates the quantity of a specific cart row
     */
    public function updateQuantity($id, $quantity) {
    // Basic safety: Ensure quantity never goes below 1 in the DB
    $quantity = ($quantity < 1) ? 1 : $quantity;
    
    $stmt = $this->db->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    return $stmt->execute([$quantity, $id]);
}

    public function removeItem($id) {
        $stmt = $this->db->prepare("DELETE FROM cart WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function clearCart($userId) {
        $stmt = $this->db->prepare("DELETE FROM cart WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }

    public function getTotal($userId) {
        $stmt = $this->db->prepare("
            SELECT SUM(cart.quantity * products.price) as total 
            FROM cart 
            JOIN products ON cart.product_id = products.id 
            WHERE cart.user_id = ?
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function getItemCount($userId) {
        $stmt = $this->db->prepare("SELECT SUM(quantity) as count FROM cart WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
}