<?php 
// Fallback if the constant isn't loaded for some reason
if (!defined('APPROOT')) {
    define('APPROOT', dirname(dirname(dirname(__FILE__))));
}

$pageTitle = 'Dashboard - StoreHub'; 
include APPROOT . '/Views/layouts/header.php'; 
?>
<h4 class="mb-4 fw-bold">Dashboard</h4>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value"><?= $totalProducts ?></div>
                    <div class="stat-label">Total Products</div>
                </div>
                <div class="stat-icon" style="background: #3b82f6;"><i class='bx bx-package'></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value"><?= $totalOrders ?></div>
                    <div class="stat-label">Total Orders</div>
                </div>
                <div class="stat-icon" style="background: #10b981;"><i class='bx bx-receipt'></i></div>
            </div>
        </div>
    </div>
    <?php if (isset($totalUsers)): ?>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value"><?= $totalUsers ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-icon" style="background: #f59e0b;"><i class='bx bx-group'></i></div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="table-container">
    <div class="p-3 border-bottom">
        <h6 class="mb-0 fw-bold">Recent Orders</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentOrders)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">No orders yet</td></tr>
                <?php else: ?>
                    <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['customer_name']) ?></td>
                        <td>₱<?= number_format($order['total_amount'], 2) ?></td>
                        <td><span class="badge status-<?= strtolower($order['status']) ?>"><?= $order['status'] ?></span></td>
                        <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APPROOT . '/Views/layouts/footer.php'; ?>
