<?php
class Controller {
    protected function model($model) {
        require_once "../App/Models/{$model}.php";
        return new $model;
    }

    protected function view($view, $data = []) {
        extract($data);
        require_once "../App/Views/{$view}.php";
    }

    protected function redirect($url) {
        header("Location: /MVC_PHP/public/{$url}");
        exit;
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('Auth/login');
        }
    }

    protected function requireRole($roles) {
        $this->requireLogin();
        if (!in_array($_SESSION['role_name'], (array)$roles)) {
            $_SESSION['error'] = 'Access denied.';
            $this->redirect('Dashboard');
        }
    }

    protected function getUserRole() {
        return $_SESSION['role_name'] ?? null;
    }
}
