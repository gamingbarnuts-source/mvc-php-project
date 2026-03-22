<?php
require_once "../App/Core/Controller.php";

class CartController extends Controller {
    public function index() {
    $this->requireLogin();
    $cartModel = $this->model('CartModel');
    $productModel = $this->model('ProductModel'); // NEW

    $data['cartItems'] = $cartModel->getByUser($_SESSION['user_id']);
    $data['total'] = $cartModel->getTotal($_SESSION['user_id']);
    $data['products'] = $productModel->getAll(); // NEW: Fetch all products for browsing

    $this->view('cart/index', $data);
}

   public function add($productId) {
    $this->requireRole('customer');
    
    $productModel = $this->model('ProductModel');
    $cartModel = $this->model('CartModel');
    
    $product = $productModel->findById($productId);
    if (!$product) $this->redirect('Cart');

    // Check if item is already in cart to see total requested quantity
    $existingItem = $cartModel->getSpecificItem($_SESSION['user_id'], $productId);
    $newQty = ($existingItem) ? $existingItem['quantity'] + 1 : 1;

    // Logic: Is there enough stock?
    if ($product['stock'] < $newQty) {
        $_SESSION['error'] = "Only " . $product['stock'] . " units available.";
        $this->redirect('Cart');
        return;
    }

    $cartModel->add($_SESSION['user_id'], $productId);
    $_SESSION['success'] = "Added to cart!";
    $this->redirect('Cart');
}
    public function update() {
        $this->requireRole('customer');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartModel = $this->model('CartModel');
            $id = (int)$_POST['cart_id'];
            $qty = (int)$_POST['quantity'];
            if ($qty > 0) {
                $cartModel->updateQuantity($id, $qty);
            } else {
                $cartModel->removeItem($id);
            }
        }
        $this->redirect('Cart');
    }

    public function remove($id = null) {
        $this->requireRole('customer');
        if ($id) {
            $cartModel = $this->model('CartModel');
            $cartModel->removeItem($id);
            $_SESSION['success'] = 'Item removed from cart.';
        }
        $this->redirect('Cart');
    }
}
