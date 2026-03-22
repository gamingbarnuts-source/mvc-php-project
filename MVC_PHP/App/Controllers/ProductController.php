<?php
require_once "../App/Core/Controller.php";

class ProductController extends Controller {
   public function index() {
        $this->requireLogin();
        $productModel = $this->model('ProductModel');
        $cartModel = $this->model('CartModel');

        // Fetch products based on role
        if ($_SESSION['role_name'] === 'seller') {
            $data['products'] = $productModel->getBySeller($_SESSION['user_id']);
        } else {
            $data['products'] = $productModel->getAll();
        }

        // NEW: Get current user's cart quantities to calculate available stock in the view
        $cartItems = $cartModel->getByUser($_SESSION['user_id']);
        $cartQtys = [];
        foreach ($cartItems as $item) {
            $cartQtys[$item['product_id']] = $item['quantity'];
        }
        $data['cartQtys'] = $cartQtys;

        $this->view('products/index', $data);
    }

    public function create() {
        $this->requireRole(['admin', 'seller']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image = 'uploads/' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../public/' . $image);
            }

            $productModel = $this->model('ProductModel');
            $productModel->create([
                'seller_id' => $_SESSION['user_id'],
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'price' => (float)$_POST['price'],
                'stock' => (int)$_POST['stock'],
                'image' => $image
            ]);

            $_SESSION['success'] = 'Product created successfully.';
            $this->redirect('Product');
        }

        $this->view('products/create');
    }

    public function edit($id = null) {
        $this->requireRole(['admin', 'seller']);
        if (!$id) $this->redirect('Product');

        $productModel = $this->model('ProductModel');
        $product = $productModel->findById($id);

        if (!$product) $this->redirect('Product');
        if ($_SESSION['role_name'] === 'seller' && $product['seller_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Access denied.';
            $this->redirect('Product');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'price' => (float)$_POST['price'],
                'stock' => (int)$_POST['stock'],
                'image' => ''
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $data['image'] = 'uploads/' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../public/' . $data['image']);
            }

            $productModel->update($id, $data);
            $_SESSION['success'] = 'Product updated successfully.';
            $this->redirect('Product');
        }

        $this->view('products/edit', ['product' => $product]);
    }

    public function delete($id = null) {
        $this->requireRole(['admin', 'seller']);
        if (!$id) $this->redirect('Product');

        $productModel = $this->model('ProductModel');
        $product = $productModel->findById($id);

        if ($product) {
            if ($_SESSION['role_name'] === 'seller' && $product['seller_id'] != $_SESSION['user_id']) {
                $_SESSION['error'] = 'Access denied.';
            } else {
                $productModel->delete($id);
                $_SESSION['success'] = 'Product deleted.';
            }
        }
        $this->redirect('Product');
    }
}
