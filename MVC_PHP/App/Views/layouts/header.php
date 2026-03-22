<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'StoreHub' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="/MVC_PHP/public/css/style.css" rel="stylesheet">
</head>
<body>
<?php if (isset($_SESSION['user_id'])): ?>
    <?php include "../App/Views/layouts/sidebar.php"; ?>
    <div class="main-content">
        <div class="topbar">
            <button class="toggle-btn" id="sidebarToggle"><i class='bx bx-menu'></i></button>
            <div></div>
            <div class="user-info">
                <span><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <span class="badge bg-primary"><?= ucfirst($_SESSION['role_name']) ?></span>
            </div>
        </div>
        <div class="content-area">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show"><?= $_SESSION['success'] ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show"><?= $_SESSION['error'] ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
<?php endif; ?>
