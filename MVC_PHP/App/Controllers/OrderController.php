<?php
require_once "../App/Core/Controller.php";

class OrderController extends Controller {
    public function index() {
        $this->requireLogin();
        $orderModel = $this->model('OrderModel');

        if ($_SESSION['role_name'] === 'admin') {
            $data['orders'] = $orderModel->getAll();
        } elseif ($_SESSION['role_name'] === 'seller') {
            $data['orders'] = $orderModel->getBySeller($_SESSION['user_id']);
        } else {
            $data['orders'] = $orderModel->getByCustomer($_SESSION['user_id']);
        }

        $this->view('orders/index', $data);
    }

    public function checkout() {
        $this->requireRole('customer');

        $cartModel = $this->model('CartModel');
        $cartItems = $cartModel->getByUser($_SESSION['user_id']);

        if (empty($cartItems)) {
            $_SESSION['error'] = 'Your cart is empty.';
            $this->redirect('Cart');
        }

        $total = $cartModel->getTotal($_SESSION['user_id']);
        $orderModel = $this->model('OrderModel');
        $productModel = $this->model('ProductModel');

        $orderId = $orderModel->create($_SESSION['user_id'], $total);

        foreach ($cartItems as $item) {
            $orderModel->addItem($orderId, $item['product_id'], $item['quantity'], $item['price']);
            $productModel->reduceStock($item['product_id'], $item['quantity']);
        }

        $cartModel->clearCart($_SESSION['user_id']);
        $_SESSION['success'] = 'Order placed successfully!';
        $this->redirect('Order');
    }

    public function details($id = null) {
        $this->requireLogin();
        if (!$id) $this->redirect('Order');

        $orderModel = $this->model('OrderModel');
        $order = $orderModel->findById($id);

        if (!$order) $this->redirect('Order');

        if ($_SESSION['role_name'] === 'customer' && $order['customer_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Access denied.';
            $this->redirect('Order');
        }

        $data['order'] = $order;
        $data['items'] = $orderModel->getItems($id);
        
        $this->view('orders/view', $data);
    }

    public function updateStatus($id = null) {
        $this->requireRole('admin');
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('Order');

        $status = $_POST['status'] ?? '';
        if (in_array($status, ['Pending', 'Completed', 'Cancelled'])) {
            $orderModel = $this->model('OrderModel');
            
            // Logic: If Admin manually cancels, you might also want to restock here
            // But usually, customers cancel their own pending orders.
            $orderModel->updateStatus($id, $status);
            $_SESSION['success'] = 'Order status updated.';
        }
        
        $this->redirect("Order/details/{$id}");
    }

    public function cancel($id = null) {
        $this->requireRole('customer');
        // Added check for POST request for security
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('Order');

        $orderModel = $this->model('OrderModel');
        $order = $orderModel->findById($id);

        if (!$order || $order['customer_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Order not found.';
            $this->redirect('Order');
        }

        if ($order['status'] !== 'Pending') {
            $_SESSION['error'] = 'Cannot cancel an order that is already ' . $order['status'] . '.';
            $this->redirect("Order/details/{$id}");
        }

        // 1. Set the status in the database
        if ($orderModel->updateStatus($id, 'Cancelled')) {
            $items = $orderModel->getItems($id);
            $productModel = $this->model('ProductModel');
            
            // 2. Return items to stock
            foreach ($items as $item) {
                $productModel->addStock($item['product_id'], $item['quantity']);
            }
            
            $_SESSION['success'] = 'Order cancelled and stock restored.';
        } else {
            $_SESSION['error'] = 'Something went wrong.';
        }
        
        $this->redirect("Order/details/{$id}");
    }
}