<?php $pageTitle = 'Users - StoreHub'; include "../App/Views/layouts/header.php"; ?>

<h4 class="mb-4 fw-bold">Manage Users</h4>

<div class="table-container">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><span class="badge bg-primary"><?= ucfirst($user['role_name']) ?></span></td>
                    <td>
                        <a href="/MVC_PHP/public/User/edit/<?= $user['id'] ?>" class="btn btn-outline-primary btn-sm"><i class='bx bx-edit'></i></a>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <button onclick="confirmDelete('/MVC_PHP/public/User/delete/<?= $user['id'] ?>')" class="btn btn-outline-danger btn-sm"><i class='bx bx-trash'></i></button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../App/Views/layouts/footer.php"; ?>
