<?php
require_once "../App/Core/Controller.php";

class DashboardController extends Controller {
    
    public function index() {
        $this->requireLogin();

        $productModel = $this->model('ProductModel');
        $orderModel = $this->model('OrderModel');

        // Use null coalescing ?? to prevent warnings if models return null
        $data['totalProducts'] = $productModel->countAll() ?? 0;
        $data['totalOrders'] = $orderModel->countAll() ?? 0;
        $data['recentOrders'] = $orderModel->getRecent(5) ?? [];

        if (isset($_SESSION['role_name']) && $_SESSION['role_name'] === 'admin') {
            $userModel = $this->model('UserModel');
            $data['totalUsers'] = $userModel->countAll() ?? 0;
        }

        $this->view('dashboard/index', $data);
    }
}