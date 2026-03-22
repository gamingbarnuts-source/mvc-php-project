<?php
require_once "../App/Core/Controller.php";

class UserController extends Controller {
    public function index() {
        $this->requireRole('admin');
        $userModel = $this->model('UserModel');
        $data['users'] = $userModel->getAll();
        $this->view('users/index', $data);
    }

    public function edit($id = null) {
        $this->requireRole('admin');
        if (!$id) $this->redirect('User');

        $userModel = $this->model('UserModel');
        $user = $userModel->findById($id);
        if (!$user) $this->redirect('User');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $role_id = (int)$_POST['role_id'];

            if (!$userModel->emailExists($email, $id)) {
                $userModel->update($id, $name, $email, $role_id);
                $_SESSION['success'] = 'User updated.';
                $this->redirect('User');
            }
            $data['error'] = 'Email already in use.';
        }

        $data['user'] = $user;
        $this->view('users/edit', $data);
    }

    public function delete($id = null) {
        $this->requireRole('admin');
        if ($id && $id != $_SESSION['user_id']) {
            $userModel = $this->model('UserModel');
            $userModel->delete($id);
            $_SESSION['success'] = 'User deleted.';
        }
        $this->redirect('User');
    }
}
