<?php
require_once "../App/Core/Controller.php";

class AuthController extends Controller {
    
    // Default method if no method is specified in the URL
    public function index() {
        $this->login();
    }

    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirect('Dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $userModel = $this->model('UserModel');
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['role_name'] = $user['role_name'];
                $this->redirect('Dashboard');
            } else {
                $data['error'] = 'Invalid email or password.';
            }
        }
        $this->view('auth/login', $data ?? []);
    }

    public function register() {
        if ($this->isLoggedIn()) {
            $this->redirect('Dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            $role_id = (int)($_POST['role_id'] ?? 3);

            if ($role_id < 2 || $role_id > 3) $role_id = 3;

            $errors = [];
            if (empty($name)) $errors[] = 'Name is required.';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
            if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
            if ($password !== $confirm) $errors[] = 'Passwords do not match.';

            $userModel = $this->model('UserModel');
            if ($userModel->emailExists($email)) $errors[] = 'Email already exists.';

            if (empty($errors)) {
                $userModel->register($name, $email, $password, $role_id);
                $_SESSION['success'] = 'Registration successful! Please login.';
                $this->redirect('Auth/login');
            }
            $data['errors'] = $errors;
            $data['old'] = $_POST;
        }
        $this->view('auth/register', $data ?? []);
    }

    public function logout() {
        session_unset();
        session_destroy();
        // Use the redirect helper from your core controller
        $this->redirect('Auth/login');
    }
}