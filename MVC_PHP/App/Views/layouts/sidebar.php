<?php
$currentUrl = $_GET['url'] ?? '';
$role = $_SESSION['role_name'] ?? '';
?>
<aside class="sidebar" id="sidebar">
    <div class="brand">
        <i class='bx bx-store-alt'></i>
        <span>StoreHub</span>
    </div>
    <ul class="nav-menu">
        <li>
            <a href="/MVC_PHP/public/Dashboard" class="<?= strpos($currentUrl, 'Dashboard') !== false ? 'active' : '' ?>">
                <i class='bx bx-grid-alt'></i> Dashboard
            </a>
        </li>
        <li>
            <a href="/MVC_PHP/public/Product" class="<?= strpos($currentUrl, 'Product') !== false ? 'active' : '' ?>">
                <i class='bx bx-package'></i> Products
            </a>
        </li>
        <li>
            <a href="/MVC_PHP/public/Order" class="<?= strpos($currentUrl, 'Order') !== false ? 'active' : '' ?>">
                <i class='bx bx-receipt'></i> Orders
            </a>
        </li>
        <?php if ($role === 'admin'): ?>
        <li>
            <a href="/MVC_PHP/public/User" class="<?= strpos($currentUrl, 'User') !== false ? 'active' : '' ?>">
                <i class='bx bx-group'></i> Users
            </a>
        </li>
        <?php endif; ?>
        <?php if ($role === 'customer'): ?>
        <li>
            <a href="/MVC_PHP/public/Cart" class="<?= strpos($currentUrl, 'Cart') !== false ? 'active' : '' ?>">
                <i class='bx bx-cart'></i> Cart
            </a>
        </li>
        <?php endif; ?>
        <li style="margin-top: 24px; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 12px;">
            <a href="/MVC_PHP/public/Auth/logout">
                <i class='bx bx-log-out'></i> Logout
            </a>
        </li>
    </ul>
</aside>
